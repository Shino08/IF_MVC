<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class CategoriasModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Trae todas las categorías de la base de datos
    public function getAll(): array
    {
        try {
            // Ordenamos por nombre para que el select se vea ordenado A-Z
            $stmt = $this->db->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en CategoriasModel::getAll - " . $e->getMessage());
            return [];
        }
    }

    // 2. Verificar si el nombre ya existe (Opcional: excluir un ID al editar)
    public function findByName(string $nombre, ?int $excludeId = null): ?array
    {
        try {
            $sql = "SELECT id FROM categorias WHERE nombre = :nombre";
            $params = [':nombre' => $nombre];

            if ($excludeId) {
                $sql .= " AND id != :id";
                $params[':id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            error_log("Error en CategoriasModel::findByName - " . $e->getMessage());
            return null;
        }
    }

    // 3. Crear nueva categoría
    public function create(string $nombre): bool
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO categorias (nombre) VALUES (:nombre)");
            return $stmt->execute([':nombre' => $nombre]);
        } catch (PDOException $e) {
            error_log("Error en CategoriasModel::create - " . $e->getMessage());
            return false;
        }
    }

    // 4. Actualizar categoría existente
    public function update(int $id, string $nombre): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE categorias SET nombre = :nombre WHERE id = :id");
            return $stmt->execute([
                ':nombre' => $nombre,
                ':id'     => $id
            ]);
        } catch (PDOException $e) {
            error_log("Error en CategoriasModel::update - " . $e->getMessage());
            return false;
        }
    }

    // 5. Eliminar categoría
    public function delete(int $id): string
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return 'success';
        } catch (PDOException $e) {
            // Código 1451 de MySQL significa "Cannot delete or update a parent row: a foreign key constraint fails"
            // Esto salta si intentamos borrar una categoría que ya tiene productos asignados
            if ($e->getCode() == 23000 || $e->errorInfo[1] == 1451) {
                return 'in_use';
            }
            error_log("Error en CategoriasModel::delete - " . $e->getMessage());
            return 'error';
        }
    }
}