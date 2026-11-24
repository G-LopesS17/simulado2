<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$id = intval($_GET['id']);
$erro = "";
$ag = $conn->query("SELECT * FROM agendamento WHERE id_agendamento = $id")->fetch_assoc();
$tutores = $conn->query("SELECT id_tutor, nome FROM tutor ORDER BY nome");
$pets = $conn->query("SELECT p.id_pet, p.nome, t.nome AS dono FROM pet p INNER JOIN tutor t ON p.id_tutor = t.id_tutor ORDER BY p.nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $id_tutor = intval($_POST['id_tutor']);
    $id_pet = intval($_POST['id_pet']);

    $check = $conn->prepare("SELECT id_pet FROM pet WHERE id_pet = ? AND id_tutor = ?");
    $check->bind_param("ii", $id_pet, $id_tutor);
    $check->execute();

    if ($check->get_result()->num_rows === 0) {
        $erro = "Erro: O pet escolhido não pertence ao tutor selecionado!";
    } else {
        $stmt = $conn->prepare("UPDATE agendamento SET data = ?, id_tutor = ?, id_pet = ? WHERE id_agendamento = ?");
        $stmt->bind_param("siii", $data, $id_tutor, $id_pet, $id);

        try {
            $stmt->execute();
            header("Location: index.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            $erro = "Erro ao atualizar: Data já ocupada.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Agendamento</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Editar Agendamento</h2>
        <?php if ($erro): ?><div class="alerta-erro"><?= $erro ?></div><?php endif; ?>

        <form method="POST">
            <label>Data:</label>
            <input type="date" name="data" value="<?= $ag['data'] ?>" required>

            <label>Tutor:</label>
            <select name="id_tutor" required>
                <?php while ($t = $tutores->fetch_assoc()): ?>
                    <option value="<?= $t['id_tutor'] ?>" <?= ($t['id_tutor'] == $ag['id_tutor']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Pet:</label>
            <select name="id_pet" required>
                <?php while ($p = $pets->fetch_assoc()): ?>
                    <option value="<?= $p['id_pet'] ?>" <?= ($p['id_pet'] == $ag['id_pet']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nome']) ?> (Tutor: <?= htmlspecialchars($p['dono']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Atualizar</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>
