<?php
session_start();
require_once 'conexao.php';
require_once 'includes/cabecalho.php';

if ($_SESSION['perfil'] != 1 ) {
    echo "<script>alert('Acesso negado. Você não tem permissão para acessar esta página.'); window.location.href='principal.php';</script>";
    exit();
}

// Inicializa a variavel para evitar erros
$usuarios = [];

// Se o formulario for enviado, busca o usuario pelo id ou nome
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
    $busca = trim($_POST['busca']);

    // Verifica se a busca é um número (ID) ou um nome
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_usuario = :busca ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":busca", $busca, PDO::PARAM_INT); // Busca por ID
    } else {
        $sql = "SELECT * FROM funcionario WHERE nome_funcionario LIKE :busca_nome ORDER BY nome_funcionario ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":busca_nome", "$busca%", PDO::PARAM_STR); // Busca por nome
        $busca = "$busca%"; // Para busca por nome
    }
} else {
    // Busca todos os funcionarios se o formulario não for enviado
    $sql = "SELECT * FROM funcionario ORDER BY nome_funcionario ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Funcionários</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Lista de Funcionários</h2>
    <!-- Formulário de busca -->
    <form action="buscar_funcionario.php" method="post">
        <label for="busca">Digite o ID ou Nome(opcional):</label>
        <input type="text" id="busca" name="busca" required>
        <button type="submit">Buscar</button>
    </form>

    <?php if (!empty($usuarios)): ?>
        <div class="lista-usuarios">
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id_funcionario']) ?></td>
                        <td><?= htmlspecialchars($usuario['nome_funcionario']) ?></td>
                        <td><?= htmlspecialchars($usuario['endereco']) ?></td>
                        <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td>
                            <a href="alterar_usuario.php?id=<?= htmlspecialchars($usuario['id_funcionario']) ?>">Alterar</a>
                            <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_funcionario']) ?>"
                                onclick="return confirm('Tem certeza que deseja excluir este usuario?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

        <?php else: ?>
            <p>Nenhum funcionario encontrado.</p>
        <?php endif; ?>
    </div>
    <a href="principal.php" class="btn">Voltar</a>
</body>

</html>