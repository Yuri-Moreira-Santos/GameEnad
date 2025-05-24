<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/func/php/validarCadastro.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/func/php/senhaHash.php');

$tipoCadastro = $_GET['tipo'] ?? 'aluno';

if ($tipoCadastro === 'coordenador' && ($_SESSION['cargo_id'] ?? null) !== 3) {
    header('Location: login.php');
    exit;
}

$nomeUsuario = filter_input(INPUT_POST, 'nomeUsuario', FILTER_SANITIZE_SPECIAL_CHARS);
$emailInstitucional = filter_input(INPUT_POST, 'emailInstitucional', FILTER_SANITIZE_EMAIL);
$confirmarEmail = filter_input(INPUT_POST, 'confirmarEmail', FILTER_SANITIZE_EMAIL);
$dataNascimento = filter_input(INPUT_POST, 'dataNascimento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$senha = $_POST['senha'] ?? '';
$confirmarSenha = $_POST['confirmarSenha'] ?? '';
$validarTermos = isset($_POST['termos']) && $_POST['termos'] === 'on';

$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = validarCadastroUsuario(
        $nomeUsuario,
        $emailInstitucional,
        $confirmarEmail,
        $dataNascimento,
        $senha,
        $confirmarSenha,
        $validarTermos
    );

    if (empty($erros)) {
        $usuarioExiste = selectExisteUsuario($emailInstitucional);
        if (!$usuarioExiste) {
            $senhaCriptografada = criptografarSenha($senha);

            // Definindo cargo: 3 = Coordenador, 2 = Professor, 1 = Aluno
            $cargoId = ($tipoCadastro === 'professor') ? 2 : 1;

            cadastrarUsuario($nomeUsuario, $dataNascimento, $emailInstitucional, $senhaCriptografada, $cargoId);

            header('Location: login.php');
            exit;
        } else {
            $erros['emailInstitucional'][] = "Usuário já cadastrado com esse e-mail.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Cadastro Coordenador</title>
</head>
<body>
    <header>
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . '/assets/php/navBar.php')
        ?>
        <div class="form">
            <form class="loginForm" method="POST">
                <section>
                    <h2>Cadastro de <?= ($tipoCadastro === 'professor') ? 'Professor' : 'Aluno' ?></h2>
                    <div class="formGroup">
                        <span class="inputForm fa fa-user"></span>
                        <input class="formControl" type="text" name="nomeUsuario" value="<?= htmlspecialchars($nomeUsuario ?? '') ?>" required placeholder="Nome completo">
                    </div>
                    <?php if (!empty($erros['nome'])): ?>
                        <div class="erro"><?= $erros['nome'] ?></div>
                    <?php endif; ?>
                    <div class="formGroup">
                        <span class="inputForm fa fa-envelope"></span>
                        <input class="formControl" type="email" name="emailInstitucional" value="<?= htmlspecialchars($emailInstitucional ?? '') ?>" required placeholder="E-mail">                    
                    </div>
                    <?php if (!empty($erros['emailInstitucional'])): ?>
                        <?php foreach ($erros['emailInstitucional'] as $erro): ?>
                            <div class="erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="formGroup">
                        <span class="inputForm fa fa-envelope"></span>
                        <input class="formControl" type="email" name="confirmarEmail" value="<?= htmlspecialchars($confirmarEmail ?? '') ?>" required placeholder="Confirmar e-mail">
                    </div>
                    <?php if (!empty($erros['confirmarEmail'])): ?>
                        <?php foreach ($erros['confirmarEmail'] as $erro): ?>
                            <div class="erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="formGroup">
                        <span class="inputForm fa fa-calendar"></span>
                        <input class="formControl" type="date" name="dataNascimento" value="<?= htmlspecialchars($dataNascimento ?? '') ?>" required>
                    </div>
                    <?php if (!empty($erros['dataNascimento'])): ?>
                        <div class="erro"><?= $erros['dataNascimento'] ?></div>
                    <?php endif; ?>
                    <div class="formGroup">
                        <span class="inputForm fa fa-lock"></span>
                        <input class="formControl" type="password" name="senha" required placeholder="Senha">
                    </div>
                    <?php if (!empty($erros['senha'])): ?>
                        <?php foreach ($erros['senha'] as $erro): ?>
                            <div class="erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="formGroup">
                        <span class="inputForm fa fa-lock"></span>
                        <input class="formControl" type="password" name="confirmarSenha" required placeholder="Confirmar senha">
                    </div>
                    <?php if (!empty($erros['confirmarSenha'])): ?>
                        <?php foreach ($erros['confirmarSenha'] as $erro): ?>
                            <div class="erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="formGroup">
                        <input type="checkbox" name="termos" <?= isset($validarTermos) && $validarTermos ? 'checked' : '' ?>>
                        <span for="termos">Concordo com os
                            <a href="#" class="link">termos de uso e privacidade</a>
                        </span>
                    </div>
                    <?php if (!empty($erros['termos'])): ?>
                        <div class="erro"><?= $erros['termos'] ?></div>
                    <?php endif; ?>
                    <div class="final">
                        <button type="submit" class="btn">Cadastrar</button>
                    </div>
                </section>
                <div class="register">
                    <span>Já possui conta?</span>
                    <a class="link" href="login.php">Entrar</a>
                </div>
            </form>
        </div>
    </header>
</body>
</html>