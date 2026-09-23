<?php
session_start();

if (isset($_SESSION['utilizador_id'])) {
    header("Location: dashboard.php");
    exit;
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once "config/database.php";

    $utilizador = trim($_POST["utilizador"] ?? '');
    $senha = $_POST["senha"] ?? '';

    if (empty($utilizador) || empty($senha)) {

        $erro = "Preencha todos os campos.";
    } else {

        $sql = "SELECT id, nome, utilizador, senha
                FROM utilizadores
                WHERE UTILIZADOR = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $utilizador);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {

            $user = $resultado->fetch_assoc();

            if (password_verify($senha, $user["senha"])) {

                $_SESSION["utilizador_id"] = $user["id"];
                $_SESSION["nome"] = $user["nome"];
                $_SESSION["utilizador"] = $user["utilizador"];

                header("Location: dashboard.php");
                exit;
            } else {
                $erro = "Utilizador ou senha incorretos.";
            }
        } else {
            $erro = "Utilizador ou senha incorretos.";
        }

        $stmt->close();
        $conexao->close();
    }
}
?>


<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Barbearia | Login</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="login-body">

    <div class="login-container">

        <div class="login-logo">

            <span>✂</span>

            <h1>BARBEARIA</h1>

            <p>Estilo que marca presença</p>

        </div>


        <?php if (!empty($erro)): ?>

            <div class="login-error">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>


        <form method="POST" action="">

            <div class="input-group">

                <label for="utilizador">
                    Utilizador
                </label>

                <input
                    type="text"
                    id="utilizador"
                    name="utilizador"
                    placeholder="Digite o utilizador"
                    required>

            </div>


            <div class="input-group">

                <label for="senha">
                    Senha
                </label>

                <div class="password-box">

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite a senha"
                        required>

                    <button
                        type="button"
                        id="togglePassword">
                        👁
                    </button>

                </div>

            </div>


            <button
                type="submit"
                class="btn-primary">

                ENTRAR

            </button>

        </form>


        <p class="login-footer">
            © 2026 Barbearia
        </p>

    </div>


    <script src="js/script.js"></script>

</body>

</html>