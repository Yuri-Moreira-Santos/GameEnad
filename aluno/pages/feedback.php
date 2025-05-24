<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/aluno/func/php/quickchart.php');
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
?>
</div>
<?php endif; ?>