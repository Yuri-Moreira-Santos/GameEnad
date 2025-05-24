<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

// Verifica se é coordenador
if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: /login.php');
    exit;
}

// Recebe dados do formulário
$nome = trim($_POST['nomeDisciplina']);
$ementa = trim($_POST['ementaDisciplina']);
$objetivo = trim($_POST['objetivo']);

// Verifica se todos os campos estão preenchidos
if (empty($nome) || empty($ementa) || empty($objetivo)) {
    $_SESSION['erro'] = "Preencha todos os campos.";
    header('Location: /home.php?page=cadastrarDisciplina');
    exit;
}

// Insere no banco
$sql = "INSERT INTO disciplinas (nome, ementa, objetivo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $ementa, $objetivo);

if ($stmt->execute()) {
    $_SESSION['certo'] = "Disciplina cadastrada com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao cadastrar disciplina. Tente novamente.";
}

$stmt->close();
$conn->close();

header('Location: /home.php?page=cadastrarDisciplina');
exit;
?>
