<?php
require "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $imagem = $_POST['imagem'];
    $descricao = $_POST['descricao'];
    $raridade = $_POST['raridade'];
    $tipo = $_POST['tipo'];
    $universo_id = !empty($_POST['universo_id']) ? $_POST['universo_id'] : null;
    $habilidades = json_decode($_POST['habilidades'], true);
    $forca = $_POST['forca'];
    $velocidade = $_POST['velocidade'];
    $inteligencia = $_POST['inteligencia'];
    $vitalidade = $_POST['vitalidade'];
    $resistencia = $_POST['resistencia'];

    try {
        $stmt = $pdo->prepare("
            INSERT INTO heroi (
                nome, imagem, descricao, raridade, tipo, universo_id,
                forca, velocidade, inteligencia, vitalidade, resistencia
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $nome, $imagem, $descricao, $raridade, $tipo, $universo_id,
            $forca, $velocidade, $inteligencia, $vitalidade, $resistencia
        ]);

        $heroi_id = $pdo->lastInsertId();


        if (!empty($habilidades)) {
            foreach ($habilidades as $hab) {
                $stmt = $pdo->prepare("INSERT INTO heroi_habilidade (heroi_id, habilidade_id, nivel) VALUES (?, ?, ?)");
                $stmt->execute([$heroi_id, $hab['id'], $hab['nivel']]);
            }
        }        

        echo "sucesso";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
