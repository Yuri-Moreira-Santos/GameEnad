<?php
require_once("coordenador/func/Php/Functions.php");

$cursos = selectCursos();

?>

<h2>Lista de Cursos</h2>
<?php foreach ($cursos as $curso): ?>
    <div class="form">
        <button class="toggle"><?=($curso[2])?></button>
        <div class="detalhes">
            <div class="formGroup">
                <span class="inputForm">Objetivo do curso:</span>
                <p class="formControl"><?=($curso[3])?></p>
            </div>
            <div class="formGroup">
                <span class="inputForm">Eixo do curso:</span>
                <p class="formControl"><?=($curso[4])?></p>
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
