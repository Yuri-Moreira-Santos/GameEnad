<?php
if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 3) {
    header('Location: /login.php');
    exit;
}
?>
<h2>Cadastro de Disciplina</h2>

<form class="form" method="post" action="coordenador/func/Php/processaCadastrarDisciplina.php">
    <div class="formGroup">
        <span class="inputForm" for="nomeDisciplina">Nome da disciplina:</span>
        <input class="formControl" type="text" id="nomeDisciplina" name="nomeDisciplina" maxlength="100" required>
    </div>
    <div class="formGroup">
        <span class="inputForm" for="ementaDisciplina">Ementa da disciplina:</span>
        <textarea class="formControl" id="ementaDisciplina" name="ementaDisciplina" rows="5" required></textarea>
    </div>
    <div class="formGroup">
        <span class="inputForm" for="objetivo">Objetivos de Aprendizagem:</span>
        <textarea class="formControl" id="objetivo" name="objetivo" rows="5" cols="60" required></textarea>
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
