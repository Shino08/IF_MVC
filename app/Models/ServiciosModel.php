<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class ServiciosModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Buscar servicio por ID ────────────────────────────────────────
    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT s.*, c.nombre AS categoria_nombre, tc.nombre AS tipo_cobro_nombre
                 FROM servicios s
                 LEFT JOIN categorias c  ON s.categoria_id   = c.id
                 LEFT JOIN tipos_cobro tc ON s.tipo_cobro_id = tc.id
                 WHERE s.id = :id LIMIT 1'
            );
            $stmt->execute([':id' => $id]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res ?: null;
        } catch (PDOException $e) {
            error_log('ServiciosModel::findById — ' . $e->getMessage());
            return null;
        }
    }

    // ── Validar código único ──────────────────────────────────────────
    public function findByCodigo(string $codigo, ?int $excludeId = null): ?array
    {
        try {
            $sql    = 'SELECT id FROM servicios WHERE codigo = :codigo';
            $params = [':codigo' => $codigo];
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
            error_log('ServiciosModel::findByCodigo — ' . $e->getMessage());
            return null;
        }
    }

    // ── Todos los servicios con info de categoría ─────────────────────
    public function getAll(): array
    {
        try {
            $sql = 'SELECT s.*, c.nombre AS categoria_nombre, tc.nombre AS tipo_cobro_nombre
                    FROM servicios s
                    LEFT JOIN categorias c   ON s.categoria_id   = c.id
                    LEFT JOIN tipos_cobro tc ON s.tipo_cobro_id  = tc.id
                    ORDER BY s.id DESC';
            return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ServiciosModel::getAll — ' . $e->getMessage());
            return [];
        }
    }

    // ── Obtener servicios similares ───────────────────────────────────
    public function getSimilares(int $categoriaId, int $excludeId, int $limit = 4): array
    {
        try {
            $stmt = $this->db->prepare(
                'SELECT s.*, c.nombre AS categoria_nombre
                 FROM servicios s
                 LEFT JOIN categorias c ON s.categoria_id = c.id
                 WHERE s.categoria_id = :cat_id AND s.id != :id
                 ORDER BY RAND() LIMIT :limit'
            );
            $stmt->bindValue(':cat_id', $categoriaId, PDO::PARAM_INT);
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ServiciosModel::getSimilares - " . $e->getMessage());
            return [];
        }
    }

    // ── Todos los tipos de cobro ──────────────────────────────────────
    public function getTiposCobro(): array
    {
        try {
            return $this->db->query('SELECT * FROM tipos_cobro ORDER BY id ASC')
                            ->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('ServiciosModel::getTiposCobro — ' . $e->getMessage());
            return [];
        }
    }

    // ── Crear servicio ────────────────────────────────────────────────
    public function create(
        string $codigo, string $nombre, ?int $catId, float $precio,
        ?int $tipoCobro, string $desc, array $imagenes
    ): int {
        try {
            $this->db->beginTransaction();
            $principal = !empty($imagenes) ? $imagenes[0] : null;
            $sql  = 'INSERT INTO servicios
                     (codigo, nombre, categoria_id, precio_referencial, tipo_cobro_id, descripcion, imagen_principal)
                     VALUES (:cod, :nom, :cat, :pre, :tc, :desc, :img)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cod'  => $codigo,
                ':nom'  => $nombre,
                ':cat'  => $catId ?: null,
                ':pre'  => $precio,
                ':tc'   => $tipoCobro ?: null,
                ':desc' => $desc,
                ':img'  => $principal,
            ]);
            $servicioId = (int)$this->db->lastInsertId();

            if (count($imagenes) > 1) {
                $sqlImg = "INSERT INTO servicio_imagenes (servicio_id, ruta_imagen) VALUES (:sid, :ruta)";
                $stmtImg = $this->db->prepare($sqlImg);
                for ($i = 1; $i < count($imagenes); $i++) {
                    $stmtImg->execute([
                        ':sid'  => $servicioId,
                        ':ruta' => $imagenes[$i]
                    ]);
                }
            }
            $this->db->commit();
            return $servicioId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('ServiciosModel::create — ' . $e->getMessage());
            throw $e;
        }
    }

    // ── Actualizar servicio ───────────────────────────────────────────
    public function update(
        int $id, string $codigo, string $nombre, ?int $catId, float $precio,
        ?int $tipoCobro, string $desc, array $nuevasImagenes
    ): bool {
        try {
            $this->db->beginTransaction();
            $sql = 'UPDATE servicios SET
                    codigo=:cod, nombre=:nom, categoria_id=:cat,
                    precio_referencial=:pre, tipo_cobro_id=:tc, descripcion=:desc
                    WHERE id=:id';
            $params = [
                ':cod'  => $codigo,
                ':nom'  => $nombre,
                ':cat'  => $catId ?: null,
                ':pre'  => $precio,
                ':tc'   => $tipoCobro ?: null,
                ':desc' => $desc,
                ':id'   => $id,
            ];
            $this->db->prepare($sql)->execute($params);

            if (!empty($nuevasImagenes)) {
                $this->db->prepare('UPDATE servicios SET imagen_principal=:img WHERE id=:id')
                         ->execute([':img' => $nuevasImagenes[0], ':id' => $id]);

                if (count($nuevasImagenes) > 1) {
                    $stmtImg = $this->db->prepare("INSERT INTO servicio_imagenes (servicio_id, ruta_imagen) VALUES (:sid, :ruta)");
                    for ($i = 1; $i < count($nuevasImagenes); $i++) {
                        $stmtImg->execute([':sid' => $id, ':ruta' => $nuevasImagenes[$i]]);
                    }
                }
            }
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('ServiciosModel::update — ' . $e->getMessage());
            return false;
        }
    }

    // ── Obtener imagen principal (para borrar archivo físico al reemplazar) ─
    public function getImagen(int $id): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT imagen_principal FROM servicios WHERE id=:id');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['imagen_principal'] : null;
        } catch (PDOException $e) {
            error_log('ServiciosModel::getImagen — ' . $e->getMessage());
            return null;
        }
    }

    public function getImages(int $id): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM servicio_imagenes WHERE servicio_id = :id ORDER BY id ASC');
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en ServiciosModel::getImages - " . $e->getMessage());
            return [];
        }
    }

    // ── Borrar imagen principal (setea NULL) ──────────────────────────
    public function deleteImagen(int $id): ?string
    {
        try {
            $vieja = $this->getImagen($id);
            if (!$vieja) return null;
            $this->db->prepare('UPDATE servicios SET imagen_principal=NULL WHERE id=:id')
                     ->execute([':id' => $id]);
            return $vieja;
        } catch (PDOException $e) {
            error_log('ServiciosModel::deleteImagen — ' . $e->getMessage());
            return null;
        }
    }

    // ── Eliminar servicio ─────────────────────────────────────────────
    public function delete(int $id): bool
    {
        try {
            $this->db->prepare('DELETE FROM servicios WHERE id=:id')->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log('ServiciosModel::delete — ' . $e->getMessage());
            return false;
        }
    }

    public function deleteImage(int $imageId, int $servicioId): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT ruta_imagen FROM servicio_imagenes WHERE id=:id AND servicio_id=:sid');
            $stmt->execute([':id' => $imageId, ':sid' => $servicioId]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$img) return null;

            $this->db->prepare('DELETE FROM servicio_imagenes WHERE id=:id')->execute([':id' => $imageId]);
            return $img['ruta_imagen'];
        } catch (PDOException $e) {
            error_log("Error en ServiciosModel::deleteImage - " . $e->getMessage());
            return null;
        }
    }

    public function replaceImage(int $imageId, int $servicioId, string $nuevaRuta): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT ruta_imagen FROM servicio_imagenes WHERE id=:id AND servicio_id=:sid');
            $stmt->execute([':id' => $imageId, ':sid' => $servicioId]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$img) return null;

            $old = $img['ruta_imagen'];
            $this->db->prepare('UPDATE servicio_imagenes SET ruta_imagen=:ruta WHERE id=:id')
                ->execute([':ruta' => $nuevaRuta, ':id' => $imageId]);
            return $old;
        } catch (PDOException $e) {
            error_log("Error en ServiciosModel::replaceImage - " . $e->getMessage());
            return null;
        }
    }

    public function replacePrincipal(int $servicioId, string $nuevaRuta): ?string
    {
        try {
            $stmt = $this->db->prepare('SELECT imagen_principal FROM servicios WHERE id=:id');
            $stmt->execute([':id' => $servicioId]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$prod) return null;

            $old = $prod['imagen_principal'];
            $this->db->prepare('UPDATE servicios SET imagen_principal=:img WHERE id=:id')
                ->execute([':img' => $nuevaRuta, ':id' => $servicioId]);
            return $old;
        } catch (PDOException $e) {
            error_log("Error en ServiciosModel::replacePrincipal - " . $e->getMessage());
            return null;
        }
    }
}
