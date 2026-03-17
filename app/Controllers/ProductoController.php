<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\ProductsModel;

class ProductoController extends Router
{
    public function store(): void
    {
        header('Content-Type: application/json');

        $nombre       = strip_tags(trim($_POST['nombre'] ?? ''));
        $sku          = strtoupper(strip_tags(trim($_POST['sku'] ?? '')));
        $categoria_id = (int)($_POST['categoria_id'] ?? 0);
        $precio       = (float)($_POST['precio'] ?? 0);
        $marca        = strip_tags(trim($_POST['marca'] ?? ''));
        $modelo_prod  = strip_tags(trim($_POST['modelo'] ?? ''));
        $existencia   = (int)($_POST['existencia'] ?? 0);
        $descripcion  = strip_tags(trim($_POST['descripcion'] ?? ''));

        if (empty($nombre) || empty($sku) || $categoria_id === 0) {
            echo json_encode(['success' => false, 'error' => 'Por favor, completa el Nombre, SKU y Categoría.']);
            exit;
        }

        if ($precio <= 0) {
            echo json_encode(['success' => false, 'error' => 'El precio debe ser mayor a 0.']);
            exit;
        }

        $productModel = new ProductsModel();
        
        if ($productModel->findBySku($sku)) {
            echo json_encode(['success' => false, 'error' => "El código SKU '{$sku}' ya está registrado en otro producto."]);
            exit;
        }
        $imagenesSubidas = [];
        
        $directorioDestino = dirname(__DIR__, 2) . '/public/img/productos/';

        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0777, true)) {
                echo json_encode(['success' => false, 'error' => 'Error de permisos: No se pudo crear la carpeta para guardar imágenes.']);
                exit;
            }
        }

        if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
            $files = $_FILES['imagenes'];
            $totalFiles = count($files['name']);
            
            if ($totalFiles > 5) {
                echo json_encode(['success' => false, 'error' => 'No puedes subir más de 5 imágenes.']);
                exit;
            }

            for ($i = 0; $i < $totalFiles; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $nombreOriginal = $files['name'][$i];
                    $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
                    
                    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
                    if (!in_array($extension, $permitidas)) {
                        echo json_encode(['success' => false, 'error' => "El archivo {$nombreOriginal} no es una imagen válida."]);
                        exit;
                    }
                    
                    $nuevoNombre = "{$sku}_" . time() . "_{$i}.{$extension}";
                    $rutaCompleta = $directorioDestino . $nuevoNombre;

                    if (move_uploaded_file($files['tmp_name'][$i], $rutaCompleta)) {
                        $imagenesSubidas[] = $nuevoNombre;
                    } else {
                        echo json_encode(['success' => false, 'error' => "Falló al mover. Ruta intentada: " . $rutaCompleta]);
                        exit;
                    }
                }
            }
        }

        $exito = $productModel->saveFullProduct(
            $nombre, $sku, $categoria_id, $precio, $marca, $modelo_prod, 
            $existencia, $descripcion, $imagenesSubidas
        );

        if ($exito) {
            echo json_encode([
                'success' => true, 
                'message' => 'Producto guardado con éxito. Redirigiendo...',
                'redirect' => $this->baseUrl() . '/dashboard/productos'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Hubo un error al registrar los datos en MySQL.']);
        }
        exit;
    }

    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }
}