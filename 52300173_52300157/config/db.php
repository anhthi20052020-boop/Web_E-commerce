<?php
$host = "localhost";
$dbname = "techshop_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

// Tao admin mac dinh neu chua co
$stmt = $pdo->query("SELECT COUNT(*) FROM admins");
if ($stmt->fetchColumn() == 0) {
    $password = password_hash('123456', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)")
        ->execute(['admin', $password]);
}
?>
