<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/func/php/validarCadastro.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/func/php/senhaHash.php');

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

            $cargoId = 2;

            cadastrarUsuario($nomeUsuario, $dataNascimento, $emailInstitucional, $senhaCriptografada, $cargoId);
            echo "<script>
                alert('Cadastro realizado com sucesso!');
                window.location.href = window.location.pathname;
            </script>";
            exit;

        } else {
            $erros['emailInstitucional'][] = "Usuário já cadastrado com esse e-mail.";
        }
    }
}
?>
<div class="form">
    <form class="loginForm" method="POST">
        <section>
            <h2>Cadastro de Professor</h2>
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