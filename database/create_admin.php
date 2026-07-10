<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/database.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass']
);

$name = 'Crislan';
$email = 'admin@crislan.com';
$password = 'masso123.,'; // TROCAR antes de rodar

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $hash]);

echo "Usuário admin criado com sucesso! ID: " . $pdo->lastInsertId();
echo "<br><strong>⚠️ APAGUE ESTE ARQUIVO AGORA!</strong>";