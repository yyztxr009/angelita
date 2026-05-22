<!DOCTYPE html>
<html>
<body>
<h2>🧪 TESTE SESSION</h2>
<?php 
session_start(); 

// SIMULA o que a loja deveria mandar
$_SESSION['carrinho_produtos'] = 'Coca Cola|Guaraná';
$_SESSION['carrinho_total'] = 9.50;

echo "✅ SESSION PREENCHIDA!<br>";
echo "Produtos: " . $_SESSION['carrinho_produtos'] . "<br>";
echo "<a href='confirmacao.php'>→ IR PARA CONFIRMAÇÃO</a>";
?>
</body>
</html>
