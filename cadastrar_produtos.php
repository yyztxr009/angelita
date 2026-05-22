<?php
$pdo = new PDO("mysql:host=localhost;dbname=tcc1", "root", "");

// CADASTRAR NOVO PRODUTO
if(isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
    $stmt->execute([$nome, $preco]);
    echo "<script>alert('✅ Produto cadastrado!');</script>";
}

// LISTAR TODOS PRODUTOS
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Produtos - Admin TCC</title>
    <style>
        body { font-family: Arial; max-width: 800px; margin: 50px auto; padding: 20px; }
        .form-cadastro { background: #e8f5e8; padding: 20px; border-radius: 10px; margin: 20px 0; }
        input, button { padding: 12px; margin: 5px; border-radius: 5px; border: 1px solid #ddd; }
        button { background: #28a745; color: white; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #007bff; color: white; }
        .excluir { background: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🛍️ Admin - Cadastrar Produtos</h1>
    
    <!-- FORMULÁRIO SIMPLES -->
    <div class="form-cadastro">
        <h3>➕ Novo Produto</h3>
        <form method="POST">
            <input type="text" name="nome" placeholder="Nome do produto (ex: Fanta Laranja)" required style="width: 300px;">
            <input type="number" name="preco" placeholder="Preço (ex: 4.50)" step="0.01" required style="width: 150px;">
            <button name="cadastrar">CADASTRAR PRODUTO</button>
        </form>
    </div>

    <!-- LISTA DE PRODUTOS -->
    <h3>📋 Produtos Cadastrados (<?= count($produtos) ?>)</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
        <?php foreach($produtos as $produto): ?>
        <tr>
            <td><strong>#<?= $produto['id'] ?></strong></td>
            <td><?= htmlspecialchars($produto['nome']) ?></td>
            <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
            <td><?= $produto['estoque'] ?></td>
            <td>
                <a href="editar_produto.php?id=<?= $produto['id'] ?>" style="background:#007bff;color:white;padding:5px 10px;text-decoration:none;border-radius:3px;">Editar</a>
                <a href="?excluir=<?= $produto['id'] ?>" class="excluir" onclick="return confirm('Excluir?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="loja.html" style="background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">← Voltar Loja</a>
    <a href="admin.php" style="background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin-left:10px;">📋 Ver Pedidos</a>
</body>
</html>

<?php
// EXCLUIR PRODUTO
if(isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>alert('🗑️ Produto excluído!'); window.location='';</script>";
}
?>
