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
    <h2>Visualizar Alternativas</h2>

    <form method="get" style="margin-bottom: 20px;">
        <input type="hidden" name="page" value="visualizarAlternativa">
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
            <table class="tabela">
                <thead>
                    <tr>
                        <th>Texto</th>
                        <th>Correta</th>
                        <th>Nível de dificuldade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alternativas as $alt): ?>
                        <tr>
                            <td><?= htmlspecialchars($alt['texto']) ?></td>
                            <td><?= $alt['correta'] ? 'Sim' : 'Não' ?></td>
                            <td><?= htmlspecialchars($alt['nivel_dificuldade']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma alternativa cadastrada para esta questão.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
