<?php

session_start();

if (!isset($_SESSION["utilizador_id"])) {
    header("Location: index.php");
    exit;
}

require_once "config/database.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {

    header("Location: clientes.php");
    exit;
}

// ============================== 
// ATUALIZAR CLIENTE 
// ============================== 

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if (!empty($nome)) {

        $sql = "UPDATE clientes 
            SET nome = ?, 
                telefone = ?,  
                email = ? 
            WHERE id = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param("sssi", $nome, $telefone, $email, $id);

        $stmt->execute();

        $stmt->close();

        header("Location: clientes.php");
        exit;
    }
}

// ============================== 
// BUSCAR CLIENTE 
// ============================== 

$sql = "SELECT * 
            FROM clientes 
            WHERE id = ?";

$stmt = $conexao->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {

    header("Location: clientes.php");
    exit;
}

$cliente = $resultado->fetch_assoc();

$stmt->close();
?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="dashboard-body">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div>✂</div>
            <h2>BARBEARIA</h2>
        </div>
        <nav class="sidebar-menu"> <a href="dashboard.php"> 🏠 Dashboard </a> <a href="clientes.php" class="active"> 👥 Clientes </a> <a href="agendamentos.php"> 📅 Agendamentos </a> <a href="servicos.php"> ✂ Serviços </a> <a href="barbeiros.php"> 👨‍💼 Barbeiros </a> </nav> <a href="logout.php" class="logout"> 🚪 Sair </a>
    </aside>
    <main class="dashboard-main">
        <header class="dashboard-header">
            <div>
                <h1>Editar Cliente</h1>
                <p> Alterar informações do cliente </p>
            </div>
        </header>
        <section class="dashboard-section">
            <form method="POST" class="edit-form">
                <div class="form-group"> <label> Nome completo </label> <input type="text" name="nome" value="<?= htmlspecialchars($cliente["nome"]) ?>" required> </div>
                <div class="form-group"> <label> Telefone </label> <input type="text" name="telefone" value="<?= htmlspecialchars($cliente["telefone"]) ?>"> </div>
                <div class="form-group"> <label> Email </label> <input type="email" name="email" value="<?= htmlspecialchars($cliente["email"]) ?>"> </div> <button type="submit" class="btn-primary"> Guardar Alterações </button> <a href="clientes.php" class="btn-cancel"> Cancelar </a>
            </form>
        </section>
    </main>
</body>

</html>