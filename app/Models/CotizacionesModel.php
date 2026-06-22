<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class CotizacionesModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getBorradorByUserId(int $userId): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM cotizaciones WHERE usuario_id = :userId AND estado_id = 1 LIMIT 1');
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cotizacion ?: null;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getBorradorByUserId - " . $e->getMessage());
            return null;
        }
    }

    public function createBorrador(int $userId): int
    {
        try {
            $sql = 'INSERT INTO cotizaciones (usuario_id, estado_id, total, subtotal, impuestos) VALUES (:userId, 1, 0, 0, 0)';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':userId' => $userId]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::createBorrador - " . $e->getMessage());
            return 0;
        }
    }

    public function getDetalles(int $cotizacionId): array
    {
        try {
            $sql = 'SELECT cd.*, 
                           p.nombre as producto_nombre, p.precio as producto_precio, p.imagen_principal as producto_imagen, p.sku,
                           s.nombre as servicio_nombre, s.precio_referencial as servicio_precio, s.imagen_principal as servicio_imagen, s.codigo
                    FROM cotizacion_detalles cd
                    LEFT JOIN productos p ON cd.producto_id = p.id
                    LEFT JOIN servicios s ON cd.servicio_id = s.id
                    WHERE cd.cotizacion_id = :cotizacionId';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cotizacionId', $cotizacionId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getDetalles - " . $e->getMessage());
            return [];
        }
    }

    public function addItem(int $cotizacionId, ?int $productoId, ?int $servicioId, float $cantidad, float $precioUnitario): bool
    {
        try {
            // Verificar si el item ya existe en la cotizacion
            if ($productoId) {
                $sqlCheck = 'SELECT id, cantidad FROM cotizacion_detalles WHERE cotizacion_id = :cotId AND producto_id = :prodId';
                $stmtCheck = $this->db->prepare($sqlCheck);
                $stmtCheck->execute([':cotId' => $cotizacionId, ':prodId' => $productoId]);
            } else {
                $sqlCheck = 'SELECT id, cantidad FROM cotizacion_detalles WHERE cotizacion_id = :cotId AND servicio_id = :servId';
                $stmtCheck = $this->db->prepare($sqlCheck);
                $stmtCheck->execute([':cotId' => $cotizacionId, ':servId' => $servicioId]);
            }
            
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $nuevaCantidad = $existing['cantidad'] + $cantidad;
                $sql = 'UPDATE cotizacion_detalles SET cantidad = :cantidad, precio_unitario = :precio WHERE id = :id';
                $stmt = $this->db->prepare($sql);
                $res = $stmt->execute([
                    ':cantidad' => $nuevaCantidad,
                    ':precio'   => $precioUnitario,
                    ':id'       => $existing['id']
                ]);
            } else {
                $sql = 'INSERT INTO cotizacion_detalles (cotizacion_id, producto_id, servicio_id, cantidad, precio_unitario) 
                        VALUES (:cotId, :prodId, :servId, :cant, :precio)';
                $stmt = $this->db->prepare($sql);
                $res = $stmt->execute([
                    ':cotId'  => $cotizacionId,
                    ':prodId' => $productoId,
                    ':servId' => $servicioId,
                    ':cant'   => $cantidad,
                    ':precio' => $precioUnitario
                ]);
            }

            $this->updateCotizacionTotals($cotizacionId);
            return $res;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::addItem - " . $e->getMessage());
            return false;
        }
    }

    public function updateItemPrice(int $detalleId, float $precioUnitario): bool
    {
        try {
            // Obtener el cotizacion_id antes de actualizar
            $stmt = $this->db->prepare('SELECT cotizacion_id FROM cotizacion_detalles WHERE id = :id');
            $stmt->execute([':id' => $detalleId]);
            $cotizacionId = $stmt->fetchColumn();

            if (!$cotizacionId) return false;

            $sql = 'UPDATE cotizacion_detalles SET precio_unitario = :precio WHERE id = :id';
            $stmtUpdate = $this->db->prepare($sql);
            $res = $stmtUpdate->execute([':precio' => $precioUnitario, ':id' => $detalleId]);

            $this->updateCotizacionTotals((int)$cotizacionId);
            return $res;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::updateItemPrice - " . $e->getMessage());
            return false;
        }
    }

    public function updateItemQuantity(int $detalleId, float $cantidad): bool
    {
        try {
            // Obtener el cotizacion_id antes de actualizar
            $stmt = $this->db->prepare('SELECT cotizacion_id FROM cotizacion_detalles WHERE id = :id');
            $stmt->execute([':id' => $detalleId]);
            $cotizacionId = $stmt->fetchColumn();

            if (!$cotizacionId) return false;

            $sql = 'UPDATE cotizacion_detalles SET cantidad = :cantidad WHERE id = :id';
            $stmtUpdate = $this->db->prepare($sql);
            $res = $stmtUpdate->execute([':cantidad' => $cantidad, ':id' => $detalleId]);

            $this->updateCotizacionTotals((int)$cotizacionId);
            return $res;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::updateItemQuantity - " . $e->getMessage());
            return false;
        }
    }

    public function removeItem(int $detalleId): bool
    {
        try {
            $stmt = $this->db->prepare('SELECT cotizacion_id FROM cotizacion_detalles WHERE id = :id');
            $stmt->execute([':id' => $detalleId]);
            $cotizacionId = $stmt->fetchColumn();

            if (!$cotizacionId) return false;

            $stmtDel = $this->db->prepare('DELETE FROM cotizacion_detalles WHERE id = :id');
            $res = $stmtDel->execute([':id' => $detalleId]);

            $this->updateCotizacionTotals((int)$cotizacionId);
            return $res;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::removeItem - " . $e->getMessage());
            return false;
        }
    }

    public function updateComercialFields(int $cotizacionId, array $data): bool
    {
        try {
            $fields = [];
            $params = [':id' => $cotizacionId];
            
            $allowedFields = [
                'fecha_vencimiento', 'impuestos', 'descuento', 'id_metodo_pago',
                'condiciones_pago', 'notas_internas', 'notas_tecnicas',
                'proyecto_referencia', 'direccion_envio', 'direccion_facturacion'
            ];
            
            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "`$field` = :$field";
                    $params[":$field"] = $data[$field];
                }
            }
            
            if (empty($fields)) return false;
            
            $sql = 'UPDATE cotizaciones SET ' . implode(', ', $fields) . ' WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute($params);
            
            // Recalcular total si cambió descuento o impuestos
            if (array_key_exists('descuento', $data) || array_key_exists('impuestos', $data)) {
                $this->updateCotizacionTotals($cotizacionId);
            }
            
            return $res;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::updateComercialFields - " . $e->getMessage());
            return false;
        }
    }

    public function emitirCotizacion(int $cotizacionId, string $notasCliente = ''): bool
    {
        try {
            // estado 3 = enviada (solo desde pendiente_revision)
            $sql = 'UPDATE cotizaciones SET estado_id = 3, notas_tecnicas = COALESCE(:notas, notas_tecnicas) WHERE id = :id AND estado_id = 2';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':notas' => !empty($notasCliente) ? $notasCliente : null,
                ':id'    => $cotizacionId
            ]);
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::emitirCotizacion - " . $e->getMessage());
            return false;
        }
    }

    public function getMetodosPago(): array
    {
        try {
            $stmt = $this->db->query('SELECT id, metodo FROM metodos_de_pagos ORDER BY metodo');
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getMetodosPago - " . $e->getMessage());
            return [];
        }
    }

    private function updateCotizacionTotals(int $cotizacionId): void
    {
        try {
            // Calcular subtotal desde detalles
            $sql = 'SELECT SUM(cantidad * precio_unitario) as subtotal FROM cotizacion_detalles WHERE cotizacion_id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $cotizacionId]);
            $subtotal = $stmt->fetchColumn() ?: 0;

            // Obtener descuento e impuestos actuales
            $sqlC = 'SELECT descuento, impuestos FROM cotizaciones WHERE id = :id';
            $stmtC = $this->db->prepare($sqlC);
            $stmtC->execute([':id' => $cotizacionId]);
            $cot = $stmtC->fetch(PDO::FETCH_ASSOC);
            
            $descuento = (float)($cot['descuento'] ?? 0);
            $impuestos = (float)($cot['impuestos'] ?? 0);
            
            // Total = subtotal + impuestos - descuento
            $total = $subtotal + $impuestos - $descuento;
            if ($total < 0) $total = 0;

            $sqlUpdate = 'UPDATE cotizaciones SET subtotal = :sub, total = :tot WHERE id = :id';
            $stmtUpd = $this->db->prepare($sqlUpdate);
            $stmtUpd->execute([
                ':sub' => $subtotal,
                ':tot' => $total,
                ':id'  => $cotizacionId
            ]);
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::updateCotizacionTotals - " . $e->getMessage());
        }
    }

    public function sendCotizacion(int $cotizacionId, string $notas): bool
    {
        try {
            // estado 2 = pendiente_revision
            $sql = 'UPDATE cotizaciones SET estado_id = 2, notas_tecnicas = :notas, fecha_solicitud = CURRENT_TIMESTAMP WHERE id = :id AND estado_id = 1';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':notas' => $notas,
                ':id'    => $cotizacionId
            ]);
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::sendCotizacion - " . $e->getMessage());
            return false;
        }
    }

    public function getHistoryByUserId(int $userId): array
    {
        try {
            $sql = 'SELECT c.*, e.nombre as estado_nombre 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    WHERE c.usuario_id = :userId AND c.estado_id != 1
                    ORDER BY c.fecha_solicitud DESC';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getHistoryByUserId - " . $e->getMessage());
            return [];
        }
    }

    public function getById(int $cotizacionId, int $userId): ?array
    {
        try {
            $sql = 'SELECT c.*, e.nombre as estado_nombre, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email, u.telefono as cliente_telefono, u.cedula as cliente_cedula, u.empresa as cliente_empresa 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    JOIN usuarios u ON c.usuario_id = u.id
                    WHERE c.id = :id AND c.usuario_id = :userId';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $cotizacionId, ':userId' => $userId]);
            
            $cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cotizacion ?: null;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getById - " . $e->getMessage());
            return null;
        }
    }

    public function getAllAdmin(): array
    {
        try {
            $sql = 'SELECT c.*, e.nombre as estado_nombre, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email, u.telefono as cliente_telefono 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    JOIN usuarios u ON c.usuario_id = u.id
                    WHERE c.estado_id != 1
                    ORDER BY c.fecha_solicitud DESC';
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getAllAdmin - " . $e->getMessage());
            return [];
        }
    }

    public function getByIdAdmin(int $id): ?array
    {
        try {
            $sql = 'SELECT c.*, e.nombre as estado_nombre, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email, u.telefono as cliente_telefono, u.cedula as cliente_cedula, u.empresa as cliente_empresa 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    JOIN usuarios u ON c.usuario_id = u.id
                    WHERE c.id = :id AND c.estado_id != 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $cotizacion = $stmt->fetch(PDO::FETCH_ASSOC);
            return $cotizacion ?: null;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::getByIdAdmin - " . $e->getMessage());
            return null;
        }
    }
}
