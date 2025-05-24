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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enunciado_id = intval($_POST['enunciado_id'] ?? 0);
    $texto = trim($_POST['texto'] ?? '');
    $dificuldade_index = intval($_POST['nivel_dificuldade'] ?? -1);

    // Validações
    if ($enunciado_id === 0 || empty($texto) || !isset($enum[$dificuldade_index])) {
        echo "<script>alert('Preencha todos os campos corretamente.');</script>";
    } else {
        $dificuldade = $enum[$dificuldade_index];

        $sql = "INSERT INTO questoes (enunciado_id, texto, nivel_dificuldade) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $enunciado_id, $texto, $dificuldade);

        if ($stmt->execute()) {
            echo "<script>alert('Questão cadastrada com sucesso!'); window.location.href='?page=cadastroQuestoes';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar questão.');</script>";
        }
        $stmt->close();
    }
}
?>

<div class="form">
    <h2>Cadastro de Questões</h2>
    <form method="post">
        <div class="formGroup">
            <h4 for="enunciado_id">Selecione o Enunciado:</h4>
            <select class="select-curso" name="enunciado_id" id="enunciado_id" required>
                <option value="">Selecione</option>
                <?php foreach ($enunciados as $enunciado): ?>
                    <option value="<?= htmlspecialchars($enunciado['id']) ?>">
                        (<?= htmlspecialchars($enunciado['disciplina_nome']) ?>) <?= htmlspecialchars($enunciado['titulo']) ?> 
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formGroup">
            <span class="inputForm" for="texto">Pergunta:</span>
            <input class="formControl" type="text" id="texto" name="texto" maxlength="255" required>
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


        <div class="final">
            <button type="submit" class="btn">Cadastrar</button>
        </div>
    </form>
</div>
