<?php
class BaseModel {
    protected $db;

    public function __construct() {
        require_once __DIR__ . '/../../config/Database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
}
