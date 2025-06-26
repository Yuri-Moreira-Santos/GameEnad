<?php 
    session_start();
    require_once('assets/php/config.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/style/style.css">
	<title>Game-Enad</title>
</head>
<body>
    <?php
        require('assets/php/vlibras.php')
    ?>
    <header>
        <?php
            require('assets/php/navBar.php')
        ?>
        <section class="form">
            <div class="spaceBtw gap">
                <h1 class="txtIntro">Bem vindo ao Enadinho,<br> uma plataforma de estudos para o ENADE de forma eficaz e divertida!</h1>
                <div>
                    <a class="btn" href="login.php">Entrar</a>
                </div>
            </div>
            <div class="bottom">
                <h3>Abaixo temos algumas informações sobre a plataforma</h3>
            </div>
        </section>
    </header>
    <main>
        <section class="centerScreen secPar">
            <div>
                <h2>O que é o ENADE?</h2>
                <p>
                    Conforme o Instituto Nacional de Estudos e Pesquisas Educacionais Anísio Teixeira
                    (INEP, 2024), o Exame Nacional de Desempenho de Estudantes (ENADE) é uma das
                    estratégias implementadas pelo Sistema Nacional de Avaliação da Educação Superior
                    (SINAES) com o propósito de avaliar o desempenho das instituições de ensino superior
                    no Brasil. Nesse contexto, a pontuação obtida no ENADE desempenha um papel
                    fundamental como um indicador utilizado pelos estudantes para avaliar as alternativas
                    disponíveis na hora de escolher um curso de graduação.
                </p>
                <br>
                <p>
                    De acordo com o art. 1º da Lei nº 10.861, de 14 de abril de 2004, foi criado o SINAES,
                    que tem como objetivo avaliar instituições, cursos e alunos de ensino superior em nível
                    nacional, visando à melhoria do ensino, ao aumento da oferta e à eficácia institucional. O
                    SINAES enfatiza as responsabilidades sociais das instituições, valorizando a missão
                    pública, os valores democráticos, o respeito à diversidade e afirmar a autonomia e
                    identidade institucional, sendo desenvolvido em conjunto com os sistemas de ensino
                    estaduais (Brasil, 2004).
                </p>
            </div>
            <div>
                <img class="logoEnade" src="assets/imgs/enadeLogoSemFundo.png" alt="Logo do Enade">
                <p>
                    Anualmente, o (INEP, 2024), que está sob a jurisdição do Ministério da Educação
                    (MEC), conduz uma avaliação cujos principais propósitos são aferir a excelência do
                    ensino superior e fomentar a capacitação de estudantes para uma transição mais
                    competente ao ambiente de trabalho.			
                </p>
            </div>
        </section>
        <section class="centerScreen secImpar">
            <div>
                <h2>Quem nos somos</h2>
                <p>
                    A Fatec Franco da Rocha no último exame aplicado, no Curso Superior de Tecnologia
                    (CST) em Gestão da Tecnologia da Informação (GTI), atingiu a nota máxima da avaliação
                    (nota 5), sendo a única faculdade do Centro Paula Souza (CPS) que obteve esse resultado
                    neste curso.
                </p>
                <br>
                <p>
                    Entretanto um problema pode ocorrer durante a preparação para o exame do ponto de
                    vista dos estudantes, a falta de um método prático e eficaz para estudar para a avaliação.
                    Isto ocorre porque o método de aplicação de algumas questões durante a realização da
                    prova do ENADE pode ser apresentado de uma forma diferente da que os alunos estão
                    acostumados a responder.
                </p>
            </div>
        </section>
        <section class="centerScreen secPar">
            <div>
                <h2>O motivo do projeto</h2>
                <p>
                    Desta forma identificou-se na instituição de ensino Fatec Franco da Rocha uma
                    necessidade pela coordenadoria do CST em Gestão da Tecnologia da Informação quanto
                    ao treinamento e estudo buscando manter o desempenho dos alunos no ENADE, visto
                    que é observado que muitos estudantes possuem dificuldades em como se preparar para
                    a prova e/ou esclarecer dúvidas de como ela funciona no dia de sua aplicação.
                </p>
                <br>
                <p>
                    Sendo uma preocupação da instituição manter sua nota neste exame, busca-se propor
                    uma forma de estudo para essa demanda a fim de incentivar e preparar os alunos para
                    desafios futuros. A partir daí manifestou-se como ideia de um projeto a criação de uma
                    plataforma gamificada para auxiliar e viabilizar uma preparação com engajamento dos
                    alunos para o exame, permitindo que os estudantes tenham consciência e conhecimento
                    sobre o tipo de questões que podem aparecer e interpretá-las com mais facilidade.
                </p>
            </div>
        </section>
    </main>
</body>
</html>