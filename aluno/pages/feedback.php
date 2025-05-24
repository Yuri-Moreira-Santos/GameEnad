<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/aluno/func/php/quickchart.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/aluno/func/phpmailer/email.php');
?>
<?php if ($formFoiEnviado): ?>
<div class="form" id="feedbackProva">
	<h2>Resultado da Prova</h2>

<?php
$alternativasCertas = 0;
$alternativasErradas = 0;

foreach ($_POST as $key => $alternativaId) {
    if (strpos($key, 'alternativa') === 0) {
        // Buscar alternativa pelo ID
        $stmtAlt = $conn->prepare("SELECT * FROM alternativas WHERE id = ?");
        $stmtAlt->bind_param("i", $alternativaId);
        $stmtAlt->execute();
        $resultAlt = $stmtAlt->get_result();
        $alternativa = $resultAlt->fetch_assoc();

        if (!$alternativa) {
            echo "<p>Alternativa com ID $alternativaId não encontrada.</p>";
            continue;
        }

        $isCorreta = (bool) $alternativa['correta'];
        if ($isCorreta) {
            $alternativasCertas++;
            $feedbackTexto = "Parabéns! Você acertou esta questão.";
        } else {
            $alternativasErradas++;
            $feedbackTexto = "Ops, essa resposta está incorreta. Tente novamente.";
        }

        echo '
        <div style="margin-bottom: 1em;">
            <h3 style="color:#000;">Questão ' . (intval(str_replace('alternativa', '', $key)) + 1) . '</h3>
            <div class="' . ($isCorreta ? 'resposta-correta' : 'resposta-errada') . '" style="font-weight: bold;">
                ' . ($isCorreta ? '✓ Resposta Correta' : '✗ Resposta Incorreta') . '
            </div>
            <div class="feedback-texto">
                ' . $feedbackTexto . '
            </div>
        </div>
        ';
    }
}

grafico($alternativasCertas, $alternativasErradas);

$usuarioId = $_SESSION['usuario']['id'] ?? null;

if ($usuarioId) {
    $stmtUser = $conn->prepare("SELECT nome, email FROM usuarios WHERE id = ?");
    $stmtUser->bind_param("i", $usuarioId);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();

    if ($user) {
        $emailAluno = $user['email'];
        $nomeAluno = $user['nome'];

        // 2. Gera imagem do gráfico
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => ['Desempenho'],
                'datasets' => [
                    [
                        'label' => 'Corretas',
                        'data' => [$alternativasCertas],
                        'backgroundColor' => '#00cc66'
                    ],
                    [
                        'label' => 'Erradas',
                        'data' => [$alternativasErradas],
                        'backgroundColor' => '#cc3333'
                    ],
                ]
            ]
        ];

        $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig)) . '&width=400&height=300';
        $imagem = file_get_contents($chartUrl);

        // 3. Monta o email
        $assunto = "Seu Desempenho na Prova - GameEnad";
        $corpo = "
            <h3>Olá, $nomeAluno!</h3>
            <p>Parabéns por concluir sua prova no GameEnad!</p>
            <p>Segue seu desempenho:</p>
            <ul>
                <li>✅ Corretas: <b>$alternativasCertas</b></li>
                <li>❌ Erradas: <b>$alternativasErradas</b></li>
            </ul>
            <p>O gráfico de desempenho está em anexo.</p>
            <p><b>Equipe GameEnad</b></p>
        ";

        // 4. Envia
        if (enviarEmailPHPMailer($emailAluno, $imagem, $assunto, $corpo)) {
            echo "<p style='color:green;'>✅ Seu desempenho foi enviado para o email <b>$emailAluno</b>.</p>";
        } else {
            echo "<p style='color:red;'>❌ Falha ao enviar email para <b>$emailAluno</b>. Verifique os dados.</p>";
        }

    } else {
        echo "<p style='color:red;'>❌ Usuário não encontrado.</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Sessão de usuário não encontrada.</p>";
}
?>
</div>
<?php endif; ?>
