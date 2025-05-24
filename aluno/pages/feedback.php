<?php
    require_once("aluno/func/Php/Functions.php");
    require_once("assets/func/php/Conn.php")
?>
<?php if ($formFoiEnviado): ?>
<div class="form" id="feedbackProva">
	<h2>Resultado da Prova</h2>

	<?php
	$alternativasCertas = 0;
	$alternativasErradas = 0;

	foreach ($_POST as $key => $alternativaId) {
		if (strpos($key, 'alternativa') === 0) {
			$questaoIndex = str_replace('alternativa', '', $key);
			$alternativa = selectDatabase("SELECT * FROM alternativas WHERE id_alternativa = $alternativaId", $conn);
			$isCorreta = (bool) $alternativa[0][3] ?? false;
			$questaoId = $alternativa[0][1] ?? null;
			$feedback = selectDatabase("SELECT feedback_texto FROM feedbacks WHERE fk_questao = $questaoId", $conn);

			if ($isCorreta) {
				$alternativasCertas++;
			} else {
				$alternativasErradas++;
			}

			echo '
			<div style="margin-bottom: 1em;">
				<h3 style="color:#000;">Questão ' . ($questaoIndex + 1) . '</h3>
				<div class="' . ($isCorreta ? 'resposta-correta' : 'resposta-errada') . '" style="font-weight: bold;">
					' . ($isCorreta ? '✓ Resposta Correta' : '✗ Resposta Incorreta') . '
				</div>
				<div class="feedback-texto">
					' . ($feedback[0][0] ?? 'Nenhum feedback disponível para esta questão.') . '
				</div>
			</div>
			';
		}
	}
	grafico($alternativasCertas, $alternativasErradas);
	?>
</div>
<?php endif; ?>