<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
$cursos = selectCursos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCurso = $_POST['idCurso'];
    $nome = $_POST['nomeCurso'];
    $objetivo = $_POST['objetivoCurso'];
    $eixo = $_POST['eixoCurso'];

    if (isset($_POST['editar'])) {
        if (editarCurso($idCurso, $nome, $objetivo, $eixo)) {
            echo "<script>alert('Curso editado com sucesso!'); window.location.href='?page=editarCurso';</script>";
        } else {
            echo "<script>alert('Erro ao editar o curso.');</script>";
        }
    }

    if (isset($_POST['excluir'])) {
        if (excluirCurso($idCurso)) {
            echo "<script>alert('Curso excluído com sucesso!'); window.location.href='?page=editarCurso';</script>";
        } else {
            echo "<script>alert('Erro ao excluir o curso.');</script>";
        }
    }
}
?>

<h2>Lista de cursos editáveis</h2>

<?php foreach ($cursos as $curso): ?>
<div class="form">
    <button class="toggle"><?= htmlspecialchars($curso['nome']) ?></button>
    <div class="detalhes">
        <form method="post" onsubmit="return verificarAcao(this);">
            <input type="hidden" name="idCurso" value="<?= $curso['id'] ?>">
            <div class="formGroup">
                <span class="inputForm" for="nomeCurso">Nome do curso:</span>
                <input class="formControl" type="text" name="nomeCurso" maxlength="100" value="<?= htmlspecialchars($curso['nome']) ?>" required>
            </div>
            <div class="formGroup">
                <span class="inputForm">Objetivo do curso:</span>
                <input class="formControl" type="text" name="objetivoCurso" maxlength="100" value="<?= htmlspecialchars($curso['objetivo']) ?>" required>
            </div>
            <div class="formGroup">
                <span class="inputForm" for="eixoCurso">Eixo do curso:</span>
                <textarea class="formControl" name="eixoCurso" rows="4" required><?= htmlspecialchars($curso['eixo']) ?></textarea>
            </div>
            <div class="final" style="justify-content: space-between;">
                <button type="submit" name="editar" class="btn editar-btn">Editar</button>
                <button type="submit" name="excluir" class="btn excluir-btn">Excluir</button>
            </div>
        </form>
        <form method="post" onsubmit="return confirm('Tem certeza que deseja excluir este curso?');">
            <input type="hidden" name="idCurso" value="<?= $curso['id'] ?>">
            <input type="hidden" name="acao" value="excluir">
        </form>
    </div>
</div>
<?php endforeach; ?>

<script>
    const botoes = document.querySelectorAll('.toggle');
    const cards = document.querySelectorAll('.form');

    botoes.forEach((botao, index) => {
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
            return confirm('Tem certeza que deseja excluir este curso?');
        }
        return true;
    }
</script>
