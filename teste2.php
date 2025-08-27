<script>
function aplicarMascaraTelefone(input) {
    let valor = input.value.replace(/\D/g, ""); // remove tudo que não é número

    if (valor.length <= 10) {
        // Formato (XX) XXXX-XXXX
        valor = valor.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else {
        // Formato (XX) XXXXX-XXXX
        valor = valor.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, "($1) $2-$3");
    }

    input.value = valor;
}

function aplicarMascaraEmail(input) {
    input.value = input.value.toLowerCase().trim(); 
}

function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value.trim();
    let telefone = document.getElementById("telefone").value.replace(/\D/g, ""); 
    let email = document.getElementById("email").value.trim();

    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    let regexTelefone = /^[0-9]{10,11}$/;
    if (!regexTelefone.test(telefone)) {
        alert("Digite um telefone válido (10 ou 11 dígitos).");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}
</script>

<form action="cadastro_funcionario.php" method="post" onsubmit="return validarFuncionario()">
    <label for="nome_funcionario">Nome:</label>
    <input type="text" name="nome_funcionario" id="nome_funcionario" required>

    <label for="endereco">Endereço:</label>
    <input type="text" name="endereco" id="endereco" required>

    <label for="telefone">Telefone:</label>
    <input type="text" name="telefone" id="telefone" maxlength="15" 
           oninput="aplicarMascaraTelefone(this)" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" 
           oninput="aplicarMascaraEmail(this)" required>

    <button type="submit">Salvar</button>
    <button type="reset">Cancelar</button>
</form>
