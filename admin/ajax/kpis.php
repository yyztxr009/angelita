<?php
include __DIR__ . "/../../db.php";

/*
  KPIs:
  - pedidos_pendentes: pedidos com status pendente/preparando/pronto/saiu
  - usuarios: total de usuários
  - vendas_hoje_formatted: soma dos pedidos entregues hoje
*/

// Garante estrutura base da resposta
$resp = [
    'pedidos_pendentes'      => 0,
    'usuarios'               => 0,
    'vendas_hoje_formatted'  => 'R$ 0,00',
];

// 1) Pedidos pendentes
$sql = "
    SELECT COUNT(*) AS qt
    FROM pedidos
    WHERE status IN ('pendente','preparando','pronto','saiu')
";
$r = $conn->query($sql);
if ($r && $row = $r->fetch_assoc()) {
    $resp['pedidos_pendentes'] = (int) $row['qt'];
}

// 2) Usuários cadastrados
$r = $conn->query("SELECT COUNT(*) AS qt FROM usuarios");
if ($r && $row = $r->fetch_assoc()) {
    $resp['usuarios'] = (int) $row['qt'];
}

// 3) Vendas hoje (pedidos entregues hoje)
$sql = "
    SELECT COALESCE(SUM(valor_total),0) AS total
    FROM pedidos
    WHERE status = 'entregue'
      AND DATE(data_pedido) = CURDATE()
";
$r = $conn->query($sql);

$total = 0.0;
if ($r && $row = $r->fetch_assoc()) {
    $total = (float) $row['total'];
}

// Formata em reais (padrão brasileiro)
$resp['vendas_hoje_formatted'] = 'R$ ' . number_format($total, 2, ',', '.');

// Cabeçalho JSON
header('Content-Type: application/json; charset=utf-8');

// json_encode com UNESCAPED_UNICODE para não escapar acentos (\u00e1 etc.)
echo json_encode($resp, JSON_UNESCAPED_UNICODE);
