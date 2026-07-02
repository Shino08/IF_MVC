<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class PagosModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createPago(array $data): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = 'INSERT INTO pagos (
                        pedido_id, metodo_pago_id, monto, moneda, referencia,
                        banco_origen, telefono_pagador, comprobante_url, estado
                    ) VALUES (
                        :pedido_id, :metodo_pago_id, :monto, :moneda, :referencia,
                        :banco_origen, :telefono_pagador, :comprobante_url, "por_validar"
                    )';
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute([
                ':pedido_id'        => $data['pedido_id'],
                ':metodo_pago_id'   => $data['metodo_pago_id'],
                ':monto'            => $data['monto'],
                ':moneda'           => $data['moneda'],
                ':referencia'       => $data['referencia'],
                ':banco_origen'     => $data['banco_origen'] ?? null,
                ':telefono_pagador' => $data['telefono_pagador'] ?? null,
                ':comprobante_url'  => $data['comprobante_url'] ?? null,
            ]);

            if ($res) {
                // Update pedido state
                $sql2 = 'UPDATE pedidos SET estado_pedido = "pago_por_validar", fecha_pago_reportado = CURRENT_TIMESTAMP WHERE id = :id';
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([':id' => $data['pedido_id']]);
            }

            $this->db->commit();
            return $res;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en PagosModel::createPago - " . $e->getMessage());
            return false;
        }
    }

    public function getPagosByPedido(int $pedidoId): array
    {
        try {
            $sql = 'SELECT p.*, m.metodo as metodo_nombre 
                    FROM pagos p 
                    LEFT JOIN metodos_de_pagos m ON p.metodo_pago_id = m.id 
                    WHERE p.pedido_id = :id 
                    ORDER BY p.fecha_reporte DESC';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $pedidoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error en PagosModel::getPagosByPedido - " . $e->getMessage());
            return [];
        }
    }

    public function validarPago(int $pagoId, string $estado, ?string $obs = null): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = 'UPDATE pagos SET estado = :estado, observaciones_admin = :obs, fecha_validacion = CURRENT_TIMESTAMP WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute([
                ':estado' => $estado,
                ':obs'    => $obs,
                ':id'     => $pagoId
            ]);

            if ($res) {
                $sql2 = 'SELECT pedido_id FROM pagos WHERE id = :id';
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([':id' => $pagoId]);
                $pedidoId = $stmt2->fetchColumn();

                if ($pedidoId) {
                    if ($estado === 'validado') {
                        $sql3 = 'UPDATE pedidos SET estado_pedido = "procesando", fecha_pago_validado = CURRENT_TIMESTAMP WHERE id = :pedido_id';
                        $stmt3 = $this->db->prepare($sql3);
                        $stmt3->execute([':pedido_id' => $pedidoId]);
                    } elseif ($estado === 'rechazado') {
                        $sql3 = 'UPDATE pedidos SET estado_pedido = "pendiente_pago" WHERE id = :pedido_id';
                        $stmt3 = $this->db->prepare($sql3);
                        $stmt3->execute([':pedido_id' => $pedidoId]);
                    }
                }
            }

            $this->db->commit();
            return $res;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en PagosModel::validarPago - " . $e->getMessage());
            return false;
        }
    }

    public function getPedidoIdByPagoId(int $pagoId): ?int
    {
        try {
            $stmt = $this->db->prepare("SELECT pedido_id FROM pagos WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $pagoId]);
            $pedidoId = $stmt->fetchColumn();

            return $pedidoId ? (int)$pedidoId : null;
        } catch (PDOException $e) {
            error_log('Error en PagosModel::getPedidoIdByPagoId - ' . $e->getMessage());
            return null;
        }
    }
}
