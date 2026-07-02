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

    // 1. Buscar producto por ID
    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM productos WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res ?: null;
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::findById - " . $e->getMessage());
            return null;
        }
    }

    // 2. VALIDACIÓN: Verificamos si el SKU ya existe (excluye un ID al editar)
    public function findBySku(string $sku, ?int $excludeId = null): ?array
    {
        try {
            $sql = 'SELECT id FROM productos WHERE sku = :sku';
            $params = [':sku' => $sku];
            if ($excludeId !== null) {
                $sql .= ' AND id != :id';
                $params[':id'] = $excludeId;
            }
            $sql .= ' LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res ?: null;
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::findBySku - " . $e->getMessage());
            return null;
        }
    }

    public function getSimilares(int $categoriaId, int $excludeId, int $limit = 4): array
    {
        try {
            $stmt = $this->db->prepare('SELECT id, sku, nombre, precio, imagen_principal FROM productos WHERE categoria_id = :cat_id AND id != :exc_id ORDER BY RAND() LIMIT :lim');
            $stmt->bindParam(':cat_id', $categoriaId, PDO::PARAM_INT);
            $stmt->bindParam(':exc_id', $excludeId, PDO::PARAM_INT);
            $stmt->bindParam(':lim', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::getSimilares - " . $e->getMessage());
            return [];
        }
    }

    // 3. Imágenes de galería de un producto
    public function getImages(int $productoId): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM producto_imagenes WHERE producto_id = :pid ORDER BY id ASC');
            $stmt->execute([':pid' => $productoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::getImages - " . $e->getMessage());
            return [];
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

    // Actualizar datos de un producto
    public function update(
        int $id, string $nombre, string $sku, int $cat_id, float $precio,
        string $marca, string $modelo, int $stock, string $desc, array $nuevasImagenes
    ): bool {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE productos SET nombre=:nom, sku=:sku, categoria_id=:cat, precio=:pre,
                    marca=:mar, modelo=:mod, existencia=:ext, descripcion=:desc
                    WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':nom'  => $nombre,
                ':sku'  => $sku,
                ':cat'  => $cat_id,
                ':pre'  => $precio,
                ':mar'  => $marca,
                ':mod'  => $modelo,
                ':ext'  => $stock,
                ':desc' => $desc,
                ':id'   => $id,
            ]);

            // Si se pasan nuevas imágenes, la primera reemplaza la principal
            if (!empty($nuevasImagenes)) {
                $stmtPrincipal = $this->db->prepare("UPDATE productos SET imagen_principal=:img WHERE id=:id");
                $stmtPrincipal->execute([':img' => $nuevasImagenes[0], ':id' => $id]);

                // El resto van a galería
                if (count($nuevasImagenes) > 1) {
                    $stmtImg = $this->db->prepare("INSERT INTO producto_imagenes (producto_id, ruta_imagen) VALUES (:pid, :ruta)");
                    for ($i = 1; $i < count($nuevasImagenes); $i++) {
                        $stmtImg->execute([':pid' => $id, ':ruta' => $nuevasImagenes[$i]]);
                    }
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en ProductsModel::update - " . $e->getMessage());
            return false;
        }
    }

    // Eliminar una imagen de galería por ID
    public function deleteImage(int $imageId, int $productoId): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT ruta_imagen FROM producto_imagenes WHERE id=:id AND producto_id=:pid');
            $stmt->execute([':id' => $imageId, ':pid' => $productoId]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$img) return null;

            $this->db->prepare('DELETE FROM producto_imagenes WHERE id=:id')->execute([':id' => $imageId]);
            return $img['ruta_imagen'];
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::deleteImage - " . $e->getMessage());
            return null;
        }
    }

    // Reemplazar imagen de galería por ID
    public function replaceImage(int $imageId, int $productoId, string $nuevaRuta): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT ruta_imagen FROM producto_imagenes WHERE id=:id AND producto_id=:pid');
            $stmt->execute([':id' => $imageId, ':pid' => $productoId]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$img) return null;

            $old = $img['ruta_imagen'];
            $this->db->prepare('UPDATE producto_imagenes SET ruta_imagen=:ruta WHERE id=:id')
                ->execute([':ruta' => $nuevaRuta, ':id' => $imageId]);
            return $old;
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::replaceImage - " . $e->getMessage());
            return null;
        }
    }

    // Reemplazar imagen principal
    public function replacePrincipal(int $productoId, string $nuevaRuta): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT imagen_principal FROM productos WHERE id=:id');
            $stmt->execute([':id' => $productoId]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$prod) return null;

            $old = $prod['imagen_principal'];
            $this->db->prepare('UPDATE productos SET imagen_principal=:img WHERE id=:id')
                ->execute([':img' => $nuevaRuta, ':id' => $productoId]);
            return $old;
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::replacePrincipal - " . $e->getMessage());
            return null;
        }
    }

    // Eliminar imagen principal (deja en null)
    public function deletePrincipal(int $productoId): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT imagen_principal FROM productos WHERE id=:id');
            $stmt->execute([':id' => $productoId]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$prod || !$prod['imagen_principal']) return null;

            $old = $prod['imagen_principal'];
            $this->db->prepare('UPDATE productos SET imagen_principal=NULL WHERE id=:id')->execute([':id' => $productoId]);
            return $old;
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::deletePrincipal - " . $e->getMessage());
            return null;
        }
    }

    // Eliminar producto completo
    public function delete(int $id): bool
    {
        try {
            $this->db->beginTransaction();
            // Las imágenes de galería se eliminan en cascada por FK
            $this->db->prepare('DELETE FROM productos WHERE id=:id')->execute([':id' => $id]);
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en ProductsModel::delete - " . $e->getMessage());
            return false;
        }
    }

    // Obtener todos los productos con el nombre de su categoría
    public function getAllProductsWithCategory(): array
    {
        try {
            $sql = "SELECT p.*, c.nombre as categoria_nombre 
                    FROM productos p 
                    LEFT JOIN categorias c ON p.categoria_id = c.id 
                    ORDER BY p.id DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ProductsModel::getAllProductsWithCategory - " . $e->getMessage());
            return [];
        }
    }
}