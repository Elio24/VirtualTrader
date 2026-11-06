<?php
$host = getenv("DB_HOST") ?: "localhost";
$dbname = getenv("DB_DBNAME") ?: "virtual_trader";
$username = getenv("DB_USERNAME") ?: "root";
$password = getenv("DB_PASSWORD") ?: "";                    

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}
?>
