<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

// Cursos
$cursos = [];
$resCursos = $conn->query("SELECT id, nome FROM cursos ORDER BY nome");
if ($resCursos) {
    while ($row = $resCursos->fetch_assoc()) {
        $cursos[] = $row;
    }
}

// Disciplinas
$disciplinas = [];
$resDisciplinas = $conn->query("SELECT id, nome FROM disciplinas ORDER BY nome");
if ($resDisciplinas) {
    while ($row = $resDisciplinas->fetch_assoc()) {
        $disciplinas[] = $row;
    }
}

// Alocações
$alocacoes = [];
$resAlocacoes = $conn->query("SELECT curso_id, disciplina_id FROM alocacoes_disciplinas");
if ($resAlocacoes) {
    while ($row = $resAlocacoes->fetch_assoc()) {
        $cursoId = $row['curso_id'];
        $discId = $row['disciplina_id'];
        if (!isset($alocacoes[$cursoId])) {
            $alocacoes[$cursoId] = [];
        }
        $alocacoes[$cursoId][] = $discId;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cursoId'], $_POST['disciplinas'])) {
    $cursoId = intval($_POST['cursoId']);
    $disciplinasJson = $_POST['disciplinas'];
    $disciplinasEnviadas = json_decode($disciplinasJson, true);
    if (!is_array($disciplinasEnviadas)) {
        $disciplinasEnviadas = [];
    }

    // Remove todas as alocações atuais
    $stmtDel = $conn->prepare("DELETE FROM alocacoes_disciplinas WHERE curso_id = ?");
    $stmtDel->bind_param("i", $cursoId);
    $stmtDel->execute();
    $stmtDel->close();

    // Insere as disciplinas enviadas
    if (count($disciplinasEnviadas) > 0) {
        $stmtIns = $conn->prepare("INSERT INTO alocacoes_disciplinas (curso_id, disciplina_id) VALUES (?, ?)");
        foreach ($disciplinasEnviadas as $discId) {
            $discId = intval($discId);
            $stmtIns->bind_param("ii", $cursoId, $discId);
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
  <h1>Alocar Disciplinas em Cursos</h1>

  <label for="curso-select">Selecione o Curso:</label><br />
  <select id="curso-select" class="select-curso" onchange="mostrarListas()">
    <option value="">-- Escolha um curso --</option>
    <?php foreach ($cursos as $curso): ?>
      <option value="<?= $curso['id'] ?>"><?= htmlspecialchars($curso['nome']) ?></option>
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
    <input type="hidden" name="cursoId" id="form-cursoId" />
    <input type="hidden" name="disciplinas" id="form-disciplinas" />
  </form>
</div>

<script>
const disciplinas = <?= json_encode($disciplinas) ?>;
const alocacoesOriginais = <?= json_encode($alocacoes) ?>;

const cursoSelect = document.getElementById('curso-select');
const listasContainer = document.getElementById('listas');
const listaAlocadas = document.getElementById('lista-alocadas');
const listaDisponiveis = document.getElementById('lista-disponiveis');
const btnSalvar = document.getElementById('btn-salvar');
const formSalvar = document.getElementById('form-salvar');
const formCursoId = document.getElementById('form-cursoId');
const formDisciplinas = document.getElementById('form-disciplinas');

let alocacoesAtual = {}; // estado local, vai ser atualizado ao interagir

function mostrarListas() {
  const cursoId = cursoSelect.value;
  if (!cursoId) {
    listasContainer.classList.add('oculto');
    btnSalvar.style.display = 'none';
    listaAlocadas.innerHTML = '';
    listaDisponiveis.innerHTML = '';
    return;
  }
  listasContainer.classList.remove('oculto');
  btnSalvar.style.display = 'inline-block';

  alocacoesAtual[cursoId] = alocacoesOriginais[cursoId] ? [...alocacoesOriginais[cursoId]] : [];

  renderListas(cursoId);
}

function renderListas(cursoId) {
  const alocadasIds = alocacoesAtual[cursoId] || [];

  const disciplinasAlocadas = disciplinas.filter(d => alocadasIds.includes(String(d.id)));
  const disciplinasDisponiveis = disciplinas.filter(d => !alocadasIds.includes(String(d.id)));

  listaAlocadas.innerHTML = disciplinasAlocadas
      .map(d => `<li class="lista-item" data-id="${d.id}">${d.nome}</li>`)
      .join('');
  listaDisponiveis.innerHTML = disciplinasDisponiveis
      .map(d => `<li class="lista-item" data-id="${d.id}">${d.nome}</li>`)
      .join('');

  addClickHandlers(cursoId);
}

function addClickHandlers(cursoId) {
  listaAlocadas.querySelectorAll('.lista-item').forEach(li => {
    li.onclick = () => {
      const discId = li.dataset.id;
      alocacoesAtual[cursoId] = alocacoesAtual[cursoId].filter(id => id != discId);
      renderListas(cursoId);
    };
  });
  listaDisponiveis.querySelectorAll('.lista-item').forEach(li => {
    li.onclick = () => {
      const discId = li.dataset.id;
      if (!alocacoesAtual[cursoId].includes(discId)) {
        alocacoesAtual[cursoId].push(discId);
      }
      renderListas(cursoId);
    };
  });
}

function salvarAlteracoes() {
  const cursoId = cursoSelect.value;
  if (!cursoId) return alert("Selecione um curso.");

  formCursoId.value = cursoId;
  formDisciplinas.value = JSON.stringify(alocacoesAtual[cursoId]);

  formSalvar.submit();
}
</script>