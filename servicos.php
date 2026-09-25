<?php

session_start();

if (!isset($_SESSION["utilizador_id"])) {
    header("Location: index.php");
    exit;
}

require_once "config/database.php";

$mensagem = "";
$tipoMensagem = "";

// CADASTRAR SERVIÇO

if ($_SERVER["REQUEST_METHOD"] === "post") {

    $nome = trim($_POST["nome"]);
    $preco = trim($_POST["preco"]);
    $duracao = trim($_POST["duracao"]);

    if (empty($nome) || empty($preco) || $duracao <= 0) {

        $mensagem = "Preencha todos os campos corretamente.";
        $tipoMensagem = "erro";
    } else {

        $sql = "INSERT INTO servicos (nome, preco, duracao)
                    VALUES (?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $preco = floatval($preco);

        $stmt->bind_param(
            "sid",
            $nome,
            $preco,
            $duracao
        );

        if ($stmt->execute()) {

            $mensagem = "Serviço cadastrado com sucesso";
            $tipoMensagem = "sucesso";
        } else {

            $mensagem = "Erro ao cadastrar serviço";
            $tipoMensagem = "erro";
        }

        $stmt->close();
    }
}

// LISTAR SERVIÇOS

$sql = "SELECT * FROM servicos
            ORDER BY id DESC";

$resultado = $conexao->query($sql);

?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços | Barbearia</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="dashboard-body"> <!-- MENU -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div>✂</div>
            <h2>BARBEARIA</h2>
        </div>
        <nav class="sidebar-menu"> <a href="dashboard.php"> 🏠 Dashboard </a> <a href="clientes.php"> 👥 Clientes </a> <a href="agendamentos.php"> 📅 Agendamentos </a> <a href="servicos.php" class="active"> ✂ Serviços </a> <a href="barbeiros.php"> 👨‍💼 Barbeiros </a> </nav> <a href="logout.php" class="logout"> 🚪 Sair </a>
    </aside> <!-- CONTEÚDO -->
    <main class="dashboard-main">
        <header class="dashboard-header">
            <div>
                <h1>Serviços</h1>
                <p> Gerencie os serviços oferecidos pela barbearia. </p>
            </div>
            <div class="admin-profile"> 👤 <?= htmlspecialchars($_SESSION["nome"]) ?> </div>
        </header> <!-- MENSAGEM --> <?php if (!empty($mensagem)): ?> <div class="<?= $tipoMensagem === "sucesso" ? "alert-success" : "alert-error" ?>"> <?= htmlspecialchars($mensagem) ?> </div> <?php endif; ?> <!-- CADASTRO -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2> Novo Serviço </h2>
            </div>
            <form method="POST" class="client-form">
                <div class="form-group"> <label> Nome do serviço </label> <input type="text" name="nome" placeholder="Ex: Corte de cabelo" required> </div>
                <div class="form-group"> <label> Preço (Kz) </label> <input type="number" name="preco" step="0.01" min="0" placeholder="5000" required> </div>
                <div class="form-group"> <label> Duração (minutos) </label> <input type="number" name="duracao" min="1" placeholder="30" required> </div> <button type="submit" class="btn-primary"> + Cadastrar Serviço </button>
            </form>
        </section> <!-- LISTAGEM -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2> Serviços cadastrados </h2> <span class="client-count"> Total: <?= $resultado->num_rows ?> </span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Serviço</th>
                            <th>Preço</th>
                            <th>Duração</th>
                        </tr>
                    </thead>
                    <tbody> <?php if ($resultado->num_rows > 0): ?> <?php while ($servico = $resultado->fetch_assoc()): ?> <tr>
                                    <td> <?= $servico["id"] ?> </td>
                                    <td> <?= htmlspecialchars($servico["nome"]) ?> </td>
                                    <td> <?= number_format($servico["preco"], 2, ",", ".") ?> Kz </td>
                                    <td> <?= $servico["duracao"] ?> minutos </td>
                                </tr> <?php endwhile; ?> <?php else: ?> <tr>
                                <td colspan="4" class="empty"> Nenhum serviço cadastrado. </td>
                            </tr> <?php endif; ?> </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>