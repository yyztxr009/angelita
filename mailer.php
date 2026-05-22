<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // se instalou via Composer

function criarMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    // Config SMTP básico (exemplo Gmail)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'seuemail@gmail.com';      // SEU email
    $mail->Password   = 'SUA_SENHA_DE_APP';        // senha de app do Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
    $mail->Port       = 587;                       // porta TLS recomendada [web:49][web:55]

    // Padrão de remetente
    $mail->setFrom('angelitarefeicoes@gmail.com', 'Angelita Refeições');
    $mail->addReplyTo('angelitarefeicoes@gmail.com', 'Angelita Refeições');

    $mail->isHTML(false); // texto simples
    $mail->CharSet = 'UTF-8';

    return $mail;
}
