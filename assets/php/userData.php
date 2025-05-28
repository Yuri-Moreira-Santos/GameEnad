<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$userId = $_SESSION['usuario']['id'] ?? null;

if ($userId) {
    $stmt = $conn->prepare("SELECT nome, email, data_nascimento, cargo_id, foto_perfil FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($nomeExibido, $email, $nascimento, $cargoId, $fotoPerfil);
    $stmt->fetch();
    $stmt->close();

    // Buscar nome do cargo
    $resCargo = $conn->query("SELECT nome FROM cargo WHERE id = $cargoId");
    $cargo = ($resCargo && $row = $resCargo->fetch_assoc()) ? $row['nome'] : 'Sem Cargo';

    // Foto padrão se não tiver
    $fotoPerfil = $fotoPerfil ?: '/assets/imgs/profile.png';
} else {
    // Caso não esteja logado
    $nomeExibido = 'Usuário';
    $cargo = '';
    $fotoPerfil = '/assets/imgs/profile.png';
}
?>
