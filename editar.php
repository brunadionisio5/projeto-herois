<?php
require "config.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $pdo->prepare("SELECT * FROM heroi WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $heroi = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($heroi);
} else {
    echo json_encode(["erro" => "ID não fornecido"]);
}
?>