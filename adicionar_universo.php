<?php
require "config.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Add Herói</title>
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

    <div class="container">
        <h3>Adicionar Universo</h3>
        <label>Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <br>
        <label>URL da logo:</label>
        <input type="text" name="logo" id="logo" required>

        <br>
        <button class="btnAdicionar" type="button">Adicionar</button>
    </div>

    <script>
    $(document).ready(function() {
        $(".btnAdicionar").click(function() {
            var nome = $("#nome").val();
            var logo = $("#logo").val();

            if (!nome || !logo) {
                $("#mensagem").text("⚠️ Preencha todos os campos!");
                return;
            }

            $.post("add_universo.php", {
                nome: nome,
                logo: logo,
            }, function(resposta) {
                if (resposta.trim() === "sucesso") {
                    window.location.href = "home.php";
                } else {
                    $("#mensagem").text(resposta);
                }
            });
        });
    });
    </script>

</body>
</html>
