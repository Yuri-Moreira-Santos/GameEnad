<?php
require_once("aluno/func/Php/Functions.php");

if (!isset($_SESSION['prova'])) {
    $_SESSION['prova'] = gerarProvaAleatoria();
}

$provaGerada = $_SESSION['prova'];

$formFoiEnviado = !empty($_POST);
?>
<?php if (!$formFoiEnviado): ?>
<form method="post" class="form">
    <div id="perguntas">
        <?php foreach ($provaGerada as $index => $questao): ?>
            <div class="pergunta<?= $index === 0 ? ' mostrar' : '' ?>">
                <h2><?= $questao["titulo_enunciado$index"] ?></h2>
                <p><?= $questao["texto_enunciado$index"] ?></p>
                <?php foreach ($questao["alternativas$index"] as $altIndex => $alternativa): ?>
                    <div class="checkbox-group">
                        <input type="radio" name="alternativa<?= $index ?>"
                               id="alt<?= $index ?>_<?= $altIndex ?>"
                               value="<?= $alternativa[0] ?>"
                               required>
                        <label for="alt<?= $index ?>_<?= $altIndex ?>">
                            <?= chr(65 + $altIndex) ?> - <?= $alternativa[1] ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="navegacao">
        <button type="button" id="voltar" class="btn">Voltar</button>
        <div id="bolinhas"></div>
        <button type="button" id="avancar" class="btn">Avançar</button>
        <button type="submit" id="enviar" class="btn" style="display: none;">Enviar Prova</button>
    </div>
</form>
<?php else: ?>
    <?php include("feedback.php"); ?>
<?php endif; ?>
<script>
    const perguntas = document.querySelectorAll('.pergunta');
    const bolinhasDiv = document.getElementById('bolinhas');
    const btnAvancar = document.getElementById('avancar');
    const btnVoltar = document.getElementById('voltar');
    const btnEnviar = document.getElementById('enviar');
    let atual = 0;

    perguntas.forEach((_, idx) => {
        const bolinha = document.createElement('div');
        bolinha.className = 'bolinha' + (idx === 0 ? ' ativa' : '');
        bolinha.addEventListener('click', () => {
            atual = idx;
            atualizarTela();
        });
        bolinhasDiv.appendChild(bolinha);
    });

    const atualizarTela = () => {
        perguntas.forEach((p, i) => {
            p.classList.toggle('mostrar', i === atual);
        });

        document.querySelectorAll('.bolinha').forEach((b, i) => {
            b.classList.toggle('ativa', i === atual);
        });

        btnVoltar.style.visibility = atual === 0 ? 'hidden' : 'visible';

        if (atual === perguntas.length - 1) {
            btnAvancar.style.display = 'none';
            btnEnviar.style.display = 'inline-block';
        } else {
            btnAvancar.style.display = 'inline-block';
            btnEnviar.style.display = 'none';
        }

    };

    btnAvancar.addEventListener('click', () => {
        if (atual < perguntas.length - 1) {
            atual++;
            atualizarTela();
        }
    });

    btnVoltar.addEventListener('click', () => {
        if (atual > 0) {
            atual--;
            atualizarTela();
        }
    });

    atualizarTela();
</script>
