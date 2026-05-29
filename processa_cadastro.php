<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$nome      = trim($_POST['nome'] ?? '');
$email     = trim($_POST['email'] ?? '');
$senha     = $_POST['senha'] ?? '';
$cpf       = trim($_POST['cpf'] ?? '');
$endereco  = trim($_POST['endereco'] ?? '');
$telefone  = trim($_POST['telefone'] ?? '');
$data_nascimento = trim($_POST['data_nascimento'] ?? '');

if ($nome === '' || $email === '' || $senha === '') {
    header("Location: login.php?erro=campos");
    exit;
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Tratar campos vazios como NULL
$cpf_db = !empty($cpf) ? $cpf : null;
$endereco_db = !empty($endereco) ? $endereco : null;
$telefone_db = !empty($telefone) ? $telefone : null;
$data_nascimento_db = !empty($data_nascimento) ? $data_nascimento : null;

// tipo por padrão cliente
$stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash, cpf, endereco, telefone, data_nascimento, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, 'cliente')");
try {
    $stmt->execute([$nome, $email, $senha_hash, $cpf_db, $endereco_db, $telefone_db, $data_nascimento_db]);
    
    // GATILHO DO MODAL ADICIONADO AQUI
    $_SESSION['msg_sucesso'] = "Sua conta foi criada com sucesso! Agora você já pode fazer o login.";
    header("Location: login.php");
    exit;
    
} catch (PDOException $e) {
    header("Location: login.php?erro=email");
    exit;
}