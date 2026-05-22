<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = (int)$_SESSION['usuario_id'];

$nome            = $_POST['nome']            ?? '';
$email           = $_POST['email']           ?? '';
$cpf             = $_POST['cpf']             ?? '';
$telefone        = $_POST['telefone']        ?? '';
$endereco        = $_POST['endereco']        ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';

$senha_atual = $_POST['senha_atual']     ?? "";
$nova        = $_POST['nova_senha']      ?? "";
$confirmar   = $_POST['confirmar_senha'] ?? "";

/* Atualizar campos básicos */
$sql = "UPDATE usuarios
        SET nome = ?, email = ?, cpf = ?, telefone = ?, endereco = ?, data_nascimento = ?
        WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$nome, $email, $cpf, $telefone, $endereco, $data_nascimento, $id]);

/* Alterar senha se algum campo de senha foi preenchido */
if (!empty($senha_atual) || !empty($nova) || !empty($confirmar)) {

    // 1. Buscar hash atual
    $sql = "SELECT senha_hash FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $senhaBanco = $stmt->fetchColumn();

    // Se não houver hash salvo, impede alteração por segurança
    if (!$senhaBanco) {
        header("Location: editar_perfil.php?erro=Não foi possível validar a senha atual.");
        exit;
    }

    // 2. Conferir senha atual
    if (!password_verify($senha_atual, $senhaBanco)) {
        header("Location: editar_perfil.php?erro=Senha atual incorreta.");
        exit;
    }

    // 3. Validar tamanho da nova senha
    if (strlen($nova) < 6 || strlen($nova) > 10) {
        header("Location: editar_perfil.php?erro=A nova senha deve ter entre 6 e 10 caracteres.");
        exit;
    }

    // 4. Conferir se nova == confirmar
    if ($nova !== $confirmar) {
        header("Location: editar_perfil.php?erro=As senhas não conferem.");
        exit;
    }

    // 5. Gerar novo hash e salvar
    $hash = password_hash($nova, PASSWORD_DEFAULT);
    $sql  = "UPDATE usuarios SET senha_hash = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$hash, $id]);
}

header("Location: editar_perfil.php?sucesso=Perfil atualizado!");
exit;
