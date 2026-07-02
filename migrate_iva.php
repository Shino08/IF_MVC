<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = "ALTER TABLE cotizaciones 
        ADD COLUMN aplica_iva TINYINT(1) DEFAULT NULL, 
        ADD COLUMN tasa_iva DECIMAL(5,2) NOT NULL DEFAULT 16.00, 
        ADD COLUMN motivo_exento VARCHAR(255) DEFAULT NULL";
$db->exec($sql);
echo "Migración completada.\n";
