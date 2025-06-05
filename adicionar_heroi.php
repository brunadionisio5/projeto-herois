<?php
require "config.php";
$universos = $pdo->query("SELECT * FROM universo")->fetchAll(PDO::FETCH_ASSOC);
$habilidades = $pdo->query("SELECT * FROM habilidades")->fetchAll(PDO::FETCH_ASSOC);
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
        <h3>Adicionar Herói</h3>
        <label>Nome do Herói:</label>
        <input type="text" name="nome" id="nome" required>
        <label>URL da Imagem:</label>
        <input type="text" name="imagem" id="imagem" required>
        <br>
        <label>Descrição:</label>
        <br>
        <textarea name="descricao" id="descricao" required></textarea>
        <br>
        <label>Raridade:</label>
        
        <select name="raridade" id="raridade" required>
            <option value="comum">Comum</option>
            <option value="incomum">Incomum</option>
            <option value="raro">Raro</option>
            <option value="epico">Épico</option>
            <option value="lendario">Lendário</option>
        </select>
        <br>
        <label>Tipo:</label>
        <select name="tipo" id="tipo" required>
            <option value="heroi">Herói</option>
            <option value="antiheroi">Anti-herói</option>
            <option value="vilao">Vilão</option>
        </select>
        <br>
        <label>Universo:</label>
        <select name="universo_id" id="universo_id">
            <?php foreach ($universos as $u): ?>
                <option value="<?= $u['id'] ?>"><?= $u['nome'] ?></option>
            <?php endforeach; ?>
        </select>
        
        <br><br>
        <label>Habilidades:</label>
        <select id="selectHabilidade">
            <option value="">Selecione uma habilidade</option>
            <?php foreach ($habilidades as $h): ?>
                <option value="<?= $h['id'] ?>"><?= $h['nome'] ?></option>
            <?php endforeach; ?>
        </select>

        <label>Nível:</label>
        <input type="number" id="selectNivel" min="1" max="100">

        <button type="button" id="btnAddHabilidade">Adicionar Habilidade</button>

        <ul id="listaHabilidades"></ul>


 
        <br><br>
        <fieldset>
            <legend>Atributos (0 a 100)</legend>

            <label>Força:</label>
            <input type="number" name="forca" min="0" max="100" id="forca" required>

            <label>Velocidade:</label>
            <input type="number" name="velocidade" min="0" max="100" id="velocidade" required>

            <label>Inteligência:</label>
            <input type="number" name="inteligencia" min="0" max="100" id="inteligencia" required>

            <label>Vitalidade:</label>
            <input type="number" name="vitalidade" min="0" max="100" id="vitalidade" required>

            <label>Resistência:</label>
            <input type="number" name="resistencia" min="0" max="100" id="resistencia" required>
        </fieldset>

        <br>
        <button class="btnAdicionar" type="button">Adicionar Herói</button>
    </div>

    <script>
    $(document).ready(function() {

        let habilidadesSelecionadas = [];

        $("#btnAddHabilidade").click(function () {
            let id = $("#selectHabilidade").val();
            let nome = $("#selectHabilidade option:selected").text();
            let nivel = $("#selectNivel").val();

            if (!id || habilidadesSelecionadas.some(h => h.id === id)) {
                alert("Escolha uma habilidade válida que ainda não foi adicionada.");
                return;
            }

            habilidadesSelecionadas.push({ id, nivel });

            $("#listaHabilidades").append(`
                <li data-id="${id}">
                    ${nome} - Nível ${nivel}
                    <button type="button" class="removerHabilidade">Remover</button>
                </li>
            `);
        });

        $("#listaHabilidades").on("click", ".removerHabilidade", function () {
            let li = $(this).closest("li");
            let id = li.data("id");

            habilidadesSelecionadas = habilidadesSelecionadas.filter(h => h.id != id);
            li.remove();
        });


        $(".btnAdicionar").click(function() {
            var nome = $("#nome").val();
            var imagem = $("#imagem").val();
            var descricao = $("#descricao").val();
            var raridade = $("#raridade").val();
            var tipo = $("#tipo").val();
            var universo_id = $("#universo_id").val();
            var forca = $("#forca").val();
            var velocidade = $("#velocidade").val();
            var inteligencia = $("#inteligencia").val();
            var vitalidade = $("#vitalidade").val();
            var resistencia = $("#resistencia").val();

            if (
                !nome || !imagem || !descricao || !raridade || !tipo ||
                forca === "" || velocidade === "" || inteligencia === "" ||
                vitalidade === "" || resistencia === ""
            ) {
                $("#mensagem").text("⚠️ Preencha todos os campos!");
                return;
            }

            $.post("add.php", {
                nome: nome,
                imagem: imagem,
                descricao: descricao,
                raridade: raridade,
                tipo: tipo,
                universo_id: universo_id,
                habilidades: JSON.stringify(habilidadesSelecionadas),
                forca: forca,
                velocidade: velocidade,
                inteligencia: inteligencia,
                vitalidade: vitalidade,
                resistencia: resistencia
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
