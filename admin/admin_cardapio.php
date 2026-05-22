<?php
session_start();
require_once __DIR__ . '/../db.php';

// só admin
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// tratar formulário (salvar novo ou edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nome      = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $precoBr   = trim($_POST['preco'] ?? '0');

    // converte "19,90" para 19.90
    $precoBr = str_replace(['.', ','], ['', '.'], $precoBr);
    $preco   = (float)$precoBr;

    if ($nome !== '' && $preco > 0) {
        if ($id > 0) {
            // atualizar
            $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=? WHERE id=?");
            $stmt->bind_param("ssdi", $nome, $descricao, $preco, $id);
            $stmt->execute();
        } else {
            // inserir
            $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, estoque) VALUES (?,?,?,0)");
            $stmt->bind_param("ssd", $nome, $descricao, $preco);
            $stmt->execute();
        }
    }
    header("Location: admin_cardapio.php");
    exit;
}

// buscar produtos atuais
$res = $conn->query("SELECT id, nome, descricao, preco FROM produtos ORDER BY id ASC");
$produtos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cardápio - Angelita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#f5f5f0;font-family:'Poppins',system-ui,sans-serif;}
    .page-wrap{padding:20px 30px;}
    .page-title{color:#5a0018;margin-bottom:20px;}
  </style>
</head>
<body>
<div class="page-wrap">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="page-title"><i class="bi bi-book"></i> Gerenciar cardápio</h3>
    <a href="admin.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Voltar ao dashboard
    </a>
  </div>

  <!-- Lista de produtos -->
  <div class="card mb-4">
    <div class="card-header">
      Produtos atuais
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th style="width:120px;">Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($produtos)): ?>
          <tr><td colspan="5" class="text-center p-3">Nenhum produto cadastrado.</td></tr>
        <?php else: ?>
          <?php foreach ($produtos as $p): ?>
            <tr>
              <td><?= (int)$p['id'] ?></td>
              <td><?= htmlspecialchars($p['nome']) ?></td>
              <td><?= htmlspecialchars($p['descricao'] ?? '') ?></td>
              <td>R$ <?= number_format((float)$p['preco'],2,',','.') ?></td>
              <td>
  <button
    type="button"
    class="btn btn-sm btn-primary"
    onclick="editarProduto(<?= (int)$p['id'] ?>,
                           '<?= htmlspecialchars($p['nome'], ENT_QUOTES) ?>',
                           '<?= htmlspecialchars($p['descricao'] ?? '', ENT_QUOTES) ?>',
                           '<?= number_format((float)$p['preco'],2,',','.') ?>')">
    Editar
  </button>
  <button
    type="button"
    class="btn btn-sm btn-danger"
    onclick="confirmarExcluir(<?= (int)$p['id'] ?>)">
    Excluir
  </button>
</td>

            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Form de novo/edição -->
  <div class="card">
    <div class="card-header">
      <span id="titulo-form">Adicionar novo produto</span>
    </div>
    <div class="card-body">
      <form method="POST">
        <input type="hidden" name="id" id="prod_id">
        <div class="mb-3">
          <label class="form-label">Nome</label>
          <input type="text" name="nome" id="prod_nome" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Descrição</label>
          <textarea name="descricao" id="prod_desc" class="form-control" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Preço (R$)</label>
          <input type="text" name="preco" id="prod_preco" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <button type="button" class="btn btn-secondary" onclick="limparForm()">Limpar</button>
      </form>
    </div>
  </div>
</div>

<script>
function editarProduto(id, nome, desc, preco){
  document.getElementById('prod_id').value   = id;
  document.getElementById('prod_nome').value = nome;
  document.getElementById('prod_desc').value = desc;
  document.getElementById('prod_preco').value= preco;
  document.getElementById('titulo-form').textContent = 'Editar produto #' + id;
}
function limparForm(){
  document.getElementById('prod_id').value   = '';
  document.getElementById('prod_nome').value = '';
  document.getElementById('prod_desc').value = '';
  document.getElementById('prod_preco').value= '';
  document.getElementById('titulo-form').textContent = 'Adicionar novo produto';
}
</script>

<script>
function confirmarExcluir(id){
  if (confirm('Tem certeza que deseja excluir este produto?')) {
    window.location.href = 'admin_excluir_produto.php?id=' + id;
  }
}
</script>


</body>
</html>
