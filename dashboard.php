<?php

    session_start();

    if (!isset($_SESSION["utilizador_id"])) {
        header("Location: index.php");
        exit;
    }

    require_once "config/database.php";

    $nome = $_SESSION["nome"];

    //Total de clientes
    $sqlClientes = "SELECT COUNT(*) AS total FROM clientes";
    $resultClientes = $conexao->query($sqlClientes);
    $totalClientes = $resultClientes->fetch_assoc()["total"];

    //Total de barbeiros
    $sqlBarbeiros = "SELECT COUNT(*) AS total FROM barbeiros";
    $resultBarbeiros = $conexao->query($sqlBarbeiros);
    $totalBarbeiros = $resultBarbeiros->fetch_assoc()["total"];

    // Total de serviços
    $sqlServicos = "SELECT COUNT(*) AS total FROM servicos";
    $resultServicos = $conexao->query($sqlServicos);
    $totalServicos = $resultServicos->fetch_assoc()["total"];


    // Total de agendamentos
    $sqlAgendamentos = "SELECT COUNT(*) AS total FROM agendamentos";
    $resultAgendamentos = $conexao->query($sqlAgendamentos);
    $totalAgendamentos = $resultAgendamentos->fetch_assoc()["total"];

?>


<!DOCTYPE html>

<html lang="pt">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Barbearia</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>


<body class="dashboard-body">


    <aside class="sidebar">

        <div class="sidebar-logo">

            <div>✂</div>

            <h2>BARBEARIA</h2>

        </div>


        <nav class="sidebar-menu">

            <a href="dashboard.php"
               class="active">

                🏠 Dashboard

            </a>

            <a href="clientes.php">

                👥 Clientes

            </a>

            <a href="agendamentos.php">

                📅 Agendamentos

            </a>

            <a href="servicos.php">

                ✂ Serviços

            </a>

            <a href="barbeiros.php">

                👨‍💼 Barbeiros

            </a>

        </nav>


        <a href="logout.php"
           class="logout">

            🚪 Sair

        </a>

    </aside>


    <main class="dashboard-main">


        <header class="dashboard-header">

            <div>

                <h1>Dashboard</h1>

                <p>
                    Bem-vindo,
                    <strong>
                        <?= htmlspecialchars($nome) ?>
                    </strong>
                </p>

            </div>


            <div class="admin-profile">

                👤 <?= htmlspecialchars($nome) ?>

            </div>

        </header>


        <section class="stats">


            <div class="stat-card">

                <span>👥</span>

                <div>

                    <p>Clientes</p>

                    <h2>
                        <?= $totalClientes ?>
                    </h2>

                </div>

            </div>


            <div class="stat-card">

                <span>📅</span>

                <div>

                    <p>Agendamentos</p>

                    <h2>
                        <?= $totalAgendamentos ?>
                    </h2>

                </div>

            </div>


            <div class="stat-card">

                <span>✂</span>

                <div>

                    <p>Serviços</p>

                    <h2>
                        <?= $totalServicos ?>
                    </h2>

                </div>

            </div>


            <div class="stat-card">

                <span>👨‍💼</span>

                <div>

                    <p>Barbeiros</p>

                    <h2>
                        <?= $totalBarbeiros ?>
                    </h2>

                </div>

            </div>


        </section>


        <section class="dashboard-section">

            <div class="section-header">

                <h2>
                    Sistema da Barbearia
                </h2>

            </div>


            <p style="color:#aaa; line-height:1.8;">

                Utilize o menu lateral para administrar
                os clientes, agendamentos, serviços e
                barbeiros da sua barbearia.

            </p>

        </section>


    </main>


</body>

</html>