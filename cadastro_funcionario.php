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

    $sql = "INSERT INTO funcionario(nome_funcionario, endereco,telefone,email) values(:nome_funcionario,:endereco,:telefone,:email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_funcionario', $nome_funcionario);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "<script>alert('Funcionário cadastrado com sucesso');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar funcionário');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">

</head>
<title>Cadastro de Funcionário</title>
</head>

<body>

    <h2>Cadastrar Funcionário</h2>
    <form action="cadastro_funcionario.php" method="post">
        <label for="nome_funcionario">Nome:</label>
        <input type="text" name=" nome_funcionario" id="nome_funcionario" required>

        <label for="endereco">Endereço:</label>
        <input type="text" name=" endereco" id="endereco" required onkeypress="">

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>


        </select>


        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>

    </form>

    <a href="principal.php" class="btn">Voltar</a>


</body>