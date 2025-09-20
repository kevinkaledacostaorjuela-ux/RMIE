<?php
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../../config/db.php';

class CategoryController {
	public function index() {
		global $conn;
		$categorias = Category::getAll($conn);
		include __DIR__ . '/../views/categorias/index.php';
	}

	public function create() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			global $conn;
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['descripcion'];
			Category::create($conn, $nombre, $descripcion);
			header('Location: index.php');
			exit();
		}
		include __DIR__ . '/../views/categorias/create.php';
	}

	public function edit($id) {
		global $conn;
		$categoria = Category::getById($conn, $id);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['descripcion'];
			Category::update($conn, $id, $nombre, $descripcion);
			header('Location: index.php');
			exit();
		}
		include __DIR__ . '/../views/categorias/edit.php';
	}

	public function delete($id) {
		global $conn;
		Category::delete($conn, $id);
		header('Location: index.php');
		exit();
	}
}
?>
