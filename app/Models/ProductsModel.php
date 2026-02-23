<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class ProductsModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // 1. VALIDACIÓN: Verificamos si el SKU ya existe
    public function findBySku(string $sku): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT id FROM productos WHERE sku = :sku LIMIT 1');
            $stmt->execute([':sku' => $sku]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res ?: null;
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::findBySku - " . $e->getMessage());
            return null;
        }
    }

    // 2. TRANSACCIÓN: Guardado seguro
    public function saveFullProduct(
        string $nombre, string $sku, int $cat_id, float $precio, 
        string $marca, string $modelo, int $stock, string $desc, array $imagenes
    ): bool {
        // Doble validación de seguridad a nivel de modelo
        if ($this->findBySku($sku)) {
            return false; // El SKU ya existe, abortamos
        }

        try {
            $this->db->beginTransaction();

            // 1. Imagen principal
            $principal = $imagenes[0] ?? null;

            // 2. Insertar producto base
            $sql = "INSERT INTO productos (sku, nombre, modelo, descripcion, categoria_id, precio, marca, existencia, imagen_principal) 
                    VALUES (:sku, :nom, :mod, :desc, :cat, :pre, :mar, :ext, :img)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':sku'  => $sku,
                ':nom'  => $nombre,
                ':mod'  => $modelo,
                ':desc' => $desc,
                ':cat'  => $cat_id,
                ':pre'  => $precio,
                ':mar'  => $marca,
                ':ext'  => $stock,
                ':img'  => $principal
            ]);

            $productoId = (int)$this->db->lastInsertId();

            // 3. Insertar imágenes secundarias (galería)
            if (count($imagenes) > 1) {
                $sqlImg = "INSERT INTO producto_imagenes (producto_id, ruta_imagen) VALUES (:pid, :ruta)";
                $stmtImg = $this->db->prepare($sqlImg);

                for ($i = 1; $i < count($imagenes); $i++) {
                    $stmtImg->execute([
                        ':pid'  => $productoId,
                        ':ruta' => $imagenes[$i]
                    ]);
                }
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack(); // Revertimos todo si hay un error
            error_log("Error en ProductsModel::saveFullProduct - " . $e->getMessage());
            throw $e;
            // return false;
        }
    }

    // Obtener todos los productos con el nombre de su categoría
    public function getAllProductsWithCategory(): array
    {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    ORDER BY p.id DESC"; // DESC para que los recién agregados salgan primero
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error en ProductsModel::getAllProductsWithCategory - " . $e->getMessage());
            return [];
        }
    }
}