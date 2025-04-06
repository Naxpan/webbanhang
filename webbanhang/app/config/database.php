<?php
class Database {
    private $host = "localhost";
    private $db_name = "my_store"; // Đổi tên database nếu cần
    private $username = "root";
    private $password = "";
    private $conn;

    // Hàm kết nối database và trả về đối tượng PDO
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Kết nối database thất bại: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
