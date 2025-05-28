<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/assets/php/config.php');

if (!isset($_SESSION['logged']) || $_SESSION['usuario']['tipo'] != 2) {
    header('Location: /login.php');
    exit;
}

$userId = $_SESSION['usuario']['id'] ?? null;

// Buscar enunciados do professor com nome da disciplina
$sql = "SELECT d.id, d.nome 
        FROM disciplinas d
        INNER JOIN alocacoes a ON d.id = a.disciplina_id
        WHERE a.professor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$disciplinas = [];
while ($row = $result->fetch_assoc()) {
    $disciplinas[] = $row;
}
$stmt->close();

$enunciados = [];

if (isset($_GET['disciplina_id']) && $_GET['disciplina_id'] != '') {
    $disciplina_id = intval($_GET['disciplina_id']);

    // Buscar os enunciados dessa disciplina
    $sql = "SELECT id, titulo, texto FROM enunciados WHERE disciplina_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $disciplina_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $enunciados[] = $row;
    }
    $stmt->close();
}
?>
<div class="form" style="max-width: 45vw;">
    <h2>Visualizar Enunciados</h2>

    <form method="get">
        <input type="hidden" name="page" value="visualizarEnunciado">
        <div class="formGroup">
            <h4>Selecione a Disciplina:</h4>
            <select class="select-curso" name="disciplina_id" onchange="this.form.submit()" required>
                <option value="">Selecione</option>
                <?php foreach ($disciplinas as $disciplina): ?>
                    <option value="<?= htmlspecialchars($disciplina['id']) ?>"
                        <?= (isset($_GET['disciplina_id']) && $_GET['disciplina_id'] == $disciplina['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($disciplina['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($enunciados): ?>
        <div class="enunciados">
            <?php foreach ($enunciados as $idx => $enunciado): ?>
                <div class="enunciado-card">
                    <div class="inputForm">
                        <h3 style="color: var(--preto);"><?= htmlspecialchars($enunciado['titulo']) ?></h3>
                    </div>
                    <div class="formControl" style="border-top: 1px solid var(--branco); border-left: none;">
                        <p style="color: var(--preto);"><?= nl2br(htmlspecialchars($enunciado['texto'])) ?></p>
                    </div>
                    <div class="bolinhas"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_GET['disciplina_id'])): ?>
        <p>Nenhum enunciado cadastrado para essa disciplina.</p>
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

    atualizarTela();
</script>
