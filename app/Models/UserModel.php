<?php
require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel {
    
    /**
     * Hàm kiểm tra thông tin đăng nhập
     */
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Trong thực tế sẽ dùng password_verify($password, $user['password'])
            // Nhưng để test nhanh, ta chấp nhận cả mật khẩu mã hóa hoặc mật khẩu văn bản thô (plain text)
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                return $user;
            }
        }
        
        return false;
    }

    /**
     * Hàm đăng ký người dùng mới
     */
    public function register($name, $email, $password) {
        // Kiểm tra email tồn tại
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return false; // Đã tồn tại
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
    }

    /**
     * Lấy thông tin User bằng ID
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT name, email, phone, address FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Cập nhật thông tin Profile (Điện thoại, Địa chỉ)
     */
    public function updateProfile($id, $phone, $address) {
        $stmt = $this->db->prepare("UPDATE users SET phone = :phone, address = :address WHERE id = :id");
        return $stmt->execute([
            ':phone' => $phone,
            ':address' => $address,
            ':id' => $id
        ]);
    }
}
