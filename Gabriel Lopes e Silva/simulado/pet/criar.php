<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$erro = "";
$tutores = $conn->query("SELECT id_tutor, nome FROM tutor ORDER BY nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $especie = trim($_POST['especie']);
    $id_tutor = intval($_POST['id_tutor']);

    $stmt = $conn->prepare("INSERT INTO pet (nome, especie, id_tutor) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nome, $especie, $id_tutor);

    try {
        $stmt->execute();
        header("Location: index.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        $erro = "Erro: Este tutor já possui um pet cadastrado com este mesmo nome e espécie.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Pet</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Pet</h2>
        <?php if ($erro): ?><div class="alerta-erro"><?= $erro ?></div><?php endif; ?>

        <form method="POST">
            <label>Nome do Pet:</label>
            <input type="text" name="nome" required>

            <label>Espécie:</label>
            <input type="text" name="especie" placeholder="Ex: Cachorro, Gato, Jabuti..." required>

            <label>Tutor Responsável:</label>
            <select name="id_tutor" required>
                <option value="">Selecione um tutor...</option>
                <?php while ($t = $tutores->fetch_assoc()): ?>
                    <option value="<?= $t['id_tutor'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Salvar</button>
            <a href="index.php" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
