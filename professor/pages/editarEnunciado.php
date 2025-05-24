<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar disciplinas do professor
$sql = "SELECT d.id, d.nome 
        FROM disciplinas d
        INNER JOIN alocacoes a ON d.id = a.disciplina_id
        WHERE a.professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$disciplinas = [];
while ($row = $result->fetch_assoc()) {
    $disciplinas[] = $row;
}
$stmt->close();

// Processamento de editar ou excluir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idEnunciado = intval($_POST['idEnunciado']);
    $titulo = trim($_POST['titulo']);
    $texto = trim($_POST['texto']);

    if (isset($_POST['editar'])) {
        $sql = "UPDATE enunciados SET titulo = ?, texto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $texto, $idEnunciado);
        if ($stmt->execute()) {
            echo "<script>alert('Enunciado editado com sucesso!'); window.location.href='?page=editarEnunciado&disciplina_id=".$_GET['disciplina_id']."';</script>";
        } else {
            echo "<script>alert('Erro ao editar enunciado.');</script>";
        }
        $stmt->close();
    }

    if (isset($_POST['excluir'])) {
        $sql = "DELETE FROM enunciados WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idEnunciado);
        if ($stmt->execute()) {
            echo "<script>alert('Enunciado excluído com sucesso!'); window.location.href='?page=editarEnunciado&disciplina_id=".$_GET['disciplina_id']."';</script>";
        } else {
            echo "<script>alert('Erro ao excluir. Verifique se não há questões vinculadas.');</script>";
        }
        $stmt->close();
    }
}

// Buscar enunciados
$enunciados = [];
if (isset($_GET['disciplina_id']) && $_GET['disciplina_id'] != '') {
    $disciplina_id = intval($_GET['disciplina_id']);

    $sql = "SELECT id, titulo, texto FROM enunciados WHERE disciplina_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $disciplina_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $enunciados[] = $row;
    }
    $stmt->close();
}
?>

<div class="form">
    <h2>Editar Enunciados</h2>

    <form method="get">
        <input type="hidden" name="page" value="editarEnunciado">
        <div>
            <span for="disciplina_id">Selecione a Disciplina:</span>
            <select class="select-curso" name="disciplina_id" id="disciplina_id" onchange="this.form.submit()" required>
                <option value="">Selecione</option>
                <?php foreach ($disciplinas as $disciplina): ?>
                    <option value="<?= htmlspecialchars($disciplina['id']) ?>"
                        <?= (isset($_GET['disciplina_id']) && $_GET['disciplina_id'] == $disciplina['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($disciplina['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($enunciados): ?>
        <div class="enunciados">
            <?php foreach ($enunciados as $index => $enunciado): ?>
                <form method="post" class="card enunciado-card <?= $index === 0 ? 'mostrar' : '' ?>" onsubmit="return confirmarAcao(this);">
                    <input type="hidden" name="idEnunciado" value="<?= $enunciado['id'] ?>">

                    <div class="formGroup">
                        <span class="inputForm">Título do Enunciado:</span>
                        <input type="text" name="titulo" class="formControl" maxlength="255"
                               value="<?= htmlspecialchars($enunciado['titulo']) ?>" required>
                    </div>

                    <div class="formGroup">
                        <span class="inputForm">Texto do Enunciado:</span>
                        <textarea name="texto" class="formControl" rows="7" cols="50" required><?= htmlspecialchars($enunciado['texto']) ?></textarea>
                    </div>

                    <div class="final" style="justify-content: space-between;">
                        <button type="submit" name="editar" class="btn editar-btn">Salvar Alterações</button>

                        <div class="bolinhas"></div>

                        <button type="submit" name="excluir" class="btn excluir-btn">Excluir</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_GET['disciplina_id'])): ?>
        <p>Nenhum enunciado cadastrado para essa disciplina.</p>
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key.toLowerCase() === 'd') {
            avancar();
        }
        if (e.key === 'ArrowLeft' || e.key.toLowerCase() === 'a') {
            voltar();
        }
    });

    atualizarTela();
</script>
