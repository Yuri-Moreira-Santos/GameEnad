<?php
require_once("coordenador/func/Php/Functions.php");
$cursos = selectCursos();
?>

<h2>Lista de Cursos</h2>

<?php foreach ($cursos as $curso): ?>
<div class="form">
    <button class="toggle"><?= ($curso[2]) ?></button>
    <div class="detalhes">
        <form method="post">
            <input type="hidden" name="idCurso" value="<?= ($curso[0]) ?>">
            <div class="formGroup">
                <span class="inputForm" for="nomeCurso">Nome do curso:</span>
                <input class="formControl" type="text" name="nomeCurso" maxlength="100" value="<?= ($curso[2]) ?>" required>
            </div>
            <div class="formGroup">
                <span class="inputForm">Objetivo do curso:</span>
                <input class="formControl" value="<?= ($curso[3]) ?>" required>
            </div>
            <div class="formGroup">
                <span class="inputForm" for="eixoCurso">Eixo do curso:</span>
                <textarea class="formControl" type="text" name="eixoCurso" row="5" cols="60" required><?= ($curso[4]) ?></textarea>
            </div>
            <div class="final">
                <button type="submit" class="btn">Cadastrar</button>
            </div>
        </form>
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
