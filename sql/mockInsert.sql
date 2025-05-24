INSERT INTO usuarios (id, nome, email, data_nascimento, senha, cargo_id) VALUES
(1, 'Coordenador Teste', 'coordenador@teste.com', '1980-01-01', '$2y$10$0bOSMFpMg4aTxuxk2xdE2OvaMwbOPizTQiEYe3E9geDSnl7sU7lJe', 3),
(2, 'Professor Teste', 'professor@teste.com', '1985-05-10', '$2y$10$0bOSMFpMg4aTxuxk2xdE2OvaMwbOPizTQiEYe3E9geDSnl7sU7lJe', 2),
(3, 'Aluno Teste', 'aluno@teste.com', '2005-01-25', '$2y$10$0bOSMFpMg4aTxuxk2xdE2OvaMwbOPizTQiEYe3E9geDSnl7sU7lJe', 1);

INSERT INTO cursos (id, nome, eixo) VALUES
(1, 'Gestão da Tecnologia da Informação', 'Informação e Comunicação');

INSERT INTO coordenador_curso (coordenador_id, curso_id) VALUES
(1, 1);

INSERT INTO disciplinas (id, nome) VALUES
(1, 'Modelagem de Processos'),
(2, 'Laboratório de Hardware'),
(3, 'Engenharia de Software e Aplicações'),
(4, 'Teste do Sistema');

INSERT INTO alocacoes (professor_id, disciplina_id) VALUES
(2, 1),
(2, 4);

INSERT INTO enunciados (id, disciplina_id, titulo, texto) VALUES
(1, 4, 'Quais são as consequencias do trabalho informal no país', 'Além do contexto econômico, o avanço da tecnologia também é um dos responsáveis pelo aumento dos trabalhadores informais. E a tendência de contratação de freelancers por meio de plataformas digitais, como aplicati vos de delivery e de mobilidade urbana, ganhou até um nome: Gig Economy , ou economia dos bicos. Para os gigantes de tecnologia detentores desses aplicati vos, os motoristas são trabalhadores autônomos, que não possuem vínculo empregatí cio. Além de não estarem sujeitos a nenhuma regulamentação e proteção legal, os profi ssionais que desenvolvem esse ti po de trabalho deixam de contribuir para a Previdência Social e de possuir benefí cios como Fundo de Garanti a por Tempo de Serviço (FGTS), férias e décimo terceiro salário. Não obstante, ainda arcam com todo o custo da ati vidade que exercem. Em uma reportagem que ouviu alguns desses trabalhadores, motoristas afi rmaram sofrer com problemas de coluna e com o estresse no trânsito, além das longas jornadas de trabalho. Por esses moti vos, a Gig Economy está no centro de uma discussão mundial acerca da responsabilidade dessas companhias milionárias sobre as condições de trabalho da mão de obra que contratam. No meio do limbo jurídico, quem sofre são os trabalhadores dessas plataformas, que fi cam duplamente desprotegidos ― pelas empresas e pelo Estado.'),
(2, 4, 'Covid-19 reduz gravemente expectativa de vida de negros e latinos nos EUA. Revista Exame', 'A pandemia ocasionada pelo novo Coronavírus gerou impactos negati vos na economia e nos negócios, intensificando problemas sociais no mundo todo. Nos Estados Unidos, um estudo realizado com a parceria de duas importantes universidades verifi cou que a expectati va de vida dos norte-americanos caiu 1,1 ano em 2020. A nova expectativa é de 77,4 anos. De acordo com o estudo, esta foi a maior queda anual da expectati va de vida já registrada nos últi mos 40 anos. O declínio é ainda maior se considerada a expectati va de vida para negros que moram no país, cuja queda foi de 2,1 anos. Para a população lati na, essa queda foi de 3 anos. O declínio na expectati va de vida dos lati nos é significativo, uma vez que eles apresentam menor incidência de condições crônicas que são fatores de risco para a Covid-19 em relação às populações de brancos e negros.'),
(3, 4, 'Democracia', 'Que é democracia? Em seu famoso discurso em Gett ysburg, Abraham Lincoln disse que “a democracia é o governo do povo, feito para o povo e pelo povo, e responsável perante o povo”. O crédito desta definição é, na verdade, de Daniel Webster, que a elaborou 33 anos antes de Lincoln em outro discurso. Nesta ideia de “governo pelo povo e para o povo” surge uma questão essencial: e quando o povo esti ver em desacordo? E quando o povo ti ver preferências divergentes? O politólogo Arend Lijphart ressalta que há duas respostas principais: a resposta da “democracia majoritária” e a resposta da “democracia consensual”. Na democracia majoritária, a resposta é simples e direta: deve-se governar para a maioria do povo. A resposta alternativa, no modelo da democracia consensual é: deve-se governar para o máximo possível de pessoas. A virtude da democracia consensual é buscar consensos mais amplos no que é interesse de todos; o desafio da democracia consensual pressupõe lideranças políti cas mais maduras, tanto no governo quanto na oposição. Democratas genuínos têm aversão à ideia do totalitarismo e combatem os delírios daqueles que desejam poder sem limites.');

INSERT INTO questoes (id, enunciado_id, texto, nivel_dificuldade) VALUES
(1, 1, 'A partir das informações apresentadas, avalie as asserções a seguir e a relação proposta entre elas.
I. Trabalhadores autônomos informais que atuam em plataformas digitais sem qualquer vínculo
empregatício, desprotegidos de regulamentação ou lei trabalhista, compõem a Gig Economy .
 PORQUE
II. Os trabalhadores, na Gig Economy , arcam com todos os custos necessários para desempenhar
o seu trabalho, ganham por produção e enfrentam longas jornadas diárias, o que os deixa mais
desgastados e com problemas de saúde.
 A respeito dessas asserções, assinale a opção correta.', 'enade'),
(2, 1, 'Avalie as afirmações I e II sobre a pandemia e seu impacto...', 'enade'),
(3, 2, 'Sobre democracia e as ideias de Abraham Lincoln...', 'medio'),
(4, 3, 'Diante do corte de orçamento, qual a decisão mais estratégica?', 'dificil'),
(5, 3, 'Selecione as alternativas corretas sobre impacto social...', 'facil');

INSERT INTO alternativas (questao_id, texto, correta, nivel_dificuldade) VALUES
(1, 'As asserções I e II são proposições verdadeiras, e a II é uma justificativa correta da I.', 0, 'enade'),
(1, 'As asserções I e II são proposições verdadeiras, mas a II não é uma justificativa correta da I.', 1, 'enade'),
(1, 'A asserção I é uma proposição verdadeira, e a II é uma proposição falsa.', 0, 'enade'),
(2, 'A pandemia impactou principalmente populações vulneráveis como negros e latinos.', 1, 'enade'),
(2, 'A pandemia não afetou diferenças étnicas.', 0, 'enade'),
(3, 'Democracia é o governo do povo, feito para o povo e pelo povo.', 1, 'medio'),
(3, 'Democracia é o regime onde há um só líder com poder supremo.', 0, 'medio'),
(4, 'Negociar com a diretoria os requisitos que podem ser cortados.', 1, 'dificil'),
(4, 'Cortar 25% de todas as tarefas independentemente da relevância.', 0, 'dificil'),
(5, 'A crise sanitária aumentou a desigualdade social.', 1, 'facil'),
(5, 'A crise reduziu oportunidades em geral, sem afetar os mais pobres.', 0, 'facil');
