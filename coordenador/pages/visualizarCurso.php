<?php
if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: /login.php');
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
$cursos = selectCursos();
?>

<h2>Lista de cursos</h2>

<?php if (count($cursos) > 0): ?>
    <?php foreach ($cursos as $curso): ?>
        <div class="form">
            <button class="toggle"><?= htmlspecialchars($curso['nome']) ?></button>
            <div class="detalhes">
                <div class="formGroup">
                    <span class="inputForm">Objetivo do curso:</span>
                    <p class="formControl"><?= htmlspecialchars($curso['objetivo']) ?></p>
                </div>
                <div class="formGroup">
                    <span class="inputForm">Eixo do curso:</span>
                    <p class="formControl"><?= nl2br(htmlspecialchars($curso['eixo'])) ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nenhum curso cadastrado no momento.</p>
<?php endif; ?>

<script>
    const botoes = document.querySelectorAll('.toggle');
    botoes.forEach(botao => {
        botao.addEventListener('click', () => {
            const card = botao.parentElement;
            card.classList.toggle('active');
        });
    });
</script>
