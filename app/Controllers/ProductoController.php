<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\ProductsModel;

class ProductoController extends Router
{
    // ─── Helper: directorio de imágenes ──────────────────────────────
    private function imgDir(): string
    {
        return dirname(__DIR__, 2) . '/public/img/productos/';
    }

    // ─── Helper: procesar UN archivo subido (para reemplazar) ────────
    private function processSingleUpload(string $sku, string $inputName = 'imagen'): array
    {
        $directorioDestino = $this->imgDir();
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return [];
        }

        $file      = $_FILES[$inputName];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $permitidas)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => "El archivo {$file['name']} no es una imagen válida."]);
            exit;
        }

        $nuevoNombre  = "{$sku}_" . time() . "_0.{$extension}";
        $rutaCompleta = $directorioDestino . $nuevoNombre;

        if (move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            return [$nuevoNombre];
        }

        return [];
    }

    // ─── Helper: procesar MÚLTIPLES archivos subidos ─────────────────
    private function processUploads(string $sku, string $inputName = 'imagenes'): array
    {
        $imagenesSubidas   = [];
        $directorioDestino = $this->imgDir();

        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true);
        }

        if (!isset($_FILES[$inputName]) || empty($_FILES[$inputName]['name'][0])) {
            return [];
        }

        $files      = $_FILES[$inputName];
        $totalFiles = count($files['name']);

        for ($i = 0; $i < $totalFiles; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

            $extension  = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $permitidas)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => "El archivo {$files['name'][$i]} no es una imagen válida."]);
                exit;
            }

            $nuevoNombre  = "{$sku}_" . time() . "_{$i}.{$extension}";
            $rutaCompleta = $directorioDestino . $nuevoNombre;

            if (move_uploaded_file($files['tmp_name'][$i], $rutaCompleta)) {
                $imagenesSubidas[] = $nuevoNombre;
            }
        }

        return $imagenesSubidas;
    }

    // ─── CREAR producto ──────────────────────────────────────────────
    public function store(): void
    {
        header('Content-Type: application/json');

        $nombre      = strip_tags(trim($_POST['nombre'] ?? ''));
        $sku         = strtoupper(strip_tags(trim($_POST['sku'] ?? '')));
        $categoria_id = (int)($_POST['categoria_id'] ?? 0);
        $precio      = (float)($_POST['precio'] ?? 0);
        $marca       = strip_tags(trim($_POST['marca'] ?? ''));
        $modelo_prod = strip_tags(trim($_POST['modelo'] ?? ''));
        $existencia  = (int)($_POST['existencia'] ?? 0);
        $descripcion = strip_tags(trim($_POST['descripcion'] ?? ''));

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

        // Validar máximo 5 imágenes
        if (isset($_FILES['imagenes']) && count($_FILES['imagenes']['name']) > 5) {
            echo json_encode(['success' => false, 'error' => 'No puedes subir más de 5 imágenes.']);
            exit;
        }

        $imagenesSubidas = $this->processUploads($sku);

        $exito = $productModel->saveFullProduct(
            $nombre, $sku, $categoria_id, $precio, $marca, $modelo_prod,
            $existencia, $descripcion, $imagenesSubidas
        );

        if ($exito) {
            echo json_encode([
                'success'  => true,
                'message'  => 'Producto guardado con éxito. Redirigiendo...',
                'redirect' => $this->baseUrl() . '/dashboard/productos'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Hubo un error al registrar los datos en MySQL.']);
        }
        exit;
    }

    // ─── ACTUALIZAR producto ─────────────────────────────────────────
    public function update(int $id): void
    {
        header('Content-Type: application/json');

        $nombre      = strip_tags(trim($_POST['nombre'] ?? ''));
        $sku         = strtoupper(strip_tags(trim($_POST['sku'] ?? '')));
        $categoria_id = (int)($_POST['categoria_id'] ?? 0);
        $precio      = (float)($_POST['precio'] ?? 0);
        $marca       = strip_tags(trim($_POST['marca'] ?? ''));
        $modelo_prod = strip_tags(trim($_POST['modelo'] ?? ''));
        $existencia  = (int)($_POST['existencia'] ?? 0);
        $descripcion = strip_tags(trim($_POST['descripcion'] ?? ''));

        if (empty($nombre) || empty($sku) || $categoria_id === 0) {
            echo json_encode(['success' => false, 'error' => 'Por favor, completa el Nombre, SKU y Categoría.']);
            exit;
        }

        if ($precio <= 0) {
            echo json_encode(['success' => false, 'error' => 'El precio debe ser mayor a 0.']);
            exit;
        }

        $productModel = new ProductsModel();

        if (!$productModel->findById($id)) {
            echo json_encode(['success' => false, 'error' => 'Producto no encontrado.']);
            exit;
        }

        if ($productModel->findBySku($sku, $id)) {
            echo json_encode(['success' => false, 'error' => "El código SKU '{$sku}' ya está registrado en otro producto."]);
            exit;
        }

        $imagenesSubidas = $this->processUploads($sku);

        $exito = $productModel->update(
            $id, $nombre, $sku, $categoria_id, $precio, $marca, $modelo_prod,
            $existencia, $descripcion, $imagenesSubidas
        );

        if ($exito) {
            echo json_encode([
                'success'  => true,
                'message'  => 'Producto actualizado con éxito. Redirigiendo...',
                'redirect' => $this->baseUrl() . '/dashboard/productos'
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Hubo un error al actualizar en MySQL.']);
        }
        exit;
    }

    // ─── ELIMINAR producto ───────────────────────────────────────────
    public function delete(): void
    {
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);
        if ($id === 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido.']);
            exit;
        }

        $productModel = new ProductsModel();
        $producto     = $productModel->findById($id);

        if (!$producto) {
            echo json_encode(['success' => false, 'error' => 'Producto no encontrado.']);
            exit;
        }

        // Borrar imagen principal del disco
        if (!empty($producto['imagen_principal'])) {
            $ruta = $this->imgDir() . $producto['imagen_principal'];
            if (file_exists($ruta)) @unlink($ruta);
        }

        // Borrar imágenes de galería del disco
        $imagenes = $productModel->getImages($id);
        foreach ($imagenes as $img) {
            $ruta = $this->imgDir() . $img['ruta_imagen'];
            if (file_exists($ruta)) @unlink($ruta);
        }

        $exito = $productModel->delete($id);

        echo json_encode($exito
            ? ['success' => true]
            : ['success' => false, 'error' => 'No se pudo eliminar el producto.']
        );
        exit;
    }

    // ─── ELIMINAR imagen específica ──────────────────────────────────
    public function deleteImage(): void
    {
        header('Content-Type: application/json');

        $imageId    = (int)($_POST['image_id'] ?? 0);
        $productoId = (int)($_POST['producto_id'] ?? 0);
        $tipo       = $_POST['tipo'] ?? 'galeria'; // 'galeria' o 'principal'

        if ($productoId === 0) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
            exit;
        }

        $productModel = new ProductsModel();

        if ($tipo === 'principal') {
            $rutaVieja = $productModel->deletePrincipal($productoId);
            if ($rutaVieja === null) {
                echo json_encode(['success' => false, 'error' => 'No se encontró la imagen principal.']);
                exit;
            }
            $archivo = $this->imgDir() . $rutaVieja;
            if (file_exists($archivo)) @unlink($archivo);
            echo json_encode(['success' => true]);
            exit;
        }

        // Galería
        if ($imageId === 0) {
            echo json_encode(['success' => false, 'error' => 'ID de imagen inválido.']);
            exit;
        }

        $rutaVieja = $productModel->deleteImage($imageId, $productoId);
        if ($rutaVieja === null) {
            echo json_encode(['success' => false, 'error' => 'Imagen no encontrada.']);
            exit;
        }

        $archivo = $this->imgDir() . $rutaVieja;
        if (file_exists($archivo)) @unlink($archivo);

        echo json_encode(['success' => true]);
        exit;
    }

    // ─── REEMPLAZAR imagen específica ────────────────────────────────
    public function replaceImage(): void
    {
        header('Content-Type: application/json');

        $imageId    = (int)($_POST['image_id'] ?? 0);
        $productoId = (int)($_POST['producto_id'] ?? 0);
        $sku        = strtoupper(strip_tags(trim($_POST['sku'] ?? '')));
        $tipo       = $_POST['tipo'] ?? 'galeria';

        if ($productoId === 0 || empty($sku)) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
            exit;
        }

        $nuevas = $this->processSingleUpload($sku, 'imagen');
        if (empty($nuevas)) {
            echo json_encode(['success' => false, 'error' => 'No se recibió ninguna imagen o el archivo no es válido.']);
            exit;
        }

        $productModel = new ProductsModel();
        $nuevaRuta    = $nuevas[0];

        if ($tipo === 'principal') {
            $vieja = $productModel->replacePrincipal($productoId, $nuevaRuta);
        } else {
            if ($imageId === 0) {
                echo json_encode(['success' => false, 'error' => 'ID de imagen inválido.']);
                exit;
            }
            $vieja = $productModel->replaceImage($imageId, $productoId, $nuevaRuta);
        }

        if ($vieja === null) {
            echo json_encode(['success' => false, 'error' => 'Imagen no encontrada para reemplazar.']);
            exit;
        }

        // Borrar archivo antiguo
        $archivo = $this->imgDir() . $vieja;
        if (file_exists($archivo)) @unlink($archivo);

        echo json_encode([
            'success'  => true,
            'nueva_url' => $this->baseUrl() . '/img/productos/' . $nuevaRuta
        ]);
        exit;
    }

    // ─── Helper privado ──────────────────────────────────────────────
    private function baseUrl(): string
    {
        return rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
    }
}