<?php
class Database {
    private $host = "localhost";
    private $db_name = "clothing_store";
    private $username = "root";
    private $password = ""; // Thay bằng mật khẩu MySQL của bạn nếu có
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Sử dụng PDO để kết nối an toàn, hỗ trợ prepared statements chống SQL Injection
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Mặc định trả về mảng kết hợp
        } catch(PDOException $exception) {
            die("Lỗi kết nối CSDL: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
