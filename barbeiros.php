<?php

session_start();

if (!isset($_SESSION["utilizador_id"])) {
    header("Location: index.php");
    exit;
}

require_once "config/database.php";

$mensagem = "";
$tipoMensagem = "";

// CADASTRAR BARBEIRO

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $especialidade = trim($_POST["especialidade"]);

    if (empty($nome)) {

        $mensagem = "O nome do barbeiro é obrigatório.";
        $tipoMensagem = "erro 400";
    } else {

        $sql = "INSERT INTO barbeiros
                    (nome, telefone, especialidade)
                    VALUES (?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param(
            "sss",
            $nome,
            $telefone,
            $especialidade
        );

        if ($stmt->execute()) {

            $mensagem = "201 - Barbeiro cadastrado com sucesso.";
            $tipoMensagem = "sucesso 201";
        } else {

            $mensagem = "Erro ao cadastrar barbeiro";
            $tipoMensagem = "erro 500";
        }

        $stmt->close();
    }
}

// LISTAR BARBEIROS

$sql = "SELECT *
            FROM barbeiros
            ORDER BY id DESC";

$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbeiros | Barbearia</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="dashboard-body"> <!-- MENU LATERAL -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div>✂</div>
            <h2>BARBEARIA</h2>
        </div>
        <nav class="sidebar-menu"> <a href="dashboard.php"> 🏠 Dashboard </a> <a href="clientes.php"> 👥 Clientes </a> <a href="agendamentos.php"> 📅 Agendamentos </a> <a href="servicos.php"> ✂ Serviços </a> <a href="barbeiros.php" class="active"> 👨‍💼 Barbeiros </a> </nav> <a href="logout.php" class="logout"> 🚪 Sair </a>
    </aside> <!-- CONTEÚDO -->
    <main class="dashboard-main">
        <header class="dashboard-header">
            <div>
                <h1>Barbeiros</h1>
                <p> Gerencie os profissionais da barbearia. </p>
            </div>
            <div class="admin-profile"> 👤 <?= htmlspecialchars($_SESSION["nome"]) ?> </div>
        </header> <!-- MENSAGEM --> <?php if (!empty($mensagem)): ?> <div class="<?= $tipoMensagem === "sucesso" ? "alert-success" : "alert-error" ?>"> <?= htmlspecialchars($mensagem) ?> </div> <?php endif; ?> <!-- FORMULÁRIO -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2> Novo Barbeiro </h2>
            </div>
            <form method="POST" class="client-form">
                <div class="form-group"> <label> Nome completo </label> <input type="text" name="nome" placeholder="Ex: Carlos Manuel" required> </div>
                <div class="form-group"> <label> Telefone </label> <input type="text" name="telefone" placeholder="Ex: 923 000 000"> </div>
                <div class="form-group"> <label> Especialidade </label> <input type="text" name="especialidade" placeholder="Ex: Corte moderno"> </div> <button type="submit" class="btn-primary"> + Cadastrar Barbeiro </button>
            </form>
        </section> <!-- LISTA -->
        <section class="dashboard-section">
            <div class="section-header">
                <h2> Barbeiros cadastrados </h2> <span class="client-count"> Total: <?= $resultado->num_rows ?> </span>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Especialidade</th>
                        </tr>
                    </thead>
                    <tbody> <?php if ($resultado->num_rows > 0): ?> <?php while ($barbeiro = $resultado->fetch_assoc()): ?> <tr>
                                    <td> <?= $barbeiro["id"] ?> </td>
                                    <td> <?= htmlspecialchars($barbeiro["nome"]) ?> </td>
                                    <td> <?= htmlspecialchars($barbeiro["telefone"]) ?> </td>
                                    <td> <?= htmlspecialchars($barbeiro["especialidade"]) ?> </td>
                                </tr> <?php endwhile; ?> <?php else: ?> <tr>
                                <td colspan="4" class="empty"> Nenhum barbeiro cadastrado. </td>
                            </tr> <?php endif; ?> </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>