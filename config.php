<?php
$host = 'localhost';
$dbname = 'pizza';
$username = 'root';
$password = 'SNajainiBdCabuloso1!!';

define('BASE_URL', '/pizzaria/');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
