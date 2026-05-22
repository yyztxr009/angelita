<?php
// admin/ajax -> admin -> angelita -> db.php
include __DIR__ . "/../../db.php";

/*
 Retorna JSON com:
 - id
 - nome  (do cliente)
 - itens (resumo: "1x Marmita grande, 2x Coca lata")
 - valor (valor_total do pedido)
 - status
 - data_pedido (formato brasileiro)
 - observacoes (texto digitado na confirmação)
*/

$sql = "SELECT 
            p.id,
            c.nome AS nome_cliente,
            p.valor_total,
            p.status,
            p.data_pedido,
            p.observacoes
        FROM pedidos p
        LEFT JOIN clientes c ON c.id = p.id_cliente
        WHERE p.status IN ('pendente','preparando','pronto','saiu')
        ORDER BY p.id DESC";

$r = $conn->query($sql);
$pedidos = [];

while ($row = $r->fetch_assoc()) {
    $idPedido = (int)$row['id'];

    // Buscar itens do pedido + produtos
    $sqlItens = "SELECT ip.quantidade, pr.nome
                 FROM itens_pedido ip
                 LEFT JOIN produtos pr ON pr.id = ip.id_produto
                 WHERE ip.id_pedido = ?";
    $stmtItens = $conn->prepare($sqlItens);
    $stmtItens->bind_param("i", $idPedido);
    $stmtItens->execute();
    $resItens = $stmtItens->get_result();

    $itensResumo = [];
    while ($it = $resItens->fetch_assoc()) {
        $qtd = (int)$it['quantidade'];
        $nomeProd = $it['nome'] ?? 'Produto';
        $itensResumo[] = "{$qtd}x {$nomeProd}";
    }
    $itensStr = implode(", ", $itensResumo);

    $pedidos[] = [
        'id'          => $idPedido,
        'nome'        => $row['nome_cliente'] ?: 'Cliente',
        'itens'       => $itensStr,
        'valor'       => (float)$row['valor_total'],
        'status'      => $row['status'],
        'data_pedido' => date('d/m H:i', strtotime($row['data_pedido'])),
        'observacoes' => $row['observacoes'] ?? '',
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($pedidos);
