<?php
session_start();

require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO DE adm
if ($_SESSION['perfil'] != 1) {
    echo "<script> alert('Acesso Negado!'); window.location.href=principal.php; </script>";
    exit();
}

// INCIALIZA AS VARIAVEIS
$usuario = null;

// SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO id OU PELO nome
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['busca_usuario'])) {
        $busca = trim($_POST['busca_usuario']);

        // VERIFICA SE A BUSCA É UM id OU UM nome
        if (is_numeric($busca)) {
            $query = "SELECT * FROM usuario WHERE id_usuario = :busca";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";

            $stmt = $pdo->prepare($query);
            $stmt->bindValue(":busca_nome", $busca, PDO::PARAM_STR);
        }

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O USUARIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA
        if (!$usuario) {
            echo "<script> alert('Usuário não encontrado!'); </script>";
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
    <title>Alterar usuario</title>
    <!-- Certifique-se de que o JavaScript está sendo carregando  corretamente -->
</head>

<body>
    <h2>Alterar usuários</h2>

    <!--Formulario para buscar usuarios-->
    <form action="alterar_usuario.php" method="post">
        <label for="busca_usuario">Digite o id ou nome do usuario:</label>
        <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">

        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>

    </form>

    <?php if ($usuario): ?>
        <form action="processo_alteracao_usuario.php" method="post">:

            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">:

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<? htmlspecialchars($usuario['nome']) ?>" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<? htmlspecialchars($usuario['email']) ?>" required>

            <label for="id_perfil">Perfil:</label>
            <input type="text" id="id_perfil" name="id_perfil" value="<? htmlspecialchars($usuario['id_perfil']) ?>"
                required>

            <label for="id_perfil">Perfil:</label>
            <select name="id_Perfil" id="id_Perfil">
                <option value="1" <? $usuario['id_perfil'] == 1 ? 'selected' : '' ?>>Administrador</option>
                <option value="2" <? $usuario['id_perfil'] == 1 ? 'selected' : '' ?>>Secretária</option>
                <option value="3" <? $usuario['id_perfil'] == 1 ? 'selected' : '' ?>>Almoxarife</option>
                <option value="4" <? $usuario['id_perfil'] == 1 ? 'selected' : '' ?>>Cliente</option>

            </select>
            <!-- Se o usuário logado for ADM, exibir opção de alterar senha -->

            <?php if ($_SESSION['perfil'] == 1): ?>
                <label for="nova_senha">Nova senha</label>
                <input type="password" id="nova_senha" name="nova_senha">
            <?php endif ?>
            :
            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>

        </form>

    <?php endif ?>
    <a href="principl.php">Voltar</a>

</body>

</html>