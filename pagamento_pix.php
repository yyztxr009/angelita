<?php
session_start();

// Aqui você pode pegar o ID do pedido que acabou de ser gerado, se estiver passando pela URL
$id_pedido = isset($_GET['pedido_id']) ? $_GET['pedido_id'] : '';

// Chave PIX da Angelita Refeições (Substitua pela sua chave real)
$chave_pix = "03713500002"; 

// Link do WhatsApp com mensagem pré-programada
$numero_whats = "5555999891062";
$mensagem_whats = urlencode("Olá! Acabei de fazer um pedido" . ($id_pedido ? " (ID: $id_pedido)" : "") . " e estou enviando o comprovante do PIX.");
$link_whatsapp = "https://api.whatsapp.com/send?phone={$numero_whats}&text={$mensagem_whats}";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Pagamento PIX - Angelita Refeições</title>
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    .pix-container {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }
    .pix-key-box {
        background: #f8f9fa;
        border: 2px dashed #5a0018;
        padding: 15px;
        font-size: 1.2rem;
        font-weight: bold;
        border-radius: 8px;
        margin: 20px 0;
        word-break: break-all;
    }
    .btn-copiar {
        background-color: #6c757d;
        color: white;
        border: none;
    }
    .btn-whats {
        background-color: #25D366;
        color: white;
        font-weight: bold;
        border: none;
    }
    .btn-whats:hover {
        background-color: #1ebd5c;
        color: white;
    }
    .btn-concluir {
        background-color: #5a0018;
        color: white;
        border: none;
    }
    .btn-concluir:hover {
        background-color: #420011;
        color: white;
    }
  </style>
</head>
<body>

  <?php include('nav.php'); ?>

  <main class="main">
    <div class="container">
      <div class="pix-container">
        <i class="bi bi-qr-code-scan" style="font-size: 3rem; color: #5a0018;"></i>
        <h2 class="mt-3" style="color: #5a0018;">Pagamento via PIX</h2>
        <p class="text-muted">Seu pedido foi registrado! Para que possamos começar a prepará-lo, realize o pagamento usando a chave abaixo.</p>

        <div class="pix-key-box" id="chavePixTexto">
          <?= $chave_pix ?>
        </div>

        <button class="btn btn-copiar mb-4" onclick="copiarPix()">
          <i class="bi bi-clipboard"></i> Copiar Chave PIX
        </button>

        <hr>

        <h5 class="mb-3">Já fez o pagamento?</h5>
        <p>Envie o comprovante para o nosso WhatsApp clicando no botão abaixo:</p>
        
        <a href="<?= $link_whatsapp ?>" target="_blank" class="btn btn-whats w-100 mb-3 py-2">
          <i class="bi bi-whatsapp"></i> Enviar Comprovante
        </a>

        <a href="pedidos.php" class="btn btn-concluir w-100 py-2">
          Já enviei, ver meus pedidos
        </a>

      </div>
    </div>
  </main>

  <script>
    function copiarPix() {
      var chavePix = document.getElementById("chavePixTexto").innerText;
      navigator.clipboard.writeText(chavePix).then(function() {
        alert("Chave PIX copiada para a área de transferência!");
      }, function(err) {
        alert("Erro ao copiar a chave. Por favor, selecione e copie manualmente.");
      });
    }
  </script>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>