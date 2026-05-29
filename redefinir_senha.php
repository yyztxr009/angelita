<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email = $_SESSION['rec_email'] ?? '';
$mensagem = '';
$erro = '';

if (!$email) {
    echo "Fluxo inválido. Volte para a tela de recuperação.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha    = $_POST['senha'] ?? '';
    $confirma = $_POST['confirma'] ?? '';

    if ($senha !== $confirma) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6 || strlen($senha) > 12) {
        $erro = "Senha deve ter entre 6 e 12 caracteres.";
    } else {
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE usuarios SET senha_hash = ?, token_recuperacao = NULL, token_expira_em = NULL WHERE email = ?");
        if ($stmt->execute([$hash, $email])) {
            // limpa info de recuperação
            $stmtDel = $pdo->prepare("DELETE FROM recuperacao_codigo WHERE email = ?");
            $stmtDel->execute([$email]);
            unset($_SESSION['rec_email']);

            $mensagem = "<div class='alert alert-success text-center'>
                Senha alterada com sucesso! <a href='login.php'>Faça login</a>
            </div>";
        } else {
            $erro = "Erro ao atualizar senha.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Redefinir senha - Angelita</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#f5f3f0;font-family:'Poppins',Arial,sans-serif;}
    .reset-wrapper{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
    .reset-card{background:#fff;border-radius:12px;padding:30px 32px;max-width:420px;width:100%;box-shadow:0 6px 18px rgba(0,0,0,0.08);border-top:5px solid #5a0018;}
    .reset-title{font-size:22px;margin-bottom:8px;color:#5a0018;font-weight:600;}
    .reset-subtitle{font-size:14px;color:#666;margin-bottom:20px;}
    .btn-reset{background:#5a0018;color:#fff;border-radius:999px;padding:10px 18px;border:none;font-weight:600;width:100%;}
    .btn-reset:hover{opacity:.9;color:#fff;}
  </style>
</head>
<body>
<div class="reset-wrapper">
  <div class="reset-card">
    <h1 class="reset-title">Nova senha</h1>
    <p class="reset-subtitle">
      Defina uma nova senha para o email <strong><?= htmlspecialchars($email) ?></strong>.
    </p>

    <?php if ($erro): ?>
      <div class="alert alert-danger text-center"><?= $erro ?></div>
    <?php endif; ?>

    <?php if ($mensagem): ?>
      <?= $mensagem ?>
    <?php else: ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nova senha</label>
          <input type="password" name="senha" class="form-control" minlength="6" maxlength="10" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirme a senha</label>
          <input type="password" name="confirma" class="form-control" minlength="6" maxlength="10" required>
        </div>
        <button type="submit" class="btn-reset">Salvar nova senha</button>
      </form>
    <?php endif; ?>
  </div>
</div>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
