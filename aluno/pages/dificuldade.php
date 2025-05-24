<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/aluno/func/php/gerarProva.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dificuldade = $_POST['dificuldade'] ?? '';
    if ($dificuldade) {
        $_SESSION['dificuldade'] = $dificuldade;
        $_SESSION['prova'] = gerarProvaPorDificuldade($dificuldade);
        header("Location: /home.php?page=partida");
        exit();
    }
}
?>

<div class="form">
    <h2>Escolha a Dificuldade da Prova</h2>
    <form method="post" action="">
        <select name="dificuldade" required class="select-curso">
            <option value="">Selecione</option>
            <option value="Fácil">Fácil</option>
            <option value="Médio">Médio</option>
            <option value="Difícil">Difícil</option>
            <option value="Enade">ENADE</option>
        </select>
        <button type="submit" class="btn">Gerar Prova</button>
    </form>
</div>
