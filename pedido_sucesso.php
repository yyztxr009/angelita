<?php
$id_pedido = $_GET['id_pedido'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Pedido realizado - Angelita</title>
  <style>
  html, body { height:100%; margin:0; }
  body {
    background:#f5f3f0;
    font-family:'Poppins', Arial, sans-serif;
  }

  /* Topo simples, igual ao da confirmação */
  .checkout-header {
    background:#5a0018;
    height:70px;
    display:flex;
    align-items:center;
    padding:0 40px;
    color:#fff;
    box-sizing:border-box;
  }
  .checkout-header h1 {
    font-size:22px;
    margin:0;
    font-weight:600;
  }
  .checkout-header span {
    font-size:14px;
    margin-left:10px;
    opacity:0.8;
  }

  .success-wrapper {
    max-width:600px;
    margin:80px auto 0;
    background:#fff;
    border-radius:10px;
    padding:30px 40px;
    box-shadow:0 3px 8px rgba(0,0,0,0.08);
    text-align:center;
  }
  .success-title {
    font-size:24px;
    margin-bottom:10px;
    color:#1f7a35;
  }
  .success-msg {
    font-size:16px;
    margin-bottom:20px;
  }
  .success-btns a {
    display:inline-block;
    margin:5px 10px;
    padding:10px 20px;
    border-radius:999px;
    text-decoration:none;
    font-weight:600;
  }
  .btn-principal {
    background:#5a0018;
    color:#fff;
  }
  .btn-secundario {
    background:#e8e1d8;
    color:#5a0018;
  }
  </style>
</head>
<body>
  <header class="checkout-header">
    <h1>Pedido realizado</h1>
    <span>Obrigado por comprar com a Angelita Refeições</span>
  </header>

  <div class="success-wrapper">
    <h1 class="success-title">Pedido realizado com sucesso!</h1>
    <p class="success-msg">
      Seu pedido <strong>#<?= htmlspecialchars($id_pedido) ?></strong> foi registrado
      e já está na fila de preparação.
    </p>
    <div class="success-btns">
      <a href="pedidos.php" class="btn-principal">Ver pedidos</a>
      <a href="index.php" class="btn-secundario">Fazer outro pedido</a>
    </div>
  </div>
</body>
</html>
