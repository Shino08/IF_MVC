<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\CategoriasModel;

class CategoriasController extends Router
{
    // Procesa Crear y Editar
    public function store(): void
    {
        header('Content-Type: application/json');

        $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
        $nombre = strip_tags(trim($_POST['nombre'] ?? ''));

        if (empty($nombre)) {
            echo json_encode(['success' => false, 'error' => 'El nombre de la categoría es obligatorio.']);
            exit;
        }

        $catModel = new CategoriasModel();

        // Validar nombre duplicado
        if ($catModel->findByName($nombre, $id)) {
            echo json_encode(['success' => false, 'error' => "Ya existe una categoría llamada '{$nombre}'."]);
            exit;
        }

        if ($id) {
            // Es una edición
            $exito = $catModel->update($id, $nombre);
            $mensaje = 'Categoría actualizada con éxito.';
        } else {
            // Es una creación
            $exito = $catModel->create($nombre);
            $mensaje = 'Categoría creada con éxito.';
        }

        if ($exito) {
            echo json_encode(['success' => true, 'message' => $mensaje]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error en la base de datos al guardar la categoría.']);
        }
        exit;
    }

    // Procesa la Eliminación
    public function delete(): void
    {
        header('Content-Type: application/json');

        $id = (int)($_POST['id'] ?? 0);

        if ($id === 0) {
            echo json_encode(['success' => false, 'error' => 'ID de categoría no válido.']);
            exit;
        }

        $catModel = new CategoriasModel();
        $resultado = $catModel->delete($id);

        if ($resultado === 'success') {
            echo json_encode(['success' => true]);
        } elseif ($resultado === 'in_use') {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar esta categoría porque hay productos asignados a ella. Cambia la categoría de esos productos primero.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Hubo un error interno al intentar eliminar la categoría.']);
        }
        exit;
    }
}