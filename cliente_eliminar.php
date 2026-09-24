<?php 

    session_start();

    if (!isset($_SESSION["utilizador_id"])) {

        header("Location: index.php");
        exit;
    }

    require_once "config/database.php";

    $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
    
    if ($id > 0) {

        $sql = "DELETE FROM clientes WHERE id = ?";

        $stmt = $conexao->prepare($sql);

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();

    }

    header("Location: clientes.php");
    exit;

?>    
