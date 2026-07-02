<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Router;
use App\Models\ProductsModel;
use App\Models\ServiciosModel;

class CatalogoController extends Router
{
    public function index(): void
    {
        $productsModel = new ProductsModel();
        $serviciosModel = new ServiciosModel();
        $categoriasModel = new \App\Models\CategoriasModel();

        $productos = $productsModel->getAllProductsWithCategory();
        $servicios = $serviciosModel->getAll();
        $categorias = $categoriasModel->getActive();

        $data = [
            'title' => 'Catálogo de Productos y Servicios — InstalFuego',
            'productos' => $productos,
            'servicios' => $servicios,
            'categorias' => $categorias
        ];

        $this->view('catalogo/index', $data);
    }

    public function producto(string $id): void
    {
        $productsModel = new ProductsModel();
        $producto = $productsModel->findById((int)$id);

        if (!$producto) {
            header('Location: ' . $this->baseUrl() . '/catalogo');
            exit;
        }

        $productos_similares = [];
        if (!empty($producto['categoria_id'])) {
            $productos_similares = $productsModel->getSimilares((int)$producto['categoria_id'], (int)$id, 4);
        }

        $data = [
            'title' => htmlspecialchars($producto['nombre']) . ' — InstalFuego',
            'producto' => $producto,
            'productos_similares' => $productos_similares,
            'imagenes' => $productsModel->getImages((int)$id)
        ];

        $this->view('catalogo/producto_detalle', $data);
    }

    public function servicio(string $id): void
    {
        $serviciosModel = new ServiciosModel();
        $servicio = $serviciosModel->findById((int)$id);

        if (!$servicio) {
            header('Location: ' . $this->baseUrl() . '/catalogo');
            exit;
        }

        $servicios_similares = [];
        if (!empty($servicio['categoria_id'])) {
            $servicios_similares = $serviciosModel->getSimilares((int)$servicio['categoria_id'], (int)$id, 4);
        }

        $data = [
            'title' => htmlspecialchars($servicio['nombre']) . ' — InstalFuego',
            'servicio' => $servicio,
            'servicios_similares' => $servicios_similares,
            'imagenes' => $serviciosModel->getImages((int)$id)
        ];

        $this->view('catalogo/servicio_detalle', $data);
    }

    private function baseUrl(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        return rtrim(str_replace('/index.php', '', $scriptName), '/');
    }
}
