<?php
require_once __DIR__ . '/../models/LaporanModel.php';
require_once __DIR__ . '/../config/koneksi.php';

class LaporanController {
    private $model;

    public function __construct($conn) {
        $this->model = new LaporanModel($conn);
    }

    public function laporanStok() {
        return $this->model->getLaporanStok();
    }

    public function laporanPenjualan($start, $end) {
        return $this->model->getPenjualanByTanggal($start, $end);
    }
}
?>
