<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 2) {
    header('Location: /login.php');
    exit;
}

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar enunciados do professor
$sql = "SELECT e.id, e.titulo, d.nome as disciplina_nome
        FROM enunciados e
        INNER JOIN disciplinas d ON e.disciplina_id = d.id
        INNER JOIN alocacoes a ON d.id = a.disciplina_id
        WHERE a.professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$enunciados = [];
while ($row = $result->fetch_assoc()) {
    $enunciados[] = $row;
}
$stmt->close();

// Buscar ENUM de dificuldade
$sql = "SHOW COLUMNS FROM questoes LIKE 'nivel_dificuldade'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$enumStr = $row['Type'];
preg_match("/^enum\((.*)\)$/", $enumStr, $matches);
$enum = [];
if (isset($matches[1])) {
    foreach (explode(',', $matches[1]) as $value) {
        $v = trim($value, " '");
        $enum[] = $v;
    }
}

// Buscar questões
$questoes = [];
if (isset($_GET['enunciado_id']) && is_numeric($_GET['enunciado_id'])) {
    $enunciado_id = intval($_GET['enunciado_id']);
    $sql = "SELECT q.id, q.texto, q.nivel_dificuldade
            FROM questoes q
            WHERE q.enunciado_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $enunciado_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $questoes[] = $row;
    }
    $stmt->close();
}
?>

<div class="form" style="max-width: 45vw;">
    <h2>Visualizar Questões</h2>

    <form method="get">
        <input type="hidden" name="page" value="visualizarQuestao">
        <div class="formGroup">
            <h4>Selecione o Enunciado:</h4>
            <select class="select-curso" name="enunciado_id" onchange="this.form.submit()" required>
                <option value="">Selecione</option>
                <?php foreach ($enunciados as $enunciado): ?>
                    <option value="<?= htmlspecialchars($enunciado['id']) ?>"
                        <?= (isset($_GET['enunciado_id']) && $_GET['enunciado_id'] == $enunciado['id']) ? 'selected' : '' ?>>
                        (<?= htmlspecialchars($enunciado['disciplina_nome']) ?>) <?= htmlspecialchars($enunciado['titulo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($questoes): ?>
        <div class="enunciados">
            <?php foreach ($questoes as $idx => $questao): ?>
                <div class="enunciado-card">
                    <div class="inputForm">
                        <h3 style="color: var(--preto);"><?= htmlspecialchars($questao['texto']) ?></h3>
                    </div>
                    <div class="formControl" style="border-top: 1px solid var(--branco); border-left: none;">
                        <p style="color: var(--preto);">
                            Nível de Dificuldade: <strong><?= htmlspecialchars($questao['nivel_dificuldade']) ?></strong>
                        </p>
                    </div>
                    <div class="bolinhas"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_GET['enunciado_id'])): ?>
        <p>Nenhuma questão cadastrada para este enunciado.</p>
    <?php endif; ?>
</div>

<script>
    const enunciados = document.querySelectorAll('.enunciado-card');
    const maxBolinhas = 5;
    let atual = 0;

    const criarBolinhas = () => {
        enunciados.forEach((_, idx) => {
            const bolinhasDiv = enunciados[idx].querySelector('.bolinhas');
            bolinhasDiv.innerHTML = '';

            let inicio = Math.floor(atual / maxBolinhas) * maxBolinhas;
            let fim = Math.min(inicio + maxBolinhas, enunciados.length);

            for (let i = inicio; i < fim; i++) {
                const bolinha = document.createElement('div');
                bolinha.className = 'bolinha' + (i === atual ? ' ativa' : '');
                bolinha.addEventListener('click', () => {
                    atual = i;
                    atualizarTela();
                });
                bolinhasDiv.appendChild(bolinha);
            }
        });
    };

    const atualizarTela = () => {
        enunciados.forEach((card, idx) => {
            card.classList.toggle('mostrar', idx === atual);
        });
        criarBolinhas();
    };

    const avancar = () => {
        atual = (atual + 1) % enunciados.length;
        atualizarTela();
    };

    const voltar = () => {
        atual = (atual - 1 + enunciados.length) % enunciados.length;
        atualizarTela();
    };

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' || e.key.toLowerCase() === 'd') {
            avancar();
        }
        if (e.key === 'ArrowLeft' || e.key.toLowerCase() === 'a') {
            voltar();
        }
    });

    atualizarTela();
</script>
