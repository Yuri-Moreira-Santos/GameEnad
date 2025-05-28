<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE reset_token = ? AND reset_token_expira > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Token inválido ou expirado.");
}

$stmt->bind_result($userId);
$stmt->fetch();

$erros = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha = $_POST['senha'];
    $confirmar = $_POST['confirmar'];

    validarSenha($senha, $confirmar, $erros);

    if (empty($erros)) {
        $senhaHash = criptografarSenha($senha);

        $update = $conn->prepare("UPDATE usuarios SET senha = ?, reset_token = NULL, reset_token_expira = NULL WHERE id = ?");
        $update->bind_param("si", $senhaHash, $userId);

        if ($update->execute()) {
            echo "<script>alert('Senha alterada com sucesso!'); window.location.href='/login.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar a senha.');</script>";
        }

        $update->close();
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinir senha</title>
    <link rel="stylesheet" href="/assets/style/style.css">
</head>
<body>
    <header>
        <div class="form">
            <h2>Redefinir senha</h2>
            <?php if (!empty($erros)) : ?>
            <div class="erros">
                <?php foreach ($erros as $campo => $msgs) : ?>
                    <?php foreach ($msgs as $msg) : ?>
                        <p style="color: red;"><?= htmlspecialchars($msg) ?></p>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="formGroup">
                    <input class="formControl" type="password" name="senha" placeholder="Nova senha" required>
                </div>
                <div class="formGroup">
                    <input class="formControl" type="password" name="confirmar" placeholder="Confirmar senha" required>
                </div>
                <button type="submit" class="btn">Redefinir</button>
            </form>
        </div>
    </header>
</body>
</html>
