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

// Buscar nome do cargo
$cargo = '';
$resCargo = $conn->query("SELECT nome FROM cargo WHERE id = $cargoId");
if ($resCargo && $row = $resCargo->fetch_assoc()) {
    $cargo = $row['nome'];
}

// Atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['nome'];

    // Upload da foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = "perfil_" . $userId . "." . $ext;
        $caminho = "/uploads/" . $nomeArquivo;
        move_uploaded_file($_FILES['foto']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $caminho);
    } else {
        $caminho = $fotoPerfil; // Mantém a atual
    }

    // Atualizar no banco
    $stmtUp = $conn->prepare("UPDATE usuarios SET nome = ?, foto_perfil = ? WHERE id = ?");
    $stmtUp->bind_param("ssi", $novoNome, $caminho, $userId);
    $stmtUp->execute();
    $stmtUp->close();

    echo "<script>alert('Dados atualizados com sucesso!'); window.location.href='home.php';</script>";
    exit;
}
?>
<div class="form">
    <h1>Meu Perfil</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="profile-content">
            <div class="profile-photo-section">
                <img src="<?= $fotoPerfil ?: '/assets/imgs/profile.png' ?>" alt="Foto de perfil">
                <label class="label-upload">Alterar Foto:</label>
                <input type="file" name="foto" accept="image/*">
            </div>

            <div class="profile-info-section">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <p style="background: #a9a9a9;"><?= htmlspecialchars($email) ?></p>
                </div>

                <div class="form-group">
                    <label>Data de Nascimento:</label>
                    <p style="background: #a9a9a9;"><?= htmlspecialchars(date('d/m/Y', strtotime($nascimento))) ?></p>
                </div>

                <div class="form-group">
                    <label>Cargo:</label>
                    <p style="background: #a9a9a9;"><?= htmlspecialchars($cargo) ?></p>
                </div>
        <button class="btn" type="submit">Salvar Alterações</button>
    </form>
</div>
