<?php
require_once __DIR__ . '/../models/Subcategory.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/db.php';

class SubcategoryController {
    public function index() {
        global $conn;
        $subcategorias = Subcategory::getAll($conn);
        include __DIR__ . '/../views/subcategorias/index.php';
    }

    public function create() {
        global $conn;
        $categorias = Category::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_categoria = $_POST['id_categoria'];
            Subcategory::create($conn, $nombre, $descripcion, $id_categoria);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/subcategorias/create.php';
    }

    public function edit($id) {
        global $conn;
        $subcategoria = Subcategory::getById($conn, $id);
        $categorias = Category::getAll($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_categoria = $_POST['id_categoria'];
            Subcategory::update($conn, $id, $nombre, $descripcion, $id_categoria);
            header('Location: index.php');
            exit();
        }
        include __DIR__ . '/../views/subcategorias/edit.php';
    }

    public function delete($id) {
        global $conn;
        Subcategory::delete($conn, $id);
        header('Location: index.php');
        exit();
    }
}
?>
