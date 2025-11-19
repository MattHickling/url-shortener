<?php
namespace Src\Auth;

class AuthService {
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function login($email, $password) {
        $user = $this->user->getByEmail($email);
        if (!$user) {
            return ['success' => false, 'message' => 'Email not found'];
        }

        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Incorrect password'];
        }

        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        return ['success' => true];
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

    public function forgotPassword($email, $newPassword) {
        if (!$this->user->exists($email)) {
            return ['success' => false, 'message' => 'Email not found'];
        }
        $this->user->updatePassword($email, $newPassword);
        return ['success' => true, 'message' => 'Password updated'];
    }

    public function check() {
        session_start();
        return isset($_SESSION['user_id']);
    }
}
