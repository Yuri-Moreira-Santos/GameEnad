<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
$disciplinas = selectDisciplinas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDisciplina = $_POST['idDisciplina'];
    $nome = trim($_POST['nomeDisciplina']);
    $ementa = trim($_POST['ementaDisciplina']);
    $objetivos = trim($_POST['objetivosDisciplina']);

    if (isset($_POST['editar'])) {
        if (editarDisciplina($idDisciplina, $nome, $ementa, $objetivos)) {
            echo "<script>alert('Disciplina editada com sucesso!'); window.location.href='?page=editarDisciplina';</script>";
        } else {
            echo "<script>alert('Erro ao editar a disciplina.');</script>";
        }
    }

    if (isset($_POST['excluir'])) {
        if (excluirDisciplina($idDisciplina)) {
            echo "<script>alert('Disciplina excluída com sucesso!'); window.location.href='?page=editarDisciplina';</script>";
        } else {
            echo "<script>alert('Erro ao excluir a disciplina.');</script>";
        }
    }
}
?>

<h2>Lista de disciplinas editáveis</h2>

<?php foreach ($disciplinas as $disciplina): ?>
    <div class="form">
        <button class="toggle"><?= htmlspecialchars($disciplina['nome']) ?></button>
        <div class="detalhes">
            <form method="post" onsubmit="return verificarAcao(this);">
                <input type="hidden" name="idDisciplina" value="<?= $disciplina['id'] ?>">

                <div class="formGroup">
                    <span class="inputForm">Nome da disciplina:</span>
                    <input class="formControl" type="text" name="nomeDisciplina" maxlength="100" value="<?= htmlspecialchars($disciplina['nome']) ?>" required>
                </div>

                <div class="formGroup">
                    <span class="inputForm">Ementa:</span>
                    <textarea class="formControl" name="ementaDisciplina" rows="4" required><?= htmlspecialchars($disciplina['ementa']) ?></textarea>
                </div>

                <div class="formGroup">
                    <span class="inputForm">Objetivos de aprendizagem:</span>
                    <textarea class="formControl" name="objetivo" rows="4" required><?= htmlspecialchars($disciplina['objetivo']) ?></textarea>
                </div>

                <div class="final" style="justify-content: space-between;">
                    <button type="submit" name="editar" class="btn editar-btn">Editar</button>
                    <button type="submit" name="excluir" class="btn excluir-btn">Excluir</button>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<script>
    const botoes = document.querySelectorAll('.toggle');
    const cards = document.querySelectorAll('.form');

    botoes.forEach(botao => {
        botao.addEventListener('click', () => {
            const card = botao.parentElement;
            const detalhes = card.querySelector('.detalhes');

            card.classList.toggle('active');

            const cardsAbertos = Array.from(cards).filter(c => c.classList.contains('active'));

            cards.forEach(c => {
                const btn = c.querySelector('.editar-btn');
                if (btn) btn.style.display = 'none';
            });

            if (cardsAbertos.length > 0) {
                const primeiroAberto = cardsAbertos[0];
                const btn = primeiroAberto.querySelector('.editar-btn');
                if (btn) btn.style.display = 'block';
            }
        });
    });

    function verificarAcao(form) {
        const excluirClicado = form.querySelector('button[name="excluir"]:focus');
        if (excluirClicado) {
            return confirm('Tem certeza que deseja excluir esta disciplina?');
        }
        return true;
    }
</script>
