<?php
require_once __DIR__ . '/../models/ProdukModel.php';
require_once __DIR__ . '/../config/koneksi.php';

class ProdukController {
    private $model;

    public function __construct($conn) {
        $this->model = new ProdukModel($conn);
    }

    public function index() {
        return $this->model->getAll();
    }

    public function detail($id) {
        return $this->model->getById($id);
    }

    public function tambah($data, $file) {
        $foto = null;
        if (isset($file['foto_produk']) && $file['foto_produk']['error'] == 0) {
            $namaFile = time() . "_" . basename($file['foto_produk']['name']);
            $path = "../uploads/foto_produk/" . $namaFile;
            if (move_uploaded_file($file['foto_produk']['tmp_name'], $path)) {
                $foto = $namaFile;
            }
        }
        $data['foto_produk'] = $foto;
        return $this->model->create($data);
    }

    public function edit($id, $data, $file) {
        if (isset($file['foto_produk']) && $file['foto_produk']['error'] == 0) {
            $namaFile = time() . "_" . basename($file['foto_produk']['name']);
            $path = "../uploads/foto_produk/" . $namaFile;
            move_uploaded_file($file['foto_produk']['tmp_name'], $path);
            $data['foto_produk'] = $namaFile;
        }
        return $this->model->update($id, $data);
    }

    public function hapus($id) {
        return $this->model->delete($id);
    }
}
?>
