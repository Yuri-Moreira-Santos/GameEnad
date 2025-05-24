<?php
// ================================
// Arquivo de funções de envio de email
// ================================

// Inclui as classes do PHPMailer
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarEmailPHPMailer($destinatario, $imagem_binaria, $assunto, $corpo_texto) {
    $remetente = 'projetojogogti@gmail.com';
    $senhaApp = 'ykwv echz otfn ztly';

    try {
        $mail = new PHPMailer(true);

        // Configuração SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $remetente;
        $mail->Password = $senhaApp;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurações gerais
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // Remetente e destinatário
        $mail->setFrom($remetente, 'Projeto Jogo GTÍ');
        $mail->addAddress($destinatario);

        // Assunto e corpo
        $mail->Subject = mb_encode_mimeheader($assunto, 'UTF-8', 'B');
        $mail->Body = $corpo_texto;
        $mail->isHTML(true);
        $mail->AltBody = strip_tags($corpo_texto);

        // Anexar imagem
        if ($imagem_binaria) {
            $mime_type = getMimeType($imagem_binaria);
            $extensao = getExtensionFromMime($mime_type);
            $mail->addStringAttachment(
                $imagem_binaria,
                'grafico.' . $extensao,
                'base64',
                $mime_type
            );
        }

        return $mail->send();
    } catch (Exception $e) {
        error_log("Erro PHPMailer: " . $e->getMessage());
        return false;
    }
}

function getExtensionFromMime($mime_type) {
    $extensoes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/bmp' => 'bmp',
        'image/webp' => 'webp',
    ];
    return $extensoes[$mime_type] ?? 'dat';
}

function getMimeType($imagem_binaria) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $imagem_binaria);
    finfo_close($finfo);
    return $mime_type;
}
?>
