<?php
require "config.php";

// Verifica se os dados necessários foram enviados
if (
    !isset($_POST['id'], $_POST['nome'], $_POST['imagem'], $_POST['descricao'], $_POST['raridade'],
    $_POST['tipo'], $_POST['universo_id'], $_POST['forca'], $_POST['velocidade'],
    $_POST['inteligencia'], $_POST['vitalidade'], $_POST['resistencia'], $_POST['habilidades'])
) {
    echo "Dados incompletos.";
    exit;
}

try {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $imagem = $_POST['imagem'];
    $descricao = $_POST['descricao'];
    $raridade = $_POST['raridade'];
    $tipo = $_POST['tipo'];
    $universo_id = $_POST['universo_id'];
    $forca = $_POST['forca'];
    $velocidade = $_POST['velocidade'];
    $inteligencia = $_POST['inteligencia'];
    $vitalidade = $_POST['vitalidade'];
    $resistencia = $_POST['resistencia'];
    $habilidades = json_decode($_POST['habilidades'], true);

    // Iniciar transação
    $pdo->beginTransaction();

    // Atualizar herói
    $sql = "UPDATE heroi SET nome = ?, imagem = ?, descricao = ?, raridade = ?, tipo = ?, universo_id = ?, 
            forca = ?, velocidade = ?, inteligencia = ?, vitalidade = ?, resistencia = ?
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $nome, $imagem, $descricao, $raridade, $tipo, $universo_id,
        $forca, $velocidade, $inteligencia, $vitalidade, $resistencia, $id
    ]);

    // Limpar habilidades anteriores
    $stmt = $pdo->prepare("DELETE FROM heroi_habilidade WHERE heroi_id = ?");
    $stmt->execute([$id]);

    // Inserir novas habilidades
    if (!empty($habilidades)) {
        $stmt = $pdo->prepare("INSERT INTO heroi_habilidade (heroi_id, habilidade_id, nivel) VALUES (?, ?, ?)");
        foreach ($habilidades as $hab) {
            $stmt->execute([$id, $hab['id'], $hab['nivel']]);
        }
    }

    $pdo->commit();
    echo "sucesso";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao atualizar herói: " . $e->getMessage();
}
