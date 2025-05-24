<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar questões do professor para filtro
$sql = "SELECT q.id, q.texto as questao_texto, e.titulo as enunciado_titulo, d.nome as disciplina_nome
        FROM questoes q
        INNER JOIN enunciados e ON q.enunciado_id = e.id
        INNER JOIN disciplinas d ON e.disciplina_id = d.id
        INNER JOIN alocacoes a ON d.id = a.disciplina_id
        WHERE a.professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$questoes = [];
while ($row = $result->fetch_assoc()) {
    $questoes[] = $row;
}
$stmt->close();

// Pegar enum de nível de dificuldade da tabela alternativas
$sql = "SHOW COLUMNS FROM alternativas LIKE 'nivel_dificuldade'";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAlternativa = intval($_POST['idAlternativa'] ?? 0);
    $texto = trim($_POST['texto'] ?? '');
    $dificuldade_index = intval($_POST['nivel_dificuldade'] ?? -1);
    $correta = isset($_POST['correta']) ? 1 : 0;

    if (isset($_POST['editar'])) {
        if ($idAlternativa > 0 && !empty($texto) && isset($enum[$dificuldade_index])) {
            $dificuldade = $enum[$dificuldade_index];
            $sql = "UPDATE alternativas SET texto = ?, correta = ?, nivel_dificuldade = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sisi", $texto, $correta, $dificuldade, $idAlternativa);

            if ($stmt->execute()) {
                echo "<script>alert('Alternativa editada com sucesso!'); window.location.href='?page=editarAlternativa&questao_id=".$_GET['questao_id']."';</script>";
            } else {
                echo "<script>alert('Erro ao editar alternativa.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Preencha todos os campos corretamente.');</script>";
        }
    }

    if (isset($_POST['excluir'])) {
        $sql = "DELETE FROM alternativas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idAlternativa);
        if ($stmt->execute()) {
            echo "<script>alert('Alternativa excluída com sucesso!'); window.location.href='?page=editarAlternativa&questao_id=".$_GET['questao_id']."';</script>";
        } else {
            echo "<script>alert('Erro ao excluir alternativa.');</script>";
        }
        $stmt->close();
    }
}

$alternativas = [];
$questaoFiltro = $_GET['questao_id'] ?? '';

if ($questaoFiltro != '') {
    $sql = "SELECT id, texto, correta, nivel_dificuldade FROM alternativas WHERE questao_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $questaoFiltro);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $alternativas[] = $row;
    }
    $stmt->close();
}
?>

<div class="form">
    <h2>Editar Alternativas</h2>

    <form method="get" style="margin-bottom: 20px;">
        <input type="hidden" name="page" value="editarAlternativa">
        <span>Filtrar por Questão:</span>
        <select class="select-curso" name="questao_id" onchange="this.form.submit()">
            <option value="">Selecione</option>
            <?php foreach ($questoes as $questao): ?>
                <option value="<?= htmlspecialchars($questao['id']) ?>"
                    <?= ($questaoFiltro == $questao['id']) ? 'selected' : '' ?>>
                    (<?= htmlspecialchars($questao['disciplina_nome']) ?>) <?= 
                    htmlspecialchars(mb_strimwidth($questao['enunciado_titulo'], 0, 10, '...')) ?> - <?= 
                    htmlspecialchars(mb_strimwidth($questao['questao_texto'], 0, 10, '...')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($questaoFiltro != ''): ?>
        <?php if ($alternativas): ?>
            <?php foreach ($alternativas as $alt): ?>
                <form method="post" class="formAlternativa" style="border: 1px solid #ccc; margin-bottom: 15px; padding: 10px;">
                    <input type="hidden" name="idAlternativa" value="<?= $alt['id'] ?>">
                    
                    <div class="formGroup">
                        <span class="inputForm" for="texto_<?= $alt['id'] ?>">Texto da Alternativa:</span>
                        <input class="formControl" type="text" id="texto_<?= $alt['id'] ?>" name="texto" value="<?= htmlspecialchars($alt['texto']) ?>" maxlength="500" required>
                    </div>

                    <div class="formGroup">
                        <span style="height: 100%;" class="inputForm" for="nivel_dificuldade_<?= $alt['id'] ?>">Nível de dificuldade:</span>
                        <select class="select-curso" name="nivel_dificuldade" id="nivel_dificuldade_<?= $alt['id'] ?>" required>
                            <?php foreach ($enum as $index => $label): ?>
                                <option value="<?= $index ?>" <?= ($alt['nivel_dificuldade'] == $label) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="final">
                        <label>
                            <input type="checkbox" name="correta" value="1" <?= ($alt['correta'] ? 'checked' : '') ?>> Correta
                        </label>
                    </div>

                    <div class="final" style="justify-content: space-between;">
                        <button type="submit" name="editar" class="btn">Salvar</button>
                        <button type="submit" name="excluir" onclick="return confirm('Tem certeza que deseja excluir esta alternativa?');" class="btn btn-danger">Excluir</button>
                    </div>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma alternativa cadastrada para esta questão.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
