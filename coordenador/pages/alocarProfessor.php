<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

// Disciplinas
$disciplinas = [];
$resDisciplinas = $conn->query("SELECT id, nome FROM disciplinas ORDER BY nome");
if ($resDisciplinas) {
    while ($row = $resDisciplinas->fetch_assoc()) {
        $disciplinas[] = $row;
    }
}

// Professores
$professores = [];
$resProf = $conn->query("SELECT id, nome FROM usuarios WHERE cargo_id = 2 ORDER BY nome");
if ($resProf) {
    while ($row = $resProf->fetch_assoc()) {
        $professores[] = $row;
    }
}

// Alocações
$alocacoes = [];
$resAlocacoes = $conn->query("SELECT disciplina_id, professor_id FROM alocacoes");
if ($resAlocacoes) {
    while ($row = $resAlocacoes->fetch_assoc()) {
    $discId = $row['disciplina_id'];
    $profId = $row['professor_id'];
    if (!isset($alocacoes[$profId])) {
        $alocacoes[$profId] = [];
    }
    $alocacoes[$profId][] = $discId;
}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['professorId'], $_POST['disciplinas'])) {
    $professorId = intval($_POST['professorId']);
    $disciplinasJson = $_POST['disciplinas'];
    $disciplinasEnviadas = json_decode($disciplinasJson, true);
    if (!is_array($disciplinasEnviadas)) {
        $disciplinasEnviadas = [];
    }

    // Remove todas as alocações atuais desse professor
    $stmtDel = $conn->prepare("DELETE FROM alocacoes WHERE professor_id = ?");
    $stmtDel->bind_param("i", $professorId);
    $stmtDel->execute();
    $stmtDel->close();

    // Insere as disciplinas selecionadas
    if (count($disciplinasEnviadas) > 0) {
        $stmtIns = $conn->prepare("INSERT INTO alocacoes (professor_id, disciplina_id) VALUES (?, ?)");
        foreach ($disciplinasEnviadas as $discId) {
            $discId = intval($discId);
            $stmtIns->bind_param("ii", $professorId, $discId);
            $stmtIns->execute();
        }
        $stmtIns->close();
    }

    echo "<script>
        alert('Salvo com sucesso!');
        window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";
    exit;
}
?>
<div>
  <h1>Alocar Disciplinas em Professores</h1>

  <label for="professor-select">Selecione o Professor:</label><br />
  <select id="professor-select" class="select-curso" onchange="mostrarListas()">
    <option value="">-- Escolha um professor --</option>
    <?php foreach ($professores as $prof): ?>
      <option value="<?= $prof['id'] ?>"><?= htmlspecialchars($prof['nome']) ?></option>
    <?php endforeach; ?>
  </select>

  <div id="listas" class="listas-container oculto">
    <div class="lista">
      <h3 class="lista-titulo">Disciplinas Alocadas</h3>
      <ul id="lista-alocadas" class="lista-ul"></ul>
    </div>
    <div class="lista">
      <h3 class="lista-titulo">Disciplinas Disponíveis</h3>
      <ul id="lista-disponiveis" class="lista-ul"></ul>
    </div>
  </div>

  <div class="final">
    <button class="btn" id="btn-salvar" onclick="salvarAlteracoes()">Salvar alterações</button>
  </div>

  <form id="form-salvar" method="POST" style="display:none;">
    <input type="hidden" name="professorId" id="form-professorId" />
    <input type="hidden" name="disciplinas" id="form-disciplinas" />
  </form>
</div>

<script>
const disciplinas = <?= json_encode($disciplinas) ?>;
const alocacoesOriginais = <?= json_encode($alocacoes) ?>;

const professorSelect = document.getElementById('professor-select');
const listasContainer = document.getElementById('listas');
const listaAlocadas = document.getElementById('lista-alocadas');
const listaDisponiveis = document.getElementById('lista-disponiveis');
const btnSalvar = document.getElementById('btn-salvar');
const formSalvar = document.getElementById('form-salvar');
const formProfessorId = document.getElementById('form-professorId');
const formDisciplinas = document.getElementById('form-disciplinas');

let alocacoesAtual = {};

function mostrarListas() {
  const professorId = professorSelect.value;
  if (!professorId) {
    listasContainer.classList.add('oculto');
    btnSalvar.style.display = 'none';
    listaAlocadas.innerHTML = '';
    listaDisponiveis.innerHTML = '';
    return;
  }
  listasContainer.classList.remove('oculto');
  btnSalvar.style.display = 'inline-block';

  alocacoesAtual[professorId] = alocacoesOriginais[professorId] ? [...alocacoesOriginais[professorId]] : [];

  renderListas(professorId);
}

function renderListas(professorId) {
  const alocadasIds = alocacoesAtual[professorId] || [];

  const disciplinasAlocadas = disciplinas.filter(d => alocadasIds.includes(String(d.id)));
  const disciplinasDisponiveis = disciplinas.filter(d => !alocadasIds.includes(String(d.id)));

  listaAlocadas.innerHTML = disciplinasAlocadas
    .map(d => `<li class="lista-item" data-id="${d.id}">${d.nome}</li>`)
    .join('');

  listaDisponiveis.innerHTML = disciplinasDisponiveis
    .map(d => `<li class="lista-item" data-id="${d.id}">${d.nome}</li>`)
    .join('');

  addClickHandlers(professorId);
}

function addClickHandlers(professorId) {
  listaAlocadas.querySelectorAll('.lista-item').forEach(li => {
    li.onclick = () => {
      const discId = li.dataset.id;
      alocacoesAtual[professorId] = alocacoesAtual[professorId].filter(id => id != discId);
      renderListas(professorId);
    };
  });

  listaDisponiveis.querySelectorAll('.lista-item').forEach(li => {
    li.onclick = () => {
      const discId = li.dataset.id;
      if (!alocacoesAtual[professorId].includes(discId)) {
        alocacoesAtual[professorId].push(discId);
      }
      renderListas(professorId);
    };
  });
}

function salvarAlteracoes() {
  const professorId = professorSelect.value;
  if (!professorId) return alert("Selecione um professor.");

  formProfessorId.value = professorId;
  formDisciplinas.value = JSON.stringify(alocacoesAtual[professorId]);

  formSalvar.submit();
}
</script>
