<?php
if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: /login.php');
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
$disciplinas = selectDisciplinas();
?>

<h2>Lista de disciplinas</h2>

<?php foreach ($disciplinas as $disciplina): ?>
    <div class="form">
        <button class="toggle"><?= htmlspecialchars($disciplina[1]) ?></button>
        <div class="detalhes">
            <div class="formGroup">
                <span class="inputForm">Ementa:</span>
                <p class="formControl"><?= htmlspecialchars($disciplina[2]) ?></p>
            </div>
            <div class="formGroup">
                <span class="inputForm">Objetivos de aprendizagem:</span>
                <p class="formControl"><?= htmlspecialchars($disciplina[3]) ?></p>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    const botoes = document.querySelectorAll('.toggle');
    botoes.forEach(botao => {
        botao.addEventListener('click', () => {
            const card = botao.parentElement;
            card.classList.toggle('active');
        });
    });
</script>
