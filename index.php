<?php
session_start();
require_once __DIR__ . '/db.php';

$isAdmin = isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';

// status da loja
$statusRow  = $conn->query("SELECT aberto, cardapio_do_dia FROM statusloja WHERE id = 1")->fetch_assoc();
$lojaAberta = (int)($statusRow['aberto'] ?? 0) === 1;
$cardapioDia = $statusRow['cardapio_do_dia'] ?? '';

// usuário logado?
$logado = isset($_SESSION['usuario_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Angelita Refeições</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Poppins:wght@300;400;500;600;700;900&family=Bitter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <?php include('nav.php'); ?>


  <?php if ($isAdmin): ?>
  <div style="background:#ffe5e5; border-bottom:1px solid #e0bcbc; padding:10px 20px; text-align:center;">
    <strong>Modo administrador:</strong>
    <a href="admin/admin.php" style="margin-left:10px; color:#5a0018; text-decoration:none;">Dashboard</a>
    <a href="admin/admin_cardapio.php" style="margin-left:10px; color:#5a0018; text-decoration:none;">Gerenciar cardápio</a>
    <a href="admin/admin_pedidos.php" style="margin-left:10px; color:#5a0018; text-decoration:none;">Ver pedidos</a>
    <a href="admin/admin_usuarios.php" style="margin-left:10px; color:#5a0018; text-decoration:none;">Usuários</a>
  </div>
<?php endif; ?>

  <main class="main">

    <section id="menu" class="menu section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Cardápio</h2>
        <p>
          <b>Status da loja:</b>
          <?php echo $lojaAberta ? 'ABERTA' : 'FECHADA. VOLTAMOS A ABRIR NO PRÓXIMO DIA ÚTIL ÀS 11:30AM.'; ?>
        </p>
        <p>
          <b>Marmita do dia:</b>
          <?php echo $cardapioDia !== '' ? $cardapioDia : 'Ainda não definido.'; ?>
        </p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="tab-content" id="menuTabContent">
          <div class="tab-pane fade show active" id="menu-starters" role="tabpanel">
            <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">
              <?php
              $sql = "SELECT id, nome, descricao, preco FROM produtos ORDER BY id ASC";
              $r   = $conn->query($sql);
              while ($row = $r->fetch_assoc()):
                  $id      = (int)$row['id'];
                  $nome    = htmlspecialchars($row['nome']);
                  $desc    = htmlspecialchars($row['descricao'] ?? '');
                  $preco   = number_format((float)$row['preco'], 2, ',', '.');
                  $idCampo = 'qtd-prod-' . $id;
              ?>
              <div class="col-lg-6">
                <div class="menu-item">
                  <div class="menu-item-image">
                    <?php
                    $imgSrc = 'assets/img/placeholder.jpg';
                    if ($id === 1) {
                        $imgSrc = 'marmita_grande.jpg';
                    } elseif ($id === 2) {
                        $imgSrc = 'marmita_pequena.jpg';
                    } elseif ($id === 3) {
                        $imgSrc = 'coca600.jpg';
                    } elseif ($id === 4) {
                        $imgSrc = 'coca_lata.jpg';
                    }
                    ?>
                    <img src="<?php echo $imgSrc; ?>" class="img-fluid" alt="<?php echo $nome; ?>">
                  </div>

                  <div class="menu-item-content">
                    <div class="menu-item-header">
                      <h4><?php echo $nome; ?></h4>
                    </div>
                    <p><?php echo $desc !== '' ? $desc : 'Sem descrição.'; ?></p>
                    <div class="menu-item-footer">
                      <span class="price">R$ <?php echo $preco; ?></span>
                      <div style="display: flex; align-items: center; gap: 10px;">
                        <button type="button" onclick="mudarQuantidade(-1, '<?php echo $idCampo; ?>')">-</button>
                        <input type="text"
                               id="<?php echo $idCampo; ?>"
                               value="0"
                               readonly
                               style="width: 40px; text-align: center;">
                        <button type="button" onclick="mudarQuantidade(1, '<?php echo $idCampo; ?>')">+</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endwhile; ?>
            </div>

            <div class="row mt-5">
              <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="300">
                <form method="POST" action="salvar_session.php">
                  <input type="hidden" name="qtd_marmita_grande" id="input_marmita_grande">
                  <input type="hidden" name="qtd_marmita_pequena" id="input_marmita_pequena">
                  <input type="hidden" name="qtd_coca600" id="input_coca600">
                  <input type="hidden" name="qtd_cocalata" id="input_cocalata">

                  <?php if ($lojaAberta): ?>
                    <?php if ($logado): ?>
                      <button type="submit" class="btn" style="background-color: #5a0018; color: white; border: none; padding: 10px 25px; border-radius: 5px;">
                        Fazer Pedido
                      </button>
                    <?php else: ?>
                      <button type="button"
                              class="btn"
                              style="background-color: #5a0018; color: white; border: none; padding: 10px 25px; border-radius: 5px;"
                              data-bs-toggle="modal"
                              data-bs-target="#modalLoginAviso">
                        Fazer Pedido
                      </button>
                    <?php endif; ?>
                  <?php else: ?>
                    <button type="button" class="btn btn-secondary" style="padding: 10px 25px; border-radius: 5px;" disabled>
                      Loja fechada no momento.
                    </button>
                  <?php endif; ?>

                </form>
              </div>
            </div>

          </div>
        </div>
      </div>

    </section>
    <section id="about" class="about section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-center">
          <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
            <div class="about-image">
              <img src="maedigital32" alt="Executive Chef" class="img-fluid rounded">
              <div class="experience-badge">
                <span class="years">10+</span>
                <span class="text">Mais de 10 anos cozinhando com excelência</span>
              </div>
            </div>
          </div>

          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
            <div class="about-content">
              <div class="section-header">
                <h2>Conheça um pouco sobre nossa chef</h2>
                <p>Acumulando experiência desde 2015</p>
              </div>

              <div class="story-text">
                <p>Dona Ângela Maria — mãe, mulher, batalhadora e cozinheira...</p>
              </div>

              <div class="chef-quote">
                <blockquote>
                  "Não há nada melhor do que comer e desfrutar do seu trabalho árduo"
                </blockquote>
                <te>- Dona Angela Maria</cite>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="location" class="location section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5">

          <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
            <div class="location-content">
              <div class="content-header">
                <h2>Visite nosso estabelecimento.</h2>
                <p class="subtitle">
                  Localizado em R. Monteiro Lobato, N° 12 - Quadra 7 - RBS, Uruguaiana - RS, 97504-786
                </p>
              </div>

              <div class="map-wrapper">
                <iframe 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3462.8139336876225!2d-57.1066133!3d-29.783021300000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94535bdda4055cdf%3A0x180d6398f54651f9!2sAngelita%20Refei%C3%A7%C3%B5es%20e%20Marmitex!5e0!3m2!1spt-BR!2sbr!4v1760031322248!5m2!1spt-BR!2sbr"
                  width="100%" height="400"
                  style="border:0; border-radius: 10px;"
                  allowfullscreen="" loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="map-overlay">
                  <div class="location-badge">
                    <i class="bi bi-geo-alt"></i>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="250">
            <div class="contact-sidebar">

              <div class="contact-card">
                <div class="card-icon">
                  <i class="bi bi-building"></i>
                </div>
                <h3>Endereço</h3>
                <p>R. Monteiro Lobato, N° 12 - Quadra 7 - RBS<br>Uruguaiana - RS, 97504-786</p>
              </div>

              <div class="contact-card" data-aos="fade-up" data-aos-delay="350">
                <div class="card-icon">
                  <i class="bi bi-telephone-fill"></i>
                </div>
                <h3>Telefone e Whatsapp para contato</h3>
                <p class="phone">+55 55 99989-1062</p>
              </div>

              <div class="contact-card" data-aos="fade-up" data-aos-delay="450">
                <div class="card-icon">
                  <i class="bi bi-clock-fill"></i>
                </div>
                <h3>Horário de expediente</h3>
                <div class="hours-list">
                  <div class="hour-item">
                    <span class="day">Segunda - Sexta:</span>
                    <span class="time">11:30 - 14:30</span>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main>

  <?php include('footer.php'); ?>
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <script>
  function mudarQuantidade(valor, idCampo) {
      const input = document.getElementById(idCampo);
      let atual = parseInt(input.value) || 0;
      if (atual + valor >= 0) {
          input.value = atual + valor;
          sincronizarHidden();
      }
  }

  function sincronizarHidden() {
      document.getElementById('input_marmita_grande').value =
          document.getElementById('qtd-prod-1') ? document.getElementById('qtd-prod-1').value : 0;

      document.getElementById('input_marmita_pequena').value =
          document.getElementById('qtd-prod-2') ? document.getElementById('qtd-prod-2').value : 0;

      document.getElementById('input_coca600').value =
          document.getElementById('qtd-prod-3') ? document.getElementById('qtd-prod-3').value : 0;

      document.getElementById('input_cocalata').value =
          document.getElementById('qtd-prod-4') ? document.getElementById('qtd-prod-4').value : 0;
  }

  window.addEventListener('load', sincronizarHidden);
  </script>

  <div class="modal fade" id="modalLoginAviso" tabindex="-1" aria-labelledby="modalLoginAvisoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLoginAvisoLabel">Você não está logado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          Para fazer um pedido, você precisa entrar na sua conta ou se cadastrar.
        </div>
        <div class="modal-footer">
          <a href="login.php" class="btn" style="background-color: #5a0018; color: white;">Fazer login</a>
          <a href="login.php" class="btn btn-outline-secondary">Criar conta</a>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/main.js"></script>
</body>
</html>