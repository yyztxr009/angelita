<?php
include __DIR__ . "/../../db.php";

$id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$status = $_POST['status'] ?? '';

$permitidos = ['pendente','preparando','pronto','saiu','entregue'];

if ($id <= 0 || !in_array($status, $permitidos, true)) {
    http_response_code(400);
    exit('Dados inválidos');
}

$stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

echo "ok";
