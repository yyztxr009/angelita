<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$token = $_POST['token'] ?? '';
$senha = $_POST['senha'] ?? '';

if (strlen($senha) < 6 || strlen($senha) > 10) {
    echo "Senha fora do padrão.";
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id, token_expira_em FROM usuarios WHERE token_recuperacao = ?"
);
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Token inválido.";
    exit;
}

if ($user['token_expira_em'] < date('Y-m-d H:i:s')) {
    echo "Token expirado.";
    exit;
}

$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    "UPDATE usuarios 
     SET senha_hash = ?, token_recuperacao = NULL, token_expira_em = NULL
     WHERE id = ?"
);
$stmt->execute([$hash, $user['id']]);

echo "Senha alterada com sucesso. Agora você já pode fazer login.";
