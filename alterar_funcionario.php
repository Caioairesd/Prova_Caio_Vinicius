<?php
session_start();

require_once 'conexao.php';
require_once 'includes/cabecalho.php';

// VERIFICA SE O USUARIO TEM PERMISSAO DE adm
if ($_SESSION['perfil'] != 1) {
    echo "<script> alert('Acesso Negado!'); window.location.href=principal.php; </script>";
    exit();
}

// INCIALIZA AS VARIAVEIS
$funcionario = null;

// SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO id OU PELO nome
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);

        // VERIFICA SE A BUSCA É UM id OU UM nome
        if (is_numeric($busca)) {
            $query = "SELECT * FROM funcionario WHERE id_funcionario = :busca";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome";

            $stmt = $pdo->prepare($query);
            $stmt->bindValue(":busca_nome", $busca, PDO::PARAM_STR);
        }

        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O USUARIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA
        if (!$funcionario) {
            echo "<script> alert('Funcionário não encontrado!'); </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Alterar Funcionario</title>
    <script>
        function aplicarMascaraTelefone(input) {
            let valor = input.value.replace(/\D/g, ""); // tira tudo que não é número

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

            // Validação só no envio, não durante digitação
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
</head>

<body>
    <h2>Alterar Funcionários</h2>

    <!--Formulario para buscar usuarios-->
    <form action="alterar_funcionario.php" method="post">
        <label for="busca_funcionario">Digite o id ou nome do funcionário:</label>
        <input type="text" id="busca_funcionario" name="busca_funcionario" required onkeyup="buscarSugestoes()">

        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>

    </form>

    <?php if ($funcionario): ?>
        <form action="processa_alteracao_funcionario.php" method="post">:

            <input type="hidden" name="id_funcionario" value="<?= htmlspecialchars($funcionario['id_funcionario']) ?>">

            <label for="nome_funcionario">Nome:</label>
            <input type="text" id="nome_funcionario" name="nome_funcionario" value="<?= htmlspecialchars($funcionario['nome_funcionario']) ?>" required>

            <label for="endereco">Endereco:</label>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($funcionario['endereco']) ?>" required>

            <label for="telefone">telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['telefone']) ?>" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($funcionario['email']) ?>" required>
            
            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>

        </form>

    <?php endif ?>
    <a href="principl.php" class="btn">Voltar</a>

</body>

</html>