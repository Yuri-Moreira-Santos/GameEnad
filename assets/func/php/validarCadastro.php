<?php
function validarNome($nomeUsuario) {
    $nome = trim($nomeUsuario);
    $nomeRegex = '/^(?=.{3,100}$)([A-Za-zÀ-ÿ]+(\s[A-Za-zÀ-ÿ]+)*)$/u';
    return preg_match($nomeRegex, $nome) === 1;
}

function validarFormatoEmailInstitucional($email) {
    $emailRegex = '/^[a-zA-Z]{1,50}\.[a-zA-Z]{1,50}[0-9]{1,3}@fatec\.sp\.gov\.br$/';
    return preg_match($emailRegex, $email) === 1;
}

function validarEmail($emailInstitucional, $confirmarEmail, &$erros) {
    $emailRegex = '/^[a-zA-Z]{1,50}\.[a-zA-Z]{1,50}[0-9]{1,3}@fatec\.sp\.gov\.br$/';

    if (!preg_match($emailRegex, $emailInstitucional)) {
        $erros['emailInstitucional'][] = "E-mail institucional deve seguir o padrão nome.sobrenome000@fatec.sp.gov.br";
    }

    if ($emailInstitucional !== $confirmarEmail) {
        $erros['confirmarEmail'][] = "E-mails não coincidem";
    }
}

function validarDataNascimento($dataNascimento) {
    return $dataNascimento >= '1908-01-01' && $dataNascimento <= '2008-12-31';
}

function validarSenha($senha, $confirmarSenha, &$erros) {
    if (strlen($senha) < 10 || strlen($senha) > 20) {
        $erros['senha'][] = "Senha deve ter entre 10 e 20 caracteres";
    }
    if (!preg_match('/[a-z]/', $senha)) {
        $erros['senha'][] = "Senha deve conter ao menos uma letra minúscula";
    }
    if (!preg_match('/[A-Z]/', $senha)) {
        $erros['senha'][] = "Senha deve conter ao menos uma letra maiúscula";
    }
    if (!preg_match('/\d/', $senha)) {
        $erros['senha'][] = "Senha deve conter ao menos um número";
    }
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $senha)) {
        $erros['senha'][] = "Senha deve conter ao menos um caractere especial";
    }
    if (preg_match('/\s/', $senha)) {
        $erros['senha'][] = "Senha não deve conter espaços";
    }

    if ($senha !== $confirmarSenha) {
        $erros['confirmarSenha'][] = "As senhas não coincidem";
    }
}

function validarTermos($termosChecked) {
    return $termosChecked;
}

function validarCadastroUsuario($nome, $emailInstitucional, $confirmarEmail, $dataNascimento, $senha, $confirmarSenha, $termos) {
    $erros = [];

    if (!validarNome($nome)) {
        $erros['nome'] = "Nome deve ter entre 3 e 100 caracteres, apenas letras e espaços.";
    }

    validarEmail($emailInstitucional, $confirmarEmail, $erros);
    validarSenha($senha, $confirmarSenha, $erros);

    if (!validarDataNascimento($dataNascimento)) {
        $erros['dataNascimento'] = "Data de nascimento deve estar entre 1908 e 2008.";
    }

    if (!$termos) {
        $erros['termos'] = "Você deve aceitar os termos de uso.";
    }

    return $erros;
}
?>