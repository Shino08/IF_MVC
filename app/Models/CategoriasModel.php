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
}