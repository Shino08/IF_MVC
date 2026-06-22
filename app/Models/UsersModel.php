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
}