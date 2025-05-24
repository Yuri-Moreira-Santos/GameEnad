<?php
if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: /login.php');
    exit;
}
?>
<h2>Cadastro de curso</h2>

<form class="form" method="post" action="/coordenador/func/php/processaCadastrarCurso.php">
    <div class="formGroup">
        <span class="inputForm" for="nomeCurso">Nome do curso:</span>
        <input class="formControl" type="text" id="nomeCurso" name="nomeCurso" maxlength="100" required>
    </div>
    <div class="formGroup">
        <span class="inputForm" for="objetivoCurso">Objetivo do curso:</span>
        <input class="formControl" type="text" id="objetivoCurso" name="objetivoCurso" maxlength="255" required>
    </div>
    <div class="formGroup">
        <span class="inputForm" for="eixoCurso">Eixo do curso:</span>
        <textarea class="formControl" id="eixoCurso" name="eixoCurso" rows="5" cols="60" required></textarea>
    </div>
    <?php
        if (isset($_SESSION['erro'])) {
            echo "<div class='erro'>" . $_SESSION['erro'] . "</div>";
            unset($_SESSION['erro']);
        }
        if (isset($_SESSION['certo'])) {
            echo "<div class='certo'>" . $_SESSION['certo'] . "</div>";
            unset($_SESSION['certo']);
        }
    ?>
    <div class="final">
        <button type="submit" class="btn">Cadastrar</button>
    </div>
</form>
