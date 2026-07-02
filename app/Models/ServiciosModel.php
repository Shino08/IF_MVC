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
        ?int $tipoCobro, string $desc, ?string $imagen
    ): int {
        try {
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
                ':img'  => $imagen,
            ]);
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('ServiciosModel::create — ' . $e->getMessage());
            throw $e;
        }
    }

    // ── Actualizar servicio ───────────────────────────────────────────
    public function update(
        int $id, string $codigo, string $nombre, ?int $catId, float $precio,
        ?int $tipoCobro, string $desc, ?string $nuevaImagen
    ): bool {
        try {
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

            if ($nuevaImagen !== null) {
                $this->db->prepare('UPDATE servicios SET imagen_principal=:img WHERE id=:id')
                         ->execute([':img' => $nuevaImagen, ':id' => $id]);
            }
            return true;
        } catch (PDOException $e) {
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
}
