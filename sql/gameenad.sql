CREATE DATABASE IF NOT EXISTS gameenad;
USE gameenad;

-- Criar tabela cargo
CREATE TABLE IF NOT EXISTS cargo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
);

-- Inserir cargos
INSERT IGNORE INTO cargo (nome) VALUES ('aluno'), ('professor'), ('coordenador');

-- Criar tabela unificada de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto_perfil VARCHAR(255),
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_nascimento date NOT NULL,
    senha VARCHAR(255) NOT NULL,
    cargo_id INT NOT NULL,
    FOREIGN KEY (cargo_id) REFERENCES cargo(id)
);

-- Tabela cursos
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    eixo VARCHAR(100) NOT NULL,
    objetivo VARCHAR(255) NOT NULL
);

-- Tabela disciplinas (filha dos cursos)
CREATE TABLE IF NOT EXISTS disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ementa VARCHAR(255) NOT NULL,
    objetivo VARCHAR(255) NOT NULL
);

-- Relacionar coordenadores aos cursos que coordenam
CREATE TABLE IF NOT EXISTS coordenador_curso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    coordenador_id INT NOT NULL,
    curso_id INT NOT NULL,
    FOREIGN KEY (coordenador_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    UNIQUE (coordenador_id, curso_id)
);

CREATE TABLE IF NOT EXISTS alocacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    FOREIGN KEY (professor_id) REFERENCES usuarios(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    UNIQUE(professor_id, disciplina_id)
);

CREATE TABLE IF NOT EXISTS enunciados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    texto LONGTEXT NOT NULL,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
);

CREATE TABLE IF NOT EXISTS questoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enunciado_id INT NOT NULL,
    texto LONGTEXT NOT NULL,
    nivel_dificuldade ENUM('facil', 'medio', 'dificil', 'enade') NOT NULL,
    FOREIGN KEY (enunciado_id) REFERENCES enunciados(id)
);

CREATE TABLE IF NOT EXISTS alternativas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    questao_id INT NOT NULL,
    texto VARCHAR(500) NOT NULL,
    correta BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (questao_id) REFERENCES questoes(id)
);

CREATE TABLE IF NOT EXISTS alocacoes_disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE
);
