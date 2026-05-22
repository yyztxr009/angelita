<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tela de Login e Registro</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

  <link rel="stylesheet" href="style.css" />

  <style>
    /* AJUSTE DE ALTURA PARA CABER TODOS OS NOVOS CAMPOS */
    .container {
      min-height: 700px !important; 
    }

    .sign-up form {
      overflow-y: auto;
      padding-top: 20px;
      padding-bottom: 20px;
      justify-content: flex-start; 
    }

    .sign-up form::-webkit-scrollbar { width: 6px; }
    .sign-up form::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }

    .erro-login {
      color:#b10000; background:#ffe5e5; border-radius:8px;
      padding:8px 10px; font-size:14px; margin-bottom:10px; text-align:center;
    }
    
    .erro-msg {
      color: red; font-size: 12px; display: none; width: 100%;
      text-align: left; margin-top: -10px; margin-bottom: 10px; padding-left: 5px;
    }

    ::placeholder { color: #555555 !important; opacity: 1; }
    :-ms-input-placeholder { color: #555555 !important; }
    ::-ms-input-placeholder { color: #555555 !important; }

    .endereco-row { display: flex; gap: 5px; width: 100%; margin-bottom: 10px; }
    .endereco-row input { margin: 0 !important; }

    /* =========================================
       ESTILOS DO MODAL DE SUCESSO 
       ========================================= */
    .modal-overlay {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.6);
      display: flex; justify-content: center; align-items: center;
      z-index: 9999;
    }
    .modal-box {
      background: #fff; padding: 30px; border-radius: 10px;
      text-align: center; max-width: 400px; width: 90%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      animation: fadeIn 0.3s ease-in-out;
    }
    .modal-box i { font-size: 50px; color: #28a745; margin-bottom: 15px; }
    .modal-box h2 { color: #333; margin-bottom: 10px; font-size: 24px; }
    .modal-box p { color: #555; margin-bottom: 25px; font-size: 15px; }
    .modal-box button {
      background-color: #6a040f; color: #fff; border: none;
      padding: 10px 30px; border-radius: 5px; cursor: pointer;
      font-size: 16px; font-weight: bold; transition: 0.3s;
    }
    .modal-box button:hover { background-color: #9d0208; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <?php if (!empty($_SESSION['msg_sucesso'])): ?>
  <div class="modal-overlay" id="modalSucesso">
    <div class="modal-box">
      <i class="fa-solid fa-circle-check"></i>
      <h2>Sucesso!</h2>
      <p><?= htmlspecialchars($_SESSION['msg_sucesso']); ?></p>
      <button onclick="fecharModal()">Continuar</button>
    </div>
  </div>
  <script>
    function fecharModal() {
      document.getElementById('modalSucesso').style.display = 'none';
    }
  </script>
  <?php unset($_SESSION['msg_sucesso']); // Limpa a mensagem para não aparecer de novo ?>
  <?php endif; ?>
  <div class="container" id="container">
    <div class="form-container sign-up">
      <form method="POST" action="processa_cadastro.php" id="form-cadastro">
        <h1>Criar Conta</h1>

        <input type="text" name="nome" placeholder="Nome" required />

        <input type="email"
               name="email"
               placeholder="Email"
               required
               title="Digite um email válido, por exemplo: exemplo@dominio.com" />

        <div style="position: relative; width: 100%;">
          <input type="password"
                 name="senha"
                 id="senha"
                 placeholder="Senha (6 a 10 caracteres)"
                 required
                 minlength="6"
                 maxlength="10" />
          <span onclick="toggleSenha()"
                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">
            <i class="fa fa-eye" id="icon-senha"></i>
          </span>
        </div>

        <div style="position: relative; width: 100%;">
          <input type="password"
                 name="confirmar_senha"
                 id="confirmar_senha"
                 placeholder="Confirmar Senha"
                 required
                 minlength="6"
                 maxlength="10" />
          <span onclick="toggleConfirmarSenha()"
                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">
            <i class="fa fa-eye" id="icon-confirmar-senha"></i>
          </span>
        </div>
        <span class="erro-msg" id="erro-senha">As senhas não coincidem!</span>

        <input type="text" name="cep" id="cep" placeholder="CEP (Digite para buscar)" maxlength="9" required style="margin-bottom: 5px;" />
        <input type="text" name="rua" id="rua" placeholder="Rua" required style="margin-bottom: 5px;" />
        
        <div class="endereco-row">
          <input type="text" name="bairro" id="bairro" placeholder="Bairro" required />
          <input type="text" name="cidade" id="cidade" placeholder="Cidade" required />
          <input type="text" name="uf" id="uf" placeholder="UF" required style="width: 30%;" maxlength="2" />
        </div>

        <input type="text"
               name="cpf"
               id="cpf"
               placeholder="CPF (000.000.000-00)"
               required
               pattern="^[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}$"
               title="Digite o CPF no formato 000.000.000-00" 
               style="margin-bottom: 10px;" />
        <span class="erro-msg" id="erro-cpf">CPF inválido! Verifique os números.</span>

        <div style="width: 100%; text-align: left; margin-top: 5px;">
          <label for="data_nascimento" style="font-size: 13px; color: #333; margin-left: 5px;">Data de Nascimento:</label>
          <input type="date" name="data_nascimento" id="data_nascimento" required style="margin-top: 5px;" />
        </div>

       <div style="display: flex; align-items: center; justify-content: flex-start; width: 100%; margin: 15px 0 5px 5px; text-align: left;">
          <input type="checkbox" name="termos" id="termos" required style="width: auto; margin: 0 10px 0 0; cursor: pointer;" />
          <label for="termos" style="font-size: 12px; color: #333; cursor: pointer;">
            Li e concordo com os <a href="termos.php" target="_blank" style="color: #333; text-decoration: underline;">Termos de Uso</a>.
          </label>
        </div>
        <span class="erro-msg" id="erro-termos" style="margin-bottom: 15px;">Você precisa aceitar os Termos de Uso!</span>

        <button type="submit">
          Criar Conta
        </button>
      </form>
    </div>

    <div class="form-container sign-in">
      <form method="POST" action="processa_login.php">
        <h1>Login</h1>

        <input type="email" name="email" placeholder="Email" required />

        <div style="position: relative; width: 100%;">
          <input type="password"
                 name="senha"
                 id="senha_login"
                 placeholder="Senha"
                 required />
          <span onclick="toggleSenhaLogin()"
                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">
            <i class="fa fa-eye" id="icon-senha-login"></i>
          </span>
        </div>
        
        <?php if (!empty($_SESSION['erro_login'])): ?>
        <div class="erro-login">
          <?= htmlspecialchars($_SESSION['erro_login']); ?>
        </div>
        <?php unset($_SESSION['erro_login']); ?>
        <?php endif; ?>

        <a href="esqueci_senha.php">Esqueceu sua Senha?</a>

        <button type="submit">
          LOGIN
        </button>
      </form>
    </div>

    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Bem-vindo de volta!</h1>
          <p>Insira seus dados pessoais para usar todos os recursos do site</p>
          <button class="hidden" id="login">Login</button>
        </div>

        <div class="toggle-panel toggle-right">
          <h1>Olá, Amigo!</h1>
          <p>Registre-se com seus dados pessoais para usar todos os recursos do site</p>
          <button class="hidden" id="register">Criar Conta</button>
        </div>
      </div>
    </div>
  </div>

  <script>
  function toggleSenha() {
    const input = document.getElementById('senha');
    const icon  = document.getElementById('icon-senha');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

  function toggleConfirmarSenha() {
    const input = document.getElementById('confirmar_senha');
    const icon  = document.getElementById('icon-confirmar-senha');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

  function toggleSenhaLogin() {
    const input = document.getElementById('senha_login');
    const icon  = document.getElementById('icon-senha-login');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

  const cepInput = document.getElementById('cep');
  cepInput.addEventListener('input', function(e) {
    let v = e.target.value.replace(/\D/g, '');
    if (v.length > 8) v = v.slice(0, 8);
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5);
    e.target.value = v;
  });

  cepInput.addEventListener('blur', function() {
    let cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
      fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(resposta => resposta.json())
        .then(dados => {
          if (!dados.erro) {
            document.getElementById('rua').value = dados.logradouro;
            document.getElementById('bairro').value = dados.bairro;
            document.getElementById('cidade').value = dados.localidade;
            document.getElementById('uf').value = dados.uf;
          } else {
            alert("CEP não encontrado!");
          }
        })
        .catch(erro => console.error("Erro ao buscar CEP:", erro));
    }
  });

  const cpfInput = document.getElementById('cpf');
  cpfInput.addEventListener('input', function (e) {
    let v = e.target.value.replace(/\D/g, ''); 
    if (v.length > 11) v = v.slice(0, 11);
    let f = '';
    if (v.length > 0)  f = v.slice(0, 3);
    if (v.length > 3)  f += '.' + v.slice(3, 6);
    if (v.length > 6)  f += '.' + v.slice(6, 9);
    if (v.length > 9)  f += '-' + v.slice(9, 11);
    e.target.value = f;
  });

  function validarCpfReal(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if(cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false; 
    let soma = 0, resto;
    for(let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
    resto = (soma * 10) % 11;
    if((resto == 10) || (resto == 11)) resto = 0;
    if(resto != parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for(let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if((resto == 10) || (resto == 11)) resto = 0;
    if(resto != parseInt(cpf.substring(10, 11))) return false;
    return true;
  }

  const formCadastro = document.getElementById('form-cadastro');
  const senha = document.getElementById('senha');
  const confirmarSenha = document.getElementById('confirmar_senha');
  const erroSenha = document.getElementById('erro-senha');
  const erroCpf = document.getElementById('erro-cpf');
  const termos = document.getElementById('termos');
  const erroTermos = document.getElementById('erro-termos');

  formCadastro.addEventListener('submit', function(e) {
    let temErro = false;

    if (senha.value !== confirmarSenha.value) {
      erroSenha.style.display = 'block'; temErro = true;
    } else { erroSenha.style.display = 'none'; }

    if (!validarCpfReal(cpfInput.value)) {
      erroCpf.style.display = 'block'; temErro = true;
    } else { erroCpf.style.display = 'none'; }

    if (!termos.checked) {
      erroTermos.style.display = 'block'; temErro = true;
    } else { erroTermos.style.display = 'none'; }

    if (temErro) { e.preventDefault(); }
  });
  </script>

  <script src="script.js"></script>
</body>
</html>