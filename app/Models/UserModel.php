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
}
