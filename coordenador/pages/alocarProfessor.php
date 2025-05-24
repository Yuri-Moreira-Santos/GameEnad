<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$professores = selectProfessores(); // professor[0] = id, [2] = nome, [4] = email
$disciplinas = selectDisciplinas(); // disciplina[0] = id, [1] = nome
?>

<h2>Alocar Disciplina para Professor</h2>

<?php foreach ($professores as $professor): ?>
    <div class="form">
        <button class="toggle"><?= htmlspecialchars($professor[2]) ?> (<?= htmlspecialchars($professor[4]) ?>)</button>
        <div class="detalhes">
            <form action="coordenador/func/Php/AlocarDisciplina.php" method="post">
                <div class="formGroup">
                    <label class="inputForm">Selecione uma disciplina:</label>
                    <select class="formControl" name="disciplina" required>
                        <option value="" disabled selected>Selecione</option>
                        <?php foreach ($disciplinas as $disciplina): ?>
                            <option value="<?= $disciplina[0] ?>"><?= htmlspecialchars($disciplina[1]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="professor" value="<?= $professor[0] ?>">
                <button type="submit" class="btn">Alocar</button>
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
