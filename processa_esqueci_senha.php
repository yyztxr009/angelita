<?php
session_start();

$pdo = new PDO("mysql:host=localhost;dbname=tcc1","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$email = trim($_POST['email'] ?? '');

// valida email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email inválido.";
    exit;
}

// procura usuário pelo email
$stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// resposta genérica
$mensagemResposta = "Se este email estiver cadastrado, você receberá um código de recuperação em alguns instantes.";

// se não existir usuário, não diz nada, só mostra resposta genérica
if (!$usuario) {
    echo $mensagemResposta;
    exit;
}

// gera código de 6 dígitos e validade de 10 minutos
$codigo = sprintf("%06d", mt_rand(0, 999999));
$expira = date('Y-m-d H:i:s', time() + 600); // 10 minutos

// grava/atualiza na tabela recuperacao_codigo
$stmt = $pdo->prepare("
    INSERT INTO recuperacao_codigo (email, codigo, expira)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE codigo = VALUES(codigo), expira = VALUES(expira)
");
$stmt->execute([$email, $codigo, $expira]);

// monta texto do email
$assunto  = "Código de recuperação - Angelita Refeições";
$mensagem  = "Olá,\n\n";
$mensagem .= "Você solicitou a recuperação de senha no site Angelita Refeições.\n";
$mensagem .= "Seu código de recuperação é: {$codigo}\n\n";
$mensagem .= "Ele é válido por 10 minutos.\n\n";
$mensagem .= "Se você não fez essa solicitação, ignore este email.\n";

try {
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 0;

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'refeicoesangelita@gmail.com';
    $mail->Password   = 'wdbfrhsiadohdeww';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // desativa verificação de certificado para funcionar no Wamp [web:91][web:94]
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ],
    ];

    $mail->setFrom('refeicoesangelita@gmail.com', 'Angelita Refeições');
    $mail->addReplyTo('refeicoesangelita@gmail.com', 'Angelita Refeições');
    $mail->addAddress($email);

    $mail->isHTML(false);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;

    $mail->send();

    // guarda email na sessão para reaproveitar na próxima tela
    $_SESSION['rec_email'] = $email;

    // vai para a tela de digitar código
    header("Location: verificar_codigo.php?email=" . urlencode($email));
    exit;
} catch (Exception $e) {
    echo "Erro ao enviar email: " . htmlspecialchars($mail->ErrorInfo);
    echo "<br><br>Seu código para teste é: {$codigo}";
}
