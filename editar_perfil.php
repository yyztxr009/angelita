<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = (int)$_SESSION['usuario_id'];

/* Buscar dados do usuário */
$sql = "SELECT nome, email, cpf, telefone, endereco, data_nascimento FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Editar Perfil</title>

<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/css/main.css" rel="stylesheet">

<style>
body {
    background:#f5f3f0;
    font-family:'Poppins', sans-serif;
}
.form-card {
    background:#fff;
    border-radius:12px;
    padding:25px 28px;
    margin-top:120px;
    box-shadow:0 3px 8px rgba(0,0,0,0.08);
    border-left:6px solid #5a0018;
}
.btn-save {
    background:#5a0018;
    color:#fff;
    border-radius:999px;
    padding:10px 18px;
    border:none;
    font-weight:600;
}
.btn-save:hover {
    opacity:.9;
}
</style>
</head>

<body>

<?php include "nav.php"; ?>

<div class="container">

    <div class="form-card" data-aos="fade-up">

        <h3 style="color:#5a0018; margin-bottom:25px;">Editar Perfil</h3>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
        <?php endif; ?>

        <form action="processa_edicao.php" method="POST">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control"
                           value="<?= htmlspecialchars($user['nome']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-md-6">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control"
                           value="<?= htmlspecialchars($user['cpf'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control"
                           value="<?= htmlspecialchars($user['telefone'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Endereço</label>
                <input type="text" name="endereco" class="form-control"
                       value="<?= htmlspecialchars($user['endereco'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label class="form-label">Data de nascimento</label>
                <input type="date" name="data_nascimento" class="form-control"
                       value="<?= htmlspecialchars($user['data_nascimento'] ?? '') ?>">
            </div>

            <hr>

            <h5 class="mt-3 mb-3" style="color:#5a0018;">Alterar senha</h5>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Senha atual</label>
                    <input type="password" name="senha_atual" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nova senha (6 a 10 caracteres)</label>
                    <input type="password" name="nova_senha" class="form-control"
                           minlength="6" maxlength="10">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Confirmar nova senha</label>
                    <input type="password" name="confirmar_senha" class="form-control"
                           minlength="6" maxlength="10">
                </div>
            </div>

            <button class="btn-save">Salvar alterações</button>

        </form>

    </div>
</div>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script>AOS.init();</script>

</body>
</html>
