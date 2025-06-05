<?php
require "config.php";

if (!isset($_GET['id'])) {
    echo "Herói não especificado.";
    exit;
}

$heroi_id = $_GET['id'];

$heroi = $pdo->prepare("SELECT * FROM heroi WHERE id = ?");
$heroi->execute([$heroi_id]);
$heroi = $heroi->fetch(PDO::FETCH_ASSOC);

if (!$heroi) {
    echo "Herói não encontrado.";
    exit;
}

$universos = $pdo->query("SELECT * FROM universo")->fetchAll(PDO::FETCH_ASSOC);
$habilidades = $pdo->query("SELECT * FROM habilidades")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT hh.habilidade_id, h.nome, hh.nivel FROM heroi_habilidade hh JOIN habilidades h ON hh.habilidade_id = h.id WHERE hh.heroi_id = ?");
$stmt->execute([$heroi_id]);
$heroi_habilidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Herói</title>
    <link rel="stylesheet" href="style.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <h3>Editar Herói</h3>

    <input type="hidden" id="heroi_id" value="<?= $heroi_id ?>">

    <label>Nome:</label>
    <input type="text" id="nome" value="<?= htmlspecialchars($heroi['nome']) ?>">

    <label>Imagem (URL):</label>
    <input type="text" id="imagem" value="<?= htmlspecialchars($heroi['imagem']) ?>">

    <label>Descrição:</label>
    <textarea id="descricao"><?= htmlspecialchars($heroi['descricao']) ?></textarea>

    <label>Raridade:</label>
    <select id="raridade">
        <?php
        $raridades = ["comum", "incomum", "raro", "epico", "lendario"];
        foreach ($raridades as $r) {
            $selected = $heroi['raridade'] === $r ? 'selected' : '';
            echo "<option value='$r' $selected>" . ucfirst($r) . "</option>";
        }
        ?>
    </select>

    <label>Tipo:</label>
    <select id="tipo">
        <?php
        $tipos = ["heroi", "antiheroi", "vilao"];
        foreach ($tipos as $t) {
            $selected = $heroi['tipo'] === $t ? 'selected' : '';
            echo "<option value='$t' $selected>" . ucfirst($t) . "</option>";
        }
        ?>
    </select>

    <label>Universo:</label>
    <select id="universo_id">
        <?php foreach ($universos as $u): ?>
            <option value="<?= $u['id'] ?>" <?= $u['id'] == $heroi['universo_id'] ? 'selected' : '' ?>>
                <?= $u['nome'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <fieldset>
        <legend>Atributos (0 a 100)</legend>

        <label>Força:</label>
        <input type="number" id="forca" min="0" max="100" value="<?= $heroi['forca'] ?>">

        <label>Velocidade:</label>
        <input type="number" id="velocidade" min="0" max="100" value="<?= $heroi['velocidade'] ?>">

        <label>Inteligência:</label>
        <input type="number" id="inteligencia" min="0" max="100" value="<?= $heroi['inteligencia'] ?>">

        <label>Vitalidade:</label>
        <input type="number" id="vitalidade" min="0" max="100" value="<?= $heroi['vitalidade'] ?>">

        <label>Resistência:</label>
        <input type="number" id="resistencia" min="0" max="100" value="<?= $heroi['resistencia'] ?>">
    </fieldset>

    <br>
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

    <ul id="listaHabilidades">
        <?php foreach ($heroi_habilidades as $hh): ?>
            <li data-id="<?= $hh['habilidade_id'] ?>">
                <?= $hh['nome'] ?> - Nível <?= $hh['nivel'] ?>
                <button type="button" class="removerHabilidade">Remover</button>
            </li>
        <?php endforeach; ?>
    </ul>

    <br>
    <button id="btnSalvar">Salvar Alterações</button>
    <div id="mensagem"></div>
</div>

<script>
    let habilidadesSelecionadas = <?= json_encode(array_map(function($h) {
        return ['id' => $h['habilidade_id'], 'nivel' => $h['nivel']];
    }, $heroi_habilidades)) ?>;

    $("#btnAddHabilidade").click(function () {
        let id = $("#selectHabilidade").val();
        let nome = $("#selectHabilidade option:selected").text();
        let nivel = $("#selectNivel").val();

        if (!id || habilidadesSelecionadas.some(h => h.id == id)) {
            alert("Habilidade já adicionada ou inválida.");
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

    $("#btnSalvar").click(function () {
        let dados = {
            id: $("#heroi_id").val(),
            nome: $("#nome").val(),
            imagem: $("#imagem").val(),
            descricao: $("#descricao").val(),
            raridade: $("#raridade").val(),
            tipo: $("#tipo").val(),
            universo_id: $("#universo_id").val(),
            forca: $("#forca").val(),
            velocidade: $("#velocidade").val(),
            inteligencia: $("#inteligencia").val(),
            vitalidade: $("#vitalidade").val(),
            resistencia: $("#resistencia").val(),
            habilidades: JSON.stringify(habilidadesSelecionadas)
        };

        $.post("add_edicao_heroi.php", dados, function (resposta) {
            if (resposta.trim() === "sucesso") {
                window.location.href = "home.php";
            } else {
                $("#mensagem").text(resposta);
            }
        });
    });
</script>

</body>
</html>