<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar questões do professor
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAlternativa = intval($_POST['idAlternativa'] ?? 0);
    $texto = trim($_POST['texto'] ?? '');
    $correta = isset($_POST['correta']) ? 1 : 0;

    if (isset($_POST['editar'])) {
        if ($idAlternativa > 0 && !empty($texto)) {
            // Verificar se está tentando definir como correta
            if ($correta === 1) {
                $sql = "SELECT COUNT(*) AS total FROM alternativas 
                        WHERE questao_id = (SELECT questao_id FROM alternativas WHERE id = ?) 
                        AND correta = 1 AND id != ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $idAlternativa, $idAlternativa);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();

                if ($row['total'] > 0) {
                    echo "<script>alert('Essa questão já possui uma alternativa correta.');</script>";
                    exit;
                }
            }

            $sql = "UPDATE alternativas SET texto = ?, correta = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $texto, $correta, $idAlternativa);

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
    $sql = "SELECT id, texto, correta FROM alternativas WHERE questao_id = ?";
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
                <option value="<?= htmlspecialchars($questao['id']) ?>" <?= ($questaoFiltro == $questao['id']) ? 'selected' : '' ?>>
                    (<?= htmlspecialchars($questao['disciplina_nome']) ?>) <?= 
                    htmlspecialchars(mb_strimwidth($questao['enunciado_titulo'], 0, 20, '...')) ?> - <?= 
                    htmlspecialchars(mb_strimwidth($questao['questao_texto'], 0, 40, '...')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($questaoFiltro != ''): ?>
        <?php if ($alternativas): ?>
            <table class="tabela">
                <thead>
                    <tr>
                        <th>Texto</th>
                        <th>Correta</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternativas as $alt): ?>
                        <tr>
                            <form method="post">
                                <td>
                                    <input type="hidden" name="idAlternativa" value="<?= $alt['id'] ?>">
                                    <input class="formControl" type="text" name="texto" value="<?= htmlspecialchars($alt['texto']) ?>" required>
                                </td>
                                <td>
                                    <input type="checkbox" name="correta" <?= $alt['correta'] ? 'checked' : '' ?>>
                                </td>
                                <td>
                                    <button type="submit" name="editar" class="btn">Salvar</button>
                                    <button type="submit" name="excluir" class="btn" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma alternativa cadastrada para esta questão.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
