<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
$professores = selectProfessores();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idProfessor'];
    $nome = $_POST['nomeProfessor'];
    $dataNascimento = $_POST['dataNascimentoProfessor'];
    $email = $_POST['emailProfessor'];

    if (isset($_POST['editar'])) {
        if (updateProfessor($id, $nome, $dataNascimento, $email)) {
            echo "<script>alert('Professor editado com sucesso!'); window.location.href='?page=editarProfessor';</script>";
        } else {
            echo "<script>alert('Erro ao editar o professor.');</script>";
        }
    }

    if (isset($_POST['excluir'])) {
        if (excluirProfessor($id)) {
            echo "<script>alert('Professor excluído com sucesso!'); window.location.href='?page=editarProfessor';</script>";
        } else {
            echo "<script>alert('Erro ao excluir o professor.');</script>";
        }
    }
}
?>

<h2>Lista de professores editáveis</h2>

<?php foreach ($professores as $professor): ?>
<div class="form">
    <button class="toggle"><?= htmlspecialchars($professor['nome']) ?></button>
    <div class="detalhes">
        <form method="post" onsubmit="return verificarAcao(this);">
            <input type="hidden" name="idProfessor" value="<?= $professor['id'] ?>">
            <div class="formGroup">
                <span class="inputForm">Nome do Professor:</span>
                <input class="formControl" type="text" name="nomeProfessor" maxlength="100" value="<?= htmlspecialchars($professor['nome']) ?>" required>
            </div>
            <div class="formGroup">
                <span class="inputForm">Data de Nascimento:</span>
                <input class="formControl" type="date" name="dataNascimentoProfessor" value="<?= htmlspecialchars($professor['data_nascimento']) ?>" required>
            </div>
            <div class="formGroup">
                <span class="inputForm">E-mail:</span>
                <input class="formControl" type="email" name="emailProfessor" maxlength="100" value="<?= htmlspecialchars($professor['email']) ?>" required>
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

    botoes.forEach((botao, index) => {
        botao.addEventListener('click', () => {
            const card = botao.parentElement;
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
            return confirm('Tem certeza que deseja excluir este professor?');
        }
        return true;
    }
</script>
