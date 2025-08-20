<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado');window.location.href='principal.php'</script>";
    exit();
}

// Inicializa a variavel para evitar erros
$usuarios = [];

// Se o formulario for enviado, busca o usuario pelo id ou nome

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    // Verifica se a busca é um número (id) ou UM nome
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM usuario where id_usuario = :busca ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);
        $stmt -> bindParam(':busca',$busca, pdo::PARAM_INT);

    } else {

        $sql = "SELECT  * FROM usuario WHERE nome LIKE  :busca_nome ORDER BY nome ASC";
        $stmt = $pdo->prepare($sql);

        //POSSIVEL ERRO (Verificar AQUI!!!!!!!!!!!!!!!!!!!!)

        $stmt -> bindValue(':busca_nome', "$busca%", pdo::PARAM_STR);


    }

} else {
    $sql = "SELECT * FROM  usuario ORDER BY nome ASC";
    $stmt = $pdo -> prepare($sql);

}

$stmt -> execute();
$usuarios = $stmt -> fetchAll($pdo::FETCH_ASSOC);


?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>

<body>
    <h2>Lista de usuários</h2>

    <!--Formulario para buscar usuarios-->
    <form action="buscar_usuario.php" method="post">
        <label for="busac">Digite o id ou nome(opcional):</label>
        <input type="text" id="busca" name="busca">

        <button type="submit">Pesquisar</button>


    </form>

    <?php if (!empty($usuarios)): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>NOME</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>

            </tr>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td <?= htmlspecialchars($usuario['id_usuario']) ?>></td>
                    <td <?= htmlspecialchars($usuario['nome']) ?>></td>
                    <td <?= htmlspecialchars($usuario['email']) ?>></td>
                    <td <?= htmlspecialchars($usuario['id_perfil']) ?>></td>
                    <td>

                        <a href="alterar_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>">Alterar</a>

                        <a href="alterar_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>"
                            onclick="return confirm('Tem certeza que deseja excluir esse usuário?')">Excluir</a>
                    </td>

                </tr>
            <?php endforeach ?>

        </table>

    <?php else: ?>
        <p>Nenhum usuário encontrado!</p>

    <?php endif; ?>

    <a href="principal.php">Voltar</a>
</body>

</html>