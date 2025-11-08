<?php
class PenggunaModel {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua user
    public function getAll() {
        return mysqli_query($this->conn, "SELECT * FROM users ORDER BY id_user DESC");
    }

    // Tambah user baru
    public function create($data) {
        $nama = mysqli_real_escape_string($this->conn, $data['nama_lengkap']);
        $username = mysqli_real_escape_string($this->conn, $data['username']);
        $email = mysqli_real_escape_string($this->conn, $data['email']);
        $role = $data['role'];
        $status = $data['status_aktif'];
        $password = md5($data['password']); // ⚠️ gunakan hash default agar login cocok

        $query = "INSERT INTO users (nama_lengkap, username, password, role, email, status_aktif)
                  VALUES ('$nama', '$username', '$password', '$role', '$email', '$status')";
        return mysqli_query($this->conn, $query);
    }

    // Edit user
    public function update($id, $data) {
        $nama = mysqli_real_escape_string($this->conn, $data['nama_lengkap']);
        $email = mysqli_real_escape_string($this->conn, $data['email']);
        $role = $data['role'];
        $status = $data['status_aktif'];
        $password = !empty($data['password']) ? ", password='" . md5($data['password']) . "'" : '';

        $query = "UPDATE users SET 
                    nama_lengkap='$nama',
                    email='$email',
                    role='$role',
                    status_aktif='$status'
                    $password
                  WHERE id_user='$id'";
        return mysqli_query($this->conn, $query);
    }

    // Hapus user
    public function delete($id) {
        return mysqli_query($this->conn, "DELETE FROM users WHERE id_user='$id'");
    }
}
?>
