<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// itens e total do pedido vindos da sessão
$itens = $_SESSION['itens_pedido'] ?? [];
$total = $_SESSION['total_pedido'] ?? 0.0;

// usuário logado
$idUsuario = $_SESSION['usuario_id'] ?? 0;

// busca endereço do usuário
$enderecoUsuario = 'Endereço não cadastrado';
if ($idUsuario) {
    $stmtUser = $pdo->prepare("SELECT endereco FROM usuarios WHERE id = ?");
    $stmtUser->execute([$idUsuario]);
    $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
    if ($usuario && !empty($usuario['endereco'])) {
        $enderecoUsuario = $usuario['endereco'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Confirmação do Pedido - Angelita</title>

  <style>
  html, body {
    height: 100%;
    margin: 0;
  }
  body {
    background: #f5f3f0;
    font-family: 'Poppins', Arial, sans-serif;
  }

  .checkout-wrapper {
    min-height: 100vh;
    background: #f5f3f0;
  }

  /* Topo simples */
  .checkout-header {
    background: #5a0018;
    height: 70px;
    display: flex;
    align-items: center;
    padding: 0 40px;
    color: #fff;
    box-sizing: border-box;
  }

  .checkout-header h1 {
    font-size: 22px;
    margin: 0;
    font-weight: 600;
  }

  .checkout-header span {
    font-size: 14px;
    margin-left: 10px;
    opacity: 0.8;
  }

  /* Layout principal */
  .checkout-main {
    width: 100vw;
    height: calc(100vh - 70px);
    margin: 0;
    padding: 30px 40px;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
    box-sizing: border-box;
  }

  .checkout-left,
  .checkout-right {
    height: 100%;
    background: #ffffff;
    border-radius: 10px;
    padding: 25px 30px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.05);
  }

  .checkout-right {
    background: #e8e1d8;
  }

  .checkout-title {
    font-size: 26px;
    margin-bottom: 20px;
  }

  .card-box {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 15px;
    background: #fff;
    font-size: 14px;
  }

  .card-box h4 {
    margin: 0 0 4px;
    font-size: 15px;
  }

  .delivery-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 15px;
  }

  .right-title {
    font-size: 20px;
    margin-bottom: 20px;
  }

  .summary-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px;
  }

  .summary-list li {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    font-size: 14px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    margin-bottom: 4px;
  }

  .summary-total {
    display: flex;
    justify-content: space-between;
    font-weight: 700;
    font-size: 16px;
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px solid #c8b9a8;
  }

  .btn-finalizar {
    margin-top: 25px;
    width: 100%;
    background: #5a0018;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 12px 0;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
  }

  .btn-finalizar:hover {
    background: #7a0022;
  }

  select, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-top: 6px;
    box-sizing: border-box;
  }
  </style>
</head>
<body>
  <div class="checkout-wrapper">

    <header class="checkout-header">
      <h1>Detalhes do pedido</h1>
      <span>Confira seu endereço, entrega e forma de pagamento</span>
    </header>

    <main class="checkout-main">

      <!-- COLUNA ESQUERDA -->
      <section class="checkout-left">

        <h1 class="checkout-title">Finalize seu pedido</h1>

        <?php if (isset($_GET['erro_endereco'])): ?>
  <div style="background:#ffe5e5;color:#b10000;padding:8px 10px;border-radius:6px;margin-bottom:10px;">
    Para entrega em casa você precisa cadastrar um endereço em seu perfil.
  </div>
<?php endif; ?>


        <!-- Endereço do usuário vindo do banco -->
        <div class="card-box">
          <p><strong><?php echo htmlspecialchars($enderecoUsuario); ?></strong></p>
        </div>

        <div class="delivery-grid">
          <div class="card-box">
            <h4>Entrega</h4>
            <p>Hoje, 30–40 minutos</p>
            <p><strong>R$ 3,00</strong></p>
          </div>
          <div class="card-box">
            <h4>Retirada</h4>
            <p>Hoje, 10–15 minutos</p>
            <p><strong>Grátis</strong></p>
          </div>
        </div>

        <div class="card-box">
          <h4>Pague com Pix</h4>
          <p>Use nossa chave Pix para efetuar o pagamento e envie o comprovante pelo WhatsApp.</p>
        </div>

        <div class="card-box">
          <h4>Pague com cartão de crédito ou débito</h4>
          <p>Pague na entrega ou em nosso estabelecimento.</p>
        </div>

        <!-- Forma de pagamento -->
        <div class="card-box">
          <h4>Forma de pagamento</h4>
          <select name="pagamento" form="form-finalizar">
            <option value="pix">PIX (instantâneo)</option>
            <option value="cartao_credito">Cartão de crédito (entrega ou no estabelecimento)</option>
            <option value="cartao_debito">Cartão de débito (entrega ou no estabelecimento)</option>
            <option value="dinheiro">Dinheiro (entrega ou no estabelecimento)</option>
          </select>
        </div>

        <!-- Forma de entrega -->
        <div class="card-box">
          <h4>Forma de entrega</h4>
          <select name="entrega" form="form-finalizar" id="select_entrega">
            <option value="entrega" selected>Entrega em casa</option>
            <option value="retirada">Retirada na loja</option>
          </select>
        </div>

        <div class="card-box">
          <h4>Observações do pedido</h4>
          <textarea name="observacao" form="form-finalizar" rows="3"
                    placeholder="Ex.: carne ao ponto, sem cebola, pouco sal..."></textarea>
        </div>

      </section>

      <!-- COLUNA DIREITA -->
      <aside class="checkout-right">
        <h2 class="right-title">Seu pedido</h2>

        <ul class="summary-list">
          <?php
          foreach ($itens as $id => $dados):
            $stmtNome = $pdo->prepare("SELECT nome FROM produtos WHERE id = ?");
            $stmtNome->execute([$id]);
            $nome = $stmtNome->fetchColumn();
            $linhaTotal = $dados['qtd'] * $dados['preco'];
          ?>
          <li>
            <span><?= $dados['qtd'] ?>x <?= htmlspecialchars($nome) ?></span>
            <span>R$ <?= number_format($linhaTotal, 2, ',', '.') ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <div class="summary-row">
          <span>Subtotal</span>
          <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
        </div>
        <div class="summary-row">
          <span>Taxa de entrega</span>
          <span id="taxa_entrega_texto">R$ 3,00</span>
        </div>

        <div class="summary-total">
          <span>Total:</span>
          <span id="total_geral_texto">R$ <?= number_format($total + 3, 2, ',', '.') ?></span>
        </div>

        <form method="POST" action="salvar_pedido.php" id="form-finalizar">
          <button type="submit" class="btn-finalizar">Fazer pedido</button>
        </form>
      </aside>

    </main>

  </div>

  <script>
  const subtotal = <?= json_encode($total); ?>;

  const selectEntrega = document.getElementById('select_entrega');
  const spanTaxa = document.getElementById('taxa_entrega_texto');
  const spanTotal = document.getElementById('total_geral_texto');

  function atualizarTotal() {
    let taxa = 0;

    if (selectEntrega.value === 'entrega') {
      taxa = 3.00;
      spanTaxa.textContent = 'R$ 3,00';
    } else {
      taxa = 0;
      spanTaxa.textContent = 'R$ 0,00';
    }

    const totalGeral = subtotal + taxa;
    spanTotal.textContent = 'R$ ' + totalGeral.toFixed(2).replace('.', ',');
  }

  selectEntrega.addEventListener('change', atualizarTotal);
  atualizarTotal();
  </script>

</body>
</html>
