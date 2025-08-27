<?php
session_start();
require_once 'conexao.php';
require_once 'includes/cabecalho.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO 
// SUPONDO QUE O PERFIL 1 seja o adm


if ($_SESSION['perfil'] != 1) {
    echo 'Acesso negado!';
    exit();

}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome_funcionario = $_POST['nome_funcionario'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    try {
        // Verifica se o email já existe
        $sql = "SELECT COUNT(*) FROM funcionario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('Erro: Este e-mail já está cadastrado!');window.location.href='cadastro_funcionario.php';</script>";
        } else {
            // Faz o insert
            $sql = "INSERT INTO funcionario (nome_funcionario, endereco, telefone, email) 
                    VALUES (:nome_funcionario, :endereco, :telefone, :email)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_funcionario', $nome_funcionario);
            $stmt->bindParam(':endereco', $endereco);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                echo "<script>alert('Funcionário cadastrado com sucesso');</script>";
            } else {
                echo "<script>alert('Erro ao cadastrar funcionário'window.location.href='cadastro_funcionario.php');</script>";
            }
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro no banco de dados: " . $e->getMessage() . "');</script>";
    }


}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
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
<title>Cadastro de Funcionário</title>
</head>

<body>

    <h2>Cadastrar Funcionário</h2>
    <form action="cadastro_funcionario.php" method="post" onsubmit="return validarFuncionario()">
        <label for="nome_funcionario">Nome:</label>
        <input type="text" name="nome_funcionario" id="nome_funcionario" required>

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" maxlength="15" oninput="aplicarMascaraTelefone(this)" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" oninput="aplicarMascaraEmail(this)" required>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <a href="principal.php" class="btn">Voltar</a>


</body>