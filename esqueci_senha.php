<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Recuperar senha - Angelita</title>

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    body{
      background:#f5f3f0;
      font-family:'Poppins', Arial, sans-serif;
    }
    .reset-wrapper{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:20px;
    }
    .reset-card{
      background:#fff;
      border-radius:12px;
      padding:30px 32px;
      max-width:420px;
      width:100%;
      box-shadow:0 6px 18px rgba(0,0,0,0.08);
      border-top:5px solid #5a0018;
    }
    .reset-title{
      font-size:22px;
      margin-bottom:8px;
      color:#5a0018;
      font-weight:600;
    }
    .reset-subtitle{
      font-size:14px;
      color:#666;
      margin-bottom:20px;
    }
    .btn-reset{
      background:#5a0018;
      color:#fff;
      border-radius:999px;
      padding:10px 18px;
      border:none;
      font-weight:600;
      width:100%;
    }
    .btn-reset:hover{
      opacity:.9;
      color:#fff;
    }
    .back-link{
      margin-top:12px;
      font-size:14px;
      text-align:center;
    }
    .back-link a{
      color:#5a0018;
      text-decoration:none;
    }
  </style>
</head>
<body>

<div class="reset-wrapper">
  <div class="reset-card">
    <h1 class="reset-title">Recuperar senha</h1>
    <p class="reset-subtitle">
      Informe o email cadastrado para receber o link de redefinição de senha.
    </p>

    <form method="POST" action="processa_esqueci_senha.php">
      <div class="mb-3">
        <label class="form-label">Email cadastrado</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <button type="submit" class="btn-reset">Enviar link de recuperação</button>
    </form>

    <div class="back-link">
      <a href="login.php"><i class="bi bi-arrow-left"></i> Voltar para o login</a>
    </div>
  </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
