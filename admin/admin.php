<?php
session_start();

// se não estiver logado ou não for admin, manda para o login
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: http://localhost/Dimitri_31_prog/angelita/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Painel Administrativo — Angelita</title>

<!-- Bootstrap + Icons via CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- Quill -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
:root{
  --brand: #5a0018;
  --muted-bg: #f5f5f0;
  --card-bg: #ffffff;
}

html,body{
  height:100%;
  background: var(--muted-bg);
  margin:0;
  font-family: 'Poppins', system-ui, sans-serif;
  color:#222;
  overflow-x:hidden;
}

/* SIDEBAR */
#sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 260px;
  height: 100vh;
  background: linear-gradient(180deg,var(--brand), #3f0010);
  color: #fff;
  padding: 24px 18px;
  box-shadow: 0 6px 22px rgba(0,0,0,0.25);
  z-index: 1030;
  transition: transform .28s ease;
}
#sidebar.collapsed { transform: translateX(-100%); }
#sidebar h4 { margin:0 0 6px; font-weight:700; }
#sidebar .small { font-size: .8rem; }

#sidebar .nav-link {
  color: rgba(255,255,255,0.9);
  padding: 9px 12px;
  border-radius: 10px;
  display:flex;
  gap:10px;
  align-items:center;
  font-size:.9rem;
}
#sidebar .nav-link i{
  font-size:1rem;
}
#sidebar .nav-link.active,
#sidebar .nav-link:hover {
  background: rgba(255,255,255,0.18);
  color:#fff;
}

/* CONTEÚDO */
#content {
  padding:24px 28px 32px;
  margin-left:260px;
  transition: margin-left .28s ease;
}
#content.full { margin-left:0; }

/* TOPBAR */
.topbar {
  position: sticky;
  top:0;
  z-index: 10;
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin:-8px -4px 18px;
  padding:8px 6px 10px;
  background-color: rgba(243,244,246,0.9);
  backdrop-filter: blur(8px);
}
.topbar h3{
  margin:0;
  color:var(--brand);
  font-weight:700;
  letter-spacing:.02em;
}
.hamb {
  display:none;
  border-radius:10px;
  padding:8px 9px;
  background: #fff;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  border:0;
}

/* CARDS */
.admin-card {
  background: var(--card-bg);
  border-radius:16px;
  padding:18px 20px;
  box-shadow: 0 10px 30px rgba(15,23,42,0.06);
  margin-bottom:20px;
  border:1px solid rgba(148,163,184,0.25);
}

/* KPIs */
.kpi {
  display:flex;
  flex-wrap:wrap;
  gap:16px;
}
.kpi .item {
  flex:1 1 180px;
  background:#ffffff;
  border-radius:14px;
  padding:12px 14px;
  display:flex;
  gap:12px;
  align-items:center;
  border:1px solid rgba(148,163,184,0.25);
}
.kpi .item-icon {
  width:40px;
  height:40px;
  border-radius:999px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#f3f4f6;
  color:var(--brand);
}
.kpi .item-icon i{
  font-size:20px;
}
.kpi .value { font-weight:700; font-size:21px; color:var(--brand); line-height:1; }
.kpi .label { font-size:.8rem; color:#6b7280; }

/* TABELA */
.table-wrap { max-height:420px; overflow:auto; border-radius:12px; }
table.table {
  margin-bottom:0;
}
table.table thead th {
  position:sticky;
  top:0;
  background:#f9fafb;
  z-index:2;
  font-size:.78rem;
  text-transform:uppercase;
  letter-spacing:.06em;
}
table.table tbody td{
  vertical-align:middle;
  font-size:.88rem;
}

/* BADGES STATUS */
.status-badge {
  padding:5px 10px;
  border-radius:999px;
  font-weight:600;
  font-size:12px;
  color:#fff;
  text-transform:capitalize;
  letter-spacing:.03em;
}
.st-pendente { background:#f59e0b; }
.st-preparando { background:#0ea5e9; }
.st-pronto { background:#22c55e; }
.st-saiu { background:#6366f1; }
.st-entregue { background:#9ca3af; }

/* LOJA PILL */
.loja-pill {
  padding:8px 13px;
  border-radius:999px;
  font-weight:700;
  color:#fff;
  display:inline-flex;
  gap:8px;
  align-items:center;
  font-size:.86rem;
}
.loja-aberta { background:#16a34a; box-shadow:0 0 0 1px rgba(34,197,94,0.4); }
.loja-fechada { background:#b91c1c; box-shadow:0 0 0 1px rgba(239,68,68,0.4); }

/* Quill */
.ql-editor { min-height:160px; }

/* RESPONSIVIDADE */
@media (max-width: 991px){
  #sidebar { transform: translateX(-100%); width:240px; }
  #sidebar.show { transform: translateX(0); }
  #content { margin-left:0; padding:16px 14px 24px; }
  .hamb { display:inline-flex !important; }
  .topbar{
    padding:8px 10px 10px;
    margin:-8px -8px 14px;
  }
}

/* Ajuste leve para evitar overlap em telas muito baixas */
@media (max-height: 540px){
  #sidebar{
    padding-top:16px;
  }
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div id="sidebar">
  <h4>Angelita Admin</h4>
  <div class="small text-white-50">Painel de gerenciamento</div>

  <nav class="nav flex-column mt-4">
    <a class="nav-link active" href="admin.php"><i class="bi bi-speedometer2"></i> Controle geral</a>
    <a class="nav-link" href="admin_pedidos.php"><i class="bi bi-receipt"></i> Pedidos</a>
    <a class="nav-link" href="admin_usuarios.php"><i class="bi bi-people"></i> Usuários</a>
    <a class="nav-link" href="admin_cardapio.php"><i class="bi bi-book"></i> Produtos</a>
    <a class="nav-link" href="../index.php" target="_blank">
      <i class="bi bi-box-arrow-up-right"></i> Ver site
    </a>

    <div class="mt-4 small text-white-50">
      Logado como:
      <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Admin') ?></strong>
    </div>
  </nav>
</div>

<!-- CONTEÚDO -->
<div id="content">
  
  <div class="topbar">
    <div class="d-flex align-items-center gap-2">
      <button class="hamb" id="toggleSidebar"><i class="bi bi-list"></i></button>
      <h3>Dashboard</h3>
    </div>

    <div class="d-flex align-items-center gap-3">
      <span id="lojaPill" class="loja-pill loja-fechada"><i class="bi bi-shop"></i> Carregando...</span>
      <button class="btn btn-outline-secondary btn-sm" id="btnAtualizar"><i class="bi bi-arrow-clockwise"></i></button>
    </div>
  </div>

  <!-- KPIs -->
  <div class="admin-card">
    <div class="kpi">
      <div class="item">
        <div class="item-icon">
          <i class="bi bi-cart-check"></i>
        </div>
        <div>
          <div class="value" id="kpiPedidos">0</div>
          <div class="label">Pedidos pendentes</div>
        </div>
      </div>

      <div class="item">
        <div class="item-icon">
          <i class="bi bi-people"></i>
        </div>
        <div>
          <div class="value" id="kpiUsuarios">0</div>
          <div class="label">Usuários cadastrados</div>
        </div>
      </div>

      <div class="item">
        <div class="item-icon">
          <i class="bi bi-clock-history"></i>
        </div>
        <div>
          <div class="value" id="kpiVendasHoje">R$ 0,00</div>
          <div class="label">Vendas hoje</div>
        </div>
      </div>
    </div>
  </div>

  <!-- LOJA -->
  <div class="admin-card d-flex justify-content-between align-items-center flex-wrap">
    <div>
      <h5 style="margin:0">Controle da loja</h5>
      <small class="text-muted">Abrir/Fechar loja e atualizar cardápio</small>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-success" id="btnAbrir"><i class="bi bi-unlock"></i> Abrir</button>
      <button class="btn btn-danger" id="btnFechar"><i class="bi bi-lock"></i> Fechar</button>
    </div>
  </div>

  <!-- LISTA DE PEDIDOS -->
  <div class="admin-card">
    <div class="d-flex justify-content-between mb-2">
      <h5 style="margin:0">Pedidos pendentes</h5>
      <small class="text-muted">Atualiza a cada 30s</small>
    </div>

    <div class="table-wrap">
      <table class="table table-hover table-sm align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Itens</th>
            <th>Obs.</th>
            <th>Horário</th>
            <th>Total</th>
            <th>Status</th>
            <th style="min-width:220px">Ações</th>
          </tr>
        </thead>
        <tbody id="listaPedidos">
          <tr><td colspan="8" class="text-center">Carregando...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- MODAIS -->
<div class="modal fade" id="modalAbrir" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Atualizar cardápio do dia</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-2">Preencha o cardápio do dia. Sem cardápio, a loja não pode ser aberta.</p>
        <div id="editorCardapio"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" id="confirmAbrir">Salvar & Abrir loja</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalFechar" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Fechar loja</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Confirmar fechamento da loja?</div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger btn-sm" id="confirmFechar">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
// Sidebar
const sidebar = document.getElementById("sidebar");
const content = document.getElementById("content");
const toggleSidebar = document.getElementById("toggleSidebar");

toggleSidebar.onclick = () => {
  sidebar.classList.toggle("show");
};

// Fecha sidebar ao clicar fora
document.addEventListener("click", e => {
  if (window.innerWidth <= 991){
    if (!sidebar.contains(e.target) && !toggleSidebar.contains(e.target)){
      sidebar.classList.remove("show");
    }
  }
});

// Quill
const quill = new Quill('#editorCardapio', {
  theme: 'snow',
  placeholder: 'Escreva o cardápio do dia...'
});

// -------- KPIs --------
function carregarKPIs(){
  $.getJSON("ajax/kpis.php")
   .done(data => {
     $("#kpiPedidos").text(data.pedidos_pendentes || 0);
     $("#kpiUsuarios").text(data.usuarios || 0);
     $("#kpiVendasHoje").text(data.vendas_hoje_formatted || "R$ 0,00");
   })
   .fail((jq,x,e) => {
     console.error("Erro KPIs", x, e, jq.responseText);
   });
}

// -------- PEDIDOS --------
function montarTabelaPedidos(pedidos){
  if (!pedidos || pedidos.length === 0){
    $("#listaPedidos").html('<tr><td colspan="8" class="text-center">Nenhum pedido pendente.</td></tr>');
    return;
  }

  let html = "";
  pedidos.forEach(p => {
    const valorNum = parseFloat(p.valor || 0);
    const valor = "R$ " + valorNum.toFixed(2).replace(".", ",");
    const dataHora = p.data_pedido || "";
    const status = p.status || "pendente";
    const cls = "st-" + status;
    const obs = p.observacoes ? p.observacoes : "";

    html += `
      <tr>
        <td>${p.id}</td>
        <td>${p.nome}</td>
        <td>${p.itens || ""}</td>
        <td>${obs}</td>
        <td>${dataHora}</td>
        <td>${valor}</td>
        <td><span class="status-badge ${cls}">${status}</span></td>
        <td>
          <button class="btn btn-sm btn-outline-success atualiza-status" data-pedido="${p.id}" data-status="preparando">Preparando</button>
          <button class="btn btn-sm btn-outline-primary atualiza-status" data-pedido="${p.id}" data-status="pronto">Pronto</button>
          <button class="btn btn-sm btn-outline-warning atualiza-status" data-pedido="${p.id}" data-status="saiu">Saiu para entrega</button>
          <button class="btn btn-sm btn-outline-secondary atualiza-status" data-pedido="${p.id}" data-status="entregue">Entregue</button>
        </td>
      </tr>`;
  });
  $("#listaPedidos").html(html);
  bindAcoesPedidos();
}

function carregarPedidos(){
  $.getJSON("ajax/pedidos_pendentes.php")
   .done(data => {
     montarTabelaPedidos(data);
   })
   .fail((jq,x,e) => {
     console.error("Erro pedidos", x, e, jq.responseText);
   });
}

// -------- STATUS LOJA --------
function atualizarPillLoja(data){
  const aberto = parseInt(data.aberto ?? 0, 10) === 1;
  const pill = $("#lojaPill");
  pill.removeClass("loja-aberta loja-fechada");

  if (aberto){
    pill.addClass("loja-aberta").html('<i class="bi bi-shop"></i> Loja aberta');
  } else {
    pill.addClass("loja-fechada").html('<i class="bi bi-shop"></i> Loja fechada');
  }
}

function carregarStatusLoja(){
  $.post("ajax/status_loja.php", {acao: "buscar"})
   .done(resp => {
     let data = resp;
     if (typeof resp === "string"){
       try { data = JSON.parse(resp); } catch(e){ console.error("JSON status loja inválido", resp); return; }
     }
     atualizarPillLoja(data);
   })
   .fail((jq,x,e) => {
     console.error("Erro status loja", x, e, jq.responseText);
   });
}

// -------- AÇÕES PEDIDOS --------
function bindAcoesPedidos(){
  $(".atualiza-status").off().on("click", function(){
    const id = $(this).data("pedido");
    const status = $(this).data("status");

    $.post("ajax/atualizar_status.php", {id, status})
     .done(() => {
       carregarPedidos();
       carregarKPIs();
     })
     .fail((jq,x,e) => {
       console.error("Erro atualizar status", x, e, jq.responseText);
     });
  });
}

// -------- BOTÕES LOJA --------
$("#btnAbrir").click(() => {
  quill.root.innerHTML = "";
  new bootstrap.Modal("#modalAbrir").show();
});

$("#confirmAbrir").click(() => {
  const textoCardapio = quill.getText().trim();

  if (!textoCardapio) {
    alert("Você precisa preencher o cardápio do dia para abrir a loja.");
    return;
  }

  $.post("ajax/status_loja.php", {acao: "abrir", cardapio: quill.root.innerHTML})
   .done(() => {
     carregarStatusLoja();
     bootstrap.Modal.getInstance(document.getElementById("modalAbrir")).hide();
   })
   .fail((jq,x,e) => {
     console.error("Erro abrir loja", x, e, jq.responseText);
   });
});

$("#btnFechar").click(() => {
  new bootstrap.Modal("#modalFechar").show();
});

$("#confirmFechar").click(() => {
  $.post("ajax/status_loja.php", {acao: "fechar"})
   .done(() => {
     carregarStatusLoja();
     bootstrap.Modal.getInstance(document.getElementById("modalFechar")).hide();
   })
   .fail((jq,x,e) => {
     console.error("Erro fechar loja", x, e, jq.responseText);
   });
});

// -------- ATUALIZAÇÕES --------
$("#btnAtualizar").click(() => {
  carregarKPIs();
  carregarPedidos();
  carregarStatusLoja();
});

// Inicial
carregarKPIs();
carregarPedidos();
carregarStatusLoja();
setInterval(() => {
  carregarKPIs();
  carregarPedidos();
  carregarStatusLoja();
}, 30000);
</script>

</body>
</html>
