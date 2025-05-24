<?php 
    session_start();
    require_once('assets/php/config.php');

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true || !isset($_SESSION['usuario']['tipo'])) {
        header('Location: /login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="/assets/style/sidebar.css">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Game-Enad</title>
</head>
<body>
    <?php
        require_once('assets/php/sidebar.php')
    ?>
    <section class="home-section">
        <div class="home-content">
            <?php
            $allowedPages = [];

            if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
                switch ($_SESSION['usuario']['tipo']) {
                    case 3:
                        $allowedPages = [
                            'cadastrarCurso',
                            'visualizarCurso',
                            'editarCurso',
                            'cadastrarDisciplina',
                            'visualizarDisciplina',
                            'editarDisciplina',
                            'cadastrarProfessor',
                            'visualizarProfessor',
                            'editarProfessor',
                            'alocarDisciplina',
                            'alocarProfessor',
                            'profile',
                        ];
                        break;
                    case 2:
                        $allowedPages = [
                            'cadastrarEnunciado',
                            'visualizarEnunciado',
                            'editarEnunciado',
                            'cadastrarQuestao',
                            'visualizarQuestao',
                            'editarQuestao',
                            'cadastrarAlternativa',
                            'visualizarAlternativa',
                            'editarAlternativa',
                            'profile',
                        ];
                        break;
                    case 1:
                        $allowedPages = [
                            'partida',
                            'feedback',
                            'profile',
                        ];
                        break;
                    default:
                        echo "<p>Tipo de usuário inválido.</p>";
                        exit;
                }

                if (isset($_GET['page']) && in_array($_GET['page'], $allowedPages)) {
                    $tipoUsuario = $_SESSION['usuario']['tipo'];
                    $pasta = $tipoUsuario === 3 ? 'coordenador' : ($tipoUsuario === 2 ? 'professor' : 'aluno');
                    require_once(__DIR__ . "/$pasta/pages/{$_GET['page']}.php");
                } else {
                    echo "<h1>Bem-vindo(a) ao Painel</h1>";
                }
            } else {
                header("Location: index.php");
                exit;
            }
            ?>
        </div>
    </section>
</body>
</html>