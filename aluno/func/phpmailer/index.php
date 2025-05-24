<?php
// Inclui manualmente os arquivos do PHPMailer
require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Configurar encoding interno do PHP para UTF-8
mb_internal_encoding('UTF-8');

// Função para enviar email com PHPMailer
function enviarEmailPHPMailer($destinatario, $imagem_binaria, $assunto, $corpo_texto) {
    $remetente = 'projetojogogti@gmail.com';
    $senhaApp = 'ykwv echz otfn ztly';

    try {
        $mail = new PHPMailer(true);

        // Configuração SMTP do Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $remetente;
        $mail->Password = $senhaApp;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // ==================================================
        // CORREÇÕES PARA UTF-8 (PARTE CRÍTICA)
        // ==================================================
        $mail->CharSet = 'UTF-8'; // Define charset do email
        $mail->Encoding = 'base64'; // Melhora compatibilidade

        // Determina o tipo MIME da imagem
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_buffer($finfo, $imagem_binaria);
        finfo_close($finfo);

        // Configurações do email (com UTF-8 no nome do remetente)
        $mail->setFrom($remetente, 'Projeto Jogo GTÍ', 'UTF-8');
        $mail->addAddress($destinatario);
        
        // Assunto codificado para UTF-8
        $mail->Subject = mb_encode_mimeheader($assunto, 'UTF-8', 'B');
        
        // Corpo do email em UTF-8
        $mail->Body = $corpo_texto;
        $mail->AltBody = strip_tags($corpo_texto); // Versão texto puro

        // Anexa a imagem da memória
        $mail->addStringAttachment(
            $imagem_binaria,
            'grafico_' . date('d-m-Y') . '.' . getExtensionFromMime($mime_type), // Nome dinâmico
            'base64',
            $mime_type
        );

        return $mail->send();
    } catch (Exception $e) {
        error_log("Erro PHPMailer: " . $e->getMessage());
        return false;
    }
}

// Função auxiliar para extensão do arquivo
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

// ==================================================
// Código que gera o gráfico e dispara o email
// ==================================================
$erradas = 4;
$corretas = 6;

// Configuração do gráfico
$config = [
    'type' => 'bar',
    'format' => 'jpg',
    'data' => [
        'labels' => ['Dashboard'],
        'datasets' => [
            [
                'label' => 'Corretas',
                'data' => [$corretas],
                'backgroundColor' => '#55ff55',
                'borderColor' => '#000000',
                'borderWidth' => 3
            ],
            [
                'label' => 'Erradas',
                'data' => [$erradas],
                'backgroundColor' => '#ff5555',
                'borderColor' => '#000000',
                'borderWidth' => 3
            ],
        ]
    ]
];

// Gera URL do gráfico
$chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($config)) . '&width=200&height=200';

// Exibe o gráfico no navegador
echo "<img src='$chartUrl' alt='Dashboard de feedback' style='border: 1px solid #ccc; margin: 20px;'>";

// Envia o email
$destinatario = 'fabricio.jose426@gmail.com';
$imagem = file_get_contents($chartUrl);
$assunto = 'Relatório de Respostas - Projeto Jogo GTÍ (Acentuação: ç á ã)';
$corpo = "
    Olá Fabricio,

    Segue em anexo o gráfico atualizado com o desempenho dos usuários.
    
    Dados:
    ✅ Corretas: $corretas
    ❌ Erradas: $erradas

    Att.,
    Equipe do Projeto
";

if(enviarEmailPHPMailer($destinatario, $imagem, $assunto, $corpo)) {
    echo '<div style="color: green; padding: 15px; border: 1px solid green; margin: 20px;">
            ✅ Email enviado com sucesso! Acentuação preservada.
          </div>';
} else {
    echo '<div style="color: red; padding: 15px; border: 1px solid red; margin: 20px;">
            ❌ Falha no envio. Verifique os logs do servidor.
          </div>';
}
?>