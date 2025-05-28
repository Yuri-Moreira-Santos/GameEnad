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
    <style>
        /* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.6);
}

/* Conteúdo do modal */
.modal-content {
    background-color: var(--cor-quatro);
    border: 3px solid var(--cor-tres) ;
    margin: auto;
    padding: 20px;
    border-radius: 10px;
    width: 60%;
    max-width: 630px;
    position: relative;
    animation: fadeIn 0.3s ease;
}

/* Botão fechar */
.fechar {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.fechar:hover,
.fechar:focus {
    color: black;
}

/* Animação suave */
@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}

    </style>
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
                            <a href="#" class="link" onclick="abrirModalTermos()">termos de uso e privacidade</a>
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
        <div id="modalTermos" class="modal">
            <div class="modal-content">
                <span class="fechar" onclick="fecharModalTermos()">&times;</span>
                <h2>Termos de Uso e Privacidade</h2>
                <p>
                    Termos de Uso – Plataforma Gamificada para Estudo do ENADE - Versão beta<br>
E-banq<br>
1. A plataforma E-banq tem como objetivo principal fornecer um ambiente seguro, dinâmico e enriquecedor para o estudo e preparo dos alunos da Fatec para o ENADE. Buscamos oferecer conteúdos de valor real e concreto, como simulados, exercícios e tutoriais de uso, para que cada interação contribua de forma significativa para o aprendizado e desenvolvimento acadêmico e profissional dos estudantes.<br>
2. Valorizamos a qualidade sobre a quantidade de conteúdo, buscando assegurar que cada recurso disponibilizado tenha impacto positivo no estudo dos usuários. Nosso compromisso é construir um espaço de aprendizado livre de práticas de engajamento abusivas, publicidade invasiva ou qualquer ação que prejudique a experiência e a concentração dos alunos.<br>
3. Além disso, nunca venderemos os dados dos usuários nem compartilharemos informações com terceiros. Coletamos apenas os dados estritamente necessários para o funcionamento e proteção da plataforma. Seguimos o princípio de coletar o mínimo de informações possível, garantindo uma base de dados segura e um maior nível de proteção para todos os usuários.<br>
Aceitação dos Termos<br>
1. Ao acessar e utilizar a plataforma gamificada para estudo do ENADE, você concorda com estes Termos de Uso. Se você não concorda com algum dos termos, por favor, não utilize a plataforma.<br>
Finalidade da Plataforma<br>
1. Esta plataforma foi desenvolvida como um recurso complementar para auxiliar estudantes da Fatec no preparo para o ENADE. Seu uso destina-se exclusivamente para fins educacionais e está atualmente em fase Beta, ou seja, pode conter erros e passar por ajustes e melhorias.<br>
Acesso e Uso da Plataforma<br>
1. O uso da plataforma é gratuito e restrito a estudantes, professores e coordenadores autorizados pela Fatec.<br>
2. O usuário deve fornecer informações verídicas e manter seus dados de acesso em segurança, não compartilhando login e senha com terceiros.<br>
Fase Beta<br>
1. Esta versão da plataforma é um protótipo e está sujeita a instabilidades, interrupções e erros de funcionamento.<br>
2. Os usuários podem ser solicitados a fornecer feedback para melhorias e ajuste do sistema, o que é altamente encorajado para o desenvolvimento do projeto.<br>
Responsabilidades e Limitações<br>
1. A equipe de desenvolvimento se compromete a fazer o melhor para garantir a segurança dos dados, mas não se responsabiliza por perdas ou danos decorrentes de eventuais falhas no sistema.<br>
2. O usuário é responsável por qualquer atividade realizada na plataforma em sua conta e concorda em não utilizar a plataforma para fins ilícitos ou abusivos.<br>
Propriedade Intelectual<br>
1. O conteúdo da plataforma, incluindo elementos gráficos, jogos, exercícios e outros recursos interativos, é de propriedade exclusiva dos desenvolvedores e/ou da Fatec.<br>
2. O usuário não pode copiar, reproduzir, modificar ou distribuir o conteúdo sem autorização prévia.<br>
Coleta e Uso de Dados<br>
1. Durante o uso da plataforma, alguns dados de utilização poderão ser coletados para fins de análise e aprimoramento da plataforma. Os dados coletados serão tratados conforme as diretrizes da LGPD (Lei Geral de Proteção de Dados Pessoais).<br>
Feedback do Usuário<br>
1. A equipe de desenvolvimento valoriza o feedback dos usuários e poderá utilizar comentários e sugestões para melhorar a plataforma, sem nenhuma obrigação de compensação ao usuário.<br>
2. Caso o usuário identifique ou acidentalmente encontre uma brecha de segurança na plataforma, que permita acesso a informações sensíveis (como dados privados de outros usuários ou acesso não autorizado a áreas restritas), compromete-se a reportar o ocorrido de forma privada, através do formulário fornecido. Após a correção da falha, a equipe responsável pela plataforma compromete-se a documentar o ocorrido, as medidas tomadas e as lições aprendidas. Acreditamos na transparência e no aprendizado compartilhado, garantindo sempre a proteção máxima dos dados sensíveis dos usuários. Falhas que não envolvam dados confidenciais e não prejudiquem outros usuários serão relatadas na documentação de desenvolvimento do projeto.<br>
Modificações nos Termos de Uso<br>
1. A equipe se reserva o direito de modificar estes Termos de Uso a qualquer momento, com aviso prévio aos usuários. O uso contínuo da plataforma após alterações implica a aceitação dos novos termos.<br>
Contato para Suporte<br>
1. Em caso de dúvidas ou problemas técnicos, o usuário pode entrar em contato com a equipe de suporte por meio do formulário disponibilizado.<br>
                </p>
            </div>
        </div>
    </header>
    <script>
        function abrirModalTermos() {
                document.getElementById('modalTermos').style.display = 'block';
            }

            function fecharModalTermos() {
                document.getElementById('modalTermos').style.display = 'none';
            }

            // Fechar modal se clicar fora dele
            window.onclick = function(event) {
                const modal = document.getElementById('modalTermos');
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

    </script>
</body>
</html>