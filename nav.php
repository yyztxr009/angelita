<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['usuario_id']);
$nomeUsuario = $logado ? $_SESSION['usuario_nome'] : null;
?>

<style>
/* ---- ESTILO PARA O MENU DO USUÁRIO ---- */

.user-menu {
    position: relative;
    display: inline-block;
}

.user-btn {
    background: #fff;
    color: #333;
    border-radius: 25px;
    padding: 8px 15px;
    font-weight: 600;
    border: 1px solid #ddd;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-btn:hover {
    background: #f0f0f0;
}

.user-btn i {
    font-size: 18px;
}

.user-dropdown {
    position: absolute;
    right: 0;
    top: 110%;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #ddd;
    min-width: 180px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: 0.2s;
    z-index: 9999;
}

.user-dropdown a {
    display: block;
    padding: 10px 15px;
    color: #333;
    font-weight: 500;
    text-decoration: none;
    transition: 0.2s;
}

.user-dropdown a:hover {
    background: #f8f8f8;
}

.user-menu:hover .user-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.btn-logout {
    color: #dc3545 !important;
}

</style>

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

        <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
            <h1 class="sitename">Angelita</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php#menu">Cardápio</a></li>
                <li><a href="index.php#about">Nossa chefe</a></li>
                <li><a href="index.php#location">Localização</a></li>

                <?php if ($logado): ?>
                    <li><a href="pedidos.php">Meus pedidos</a></li>
                  
                <?php endif; ?>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <?php if ($logado): ?>
            <!-- MENU DO USUÁRIO -->
            <div class="user-menu d-none d-sm-block">

                <div class="user-btn">
                    <i class="bi bi-person-circle"></i>
                    <?= htmlspecialchars($nomeUsuario) ?>
                </div>

                <div class="user-dropdown">
                    <a href="editar_perfil.php">Editar perfil</a>
                    <a href="pedidos.php">Meus pedidos</a>  
                    <a class="btn-logout" href="logout.php">Sair</a>

                </div>
            </div>
<a href="logout.php" class="btn btn-outline-light">Sair</a>

        <?php else: ?>
            <a class="btn-getstarted d-none d-sm-block" href="login.php">Login</a>
        <?php endif; ?>

    </div>
</header>
