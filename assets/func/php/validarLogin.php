<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/func/php/conn.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/func/php/validarCadastro.php');

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'emailInstitucional', FILTER_SANITIZE_EMAIL);
    $senha = trim($_POST['senha']);

    if ($email && $senha) {
        $sql = "SELECT u.id, u.nome, u.data_nascimento, u.email, u.senha, c.nome AS cargo_nome, c.id AS cargo_id
                FROM usuarios u
                JOIN cargo c ON u.cargo_id = c.id
                WHERE u.email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['logged'] = true;
                $_SESSION['usuario'] = [
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'data_nasc' => $usuario['data_nascimento'],
                    'email' => $usuario['email'],
                    'tipo' => intval($usuario['cargo_id']), // 1: aluno, 2: professor, 3: coordenador
                    'cargo' => $usuario['cargo_nome']
                ];

                header('Location: /home.php');
                exit;
            } else {
                // Senha incorreta → erro genérico
                $_SESSION['erro'] = 'E-mail ou senha incorretos.';
                header('Location: /login.php');
                exit;
            }
        } else {
            // E-mail não encontrado → verificar se o padrão está correto
            if (!validarFormatoEmailInstitucional($email)) {
                $_SESSION['erro'] = 'E-mail deve seguir o padrão nome.sobrenome000@fatec.sp.gov.br';
            } else {
                $_SESSION['erro'] = 'E-mail ou senha incorretos.';
            }
            header('Location: /login.php');
            exit;
        }
    } else {
        $_SESSION['erro'] = 'Preencha todos os campos!';
        header('Location: /login.php');
        exit;
    }
} else {
    header('Location: /erro.php');
    exit;
}
?>
