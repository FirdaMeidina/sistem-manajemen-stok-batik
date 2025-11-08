<?php
require_once __DIR__ . '/../models/TransaksiModel.php';
require_once __DIR__ . '/../config/koneksi.php';

class TransaksiController {
    private $model;

    public function __construct($conn) {
        $this->model = new TransaksiModel($conn);
    }

    public function index() {
        return $this->model->getAll();
    }

    public function tambah($data, $details) {
        return $this->model->create($data, $details);
    }

    public function hapus($id) {
        return $this->model->delete($id);
    }
}
?>
