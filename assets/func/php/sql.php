<?php
function selectCursos() {
    global $conn;
    $sql = "SELECT id, nome, eixo, objetivo FROM cursos ORDER BY nome ASC";
    $result = $conn->query($sql);

    $cursos = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cursos[] = $row;
        }
    }
    return $cursos;
}

function editarCurso($id, $nome, $objetivo, $eixo) {
    global $conn;
    $stmt = $conn->prepare("UPDATE cursos SET nome = ?, objetivo = ?, eixo = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $objetivo, $eixo, $id);

    return $stmt->execute();
}

function excluirCurso($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cursos WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function selectDisciplinas() {
    global $conn;
    $sql = "SELECT * FROM disciplinas";
    $result = $conn->query($sql);

    $disciplinas = [];
    while ($row = $result->fetch_array()) {
        $disciplinas[] = $row;
    }
    return $disciplinas;
}

function editarDisciplina($id, $nome, $ementa, $objetivo) {
    global $conn;
    $stmt = $conn->prepare("UPDATE disciplinas SET nome = ?, ementa = ?, objetivo = ? WHERE id = ?");
    $stmt->bind_param("sssi", $nome, $ementa, $objetivo, $id);

    return $stmt->execute();
}

function excluirDisciplina($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM disciplinas WHERE id = ?");
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

function cadastrarUsuario($nome, $dataNascimento, $email, $senhaHash, $cargoId) {
    global $conn;
    $sql = "INSERT INTO usuarios (nome, data_nascimento, email, senha, cargo_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $dataNascimento, $email, $senhaHash, $cargoId);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function selectExisteUsuario($email) {
    global $conn;
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;
}

function selectProfessores() {
    global $conn;
    $sql = "SELECT id, nome, data_nascimento, email 
            FROM usuarios 
            WHERE cargo_id = 2";
    $result = mysqli_query($conn, $sql);

    $professores = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $professores[] = $row;
    }
    return $professores;
}

function updateProfessor($id, $nome, $dataNascimento, $email) {
    global $conn;

    $sql = "UPDATE usuarios 
            SET nome = ?, data_nascimento = ?, email = ? 
            WHERE id = ? AND cargo_id = 2";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $nome, $dataNascimento, $email, $id);

    return mysqli_stmt_execute($stmt);
}

function excluirProfessor($id) {
    global $conn;

    $sql = "DELETE FROM usuarios WHERE id = ? AND cargo_id = 2";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    return mysqli_stmt_execute($stmt);
}

function getDisciplinasAlocadas($cursoId) {
    global $conn;
    $sql = "SELECT d.id, d.nome 
            FROM alocacoes_disciplinas ad
            JOIN disciplinas d ON ad.disciplina_id = d.id
            WHERE ad.curso_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $cursoId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function alocarDisciplina($cursoId, $disciplinaId) {
    global $conn;

    // Verificar se já existe alocação
    $check = mysqli_prepare($conn, "SELECT * FROM alocacoes_disciplinas WHERE curso_id = ? AND disciplina_id = ?");
    mysqli_stmt_bind_param($check, "ii", $cursoId, $disciplinaId);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($res) > 0) {
        return false; // Já existe
    }

    // Inserir nova alocação
    $sql = "INSERT INTO alocacoes_disciplinas (curso_id, disciplina_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $cursoId, $disciplinaId);
    return mysqli_stmt_execute($stmt);
}

function removerDisciplina($cursoId, $disciplinaId) {
    global $conn;
    $sql = "DELETE FROM alocacoes_disciplinas WHERE curso_id = ? AND disciplina_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $cursoId, $disciplinaId);
    return mysqli_stmt_execute($stmt);
}

?>