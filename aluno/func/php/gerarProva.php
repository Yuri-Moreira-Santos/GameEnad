<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

function gerarProvaPorDificuldade($dificuldadeSelecionada) {
    global $conn;
    $prova = [];

    if ($dificuldadeSelecionada === "Enade") {
        $query = "SELECT * FROM questoes WHERE nivel_dificuldade = 'Enade' ORDER BY RAND()";
        $result = mysqli_query($conn, $query);
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $queryPrincipal = "SELECT * FROM questoes WHERE nivel_dificuldade = '$dificuldadeSelecionada' ORDER BY RAND()";
        $resultPrincipal = mysqli_query($conn, $queryPrincipal);
        $queryOutras = "SELECT * FROM questoes WHERE nivel_dificuldade != '$dificuldadeSelecionada' AND nivel_dificuldade != 'Enade' ORDER BY RAND()";
        $resultOutras = mysqli_query($conn, $queryOutras);

        $result = array_merge(
            mysqli_fetch_all($resultPrincipal, MYSQLI_ASSOC),
            mysqli_fetch_all($resultOutras, MYSQLI_ASSOC)
        );
        shuffle($result);
    }

    $contador = 0;

    foreach ($result as $questao) {
        $idQuestao = $questao['id'];
        $idEnunciado = $questao['enunciado_id'];

        // Verifica se há enunciado
        if (!$idEnunciado) {
            continue; // Pula essa questão
        }

        // Busca enunciado
        $enunQuery = "SELECT * FROM enunciados WHERE id = $idEnunciado";
        $enunResult = mysqli_query($conn, $enunQuery);
        $enunciado = mysqli_fetch_assoc($enunResult);

        if (!$enunciado) {
            continue; // Sem enunciado, pula
        }

        // Busca alternativas
        $altQuery = "SELECT * FROM alternativas WHERE questao_id = $idQuestao";
        $altResult = mysqli_query($conn, $altQuery);
        $alternativas = mysqli_fetch_all($altResult, MYSQLI_ASSOC);

        if (!$alternativas || count($alternativas) < 1) {
            continue; // Sem alternativas, pula
        }

        // Adiciona questão válida à prova
        $alternativasFormatadas = [];
        foreach ($alternativas as $alt) {
            $alternativasFormatadas[] = [$alt['id'], $alt['texto']];
        }

        $prova[] = [
            "titulo_enunciado$contador" => $enunciado['titulo'],
            "texto_enunciado$contador" => $enunciado['texto'],
            "questao_texto" => $questao['texto'],
            "alternativas$contador" => $alternativasFormatadas,
            "questao_id" => $idQuestao
        ];

        $contador++;

        // Limita a quantidade
        if ($dificuldadeSelecionada === "Enade" && $contador >= 5) {
            break;
        }
        if ($dificuldadeSelecionada !== "Enade" && $contador >= 5) {
            break;
        }
    }

    return $prova;
}
?>
