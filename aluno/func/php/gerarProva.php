<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

function gerarProvaPorDificuldade($dificuldadeSelecionada) {
    global $conn;
    $prova = [];

    if ($dificuldadeSelecionada === "Enade") {
        $query = "SELECT * FROM questoes WHERE nivel_dificuldade = 'Enade' ORDER BY RAND() LIMIT 5";
        $result = mysqli_query($conn, $query);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $queryPrincipal = "SELECT * FROM questoes WHERE nivel_dificuldade = '$dificuldadeSelecionada' ORDER BY RAND() LIMIT 3";
        $resultPrincipal = mysqli_query($conn, $queryPrincipal);
        $queryOutras = "SELECT * FROM questoes WHERE nivel_dificuldade != '$dificuldadeSelecionada' AND nivel_dificuldade != 'Enade' ORDER BY RAND() LIMIT 2";
        $resultOutras = mysqli_query($conn, $queryOutras);

        $result = array_merge(mysqli_fetch_all($resultPrincipal, MYSQLI_ASSOC), mysqli_fetch_all($resultOutras, MYSQLI_ASSOC));
        shuffle($result);
    }

    foreach ($result as $index => $questao) {
        $idQuestao = $questao['id'];

        $altQuery = "SELECT * FROM alternativas WHERE questao_id = $idQuestao";
        $altResult = mysqli_query($conn, $altQuery);
        $alternativas = mysqli_fetch_all($altResult, MYSQLI_ASSOC);

        $alternativasFormatadas = [];
        foreach ($alternativas as $alt) {
            $alternativasFormatadas[] = [$alt['id'], $alt['texto']];
        }

        $prova[] = [
            "titulo_enunciado$index" => $questao['titulo'],
            "texto_enunciado$index" => $questao['texto'],
            "alternativas$index" => $alternativasFormatadas,
            "questao_id" => $idQuestao
        ];
    }

    return $prova;
}
