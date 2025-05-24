<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar dados do usuário
$stmt = $conn->prepare("SELECT nome, email, data_nascimento, cargo_id, foto_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($nome, $email, $nascimento, $cargoId, $fotoPerfil);
$stmt->fetch();
$stmt->close();

// Buscar as disciplinas que o professor está alocado
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

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'] ?? '';
    $texto = $_POST['texto'] ?? '';
    $disciplina_id = $_POST['disciplina_id'] ?? '';

    if ($titulo && $texto && $disciplina_id) {
        $insert = $conn->prepare("INSERT INTO enunciados (disciplina_id, titulo, texto) VALUES (?, ?, ?)");
        $insert->bind_param("iss", $disciplina_id, $titulo, $texto);

        if ($insert->execute()) {
            echo "<script>alert('Enunciado cadastrado com sucesso!'); window.location.href = 'home.php?page=cadastrar_enunciado';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar enunciado.');</script>";
        }

        $insert->close();
    } else {
        echo "<script>alert('Preencha todos os campos.');</script>";
    }
}
?>

<div class="form">
    <h2>Cadastro de Enunciado</h2>
    <form method="post">
        <div class="formGroup">
            <h4 for="disciplina_id">Selecione a Disciplina:</h4>
            <select class="select-curso" name="disciplina_id" id="disciplina_id" required>
                <option value="">Selecione</option>
                <?php foreach ($disciplinas as $disciplina): ?>
                    <option value="<?= htmlspecialchars($disciplina['id']) ?>">
                        <?= htmlspecialchars($disciplina['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="formGroup">
            <span class="inputForm" for="titulo">Título do Enunciado:</span>
            <input class="formControl" type="text" id="titulo" name="titulo" maxlength="255" required>
        </div>

        <div class="formGroup">
            <span class="inputForm" for="texto">Texto do Enunciado:</span>
            <textarea class="formControl" id="texto" name="texto" rows="5" required></textarea>
        </div>

        <div class="final">
            <button type="submit" class="btn">Cadastrar</button>
        </div>
    </form>
</div>
