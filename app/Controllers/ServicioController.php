<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\ServiciosModel;

class ServicioController extends Router
{
    // ─── Directorio físico de imágenes ───────────────────────────────
    private function imgDir(): string
    {
        return dirname(__DIR__, 2) . '/public/img/servicios/';
    }

    // ─── URL base ────────────────────────────────────────────────────
    private function baseUrl(): string
    {
        return rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/');
    }

    // ─── Helper: procesar MÚLTIPLES archivos subidos ─────────────────
    private function processUploads(string $codigo, string $inputName = 'imagenes'): array
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

            $nuevoNombre  = "{$codigo}_" . time() . "_{$i}.{$extension}";
            $rutaCompleta = $directorioDestino . $nuevoNombre;

            if (move_uploaded_file($files['tmp_name'][$i], $rutaCompleta)) {
                $imagenesSubidas[] = $nuevoNombre;
            }
        }

        return $imagenesSubidas;
    }

    // ─── Borrar archivo físico si existe ─────────────────────────────
    private function removeFile(?string $nombre): void
    {
        if ($nombre) {
            $path = $this->imgDir() . $nombre;
            if (file_exists($path)) @unlink($path);
        }
    }

    // ═══════════════════════════════════════════════════════════════
    // CREAR servicio
    // ═══════════════════════════════════════════════════════════════
    public function store(): void
    {
        header('Content-Type: application/json');

        $codigo    = strtoupper(strip_tags(trim($_POST['codigo']     ?? '')));
        $nombre    = strip_tags(trim($_POST['nombre']                ?? ''));
        $catId     = (int)($_POST['categoria_id']                    ?? 0);
        $precio    = (float)($_POST['precio']                        ?? 0);
        $tipoCobro = (int)($_POST['tipo_cobro_id']                   ?? 0);
        $desc      = strip_tags(trim($_POST['descripcion']           ?? ''));

        if (empty($codigo) || empty($nombre)) {
            echo json_encode(['success' => false, 'error' => 'El Código y el Nombre son obligatorios.']);
            exit;
        }
        if ($precio <= 0) {
            echo json_encode(['success' => false, 'error' => 'El precio referencial debe ser mayor a 0.']);
            exit;
        }

        $model = new ServiciosModel();

        if ($model->findByCodigo($codigo)) {
            echo json_encode(['success' => false, 'error' => "El código '{$codigo}' ya está registrado."]);
            exit;
        }

        if (isset($_FILES['imagenes']) && is_array($_FILES['imagenes']['name']) && count($_FILES['imagenes']['name']) > 5) {
            echo json_encode(['success' => false, 'error' => 'No puedes subir más de 5 imágenes.']);
            exit;
        }

        $imagenes = $this->processUploads($codigo);

        try {
            $id = $model->create($codigo, $nombre, $catId ?: null, $precio, $tipoCobro ?: null, $desc, $imagenes);
            echo json_encode([
                'success'  => true,
                'message'  => 'Servicio creado correctamente.',
                'redirect' => $this->baseUrl() . '/dashboard/servicios',
            ]);
        } catch (\Exception $e) {
            foreach ($imagenes as $img) {
                $this->removeFile($img);
            }
            echo json_encode(['success' => false, 'error' => 'Error al guardar el servicio.']);
        }
        exit;
    }

    // ═══════════════════════════════════════════════════════════════
    // ACTUALIZAR servicio
    // ═══════════════════════════════════════════════════════════════
    public function update(int $id): void
    {
        header('Content-Type: application/json');

        $codigo    = strtoupper(strip_tags(trim($_POST['codigo']     ?? '')));
        $nombre    = strip_tags(trim($_POST['nombre']                ?? ''));
        $catId     = (int)($_POST['categoria_id']                    ?? 0);
        $precio    = (float)($_POST['precio']                        ?? 0);
        $tipoCobro = (int)($_POST['tipo_cobro_id']                   ?? 0);
        $desc      = strip_tags(trim($_POST['descripcion']           ?? ''));

        if (empty($codigo) || empty($nombre)) {
            echo json_encode(['success' => false, 'error' => 'El Código y el Nombre son obligatorios.']);
            exit;
        }

        $model = new ServiciosModel();

        if ($model->findByCodigo($codigo, $id)) {
            echo json_encode(['success' => false, 'error' => "El código '{$codigo}' ya está en uso."]);
            exit;
        }

        // Validar máximo 5 imágenes
        if (isset($_FILES['imagenes']) && is_array($_FILES['imagenes']['name']) && count($_FILES['imagenes']['name']) > 5) {
            echo json_encode(['success' => false, 'error' => 'No puedes subir más de 5 imágenes.']);
            exit;
        }

        // Subir nuevas imágenes si se enviaron
        $nuevasImagenes = $this->processUploads($codigo);
        // Opcional: borrar las viejas? No, update solo reemplaza si se enviaron.
        // wait, the previous code only replaced the principal one if sent.
        // Actually the model update now handles logic if you send an array. It replaces principal and adds to gallery.
        $ok = $model->update($id, $codigo, $nombre, $catId ?: null, $precio, $tipoCobro ?: null, $desc, $nuevasImagenes);

        echo json_encode($ok
            ? ['success' => true, 'message' => 'Servicio actualizado.', 'redirect' => $this->baseUrl() . '/dashboard/servicios']
            : ['success' => false, 'error' => 'Error al actualizar el servicio.']
        );
        exit;
    }

    // ═══════════════════════════════════════════════════════════════
    // ELIMINAR servicio
    // ═══════════════════════════════════════════════════════════════
    public function delete(): void
    {
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);
        if ($id === 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido.']);
            exit;
        }

        $model = new ServiciosModel();
        $img   = $model->getImagen($id);
        $ok    = $model->delete($id);

        if ($ok) {
            $this->removeFile($img);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el servicio.']);
        }
        exit;
    }

    // ═══════════════════════════════════════════════════════════════
    // BORRAR IMAGEN del servicio
    // ═══════════════════════════════════════════════════════════════
    public function deleteImagen(): void
    {
        header('Content-Type: application/json');

        $id = (int)($_POST['servicio_id'] ?? 0);
        if ($id === 0) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
            exit;
        }

        $model = new ServiciosModel();
        $vieja = $model->deleteImagen($id);

        if ($vieja === null) {
            echo json_encode(['success' => false, 'error' => 'No hay imagen para borrar.']);
            exit;
        }

        $this->removeFile($vieja);
        echo json_encode(['success' => true]);
        exit;
    }

    // ═══════════════════════════════════════════════════════════════
    // ELIMINAR imagen de galería
    // ═══════════════════════════════════════════════════════════════
    public function deleteImage(): void
    {
        header('Content-Type: application/json');

        $imageId = (int)($_POST['image_id'] ?? 0);
        $servicioId = (int)($_POST['servicio_id'] ?? 0);

        if ($imageId === 0 || $servicioId === 0) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
            exit;
        }

        $model = new ServiciosModel();
        $ruta = $model->deleteImage($imageId, $servicioId);

        if ($ruta) {
            $this->removeFile($ruta);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo eliminar la imagen.']);
        }
        exit;
    }
}
