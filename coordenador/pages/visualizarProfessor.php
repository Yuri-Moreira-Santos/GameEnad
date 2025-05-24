<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$professores = selectProfessores();

?>

<h2>Lista de professores</h2>
<?php foreach ($professores as $professor): ?>
    <div class="form">
        <button class="toggle">
            <?= htmlspecialchars($professor['nome']) ?>
        </button>
        <div class="detalhes">
            <div class="formGroup">
                <span class="inputForm">Data de nascimento:</span>
                <p class="formControl"><?= htmlspecialchars(date('d/m/Y', strtotime($professor['data_nascimento']))) ?></p>
            </div>
            <div class="formGroup">
                <span class="inputForm">E-mail:</span>
                <p class="formControl"><?= htmlspecialchars($professor['email']) ?></p>
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
