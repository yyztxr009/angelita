<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email = urldecode($_GET['email'] ?? ($_SESSION['rec_email'] ?? ''));
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo'] ?? '');

    $stmt = $pdo->prepare("SELECT codigo, expira FROM recuperacao_codigo WHERE email = ?");
    $stmt->execute([$email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $mensagem = "<div class='alert alert-danger text-center'>Código inválido ou não solicitado para este email.</div>";
    } elseif ($row['codigo'] !== $codigo) {
        $mensagem = "<div class='alert alert-danger text-center'>Código incorreto.</div>";
    } elseif ($row['expira'] < date('Y-m-d H:i:s')) {
        $mensagem = "<div class='alert alert-danger text-center'>Código expirado. Solicite um novo.</div>";
    } else {
    // código OK: pode ir para redefinir senha
    $_SESSION['rec_email'] = $email;
    ?>
    <script>
        window.location.href = 'redefinir_senha.php';
    </script>
    <?php
    exit;
}

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Verificar código - Angelita</title>
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
    <h1 class="reset-title">Verificar código</h1>
    <p class="reset-subtitle">
      Digite o código de 6 dígitos enviado para o email <strong><?= htmlspecialchars($email) ?></strong>.
    </p>

    <?= $mensagem ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control" maxlength="6" required>
      </div>
      <button type="submit" class="btn-reset">Confirmar código</button>
    </form>

    <div class="back-link" style="margin-top:12px;font-size:14px;text-align:center;">
      <a href="esqueci_senha.php"><i class="bi bi-arrow-left"></i> Voltar</a>
    </div>
  </div>
</div>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
