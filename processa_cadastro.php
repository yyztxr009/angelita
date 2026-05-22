<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$nome      = trim($_POST['nome'] ?? '');
$email     = trim($_POST['email'] ?? '');
$senha     = $_POST['senha'] ?? '';
$cpf     = $_POST['cpf'] ?? '';
$endereco     = $_POST['endereco'] ?? '';
$telefone     = $_POST['telefone'] ?? '';
$data_nascimento     = $_POST['data_nascimento'] ?? '';


if ($nome === '' || $email === '' || $senha === '') {
    header("Location: login.php?erro=campos");
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// tipo por padrão cliente
$stmt = $pdo->prepare("INSERT INTO usuarios (nome,email,senha_hash,cpf,endereco,telefone,data_nascimento,tipo) VALUES (?,?,?,?,?,?,?,'cliente')");
try {
    $stmt->execute([$nome,$email,$senha_hash]);
    
    // GATILHO DO MODAL ADICIONADO AQUI
    $_SESSION['msg_sucesso'] = "Sua conta foi criada com sucesso! Agora você já pode fazer o login.";
    header("Location: login.php");
    exit;
    
} catch (PDOException $e) {
    header("Location: login.php?erro=email");
    exit;
}
