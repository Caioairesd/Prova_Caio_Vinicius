<?php
session_start();
require_once "conexao.php";
require_once "funcoes_email.php"; //Arquivos que geram a senha e simulam o envio

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['$email'];

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindparam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        //GERA UMA SENHA TEMPORARIA E ALEATORIA
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_temporaria = password_hash($senha_temporaria, PASSWORD_DEFAULT);


        //ATUALIZA A SENHA DO USUARIO NO BANCO
        $query = "UPDATE usuario SET senha ,senha_temporaria = TRUE WHERE email =:email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":senha", $senha_hash);
        $stmt->bindParam(":email", $email);
        $stmt->execute();


        // SIMULA O ENVIO DO EMAIL (GRAVA txt)
        simularEnvioEmail($email,$senha_temporaria);
        echo "<script> alert('Uma senha temporaria foi gerada e enviada(simulação).Verifique o arquivo emails_simulados.txt')window.location.href='login.php';</script>";

    } else {
        echo "<script> alert('Email não encontrado.');</script>";

    }

}

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Recuperar Senha</h2>

    <form action="recuperar_senha.php">
        <label for="">Digite o seu e-mail cadastrado</label>
        <input type="email" name="email" id="email" required>

        <button type="submit">Enviar senha temporária</button>


    </form>

</body>

</html>