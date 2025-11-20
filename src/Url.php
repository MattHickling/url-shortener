<?php
namespace Src;

use Carbon\Carbon;

class Url {
    private $conn;

    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function create(int $userId, string $originalUrl): array {
        $createdAt = Carbon::now()->toDateTimeString();

        $stmt = $this->conn->prepare(
            "INSERT INTO urls (user_id, original_url, created_at) VALUES (?, ?, ?)"
        );
        if (!$stmt) {
            return ['success' => false, 'message' => 'Failed to prepare statement'];
        }
        $stmt->bind_param("iss", $userId, $originalUrl, $createdAt);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to insert URL'];
        }

        $stmt->close();

        $shortCode = $this->generateShortCode($originalUrl);

        return [
            'success' => true,
            'message' => 'URL shortened successfully',
            'short_code' => $shortCode
        ];
    }

    public function generateShortCode(string $originalUrl): string {
        $stmt = $this->conn->prepare("SELECT short_code FROM urls WHERE original_url = ?");
        $stmt->bind_param("s", $originalUrl);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($result && !empty($result['short_code'])) {
            return $result['short_code'];
        }
        $shortCode = substr(md5(uniqid($originalUrl, true)), 0, 6);

        $stmt = $this->conn->prepare("UPDATE urls SET short_code = ? WHERE original_url = ?");
        $stmt->bind_param("ss", $shortCode, $originalUrl);
        $stmt->execute();
        $stmt->close();

        return $shortCode;
    }


    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result ?: null;
    }

    public function getUserFromUrl($email) {
        $stmt = $this->conn->prepare("SELECT username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();
        return $username;
    }
}
