<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login Aluno</title>
</head>
<body>
    <header>
        <?php
            require($_SERVER['DOCUMENT_ROOT'] . '/assets/php/navBar.php')
        ?>
        <form class="center form loginForm" method="POST">
            <section>
                <h2>Encontre sua conta</h2>
                <div class="formGroup" style="padding-bottom: 2em;">
                    <span class="inputForm fa fa-user"></span>
                    <input class="formControl" type="email" name="emailInstitucional" required placeholder="E-mail">
                </div>                
                <button type="submit" class="btn">Conecte-se</button>
            </section>            
        </form>
    </header>
</body>
</html>