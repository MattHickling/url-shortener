<?php
namespace Src;

class UrlRedirect {
    private $conn;

    public function __construct($mysqli) {
        $this->conn = $mysqli;
    }

    public function redirect(string $shortCode) {
        $stmt = $this->conn->prepare("SELECT original_url FROM urls WHERE short_code = ? LIMIT 1");
        $stmt->bind_param("s", $shortCode);
        $stmt->execute();
        $stmt->bind_result($originalUrl);
        $stmt->fetch();
        $stmt->close();

        if ($originalUrl) {
            header("Location: " . $originalUrl);
            exit();
        } else {
            echo "Short URL not found.";
        }
    }
}
