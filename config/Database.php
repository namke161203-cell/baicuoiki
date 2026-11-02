<?php
class Database
{
    private $host = "localhost";
    private $db_name = "clothing_store";
    private $username = "root";
    private $password = "";
    private static $sharedConn = null; // Caching kết nối dùng chung (Singleton Connection Pool)
    public $conn;

    public function getConnection()
    {
        // Tối ưu hiệu năng: Tái sử dụng kết nối đã có, không mở lại kết nối mới cho mỗi Model
        if (self::$sharedConn !== null) {
            $this->conn = self::$sharedConn;
            return $this->conn;
        }

        try {
            // Sử dụng PDO để kết nối an toàn, hỗ trợ prepared statements chống SQL Injection
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // Sử dụng Native Prepared Statements của MySQL để tối ưu tốc độ & bảo mật
                ]
            );
            self::$sharedConn = $this->conn;
        } catch (PDOException $exception) {
            die("Lỗi kết nối CSDL: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
