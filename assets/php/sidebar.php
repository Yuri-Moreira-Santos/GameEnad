<?php
  $nomeCompleto = $_SESSION['usuario']['nome'];
  $partesNome = explode(' ', trim($nomeCompleto));

  $primeiroNome = ucfirst($partesNome[0]);
  $inicialSobrenome = isset($partesNome[1]) ? strtoupper($partesNome[1][0]) : '';

  $nomeExibido = $primeiroNome;
  if ($inicialSobrenome !== '') {
      $nomeExibido .= " " . $inicialSobrenome . ".";
  }

  $cargo = ucfirst($_SESSION['usuario']['cargo']);
?>
<div class="sidebar close">
  <div class="logo-details">
    <i class="bx bx-menu"></i>
    <span class="logo_name">E-banq</span>
  </div>
  <ul class="nav-links">
    <?php if ($_SESSION['usuario']['cargo'] === 'coordenador'): ?>    
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-collection" ></i>
          <span class="link_name">Cursos</span>
        </a>
        <i class="bx bxs-chevron-down arrow" ></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Cursos</a></li>
        <li><a href="/home.php?page=cadastrarCurso">Cadastrar curso</a></li>
        <li><a href="/home.php?page=visualizarCurso">Visualizar curso</a></li>
        <li><a href="/home.php?page=editarCurso">Editar curso</a></li>
      </ul>
    </li>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-book-alt" ></i>
          <span class="link_name">Disciplinas</span>
        </a>
        <i class="bx bxs-chevron-down arrow" ></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Disciplinas</a></li>
        <li><a href="/home.php?page=cadastrarDisciplina">Cadastrar disciplina</a></li>
        <li><a href="/home.php?page=visualizarDisciplina">Visualizar disciplina</a></li>
        <li><a href="/home.php?page=editarDisciplina">Editar disciplina</a></li>
      </ul>
    </li>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-book-alt" ></i>
          <span class="link_name">Professores</span>
        </a>
        <i class="bx bxs-chevron-down arrow"></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Professores</a></li>
        <li><a href="/home.php?page=cadastrarProfessor">Cadastrar professor</a></li>
        <li><a href="/home.php?page=visualizarProfessor">Visualizar professor</a></li>
        <li><a href="/home.php?page=editarProfessor">Editar professor</a></li>
      </ul>
    </li>      
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-plug" ></i>
          <span class="link_name">Alocações</span>
        </a>
        <i class="bx bxs-chevron-down arrow" ></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Alocações</a></li>
        <li><a href="/home.php?page=alocarDisciplina">Alocar disciplina</a></li>
        <li><a href="/home.php?page=alocarProfessor">Alocar professor</a></li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if ($_SESSION['usuario']['cargo'] === 'professor'): ?>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-book-alt" ></i>
          <span class="link_name">Enunciado</span>
        </a>
        <i class="bx bxs-chevron-down arrow"></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Enunciado</a></li>
        <li><a href="/home.php?page=cadastrarEnunciado">Cadastrar Enunciado</a></li>
        <li><a href="/home.php?page=visualizarEnunciado">Visualizar Enunciado</a></li>
        <li><a href="/home.php?page=editarEnunciado">Editar Enunciado</a></li>
      </ul>
    </li>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-book-alt" ></i>
          <span class="link_name">Questões</span>
        </a>
        <i class="bx bxs-chevron-down arrow"></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Questões</a></li>
        <li><a href="/home.php?page=cadastrarQuestao">Cadastrar Questões</a></li>
        <li><a href="/home.php?page=visualizarQuestao">Visualizar Questões</a></li>
        <li><a href="/home.php?page=editarQuestao">Editar Questões</a></li>
      </ul>
    </li>
    <li>
      <div class="iocn-link">
        <a href="#">
          <i class="bx bx-book-alt" ></i>
          <span class="link_name">Alternativas</span>
        </a>
        <i class="bx bxs-chevron-down arrow"></i>
      </div>
      <ul class="sub-menu">
        <li><a class="link_name" href="#">Alternativas</a></li>
        <li><a href="/home.php?page=cadastrarAlternativa">Cadastrar Alternativas</a></li>
        <li><a href="/home.php?page=visualizarAlternativa">Visualizar Alternativas</a></li>
        <li><a href="/home.php?page=editarAlternativa">Editar Alternativas</a></li>
      </ul>
    </li>
    <?php endif; ?>
    <?php if ($_SESSION['usuario']['cargo'] === 'aluno'): ?>
    <li>
      <a href="/home.php?page=partida">
        <i class='bx bx-book-alt'></i>
        <span class="link_name">Prova</span>
      </a>
      <ul class="sub-menu blank">
        <li><a class="link_name" href="/home.php?page=partida">Prova</a></li>
      </ul>
    </li>
    <?php endif; ?>
    <li>
      <div class="profile-details">
        <a href="/home.php?page=profile">
          <div class="profile-content">
            <img src="/assets/imgs/profile.jpg" alt="profileImg">
          </div>
          <div class="name-job">
            <div class="profile_name"><?= htmlspecialchars($nomeExibido) ?></div>
            <div class="job"><?= htmlspecialchars($cargo) ?></div>
          </div>
        </a>
        <a href="assets/func/php/logout.php"><i class="bx bx-log-out"></i></a>
      </div>
    </li>
  </ul>
</div>
<script>
let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e)=>{
  let arrowParent = e.target.parentElement.parentElement;
  arrowParent.classList.toggle("showMenu");
  });
}
let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".bx-menu");
sidebarBtn.addEventListener("click", ()=>{
  sidebar.classList.toggle("close");
});
</script>