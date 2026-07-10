<?php
require __DIR__ . '/../config/config.php';

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass']
);

$name = 'Crislan';
$email = 'admin@crislan.com';
$password = 'Masso123.,'; // TROCAR antes de rodar
$securityQuestion = 'Qual o nome do seu primeiro animal de estimação?'; // TROCAR
$securityAnswer = 'RESPOSTA_SECRETA'; // TROCAR

$hash = password_hash($password, PASSWORD_DEFAULT);
$answerHash = password_hash(mb_strtolower(trim($securityAnswer)), PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "INSERT INTO users (name, email, password, security_question, security_answer_hash) 
     VALUES (?, ?, ?, ?, ?)"
);
$stmt->execute([$name, $email, $hash, $securityQuestion, $answerHash]);

echo "Usuário admin criado com sucesso! ID: " . $pdo->lastInsertId();
echo "<br><strong>⚠️ APAGUE ESTE ARQUIVO AGORA!</strong>";