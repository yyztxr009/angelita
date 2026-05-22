<?php
$email = $_GET['email'];
$token = $_GET['token'];

require_once "conexao.php";
$conexao = conectar();
$sql = "SELECT * FROM recuperar_senha WHERE email='$email' AND 
        token = '$token' AND usado=0";
$result = mysqli_query($conexao, $sql);
if ($result == false) {
    die("Erro ao executar o SQL. " . mysqli_error($conexao));
}
$recuperacao = mysqli_fetch_assoc($result);
if (is_null($recuperacao)) {
    die("Email ou token inválidos, ou esta solicitação já foi utilizada");
}
date_default_timezone_set('America/Sao_Paulo');
$agora = new DateTime('now');
$data_criacao = DateTime::createFromFormat(
    'Y-m-d H:i:s',
    $recuperacao['data']
);
$umDia = DateInterval::createFromDateString('1 day');
$dataExpiracao = date_add($data_criacao, $umDia);

if ($agora > $dataExpiracao) {
    die("Essa solicitação de recuperação de senha expirou!
              Faça um novo pedido de recuperação de senha.");
}
?>
<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova senha</title>
</head>

<body>
    <form action="salvar_nova_senha.php" method="post">
        <input type="hidden" name="email" value="<?= $email ?>">
        <input type="hidden" name="token" value="<?= $token ?>">
        <label>Informe a nova senha: <input type="password" name="nova_senha"></label><br>
        <label>Repita a nova senha: <input type="password" name="nova_senha2"></label><br>
        <input type="submit" value="Salvar nova senha">
    </form>
</body>

</html>