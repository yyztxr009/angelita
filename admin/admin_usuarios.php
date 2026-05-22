<?php
session_start();
require_once __DIR__ . '/../db.php';

// Só admin
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// -----------------------------------------------------------------------------
// PROCESSAR A EDIÇÃO DO USUÁRIO
// -----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar_usuario') {
    $id_edit = (int)$_POST['id_usuario'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $tipo = $_POST['tipo'];

    // 1. Atualiza os dados normais
    $stmt = $conn->prepare("UPDATE usuarios SET nome=?, email=?, telefone=?, cpf=?, tipo=? WHERE id=?");
    $stmt->bind_param("sssssi", $nome, $email, $telefone, $cpf, $tipo, $id_edit);
    $stmt->execute();
    $stmt->close();

    // 2. Se o admin digitou uma nova senha, atualiza ela também
    if (!empty($_POST['senha'])) {
        // Usa password_hash para manter a segurança do sistema
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $stmt_senha = $conn->prepare("UPDATE usuarios SET senha_hash=? WHERE id=?");
        $stmt_senha->bind_param("si", $senha_hash, $id_edit);
        $stmt_senha->execute();
        $stmt_senha->close();
    }

    // Redireciona para atualizar a página e mostrar mensagem de sucesso
    header('Location: admin_usuarios.php?sucesso=1');
    exit;
}
// -----------------------------------------------------------------------------

// Busca todos os usuários da tabela `usuarios`
$sql = "
    SELECT id, nome, email, cpf, telefone, endereco, data_nascimento, tipo
    FROM usuarios
    ORDER BY id DESC
";
$res = $conn->query($sql);
$usuarios = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Usuários - Angelita</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body{background:#f5f5f0;font-family:'Poppins',system-ui,sans-serif;}
    .page-wrap{padding:20px 30px;}
    .page-title{color:#5a0018;margin-bottom:20px;}
    .pill-tipo{
      border-radius:999px;
      padding:4px 10px;
      font-size:12px;
      color:#fff;
    }
    .pill-admin{background:#5a0018;}
    .pill-cliente{background:#198754;}
  </style>
</head>
<body>
<div class="page-wrap">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="page-title"><i class="bi bi-people"></i> Usuários cadastrados</h3>
    <a href="admin.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Voltar ao dashboard
    </a>
  </div>

  <?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Usuário atualizado com sucesso!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>CPF</th>
            <th>Tipo</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($usuarios)): ?>
          <tr><td colspan="7" class="text-center p-3">Nenhum usuário encontrado.</td></tr>
        <?php else: ?>
          <?php foreach ($usuarios as $u): ?>
            <tr>
              <td><?= (int)$u['id'] ?></td>
              <td><?= htmlspecialchars($u['nome']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['telefone'] ?? '') ?></td>
              <td><?= htmlspecialchars($u['cpf'] ?? '') ?></td>
              <td>
                <?php if (($u['tipo'] ?? 'cliente') === 'admin'): ?>
                  <span class="pill-tipo pill-admin">Admin</span>
                <?php else: ?>
                  <span class="pill-tipo pill-cliente">Cliente</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <button class="btn btn-sm" style="background-color: #5a0018; color: white;" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $u['id'] ?>">
                  <i class="bi bi-pencil-square"></i> Editar
                </button>
              </td>
            </tr>

            <div class="modal fade" id="modalEdit<?= $u['id'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header" style="background:#5a0018; color:white;">
                    <h5 class="modal-title">Editar Usuário #<?= $u['id'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="POST" action="admin_usuarios.php">
                    <div class="modal-body">
                      <input type="hidden" name="acao" value="editar_usuario">
                      <input type="hidden" name="id_usuario" value="<?= $u['id'] ?>">

                      <div class="mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($u['nome']) ?>" required>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($u['email']) ?>" required>
                      </div>

                      <div class="row">
                        <div class="col-6 mb-3">
                          <label class="form-label">Telefone</label>
                          <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($u['telefone'] ?? '') ?>">
                        </div>
                        <div class="col-6 mb-3">
                          <label class="form-label">CPF</label>
                          <input type="text" class="form-control" name="cpf" value="<?= htmlspecialchars($u['cpf'] ?? '') ?>">
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Permissão / Tipo</label>
                        <select class="form-select" name="tipo" required>
                          <option value="cliente" <?= (($u['tipo'] ?? 'cliente') === 'cliente') ? 'selected' : '' ?>>Cliente</option>
                          <option value="admin" <?= (($u['tipo'] ?? 'cliente') === 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                      </div>

                      <hr>

                      <div class="mb-3">
                        <label class="form-label text-danger">Mudar Senha (Opcional)</label>
                        <input type="password" class="form-control" name="senha" placeholder="Deixe em branco para não alterar">
                        <small class="text-muted">Se você digitar algo aqui, a senha antiga do usuário será substituída.</small>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn" style="background:#5a0018; color:white;">Salvar Alterações</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>