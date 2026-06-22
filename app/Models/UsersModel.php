<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class UsersModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array
    {
        try {
            // La preparación de la consulta previene Inyección SQL
            $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user ?: null;

        } catch (PDOException $e) {
            error_log("Error en Usuario::findByEmail - " . $e->getMessage());
            return null;
        }
    }

    // NUEVO: Buscamos si la cédula ya está registrada
    public function findByCedula(string $cedula): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE cedula = :cedula LIMIT 1');
            $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $user ?: null;

        } catch (PDOException $e) {
            error_log("Error en Usuario::findByCedula - " . $e->getMessage());
            return null;
        }
    }

    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['contrasena'])) {
            unset($user['contrasena']);
            
            // Generate and save session token
            $sessionToken = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare('UPDATE usuarios SET session_token = :token WHERE id = :id');
            $stmt->execute([':token' => $sessionToken, ':id' => $user['id']]);
            $user['session_token'] = $sessionToken;
            
            return $user;
        }

        return null;
    }

    // Actualizamos la firma del método para recibir todos los nuevos campos
    public function register(string $nombre, string $apellido, string $cedula, string $empresa, string $telefono, string $email, string $contrasena_hash, int $rol_id = 2): ?array
    {
        // Verificamos si el correo o la cédula ya existen en la base de datos
        if ($this->findByEmail($email) || $this->findByCedula($cedula)) {
            return null; 
        }

        try {
            // Agregamos apellido y cedula a la inserción SQL
            $sql = 'INSERT INTO usuarios (nombre, apellido, cedula, empresa, telefono, email, contrasena, rol_id) 
                    VALUES (:nombre, :apellido, :cedula, :empresa, :telefono, :email, :contrasena, :rol_id)';
            
            $stmt = $this->db->prepare($sql);
            
            // Tratamos "empresa" para que guarde NULL si viene vacía
            $empresa_limpia = empty(trim($empresa)) ? null : trim($empresa);

            $stmt->execute([
                ':nombre'     => trim($nombre),
                ':apellido'   => trim($apellido),
                ':cedula'     => trim($cedula),
                ':empresa'    => $empresa_limpia,
                ':telefono'   => trim($telefono),
                ':email'      => trim($email),
                ':contrasena' => $contrasena_hash,
                ':rol_id'     => $rol_id
            ]);

            $id = (int) $this->db->lastInsertId();

            return [
                'id'       => $id,
                'nombre'   => $nombre,
                'apellido' => $apellido,
                'cedula'   => $cedula,
                'email'    => $email,
                'empresa'  => $empresa_limpia,
                'rol_id'   => $rol_id
            ];
            
        } catch (PDOException $e) {
            error_log("Error en Usuario::register - " . $e->getMessage());
            return null;
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE id = :id LIMIT 1');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            error_log("Error en Usuario::findById - " . $e->getMessage());
            return null;
        }
    }

    public function updateProfile(int $id, string $nombre, string $apellido, string $cedula, string $empresa, string $telefono, string $email): bool
    {
        try {
            $sql = 'UPDATE usuarios 
                    SET nombre = :nombre, apellido = :apellido, cedula = :cedula, empresa = :empresa, telefono = :telefono, email = :email 
                    WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            
            $empresa_limpia = empty(trim($empresa)) ? null : trim($empresa);
            
            return $stmt->execute([
                ':id'       => $id,
                ':nombre'   => trim($nombre),
                ':apellido' => trim($apellido),
                ':cedula'   => trim($cedula),
                ':empresa'  => $empresa_limpia,
                ':telefono' => trim($telefono),
                ':email'    => trim($email)
            ]);
        } catch (PDOException $e) {
            error_log("Error en Usuario::updateProfile - " . $e->getMessage());
            return false;
        }
    }

    public function updatePassword(int $id, string $hashedPassword): bool
    {
        try {
            $sql = 'UPDATE usuarios SET contrasena = :contrasena WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id'         => $id,
                ':contrasena' => $hashedPassword
            ]);
        } catch (PDOException $e) {
            error_log("Error en Usuario::updatePassword - " . $e->getMessage());
            return false;
        }
    }

    public function updateSessionToken(int $id, string $token): bool
    {
        try {
            $sql = 'UPDATE usuarios SET session_token = :token WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id'    => $id,
                ':token' => $token
            ]);
        } catch (PDOException $e) {
            error_log("Error en Usuario::updateSessionToken - " . $e->getMessage());
            return false;
        }
    }

    public function createPasswordResetToken(int $userId, string $token): bool
    {
        try {
            // Invalidar tokens anteriores no usados
            $sqlInvalidate = 'UPDATE password_resets SET usado = 1 WHERE usuarioid = :usuarioid AND usado = 0';
            $stmtInvalidate = $this->db->prepare($sqlInvalidate);
            $stmtInvalidate->execute([':usuarioid'  => $userId]);

            $sql = 'INSERT INTO password_resets (usuarioid, token_hash, expiracion) 
                    VALUES (:usuarioid, :token_hash, DATE_ADD(NOW(), INTERVAL 60 MINUTE))';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':usuarioid'  => $userId,
                ':token_hash' => hash('sha256', $token)
            ]);
        } catch (PDOException $e) {
            error_log("Error en Usuario::createPasswordResetToken - " . $e->getMessage());
            return false;
        }
    }

    public function canRequestPasswordReset(int $userId): bool
    {
        try {
            // Verificar si hay demasiados tokens recientes (creados en los últimos 15 min -> expiracion > NOW() + 45 MIN)
            $sql = 'SELECT COUNT(id) FROM password_resets 
                    WHERE usuarioid = :usuarioid 
                      AND expiracion > DATE_ADD(NOW(), INTERVAL 45 MINUTE)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':usuarioid' => $userId]);
            
            // Límite: 3 intentos en 15 minutos
            return (int)$stmt->fetchColumn() < 3;
        } catch (PDOException $e) {
            error_log("Error en Usuario::canRequestPasswordReset - " . $e->getMessage());
            return false;
        }
    }

    public function findByResetToken(string $token): ?array
    {
        try {
            $sql = 'SELECT u.* FROM usuarios u
                    INNER JOIN password_resets pr ON u.id = pr.usuarioid
                    WHERE pr.token_hash = :token_hash 
                      AND pr.usado = 0 
                      AND pr.expiracion > NOW() 
                    LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':token_hash' => hash('sha256', $token)
            ]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: null;
        } catch (PDOException $e) {
            error_log("Error en Usuario::findByResetToken - " . $e->getMessage());
            return null;
        }
    }

    public function verifyPasswordResetToken(int $userId, string $token): bool
    {
        try {
            $sql = 'SELECT id FROM password_resets 
                    WHERE usuarioid = :usuarioid 
                      AND token_hash = :token_hash 
                      AND usado = 0 
                      AND expiracion > NOW() 
                    LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuarioid'  => $userId,
                ':token_hash' => hash('sha256', $token)
            ]);
            
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error en Usuario::verifyPasswordResetToken - " . $e->getMessage());
            return false;
        }
    }

    public function resetPassword(int $userId, string $hashedPassword, string $token): bool
    {
        try {
            $this->db->beginTransaction();

            $this->updatePassword($userId, $hashedPassword);

            $sql = 'UPDATE password_resets SET usado = 1 
                    WHERE usuarioid = :usuarioid AND token_hash = :token_hash';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':usuarioid'  => $userId,
                ':token_hash' => hash('sha256', $token)
            ]);

            // Invalidate sessions by generating a new session_token
            $sessionToken = bin2hex(random_bytes(32));
            $stmtToken = $this->db->prepare('UPDATE usuarios SET session_token = :token WHERE id = :id');
            $stmtToken->execute([':token' => $sessionToken, ':id' => $userId]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error en Usuario::resetPassword - " . $e->getMessage());
            return false;
        }
    }
}