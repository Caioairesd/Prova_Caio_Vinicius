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

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $id_perfil = $_POST['id_perfil'];
    try {
        // Verifica se o email já existe
        $sql = "SELECT COUNT(*) FROM usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            echo "<script>alert('Erro: Este e-mail já está cadastrado!');window.location.href='cadastro_usuario.php';</script>";
        } else {


            $sql = "INSERT INTO usuario(nome, email,senha,id_perfil) values(:nome,:email,:senha,:id_perfil)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':id_perfil', $id_perfil);

            if ($stmt->execute()) {
                echo "<script>alert('Usuário cadastrado com sucesso');</script>";
            } else {
                echo "<script>alert('Erro ao cadastrar usuário');</script>";


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

        function aplicarMascaraEmail(input) {
            input.value = input.value.toLowerCase().trim();
        }


        function validarUsuario() {
            let nome = document.getElementById("nome").value.trim();
            let email = document.getElementById("email").value.trim();

            if (nome.length < 3) {
                alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
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
    <title>Cadastrar Usuário</title>
</head>

<body>

    <h2>Cadastrar usuário</h2>
    <form action="cadastro_usuario.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name=" nome" id="nome" onsubmit="return validarUsuario()">

        <label for="email">Email:</label>
        <input type="email" name=" email" id="email" oninput="aplicarMascaraEmail(this)" required>

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <label for="id_perfil">Perfil:</label>
        <select name="id_perfil" id="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Secretária</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>

        </select>


        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>

    </form>

    <a href="principal.php" class="btn">Voltar</a>


</body>