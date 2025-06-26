<link rel="stylesheet" href="/assets/style/acessibility.css">
<div id="acessibilidadeMenu" class="acessibilidade-flutuante">
    <button onclick="toggleMenu()">🎨</button>
    <div class="acessibilidade-opcoes">
        <button onclick="aplicarFiltro('protanopia')" title="Protanopia">🟥</button>
        <button onclick="aplicarFiltro('deuteranopia')" title="Deuteranopia">🟩</button>
        <button onclick="aplicarFiltro('tritanopia')" title="Tritanopia">🟦</button>
        <button onclick="aplicarFiltro('contraste')" title="Alto Contraste">⬛</button>
        <button onclick="aplicarFiltro('')" title="Padrão">🔁</button>
    </div>
</div>
<script>
function aplicarFiltro(filtro) {
    document.body.className = filtro;
    document.cookie = "filtroAcessibilidade=" + filtro + "; path=/; max-age=31536000";
}

function aplicarFiltroSalvo() {
    const filtro = document.cookie.split('; ').find(row => row.startsWith('filtroAcessibilidade='));
    if (filtro) {
        const valor = filtro.split('=')[1];
        document.body.className = valor;
    }
}

window.addEventListener("DOMContentLoaded", aplicarFiltroSalvo);

function toggleMenu() {
    document.getElementById("acessibilidadeMenu").classList.toggle("ativo");
}
</script>