<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class FacturasModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function crearFactura(int $pedidoId): ?int
    {
        try {
            // Check if it already exists
            $stmt = $this->db->prepare('SELECT id FROM facturas WHERE pedido_id = :pid');
            $stmt->execute([':pid' => $pedidoId]);
            if ($stmt->fetchColumn()) {
                return null; // Already exists
            }

            // Get pedido and cotizacion details
            $sql = 'SELECT p.id as pedido_id, p.total, p.costo_envio, p.id_metodo_pago,
                           c.subtotal, c.impuestos, c.descuento, c.direccion_envio, c.tipo_entrega,
                           u.nombre, u.apellido, u.cedula as cliente_cedula, u.direccion as cliente_direccion, u.email,
                           m.metodo as metodo_pago_texto
                    FROM pedidos p
                    JOIN cotizaciones c ON p.cotizacion_id = c.id
                    LEFT JOIN usuarios u ON p.usuario_id = u.id
                    LEFT JOIN metodos_de_pagos m ON p.id_metodo_pago = m.id
                    WHERE p.id = :pid';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':pid' => $pedidoId]);
            $ped = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$ped) return null;

            // Generate invoice number
            $year = date('Y');
            $stmt = $this->db->prepare("SELECT numero_factura FROM facturas WHERE numero_factura LIKE :yr ORDER BY id DESC LIMIT 1");
            $stmt->execute([':yr' => "FAC-$year-%"]);
            $last = $stmt->fetchColumn();
            $next = 1;
            if ($last) {
                $parts = explode('-', $last);
                if (count($parts) === 3) {
                    $next = (int)$parts[2] + 1;
                }
            }
            $numFac = sprintf("FAC-%s-%06d", $year, $next);

            // Insert invoice
            $insertSql = 'INSERT INTO facturas (pedido_id, numero_factura, subtotal, impuestos, descuento, costo_envio, total, cliente_nombre, cliente_cedula, cliente_direccion, cliente_email, metodo_pago_texto)
                          VALUES (:pid, :num, :sub, :imp, :desc, :envio, :tot, :nom, :ced, :dir, :email, :metodo)';
            $stmt = $this->db->prepare($insertSql);
            $stmt->execute([
                ':pid' => $pedidoId,
                ':num' => $numFac,
                ':sub' => $ped['subtotal'],
                ':imp' => $ped['impuestos'],
                ':desc' => $ped['descuento'],
                ':envio' => $ped['costo_envio'] ?? 0,
                ':tot' => $ped['total'],
                ':nom' => trim(($ped['nombre'] ?? '') . ' ' . ($ped['apellido'] ?? '')),
                ':ced' => $ped['cliente_cedula'] ?? '',
                ':dir' => $ped['direccion_envio'] ?: ($ped['cliente_direccion'] ?? ''),
                ':email' => $ped['email'] ?? '',
                ':metodo' => $ped['metodo_pago_texto'] ?? 'Transferencia/Otro'
            ]);

            return (int)$this->db->lastInsertId();

        } catch (PDOException $e) {
            error_log("Error crearFactura: " . $e->getMessage());
            return null;
        }
    }
    
    public function getByCotizacionId(int $cotId) {
        $stmt = $this->db->prepare('SELECT f.* FROM facturas f JOIN pedidos p ON f.pedido_id = p.id WHERE p.cotizacion_id = :cid LIMIT 1');
        $stmt->execute([':cid' => $cotId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getById(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM facturas WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
