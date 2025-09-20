<?php
require_once __DIR__ . '/../models/Subcategoria.php';
require_once __DIR__ . '/../../config/db.php';

class SubcategoriesController {
    private $subcategoriaModel;

    public function __construct($pdo) {
        $this->subcategoriaModel = new Subcategoria($pdo);
    }

    public function listar() {
        $categorias = $this->subcategoriaModel->obtenerCategorias();
        $filtro_categoria = $_GET['categoria'] ?? '';
        if ($filtro_categoria) {
            $subcategorias = array_filter($this->subcategoriaModel->obtenerTodas(), function($s) use ($filtro_categoria) {
                return $s['id_categoria'] == $filtro_categoria;
            });
        } else {
            $subcategorias = $this->subcategoriaModel->obtenerTodas();
        }
        include __DIR__ . '/../views/subcategorias/subcategorias_listar.php';
    }

    public function crear() {
        $categorias = $this->subcategoriaModel->obtenerCategorias();
        include __DIR__ . '/../views/subcategorias_crear.php';
    }

    public function guardar() {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $id_categoria = $_POST['id_categoria'] ?? '';
        $this->subcategoriaModel->crear($nombre, $descripcion, $id_categoria);
        header('Location: ?action=listar_subcategorias');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        $subcategoria = $this->subcategoriaModel->obtenerPorId($id);
        $categorias = $this->subcategoriaModel->obtenerCategorias();
        include __DIR__ . '/../views/subcategorias_editar.php';
    }

    public function actualizar() {
        $id = $_GET['id'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $id_categoria = $_POST['id_categoria'] ?? '';
        $this->subcategoriaModel->actualizar($id, $nombre, $descripcion, $id_categoria);
        header('Location: ?action=listar_subcategorias');
        exit;
    }

    public function eliminar() {
        $id = $_GET['id'] ?? null;
        $subcategoria = $this->subcategoriaModel->obtenerPorId($id);
        include __DIR__ . '/../views/subcategorias_eliminar.php';
    }

    public function eliminarConfirmar() {
        $id = $_GET['id'] ?? null;
        $this->subcategoriaModel->eliminar($id);
        header('Location: ?action=listar_subcategorias');
        exit;
    }
}
