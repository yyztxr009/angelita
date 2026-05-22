<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$usuarioId = (int)$_SESSION['usuario_id'];

/* Buscar pedidos do usuário */
$sql = "SELECT id, data_pedido, valor_total, status
        FROM pedidos
        WHERE usuario_id = ?
        ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuarioId]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Função para pegar itens do pedido */
function getItens($pdo, $pedidoId) {
    $sql = "SELECT ip.quantidade, ip.preco_unitario, p.nome 
            FROM itens_pedido ip
            JOIN produtos p ON p.id = ip.id_produto
            WHERE ip.id_pedido = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pedidoId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Meus Pedidos - Savora</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bitter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">

<style>
body {
    background:#f5f3f0;
    font-family:'Poppins', sans-serif;
}

/* Card de pedido */
.pedido-card {
    background:#fff;
    border-radius:12px;
    padding:18px 20px;
    margin-bottom:20px;
    box-shadow:0 3px 8px rgba(0,0,0,0.08);
    border-left:6px solid #5a0018;
}

/* Status bolhas */
.status-bubble {
    display:inline-block;
    padding:5px 12px;
    border-radius:999px;
    font-size:13px;
    font-weight:600;
    color:#fff;
}

.st-pendente { background:#ff9800; }
.st-preparando { background:#2196f3; }
.st-pronto { background:#4caf50; }
.st-saiu { background:#673ab7; }
.st-entregue { background:#9e9e9e; }

/* Botão detalhes */
.btn-detalhes {
    background:#5a0018;
    border:none;
    padding:6px 14px;
    color:#fff;
    border-radius:999px;
    font-size:14px;
}

/* Modal */
.modal-header {
    background:#5a0018;
    color:#fff;
}
.modal-content {
    border-radius:12px;
}

/* Timeline */
.timeline {
    margin-top:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:relative;
}

.timeline::before {
    content:"";
    position:absolute;
    top:50%;
    left:0;
    right:0;
    height:4px;
    background:#ddd;
    z-index:1;
    transform:translateY(-50%);
}

.step {
    z-index:3;
    text-align:center;
    width:20%;
}

.step .circle {
    width:22px;
    height:22px;
    border-radius:50%;
    background:#ddd;
    margin:0 auto;
    transition:background .4s ease;
}

.step.completed .circle {
    background:#5a0018;
}

.step span {
    display:block;
    font-size:12px;
    margin-top:6px;
    color:#333;
}

/* Linha ativa animada */
.line-active {
    position:absolute;
    top:50%;
    left:0;
    height:4px;
    background:#5a0018;
    z-index:2;
    width:0%;
    transition:width 1s ease;
}
</style>
</head>
<body>

<?php include "nav.php"; ?>

<div class="container" style="margin-top:120px;">

    <h2 class="mb-4" style="color:#5a0018;">Meus pedidos</h2>

    <?php if (empty($pedidos)): ?>
        <p>Nenhum pedido encontrado.</p>
    <?php endif; ?>

    <?php foreach ($pedidos as $p): ?>

        <?php
            $statusClass = [
                'pendente' => 'st-pendente',
                'preparando' => 'st-preparando',
                'pronto' => 'st-pronto',
                'saiu' => 'st-saiu',
                'entregue' => 'st-entregue'
            ][$p['status']] ?? 'st-pendente';

            /* Progresso da linha */
            $progress = [
                'pendente' => 0,
                'preparando' => 25,
                'pronto' => 50,
                'saiu' => 75,
                'entregue' => 100
            ][$p['status']];
        ?>
        
        <div class="pedido-card">

            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="m-0">Pedido #<?= $p['id'] ?></h5>
                    <small><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></small>
                </div>

                <span class="status-bubble <?= $statusClass ?>">
                    <?= ucfirst($p['status']) ?>
                </span>
            </div>

            <div class="timeline">
                <div class="line-active" style="width:<?= $progress ?>%;"></div>

                <div class="step <?= ($progress >= 0) ? 'completed' : '' ?>">
                    <div class="circle"></div>
                    <span>Pendente</span>
                </div>

                <div class="step <?= ($progress >= 25) ? 'completed' : '' ?>">
                    <div class="circle"></div>
                    <span>Preparando</span>
                </div>

                <div class="step <?= ($progress >= 50) ? 'completed' : '' ?>">
                    <div class="circle"></div>
                    <span>Pronto</span>
                </div>

                <div class="step <?= ($progress >= 75) ? 'completed' : '' ?>">
                    <div class="circle"></div>
                    <span>Saiu para entrega</span>
                </div>

                <div class="step <?= ($progress >= 100) ? 'completed' : '' ?>">
                    <div class="circle"></div>
                    <span>Entregue</span>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <strong>Total: R$ <?= number_format($p['valor_total'], 2, ',', '.') ?></strong>

                <button class="btn-detalhes" data-bs-toggle="modal" data-bs-target="#detalhes<?= $p['id'] ?>">
                    Ver detalhes
                </button>
            </div>

        </div>

        <div class="modal fade" id="detalhes<?= $p['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title">Itens do Pedido #<?= $p['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <ul class="list-group">
                <?php foreach (getItens($pdo, $p['id']) as $item): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong><?= $item['nome'] ?></strong><br>
                            <small><?= $item['quantidade'] ?>x</small>
                        </div>
                        <span>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>

                <div class="text-end mt-3">
                    <h5>Total: R$ <?= number_format($p['valor_total'], 2, ',', '.') ?></h5>
                </div>

              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              </div>

            </div>
          </div>
        </div>

    <?php endforeach; ?>

</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<script>
// Anima a linha após renderização
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".line-active").forEach(line => {
        let finalWidth = line.style.width;
        line.style.width = "0%";
        setTimeout(() => line.style.width = finalWidth, 150);
    });
});
</script>

</body>
</html>