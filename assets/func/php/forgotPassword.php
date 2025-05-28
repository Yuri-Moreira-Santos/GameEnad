<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
require($_SERVER['DOCUMENT_ROOT'] . '/aluno/func/phpmailer/email.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['emailInstitucional'];

    // Verifica se o e-mail existe
    $stmt = $conn->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Faz o bind dos resultados
        $stmt->bind_result($id, $nome);
        $stmt->fetch();

        // Gera token
        $token = bin2hex(random_bytes(50));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Atualiza no banco
        $update = $conn->prepare("UPDATE usuarios SET reset_token = ?, reset_token_expira = ? WHERE id = ?");
        $update->bind_param("ssi", $token, $expira, $id);
        $update->execute();

        // Cria link
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $link = "$protocolo://$host/assets/func/php/resetPassword.php?token=$token";

        // Monta e-mail
        $assunto = "Recuperação de senha - GameEnad";
        $corpo = "
            <h2>Recuperação de senha</h2>
            <p>Olá, $nome!</p>
            <p>Você solicitou uma recuperação de senha. Clique no link abaixo para criar uma nova senha:</p>
            <p><a href='$link'>Redefinir minha senha</a></p>
            <p>Se você não solicitou, ignore este e-mail.</p>
            <p><b>Este link expira em 1 hora.</b></p>
        ";

        // Envia
        $enviado = enviarEmailPHPMailer($email, null, $assunto, $corpo);

        if ($enviado) {
            echo "<script>alert('E-mail enviado! Verifique sua caixa de entrada ou spam.'); window.location.href='/login.php';</script>";
        } else {
            echo "<script>alert('Erro ao enviar o e-mail.');</script>";
        }
    } else {
        echo "<script>alert('E-mail não encontrado.');</script>";
    }

    $stmt->close();

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Esqueci minha senha</title>
</head>
<body>
    <header>
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . '/assets/php/navBar.php')
        ?>
        <form class="center form loginForm" method="POST">
            <section>
                <h2>Esqueci minha senha</h2>
                <div class="formGroup">
                    <span class="inputForm fa fa-user"></span>
                    <input class="formControl" type="email" name="emailInstitucional" required placeholder="E-mail">
                </div>                
                <button type="submit" class="btn">Enviar</button>
            </section>
        </form>
    </header>
</body>
</html>