<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");

// IDs fixos
$mapa = [
    1 => ['campo' => 'qtd_marmita_grande'],
    2 => ['campo' => 'qtd_marmita_pequena'],
    3 => ['campo' => 'qtd_coca600'],
    4 => ['campo' => 'qtd_cocalata'],
];

$itens = [];
$total = 0;

foreach ($mapa as $idProd => $info) {
    $qtd = isset($_POST[$info['campo']]) ? (int)$_POST[$info['campo']] : 0;
    if ($qtd > 0) {
        $preco = $pdo->query("SELECT preco FROM produtos WHERE id = $idProd")->fetchColumn();
        $itens[$idProd] = [
            'qtd'   => $qtd,
            'preco' => (float)$preco
        ];
        $total += $qtd * (float)$preco;
    }
}

if (empty($itens)) {
    echo "<script>alert('Nenhum produto selecionado!');history.back();</script>";
    exit;
}

$_SESSION['itens_pedido'] = $itens;
$_SESSION['total_pedido'] = $total;

header("Location: confirmacao.php");
exit;
?>
