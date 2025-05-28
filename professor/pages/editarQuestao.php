<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 2) {
    header('Location: /login.php');
    exit;
}

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar os enunciados que pertencem às disciplinas do professor
$sql = "SELECT e.id, e.titulo, d.nome as disciplina_nome
        FROM enunciados e
        INNER JOIN disciplinas d ON e.disciplina_id = d.id
        INNER JOIN alocacoes a ON d.id = a.disciplina_id
        WHERE a.professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$enunciados = [];
while ($row = $result->fetch_assoc()) {
    $enunciados[] = $row;
}
$stmt->close();

// Carregar enum de dificuldade
$sql = "SHOW COLUMNS FROM questoes LIKE 'nivel_dificuldade'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$enumStr = $row['Type'];
preg_match("/^enum\((.*)\)$/", $enumStr, $matches);
$enum = [];
if (isset($matches[1])) {
    foreach (explode(',', $matches[1]) as $value) {
        $v = trim($value, " '");
        $enum[] = $v;
    }
}

// Processar editar ou excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idQuestao = intval($_POST['idQuestao']);
    $texto = trim($_POST['texto']);
    $dificuldade_index = intval($_POST['nivel_dificuldade']);

    if (isset($enum[$dificuldade_index])) {
        $dificuldade = $enum[$dificuldade_index];

        if (isset($_POST['editar'])) {
            $sql = "UPDATE questoes SET texto = ?, nivel_dificuldade = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $texto, $dificuldade, $idQuestao);
            if ($stmt->execute()) {
                echo "<script>alert('Questão editada com sucesso!'); window.location.href='?page=editarQuestoes&enunciado_id=".$_GET['enunciado_id']."';</script>";
            } else {
                echo "<script>alert('Erro ao editar questão.');</script>";
            }
            $stmt->close();
        }

        if (isset($_POST['excluir'])) {
            $sql = "DELETE FROM questoes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idQuestao);
            if ($stmt->execute()) {
                echo "<script>alert('Questão excluída com sucesso!'); window.location.href='?page=editarQuestoes&enunciado_id=".$_GET['enunciado_id']."';</script>";
            } else {
                echo "<script>alert('Erro ao excluir. Verifique se não há alternativas vinculadas.');</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Nível de dificuldade inválido.');</script>";
    }
}

// Buscar questões
$questoes = [];
if (isset($_GET['enunciado_id']) && $_GET['enunciado_id'] != '') {
    $enunciado_id = intval($_GET['enunciado_id']);

    $sql = "SELECT id, texto, nivel_dificuldade FROM questoes WHERE enunciado_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $enunciado_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $questoes[] = $row;
    }
    $stmt->close();
}
?>

<div class="form">
    <h2>Editar Questões</h2>

    <form method="get">
        <input type="hidden" name="page" value="editarQuestao">
        <div>
            <span for="enunciado_id">Selecione o Enunciado:</span>
            <select class="select-curso" name="enunciado_id" id="enunciado_id" onchange="this.form.submit()" required>
                <option value="">Selecione</option>
                <?php foreach ($enunciados as $enunciado): ?>
                    <option value="<?= htmlspecialchars($enunciado['id']) ?>"
                        <?= (isset($_GET['enunciado_id']) && $_GET['enunciado_id'] == $enunciado['id']) ? 'selected' : '' ?>>
                        (<?= htmlspecialchars($enunciado['disciplina_nome']) ?>) <?= htmlspecialchars($enunciado['titulo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($questoes): ?>
        <div class="enunciados">
            <?php foreach ($questoes as $index => $questao): ?>
                <form method="post" class="card enunciado-card <?= $index === 0 ? 'mostrar' : '' ?>" onsubmit="return confirmarAcao(this);">
                    <input type="hidden" name="idQuestao" value="<?= $questao['id'] ?>">

                    <div class="formGroup">
                        <span class="inputForm">Texto da Questão:</span>
                        <textarea name="texto" class="formControl" rows="4" required><?= htmlspecialchars($questao['texto']) ?></textarea>
                    </div>

                    <div class="formGroup">
                        <span style="height: 100%;" class="inputForm">Nível de Dificuldade:</span>
                        <select name="nivel_dificuldade" class="select-curso" required>
                            <?php foreach ($enum as $idx => $label): ?>
                                <option value="<?= $idx ?>" <?= ($label == $questao['nivel_dificuldade']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="final" style="justify-content: space-between;">
                        <button type="submit" name="editar" class="btn editar-btn">Salvar Alterações</button>

                        <div class="bolinhas"></div>

                        <button type="submit" name="excluir" class="btn excluir-btn">Excluir</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_GET['enunciado_id'])): ?>
        <p>Nenhuma questão cadastrada para esse enunciado.</p>
    <?php endif; ?>
</div>

<script>
    const enunciados = document.querySelectorAll('.enunciado-card');
    const maxBolinhas = 5;
    let atual = 0;

    const criarBolinhas = () => {
        enunciados.forEach((_, idx) => {
            const bolinhasDiv = enunciados[idx].querySelector('.bolinhas');
            bolinhasDiv.innerHTML = '';

            let inicio = Math.floor(atual / maxBolinhas) * maxBolinhas;
            let fim = Math.min(inicio + maxBolinhas, enunciados.length);

            for (let i = inicio; i < fim; i++) {
                const bolinha = document.createElement('div');
                bolinha.className = 'bolinha' + (i === atual ? ' ativa' : '');
                bolinha.addEventListener('click', () => {
                    atual = i;
                    atualizarTela();
                });
                bolinhasDiv.appendChild(bolinha);
            }
        });
    };

    const atualizarTela = () => {
        enunciados.forEach((card, idx) => {
            card.classList.toggle('mostrar', idx === atual);
        });
        criarBolinhas();
    };

    const avancar = () => {
        atual = (atual + 1) % enunciados.length;
        atualizarTela();
    };

    const voltar = () => {
        atual = (atual - 1 + enunciados.length) % enunciados.length;
        atualizarTela();
    };

    

    atualizarTela();
</script>
