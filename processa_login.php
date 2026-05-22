<?php
session_start();
require_once __DIR__ . '/db.php';

// URL base do projeto
$baseUrl = 'http://localhost/Dimitri_31_prog/angelita';

// Garante que veio por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . '/login.php');
    exit;
}

// Lê dados do formulário
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validação simples
if ($email === '' || $senha === '') {
    $_SESSION['erro_login'] = 'Preencha email e senha.';
    header('Location: ' . $baseUrl . '/login.php');
    exit;
}

// Busca usuário por email
$stmt = $conn->prepare("
    SELECT id, nome, email, senha_hash, tipo 
    FROM usuarios 
    WHERE email = ? 
    LIMIT 1
");
$stmt->bind_param("s", $email);
$stmt->execute();
$res     = $stmt->get_result();
$usuario = $res->fetch_assoc();

// Confere usuário e senha
if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
    $_SESSION['erro_login'] = 'Email ou senha incorretos.';
    header('Location: ' . $baseUrl . '/login.php');
    exit;
}

// Login OK: guarda dados na sessão
$_SESSION['usuario_id']   = (int)$usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['usuario_tipo'] = $usuario['tipo'];

// Redireciona conforme o tipo
if ($usuario['tipo'] === 'admin') {
    header('Location: ' . $baseUrl . '/admin/admin.php');
} else {
    header('Location: ' . $baseUrl . '/index.php');
}
exit;