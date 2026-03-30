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

    // ─── Subir un único archivo de imagen ────────────────────────────
    private function uploadSingle(string $codigo, string $inputName = 'imagen'): ?string
    {
        $dir = $this->imgDir();
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        if (!isset($_FILES[$inputName])) {
            error_log("ServicioController::uploadSingle — \$_FILES['{$inputName}'] no existe.");
            return null;
        }

        $errorCode = $_FILES[$inputName]['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($errorCode !== UPLOAD_ERR_OK) {
            if ($errorCode !== UPLOAD_ERR_NO_FILE) {
                error_log("ServicioController::uploadSingle — error de subida: código {$errorCode}");
            }
            return null;
        }

        $file       = $_FILES[$inputName];
        $ext        = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $permitidas)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Formato de imagen no permitido (jpg, png, webp).']);
            exit;
        }

        $nombre = $codigo . '_' . time() . '.' . $ext;
        $dest   = $dir . $nombre;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $nombre;
        }

        error_log("ServicioController::uploadSingle — move_uploaded_file falló. tmp={$file['tmp_name']} dest={$dest} dir_writable=" . (is_writable($dir) ? 'si' : 'no'));
        return null;
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

        $imagen = $this->uploadSingle($codigo);

        try {
            $id = $model->create($codigo, $nombre, $catId ?: null, $precio, $tipoCobro ?: null, $desc, $imagen);
            echo json_encode([
                'success'  => true,
                'message'  => 'Servicio creado correctamente.',
                'redirect' => $this->baseUrl() . '/dashboard/servicios',
            ]);
        } catch (\Exception $e) {
            $this->removeFile($imagen);
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

        // Subir nueva imagen si se envió
        $nuevaImagen = $this->uploadSingle($codigo);
        if ($nuevaImagen) {
            // Borrar la anterior
            $this->removeFile($model->getImagen($id));
        }

        $ok = $model->update($id, $codigo, $nombre, $catId ?: null, $precio, $tipoCobro ?: null, $desc, $nuevaImagen);

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
    // REEMPLAZAR IMAGEN del servicio
    // ═══════════════════════════════════════════════════════════════
    public function reemplazarImagen(): void
    {
        header('Content-Type: application/json');

        $id     = (int)($_POST['servicio_id'] ?? 0);
        $codigo = strtoupper(strip_tags(trim($_POST['codigo'] ?? '')));

        if ($id === 0 || empty($codigo)) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
            exit;
        }

        $nueva = $this->uploadSingle($codigo);
        if (!$nueva) {
            echo json_encode(['success' => false, 'error' => 'No se recibió ninguna imagen válida.']);
            exit;
        }

        $model = new ServiciosModel();
        $vieja = $model->getImagen($id);

        $ok = $this->db_updateImagen($id, $nueva);
        if ($ok) {
            $this->removeFile($vieja);
            echo json_encode([
                'success'   => true,
                'nueva_url' => $this->baseUrl() . '/img/servicios/' . $nueva,
            ]);
        } else {
            // No se pudo actualizar BD, borrar el archivo que subimos
            $this->removeFile($nueva);
            echo json_encode(['success' => false, 'error' => 'Error al actualizar la imagen.']);
        }
        exit;
    }

    // Mini helper para actualizar sólo imagen_principal
    private function db_updateImagen(int $id, string $imagen): bool
    {
        try {
            $db = \App\Core\Database::getInstance();
            $db->prepare('UPDATE servicios SET imagen_principal=:img WHERE id=:id')
               ->execute([':img' => $imagen, ':id' => $id]);
            return true;
        } catch (\PDOException $e) {
            error_log('ServicioController::db_updateImagen — ' . $e->getMessage());
            return false;
        }
    }
}
