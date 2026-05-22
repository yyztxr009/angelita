<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=tcc1", "root", "");

$id_produto = $_POST['id_produto'];
$quantidade = (int)$_POST['quantidade'];

// Inicializa carrinho
if(!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = [];

if($quantidade > 0) {
    $_SESSION['carrinho'][$id_produto] = $quantidade;
} else {
    unset($_SESSION['carrinho'][$id_produto]);
}

// CALCULA TOTAL REAL do banco
$total = 0;
$total_itens = 0;
foreach($_SESSION['carrinho'] as $id => $qtd) {
    $preco = $pdo->query("SELECT preco FROM produtos WHERE id = $id")->fetchColumn() ?: 0;
    $total += $qtd * $preco;
    $total_itens += $qtd;
}

echo json_encode([
    'carrinho' => $_SESSION['carrinho'],
    'total' => $total,
    'total_itens' => $total_itens
]);
?>
