<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class PedidosModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById(int $pedidoId): ?array
    {
        try {
            $sql = 'SELECT p.*, c.estado_id as estado_cotizacion, u.nombre as cliente_nombre, u.apellido as cliente_apellido, u.email as cliente_email 
                    FROM pedidos p 
                    JOIN cotizaciones c ON p.cotizacion_id = c.id 
                    JOIN usuarios u ON p.usuario_id = u.id 
                    WHERE p.id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $pedidoId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error en PedidosModel::getById - " . $e->getMessage());
            return null;
        }
    }

    public function getByCotizacionId(int $cotizacionId): ?array
    {
        try {
            $sql = 'SELECT p.* FROM pedidos p WHERE p.cotizacion_id = :id';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $cotizacionId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error en PedidosModel::getByCotizacionId - " . $e->getMessage());
            return null;
        }
    }

    public function createFromCotizacion(int $cotizacionId, int $usuarioId, float $total, float $subtotal, float $impuestos, float $descuento): int
    {
        try {
            $sql = 'INSERT INTO pedidos (cotizacion_id, usuario_id, total, subtotal, impuestos, descuento, estado_pedido) 
                    VALUES (:cotId, :usrId, :total, :subtotal, :impuestos, :descuento, "pendiente_pago")';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cotId' => $cotizacionId,
                ':usrId' => $usuarioId,
                ':total' => $total,
                ':subtotal' => $subtotal,
                ':impuestos' => $impuestos,
                ':descuento' => $descuento
            ]);
            return (int) $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error en PedidosModel::createFromCotizacion - " . $e->getMessage());
            return 0;
        }
    }

    public function updateLogistics(int $pedidoId, string $tipoEntrega, ?string $direccionEnvio = null, float $costoEnvio = 0): bool
    {
        try {
            $sql = 'UPDATE pedidos SET tipo_entrega = :tipo, direccion_envio = :dir, costo_envio = :costo WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':tipo' => $tipoEntrega,
                ':dir' => $direccionEnvio,
                ':costo' => $costoEnvio,
                ':id' => $pedidoId
            ]);
        } catch (PDOException $e) {
            error_log("Error en PedidosModel::updateLogistics - " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus(int $pedidoId, string $estado): bool
    {
        try {
            $sql = 'UPDATE pedidos SET estado_pedido = :estado WHERE id = :id';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':estado' => $estado,
                ':id' => $pedidoId
            ]);
        } catch (PDOException $e) {
            error_log("Error en PedidosModel::updateStatus - " . $e->getMessage());
            return false;
        }
    }
}
