<?php
session_start();
require_once 'conexao.php';
require_once 'includes/cabecalho.php';

if ($_SESSION['perfil'] != 1) {
    echo 'Acesso negado!';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_funcionario = preg_replace('/[^a-zA-ZÀ-ÿ\s]/', '', $_POST['nome_funcionario']);
    $endereco = preg_replace('/[^a-zA-ZÀ-ÿ0-9\s,.-]/', '', $_POST['endereco']);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $sql = "INSERT INTO funcionario(nome_funcionario, endereco, telefone, email) 
            VALUES (:nome_funcionario, :endereco, :telefone, :email)";
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
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" href="styles.css">

    <script>
        // Máscara de telefone manual
        function mascaraTelefone(input) {
            let valor = input.value.replace(/\D/g, '');
            if (valor.length > 10) {
                input.value = valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else {
                input.value = valor.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            }
        }

        // Validação de caracteres
        function limparTexto(input, tipo) {
            let regex;
            if (tipo === 'nome') {
                regex = /[^a-zA-ZÀ-ÿ\s]/g;
            } else if (tipo === 'endereco') {
                regex = /[^a-zA-ZÀ-ÿ0-9\s,.-]/g;
            }
            input.value = input.value.replace(regex, '');
        }
    </script>
</head>

<body>
    <h2>Cadastrar Funcionário</h2>
    <form action="cadastro_funcionario.php" method="post">
        <label for="nome_funcionario">Nome:</label>
        <input type="text" name="nome_funcionario" id="nome_funcionario" required 
               oninput="limparTexto(this, 'nome')">

        <label for="endereco">Endereço:</label>
        <input type="text" name="endereco" id="endereco" required 
               oninput="limparTexto(this, 'endereco')">

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" required 
               oninput="mascaraTelefone(this)">

        <label for="email">Email:</label>
