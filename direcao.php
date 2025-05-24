<?php require_once('assets/php/config.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/style.css">
    <title>Quem você é?</title>
</head>
<body>
    <header>
        <?php
            require('assets/php/navBar.php')
        ?>
        <section class="form">
            <div class="gap">
                <div>
                    <h1>Em qual área você se enquadra?</h1>
                </div>
                <div class="grid">
                    <a class="btn" href="coordenador/pages/login.php">Coordenador</a>
                    <a class="btn" href="professor/pages/login.php">Professor</a>
                    <a class="btn" href="aluno/pages/login.php">Aluno</a>
                </div>
            </div>
        </section>
    </header>
</body>
</html>