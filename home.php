<?php
require "config.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Heróis</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <div class="top-container">
        <div class="nav-links">
            <a href="home.php">Heróis</a>
            <a href="adicionar_heroi.php">Adicionar Herói</a>
            <a href="adicionar_universo.php">Adicionar Universo</a>
        </div>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>   

    <div class="cards-container">
<?php
    $stmt = $pdo->query("SELECT * FROM heroi");
    $stmt = $pdo->query("SELECT heroi.*, universo.nome AS nome_universo, universo.logo AS logo_universo FROM heroi LEFT JOIN universo ON heroi.universo_id = universo.id");
    while ($heroi = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "
        <div class='card' data-id='{$heroi['id']}'>
            <img src='img/{$heroi['logo_universo']}' alt='{$heroi['nome_universo']}' class='logo-universo'>
    
            <h3>{$heroi['nome']}</h3>
            <img src='img/{$heroi['imagem']}' class='card-img'><br>
    
            <strong>Raridade:</strong> {$heroi['raridade']}<br>
            <strong>Tipo:</strong> {$heroi['tipo']}<br>
            <p><strong>Descrição:</strong> {$heroi['descricao']}</p>
            
            <strong>Força: </strong>{$heroi['forca']}<br>
            <strong>Velocidade: </strong>{$heroi['velocidade']}<br>
            <strong>Inteligência: </strong>{$heroi['inteligencia']}<br>
            <strong>Vitalidade: </strong>{$heroi['vitalidade']}<br>
            <strong>Resistência: </strong>{$heroi['resistencia']}<br><br>
    
            <button class='btn_editar' data-id='{$heroi['id']}'>Editar</button>
            <button class='btn_excluir' data-id='{$heroi['id']}'>Excluir</button>
        </div>
        ";
    }
?>

<script>
$(document).ready(function() {
    $(".btn_excluir").click(function() {
        var id = $(this).data("id");

        if (confirm("Tem certeza que deseja excluir este heroi?")) {
            $.post("excluir.php", { id: id }, function(resposta) {
                if (resposta === "sucesso") {
                    $("#heroi" + id).remove();
                } else {
                    alert("Erro ao excluir o heroi.");
                }
            });
        }
    });
});
</script>

</body>
</html>