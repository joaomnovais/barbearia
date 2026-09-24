<?php

session_start();

if (!isset($_SESSION["utilizador_id"])) {
    header("Location: index.php");
    exit;
}

require_once "config/database.php";

$mensagem = "";
$tipoMensagem = "";

//CADASTRAR CLIENTE

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if (empty($nome)) {

        $mensagem = "O nome do cliente é obrigatório.";
        $tipoMensagem = "erro 404 - requisão incompleta";
    } else {

        $sql = "INSERT INTO clientes (nome, telefone, email)
                    VALUES (?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sss",
            $nome,
            $telefone,
            $email
        );

        if ($stmt->execute()) {

            $mensagem = "Cliente cadastrado com sucesso!";
            $tipoMensagem = "200 - sucesso";
        } else {

            $mensagem = "Erro ao cadastrar cliente.";
            $tipoMensagem = "erro";
        }

        $stmt->close();
    }
}

// ========================
// LISTAR CLIENTES
// ========================

$sql = "SELECT id, nome, telefone, email, data_cadastro
            FROM clientes
            ORDER BY id DESC";

$resultado = $conexao->query($sql);

?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Barbearia</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="dashboard-body"> <!-- MENU LATERAL -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div>✂</div>
            <h2>BARBEARIA</h2>
        </div>
        <nav class="sidebar-menu"> <a href="dashboard.php"> 🏠 Dashboard </a> <a href="clientes.php" class="active"> 👥 Clientes </a> <a href="agendamentos.php"> 📅 Agendamentos </a> <a href="servicos.php"> ✂ Serviços </a> <a href="barbeiros.php"> 👨‍💼 Barbeiros </a> </nav> <a href="logout.php" class="logout"> 🚪 Sair </a>
    </aside> <!-- CONTEÚDO -->
    <main class="dashboard-main">
        <header class="dashboard-header">
            <div>
                <h1>Clientes</h1>
                <p> Gestão dos clientes da barbearia </p>
            </div>
            <div class="admin-profile"> 👤 <?= htmlspecialchars($_SESSION["nome"]) ?> </div>
        </header> <!-- MENSAGEM --> <?php if (!empty($mensagem)): ?> <div class="<?= $tipoMensagem === "sucesso" ? "alert-success" : "alert-error" ?>"> <?= htmlspecialchars($mensagem) ?> </div> <?php endif; ?> <!-- FORMULÁRIO -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2> Novo Cliente </h2>
            </div>
            <form method="POST" class="client-form">
                <div class="form-group">
                     <label for="nome"> Nome completo </label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: João Manuel" required> 
                </div>
                <div class="form-group"> 
                    <label for="telefone"> Telefone </label> 
                    <input type="text" id="telefone" name="telefone" placeholder="Ex: 923 000 000" required> 
                </div>
                <div class="form-group"> 
                    <label for="email"> Email </label> 
                    <input type="email" id="email" name="email" placeholder="Ex: joao@email.com"> 
                </div> 
                <button type="submit" class="btn-primary"> + Cadastrar Cliente </button>
            </form>
        </section> <!-- LISTA DE CLIENTES -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2> Lista de Clientes </h2> <span class="client-count"> Total: <?= $resultado->num_rows ?> </span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody> <?php if ($resultado->num_rows > 0): ?> <?php while ($cliente = $resultado->fetch_assoc()): ?> <tr>
                                    <td> <?= $cliente["id"] ?> </td>
                                    <td> <?= htmlspecialchars($cliente["nome"]) ?> </td>
                                    <td> <?= htmlspecialchars($cliente["telefone"]) ?> </td>
                                    <td> <?= htmlspecialchars($cliente["email"]) ?> </td>
                                    <td> <?= date("d/m/Y", strtotime($cliente["data_cadastro"])) ?> </td>
                                    <td> <a href="cliente_editar.php?id=<?= $cliente["id"] ?>" class="btn-edit"> Editar </a> <a href="cliente_eliminar.php?id=<?= $cliente["id"] ?>" class="btn-delete" onclick="return confirm( 'Tem certeza que deseja eliminar este cliente?' );"> Eliminar </a> </td>
                                </tr> <?php endwhile; ?> <?php else: ?> <tr>
                                <td colspan="6" class="empty"> Nenhum cliente cadastrado. </td>
                            </tr> <?php endif; ?> </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>