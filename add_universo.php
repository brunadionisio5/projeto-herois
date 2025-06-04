<?php
require "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $logo = $_POST['logo'];

    try {
        $stmt = $pdo->prepare("INSERT INTO universo (nome, logo) VALUES (?, ?)");
        $stmt->execute([$nome, $logo]);

        echo "sucesso";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>