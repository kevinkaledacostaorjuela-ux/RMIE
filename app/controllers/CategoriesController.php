<?php
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../../config/db.php';

class CategoriesController {
	private $categoriaModel;

	public function __construct($pdo) {
		$this->categoriaModel = new Categoria($pdo);
	}

	public function listar() {
		$categorias = $this->categoriaModel->obtenerTodas();
		include __DIR__ . '/../views/categorias_listar.php';
	}

	public function crear() {
		include __DIR__ . '/../views/categorias_crear.php';
	}

	public function guardar() {
		$nombre = $_POST['nombre'] ?? '';
		$descripcion = $_POST['descripcion'] ?? '';
		$this->categoriaModel->crear($nombre, $descripcion);
		header('Location: ?action=listar_categorias');
		exit;
	}

	public function editar() {
		$id = $_GET['id'] ?? null;
		$categoria = $this->categoriaModel->obtenerPorId($id);
		include __DIR__ . '/../views/categorias_editar.php';
	}

	public function actualizar() {
		$id = $_GET['id'] ?? null;
		$nombre = $_POST['nombre'] ?? '';
		$descripcion = $_POST['descripcion'] ?? '';
		$this->categoriaModel->actualizar($id, $nombre, $descripcion);
		header('Location: ?action=listar_categorias');
		exit;
	}

	public function eliminar() {
		$id = $_GET['id'] ?? null;
		$categoria = $this->categoriaModel->obtenerPorId($id);
		include __DIR__ . '/../views/categorias_eliminar.php';
	}

	public function eliminarConfirmar() {
		$id = $_GET['id'] ?? null;
		$this->categoriaModel->eliminar($id);
		header('Location: ?action=listar_categorias');
		exit;
	}
}
