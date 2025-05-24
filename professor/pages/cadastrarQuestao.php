<form class="form" method="post">
    <div class="section conteudo">        
        <h2>Cadastro das Questões</h2>       
        <div class="formGroup">
            <span class="inputForm">Pergunta:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Alternativa A:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Alternativa B:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Alternativa C:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Alternativa D:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Alternativa E:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Alternativa Correta:</span>
            <input type="text" class="formControl">
        </div>
        <div class="formGroup">
            <span class="inputForm">Nível de dificuldade da questão:</span>
            <select name="dificuldade" required>
                <option value="0">Fácil</option>
                <option value="1">Médio</option>
                <option value="2">Difícil</option>
                <option value="3">Oficial do INEP</option>
            </select>
        </div>
    </div>    
    <div class="final">
        <button type="submit" class="btn">Cadastrar</button>
    </div>
</form>