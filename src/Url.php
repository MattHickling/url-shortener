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

        $shortCode = $this->generateShortCode();

        $stmt = $this->conn->prepare(
            "INSERT INTO urls (user_id, original_url, short_code, created_at) VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            return ['success' => false, 'message' => 'Failed to prepare statement'];
        }

        $stmt->bind_param("isss", $userId, $originalUrl, $shortCode, $createdAt);

        if (!$stmt->execute()) {
            $stmt->close();
            return ['success' => false, 'message' => 'Failed to insert URL'];
        }

        $stmt->close();

        return [
            'success' => true,
            'message' => 'URL shortened successfully',
            'short_code' => $shortCode
        ];
    }

    private function generateShortCode(int $length = 6): string {
        do {
            $shortCode = substr(md5(uniqid(rand(), true)), 0, $length);

            $stmt = $this->conn->prepare("SELECT id FROM urls WHERE short_code = ? LIMIT 1");
            $stmt->bind_param("s", $shortCode);
            $stmt->execute();
            $stmt->store_result();
            $exists = $stmt->num_rows > 0;
            $stmt->close();
        } while ($exists);

        return $shortCode;
    }

}
