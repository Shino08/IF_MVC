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

    public function crearFactura(int $cotizacionId): ?int
    {
        try {
            // Check if it already exists
            $stmt = $this->db->prepare('SELECT id FROM facturas WHERE cotizacion_id = :cid');
            $stmt->execute([':cid' => $cotizacionId]);
            if ($stmt->fetchColumn()) {
                return null; // Already exists
            }

            // Get cotizacion details
            $sql = 'SELECT c.*, u.nombre, u.apellido, u.cedula_rif, u.direccion, u.email, m.metodo 
                    FROM cotizaciones c 
                    LEFT JOIN usuarios u ON c.usuario_id = u.id 
                    LEFT JOIN metodos_de_pagos m ON c.id_metodo_pago = m.id 
                    WHERE c.id = :cid';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cid' => $cotizacionId]);
            $cot = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cot) return null;

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
            $insertSql = 'INSERT INTO facturas (cotizacion_id, numero_factura, subtotal, impuestos, descuento, costo_envio, total, cliente_nombre, cliente_cedula, cliente_direccion, cliente_email, metodo_pago_texto)
                          VALUES (:cid, :num, :sub, :imp, :desc, :envio, :tot, :nom, :ced, :dir, :email, :metodo)';
            $stmt = $this->db->prepare($insertSql);
            $stmt->execute([
                ':cid' => $cotizacionId,
                ':num' => $numFac,
                ':sub' => $cot['subtotal'],
                ':imp' => $cot['impuestos'],
                ':desc' => $cot['descuento'],
                ':envio' => $cot['costo_envio'] ?? 0,
                ':tot' => $cot['total'],
                ':nom' => trim(($cot['nombre'] ?? '') . ' ' . ($cot['apellido'] ?? '')),
                ':ced' => $cot['cedula_rif'] ?? '',
                ':dir' => $cot['direccion_facturacion'] ?: ($cot['direccion'] ?? ''),
                ':email' => $cot['email'] ?? '',
                ':metodo' => $cot['metodo'] ?? 'Transferencia/Otro'
            ]);

            return (int)$this->db->lastInsertId();

        } catch (PDOException $e) {
            error_log("Error crearFactura: " . $e->getMessage());
            return null;
        }
    }
    
    public function getByCotizacionId(int $cotId) {
        $stmt = $this->db->prepare('SELECT * FROM facturas WHERE cotizacion_id = :cid LIMIT 1');
        $stmt->execute([':cid' => $cotId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getById(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM facturas WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
