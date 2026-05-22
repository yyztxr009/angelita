<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// 1) Buscar pedidos + cliente
$sql = "
    SELECT p.id,
           p.data_pedido,
           p.valor_total,
           p.status,
           u.nome AS cliente
    FROM pedidos p
    JOIN usuarios u ON u.id = p.id_cliente
    ORDER BY p.data_pedido DESC
";
$res = $conn->query($sql);
$pedidos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

// 2) Buscar itens de cada pedido, usando itens_pedido
$itensPorPedido = [];

foreach ($pedidos as $p) {
    $idPedido = (int)$p['id'];

    $sqlItens = "
        SELECT pr.nome, ip.quantidade
        FROM itens_pedido ip
        JOIN produtos pr ON pr.id = ip.id_produto
        WHERE ip.id_pedido = $idPedido
    ";
    $rItens = $conn->query($sqlItens);

    $lista = [];
    if ($rItens) {
        while ($row = $rItens->fetch_assoc()) {
            $lista[] = $row['quantidade'] . 'x ' . $row['nome'];
        }
    }
    $itensPorPedido[$idPedido] = implode(', ', $lista);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Todos os pedidos - Angelita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#f5f5f0;font-family:'Poppins',system-ui,sans-serif;}
    .page-wrap{padding:20px 30px;}
    .page-title{color:#5a0018;margin-bottom:20px;}
    .status-badge{border-radius:999px;padding:4px 10px;font-size:12px;}
  </style>
</head>
<body>
<div class="page-wrap">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="page-title"><i class="bi bi-receipt"></i> Todos os pedidos</h3>
    <a href="admin.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Voltar ao dashboard
    </a>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Itens</th>
            <th>Data/Hora</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($pedidos)): ?>
          <tr><td colspan="6" class="text-center p-3">Nenhum pedido encontrado.</td></tr>
        <?php else: ?>
          <?php foreach ($pedidos as $p): ?>
            <tr>
              <td><?= (int)$p['id'] ?></td>
              <td><?= htmlspecialchars($p['cliente']) ?></td>
              <td><?= htmlspecialchars($itensPorPedido[(int)$p['id']] ?? '') ?></td>
              <td><?= htmlspecialchars($p['data_pedido']) ?></td>
              <td>R$ <?= number_format((float)$p['valor_total'],2,',','.') ?></td>
              <td>
                <span class="status-badge bg-secondary text-white">
                  <?= htmlspecialchars($p['status']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
