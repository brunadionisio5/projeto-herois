<?php
session_start();
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $stmt = $pdo->prepare("SELECT id, senha_hash FROM usuarios WHERE usuario = :usuario");
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && hash("sha256", $senha) === $user["senha_hash"]) {
        $_SESSION["usuario"] = $usuario;
        echo "sucesso";
    } else {
        echo "Usuário ou senha incorretos!";
    }
}
?>