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
            
            $allowed = ['descuento', 'impuestos', 'costo_envio', 'fecha_vencimiento', 'ubicacion', 'fecha_tentativa', 'responsable_nombre', 'responsable_telefono', 'observaciones_tecnicas', 'aplica_iva', 'tasa_iva', 'motivo_exento'];
            
            foreach ($data as $k => $v) {
                if (in_array($k, $allowed)) {
                    $fields[] = "$k = :$k";
                    $params[":$k"] = $v;
                }
            }
            
            if (empty($fields)) return true;
            
            $sql = 'UPDATE cotizaciones SET ' . implode(', ', $fields) . ' WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute($params);
            
            // Recalcular total si cambió descuento o impuestos o costo_envio o aplica_iva
            if (array_key_exists('descuento', $data) || array_key_exists('impuestos', $data) || array_key_exists('costo_envio', $data) || array_key_exists('aplica_iva', $data) || array_key_exists('tasa_iva', $data)) {
                $this->updateCotizacionTotals($cotizacionId);
            }
            
            return $res;
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::updateComercialFields - " . $e->getMessage());
            return false;
        }
    }

    public function emitirCotizacion(int $cotizacionId, string $notasCliente = '', ?float $tasabcv = null, ?float $montousd = null): bool
    {
        try {
            // estado 3 = enviada (solo desde pendiente_revision)
            // Guardar tasa BCV del momento
            $sql = 'UPDATE cotizaciones SET estado_id = 3, notas_tecnicas = COALESCE(:notas, notas_tecnicas), 
                    tasabcv = :tasa, montousd = :usd WHERE id = :id AND estado_id = 2';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':notas' => !empty($notasCliente) ? $notasCliente : null,
                ':tasa'  => $tasabcv,
                ':usd'   => $montousd,
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

            // Obtener descuento, impuestos y costo_envio actuales
            $sqlC = 'SELECT descuento, aplica_iva, tasa_iva, costo_envio FROM cotizaciones WHERE id = :id';
            $stmtC = $this->db->prepare($sqlC);
            $stmtC->execute([':id' => $cotizacionId]);
            $cot = $stmtC->fetch(PDO::FETCH_ASSOC);
            
            $descuento = (float)($cot['descuento'] ?? 0);
            $aplica_iva = $cot['aplica_iva'];
            $tasa_iva = (float)($cot['tasa_iva'] ?? 16.00);
            $costo_envio = (float)($cot['costo_envio'] ?? 0);
            
            $impuestos = ($aplica_iva == 1) ? round($subtotal * ($tasa_iva / 100), 2) : 0.00;
            
            // Total = subtotal + impuestos + costo_envio - descuento
            $total = $subtotal + $impuestos + $costo_envio - $descuento;
            if ($total < 0) $total = 0;

            $sqlUpdate = 'UPDATE cotizaciones SET subtotal = :sub, impuestos = :imp, total = :tot WHERE id = :id';
            $stmtUpd = $this->db->prepare($sqlUpdate);
            $stmtUpd->execute([
                ':sub' => $subtotal,
                ':imp' => $impuestos,
                ':tot' => $total,
                ':id'  => $cotizacionId
            ]);
        } catch (PDOException $e) {
            error_log("Error en CotizacionesModel::updateCotizacionTotals - " . $e->getMessage());
        }
    }

    public function sendCotizacion(int $cotizacionId, string $notas, ?string $tipo_entrega = null, ?string $direccion_envio = null): bool
    {
        try {
            $this->db->beginTransaction();

            // Obtener total actual para calcular Bs
            $stmtCheckTot = $this->db->prepare('SELECT total FROM cotizaciones WHERE id = :id');
            $stmtCheckTot->execute([':id' => $cotizacionId]);
            $totalUsd = (float)$stmtCheckTot->fetchColumn();

            // Obtener tasa BCV
            $tasaData = \App\Core\TasaBCV::getTasa();
            $tasabcv = $tasaData['tasa'] ?? null;
            $montoBs = null;
            if ($tasabcv > 0 && $totalUsd > 0) {
                $montoBs = round($totalUsd * $tasabcv, 2);
            }

            $sql = "UPDATE cotizaciones 
                    SET estado_id = 4, 
                        tipo_flujo = 'compra_directa', 
                        notas_tecnicas = :notas, 
                        tipo_entrega = :tipo_entrega, 
                        direccion_envio = :direccion, 
                        fecha_solicitud = CURRENT_TIMESTAMP,
                        tasabcv = :tasabcv,
                        montousd = :montousd
                    WHERE id = :id AND estado_id = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':notas'         => $notas,
                ':tipo_entrega'  => $tipo_entrega,
                ':direccion'     => $direccion_envio,
                ':tasabcv'       => $tasabcv,
                ':montousd'      => $montoBs,
                ':id'            => $cotizacionId
            ]);

            if ($stmt->rowCount() === 0) {
                $this->db->rollBack();
                return false;
            }

            $stmtCot = $this->db->prepare('SELECT usuario_id, total, costo_envio FROM cotizaciones WHERE id = :id');
            $stmtCot->execute([':id' => $cotizacionId]);
            $cot = $stmtCot->fetch(PDO::FETCH_ASSOC);

            // Verificar si ya existe el pedido para no duplicar (por si acaso)
            $stmtCheck = $this->db->prepare('SELECT id FROM pedidos WHERE cotizacion_id = :id');
            $stmtCheck->execute([':id' => $cotizacionId]);
            if (!$stmtCheck->fetch()) {
                $sqlPed = "INSERT INTO pedidos (cotizacion_id, usuario_id, total, costo_envio, estado_pedido, direccion_envio, tipo_entrega) 
                           VALUES (:cot_id, :usr_id, :tot, :envio, 'pendiente_pago', :dir, :tipo)";
                $stmtPed = $this->db->prepare($sqlPed);
                $stmtPed->execute([
                    ':cot_id' => $cotizacionId,
                    ':usr_id' => $cot['usuario_id'],
                    ':tot'    => $cot['total'],
                    ':envio'  => $cot['costo_envio'],
                    ':dir'    => $direccion_envio,
                    ':tipo'   => $tipo_entrega
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en CotizacionesModel::sendCotizacion - " . $e->getMessage());
            return false;
        }
    }

    public function getHistoryByUserId(int $userId): array
    {
        try {
            $sql = 'SELECT c.*, 
                           COALESCE(p.estado_pedido, e.nombre) as estado_nombre, 
                           p.id as pedido_id, p.estado_pedido 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    LEFT JOIN pedidos p ON c.id = p.cotizacion_id
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
            $sql = 'SELECT c.*, COALESCE(p.estado_pedido, e.nombre) as estado_nombre, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email, u.telefono as cliente_telefono, u.cedula as cliente_cedula, u.empresa as cliente_empresa 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    JOIN usuarios u ON c.usuario_id = u.id
                    LEFT JOIN pedidos p ON c.id = p.cotizacion_id
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
            $sql = 'SELECT c.*, COALESCE(p.estado_pedido, e.nombre) as estado_nombre, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email, u.telefono as cliente_telefono 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    JOIN usuarios u ON c.usuario_id = u.id
                    LEFT JOIN pedidos p ON c.id = p.cotizacion_id
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
            $sql = 'SELECT c.*, COALESCE(p.estado_pedido, e.nombre) as estado_nombre, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email, u.telefono as cliente_telefono, u.cedula as cliente_cedula, u.empresa as cliente_empresa 
                    FROM cotizaciones c
                    JOIN estados_cotizacion e ON c.estado_id = e.id
                    JOIN usuarios u ON c.usuario_id = u.id
                    LEFT JOIN pedidos p ON c.id = p.cotizacion_id
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
