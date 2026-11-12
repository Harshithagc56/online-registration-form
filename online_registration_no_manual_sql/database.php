<?php
// XAMPP local defaults. For cloud hosting, set DB_HOST, DB_PORT,
// DB_NAME, DB_USER and DB_PASSWORD as environment variables.
$host = getenv("DB_HOST") ?: "127.0.0.1";
$port = getenv("DB_PORT") ?: "3306";
$dbname = getenv("DB_NAME") ?: "student_registration";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD") ?: "";

try {
    // Local XAMPP: connect to MariaDB first, then create the database automatically.
    if (getenv("DB_HOST") === false) {
        $pdo = new PDO(
            "mysql:host={$host};port={$port};charset=utf8mb4",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `{$dbname}`");
    } else {
        // Cloud: the managed database itself must already exist.
        $pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    // Create the registrations table automatically if it does not exist.
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL,
            phone VARCHAR(10) NOT NULL,
            course VARCHAR(100) NOT NULL,
            gender VARCHAR(30) NOT NULL,
            date_of_birth DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

} catch (PDOException $e) {
    http_response_code(500);
    exit("Database connection failed. Make sure MySQL is running in XAMPP and check database.php settings.");
}
?>
