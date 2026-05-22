<?php
include __DIR__ . "/../../db.php";

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

if ($acao === 'abrir') {
    $cardapio = $_POST['cardapio'] ?? '';

    $stmt = $conn->prepare(
        "UPDATE statusloja SET aberto = 1, cardapio_do_dia = ? WHERE id = 1"
    );
    $stmt->bind_param("s", $cardapio);
    $stmt->execute();

    echo "aberta";
    exit;
}

if ($acao === 'fechar') {
    $stmt = $conn->prepare("UPDATE statusloja SET aberto = 0 WHERE id = 1");
    $stmt->execute();

    echo "fechada";
    exit;
}

if ($acao === 'buscar') {
    $r = $conn->query(
        "SELECT aberto, cardapio_do_dia FROM statusloja WHERE id = 1"
    );
    $row = $r ? $r->fetch_assoc() : ['aberto' => 0, 'cardapio_do_dia' => ''];

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($row);
    exit;
}

http_response_code(400);
echo "acao_invalida";
