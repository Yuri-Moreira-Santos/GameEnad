<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Coordenador</title>
</head>
<body>
    <header>
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . '/assets/php/navBar.php')
        ?>
        <form class="center form loginForm" method="POST" action="/assets/func/php/validarLogin.php">
            <section>
                <h2>Login</h2>
                <div class="formGroup">
                    <span class="inputForm fa fa-user"></span>
                    <input class="formControl" type="email" name="emailInstitucional" required placeholder="E-mail">
                </div>
                <div class="formGroup">
                    <span class="inputForm fa fa-lock"></span>
                    <input class="formControl" type="password" name="senha" required placeholder="Senha">
                </div>
                <?php
                    if (isset($_SESSION['erro'])) {
                        echo "<div class='erro'>" . $_SESSION['erro'] . "</div>";
                        unset($_SESSION['erro']);
                    }
                ?>
                <div class="forgotPass">
                    <a class="link" href="/assets/func/php/forgotPassword.php">Esqueceu sua senha?</a>
                </div>
                <button type="submit" class="btn">Conecte-se</button>
            </section>
            <div class="register">
                <span>Não tem uma conta?</span>
                <a class="link" href="register.php">Cadastre-se</a>
            </div>
        </form>
    </header>
</body>
</html>