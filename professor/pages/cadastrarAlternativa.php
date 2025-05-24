<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 2) {
    header('Location: /login.php');
    exit;
}

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar questões que pertencem às disciplinas do professor
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
    $questao_id = intval($_POST['questao_id'] ?? 0);
    $texto = trim($_POST['texto'] ?? '');
    $dificuldade_index = intval($_POST['nivel_dificuldade'] ?? -1);
    $correta = isset($_POST['correta']) ? 1 : 0;

    // Validações
    if ($questao_id === 0 || empty($texto) || !isset($enum[$dificuldade_index])) {
        echo "<script>alert('Preencha todos os campos corretamente.');</script>";
    } else {
        $dificuldade = $enum[$dificuldade_index];

        $sql = "INSERT INTO alternativas (questao_id, texto, correta, nivel_dificuldade) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isis", $questao_id, $texto, $correta, $dificuldade);

        if ($stmt->execute()) {
            echo "<script>alert('Alternativa cadastrada com sucesso!'); window.location.href='?page=cadastrarAlternativa';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar alternativa.');</script>";
        }
        $stmt->close();
    }
}
?>

<div class="form">
    <h2>Cadastro de Alternativas</h2>
    <form method="post">
        <div class="formGroup">
            <h4 for="questao_id">Selecione a Questão:</h4>
            <select class="select-curso" name="questao_id" id="questao_id" required>
                <option value="">Selecione</option>
                <?php foreach ($questoes as $questao): ?>
                    <option value="<?= htmlspecialchars($questao['id']) ?>">
                        (<?= htmlspecialchars($questao['disciplina_nome']) ?>) <?= 
                        htmlspecialchars(mb_strimwidth($questao['enunciado_titulo'], 0, 10, '...')) ?> - <?= 
                        htmlspecialchars(mb_strimwidth($questao['questao_texto'], 0, 10, '...')) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formGroup">
            <span class="inputForm" for="texto">Texto da Alternativa:</span>
            <input class="formControl" type="text" id="texto" name="texto" maxlength="500" required>
        </div>

        <div class="formGroup">
            <span style="height: 100%;" class="inputForm" for="nivel_dificuldade">Nível de dificuldade:</span>
            <select class="select-curso" name="nivel_dificuldade" id="nivel_dificuldade" required>
                <option>Selecione</option>
                <?php foreach ($enum as $index => $label): ?>
                    <option value="<?= $index ?>"><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formGroup">
            <label>
                <input type="checkbox" name="correta" value="1"> Correta
            </label>
        </div>

        <div class="final">
            <button type="submit" class="btn">Cadastrar</button>
        </div>
    </form>
</div>
