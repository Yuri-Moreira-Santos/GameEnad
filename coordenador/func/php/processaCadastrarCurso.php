<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

// Verifica se é coordenador
if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: /login.php');
    exit;
}

// Recebe dados do formulário
$nome = trim($_POST['nomeCurso']);
$objetivo = trim($_POST['objetivoCurso']);
$eixo = trim($_POST['eixoCurso']);

// Verifica se todos os campos estão preenchidos
if (empty($nome) || empty($objetivo) || empty($eixo)) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header('Location: /home.php?page=cadastrarCurso');
    exit;
}

// Insere no banco
$sql = "INSERT INTO cursos (nome, eixo, objetivo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $eixo, $objetivo);

if ($stmt->execute()) {
    $_SESSION['certo'] = "Curso cadastrado com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao cadastrar curso. Tente novamente.";
}

$stmt->close();
$conn->close();

header('Location: /home.php?page=cadastrarCurso');
exit;
?>
