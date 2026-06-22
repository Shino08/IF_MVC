<?php
require_once __DIR__ . '/autoload.php';

use App\Core\Database;

try {
    $db = Database::getInstance();
    $db->exec("ALTER TABLE usuarios ADD COLUMN session_token VARCHAR(255) DEFAULT NULL;");
    echo "Column added successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
