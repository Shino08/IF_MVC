<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Usuario
{
    /**
     * Busca un usuario por su email.
     *
     * @param string $email
     * @return array|null  Retorna el usuario o null si no existe
     */
    public function findByEmail(string $email): ?array
    {
        // ── Consulta real (cuando tengas BD configurada) ─────────────────────
        // $pdo  = Database::getInstance();
        // $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email LIMIT 1');
        // $stmt->execute(['email' => $email]);
        // return $stmt->fetch() ?: null;

        // ── Simulación de datos ──────────────────────────────────────────────
        $usuarios = [
            ['id' => 1, 'email' => 'admin@test.com', 'password' => '123456', 'name' => 'Administrador'],
            ['id' => 2, 'email' => 'juan@test.com',  'password' => 'secret',  'name' => 'Juan Pérez'],
        ];

        foreach ($usuarios as $usuario) {
            if ($usuario['email'] === $email) {
                return $usuario;
            }
        }

        return null;
    }

    /**
     * Registra un nuevo usuario.
     *
     * @return array|null  Retorna el usuario creado o null si ya existía
     */
    public function register(string $email, string $name, string $lastname, string $password): ?array
    {
        // ── Consulta real (cuando tengas BD configurada) ─────────────────────
        // if ($this->findByEmail($email)) return null;
        //
        // $pdo  = Database::getInstance();
        // $stmt = $pdo->prepare(
        //     'INSERT INTO usuarios (email, nombre, apellido, password) VALUES (:email, :name, :lastname, :password)'
        // );
        // $stmt->execute(compact('email', 'name', 'lastname', 'password'));
        // return ['id' => $pdo->lastInsertId(), 'email' => $email, 'name' => $name];

        // ── Simulación de datos ──────────────────────────────────────────────
        if ($this->findByEmail($email)) {
            return null; // Ya existe
        }

        return [
            'id'       => rand(3, 100),
            'email'    => $email,
            'password' => $password, // En producción: password_hash()
            'name'     => $name,
            'lastname' => $lastname,
        ];
    }
}
