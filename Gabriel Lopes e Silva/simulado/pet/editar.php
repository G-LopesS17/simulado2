<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$id = intval($_GET['id']);
$erro = "";
$pet = $conn->query("SELECT * FROM pet WHERE id_pet = $id")->fetch_assoc();
$tutores = $conn->query("SELECT id_tutor, nome FROM tutor ORDER BY nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $especie = trim($_POST['especie']);
    $id_tutor = intval($_POST['id_tutor']);

    $stmt = $conn->prepare("UPDATE pet SET nome = ?, especie = ?, id_tutor = ? WHERE id_pet = ?");
    $stmt->bind_param("ssii", $nome, $especie, $id_tutor, $id);

    try {
        $stmt->execute();
        header("Location: index.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        $erro = "Erro: Este tutor já possui outro pet cadastrado com este nome e espécie.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Pet</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Editar Pet</h2>
        <?php if (!empty($erro)): ?><div class="alerta-erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

        <form method="POST">
            <label>Nome do Pet:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($pet['nome']) ?>" required>

            <label>Espécie:</label>
            <input type="text" name="especie" value="<?= htmlspecialchars($pet['especie']) ?>" required>

            <label>Tutor Responsável:</label>
            <select name="id_tutor" required>
                <?php while ($t = $tutores->fetch_assoc()): ?>
                    <option value="<?= $t['id_tutor'] ?>" <?= ($t['id_tutor'] == $pet['id_tutor']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Atualizar</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>
