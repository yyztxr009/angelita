<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "conexao.php";

$email = $_POST['email'];

$conexao = conectar();
$sql = "SELECT * FROM usuario WHERE email='$email'";
$result = mysqli_query($conexao, $sql);
if ($result == false) {
    die("Erro ao executar o SQL. " . mysqli_error($conexao));
}
$usuario = mysqli_fetch_assoc($result);
if (is_null($usuario) == false) {
    $token = bin2hex(random_bytes(32));
    $sql = "INSERT INTO recuperar_senha (email, token) VALUES ('$email', '$token')";
    $result = mysqli_query($conexao, $sql);
    if ($result == false) {
        die("Erro ao executar o SQL. " . mysqli_error($conexao));
    }
    enviarEmail($email, $token);
} else {
    echo "Email não existe no sistema!";
}

function enviarEmail($email, $token)
{
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';
    require_once 'PHPMailer/src/Exception.php';

    $mail = new PHPMailer(true);
    try {
        // configurações
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->setLanguage('br');
        //$mail->SMTPDebug = SMTP::DEBUG_OFF;  //tira as mensagens
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //imprime as mensagens
        $mail->isSMTP();                       //envia o email usando SMTP
        $mail->Host = 'smtp.gmail.com';        //Set the SMTP server to send through
        $mail->SMTPAuth = true;                //Enable SMTP authentication
        $mail->Username = "thiago.krug@iffarroupilha.edu.br"; //SMTP username
        $mail->Password = ""; //SMTP password
        //Enable implicit TLS encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        /* TCP port to connect to; use 587 if you have set 
    `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` */
        $mail->Port = 587;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $mail->setFrom("thiago.krug@iffarroupilha.edu.br", 'Aula de TCC');
        $mail->addAddress($email);     //Add a recipient
        $mail->addReplyTo("thiago.krug@iffarroupilha.edu.br", "Thiago Krug");

        //Content
        $mail->isHTML(true);        //Set email format to HTML
        $mail->Subject = 'Recuperação de Senha do Sistema';
        $mail->Body = 'Olá!<br>
        Você solicitou a recuperação da sua conta no nosso sistema.
        Para isso, clique no link abaixo para realizar a troca de senha:<br>
        <a href="' . $_SERVER['SERVER_NAME'] .
            '/recuperar_senha/nova_senha.php?email=' .
            $email . '&token=' . $token .
            '">Clique aqui para recuperar o acesso à sua conta!</a><br>
        <br>
        Atenciosamente<br>
        Equipe do sistema.';

        $mail->send();
        echo 'Email enviado com sucesso!<br>Confira o seu email.';
    } catch (Exception $e) {
        echo "Não foi possível enviar o email. 
          Mailer Error: {$mail->ErrorInfo}";
    }
}
