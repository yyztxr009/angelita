<?php
$novaSenha = $_POST['nova_senha'];
$novaSenha2 = $_POST['nova_senha2'];
$email = $_POST['email'];
$token = $_POST['token'];

if ($novaSenha != $novaSenha2) {
    die("As senhas não conferem, tente novamente.");
}
require_once "conexao.php";
$conexao = conectar();
$sql = "UPDATE usuario SET senha='$novaSenha' WHERE email='$email'";
$result = mysqli_query($conexao, $sql);
if ($result == false) {
    die("Erro ao executar o SQL. " . mysqli_error($conexao));
}

$sql = "UPDATE recuperar_senha SET usado=1 WHERE email='$email' AND token='$token'";
$result = mysqli_query($conexao, $sql);
if ($result == false) {
    die("Erro ao executar o SQL. " . mysqli_error($conexao));
}

echo "Nova senha salva com sucesso!";