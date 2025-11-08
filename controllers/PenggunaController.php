<?php
require_once __DIR__ . '/../models/PenggunaModel.php';
require_once __DIR__ . '/../config/koneksi.php';

class PenggunaController {
    private $model;

    public function __construct($conn) {
        $this->model = new PenggunaModel($conn);
    }

    public function index() {
        return $this->model->getAll();
    }

    public function tambah($data) {
        return $this->model->create($data);
    }

    public function edit($id, $data) {
        return $this->model->update($id, $data);
    }

    public function hapus($id) {
        return $this->model->delete($id);
    }
}
?>
