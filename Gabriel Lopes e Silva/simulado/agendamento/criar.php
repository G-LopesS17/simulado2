<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$erro = "";
$tutores = $conn->query("SELECT id_tutor, nome FROM tutor ORDER BY nome");
$pets = $conn->query("SELECT p.id_pet, p.nome, p.id_tutor, t.nome AS dono FROM pet p INNER JOIN tutor t ON p.id_tutor = t.id_tutor ORDER BY p.nome");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['data'];
    $id_tutor = intval($_POST['id_tutor']);
    $id_pet = intval($_POST['id_pet']);

    // Validação explícita no PHP: verifica se o pet pertence ao tutor selecionado
    $check = $conn->prepare("SELECT id_pet FROM pet WHERE id_pet = ? AND id_tutor = ?");
    $check->bind_param("ii", $id_pet, $id_tutor);
    $check->execute();
    $valido = $check->get_result()->num_rows > 0;

    if (!$valido) {
        $erro = "Erro de vínculo: O pet selecionado não pertence a este tutor!";
    } else {
        $stmt = $conn->prepare("INSERT INTO agendamento (data, id_tutor, id_pet) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $data, $id_tutor, $id_pet);

        try {
            $stmt->execute();
            header("Location: index.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            $erro = "Erro: Já existe um agendamento nesta data ou conflito de chave.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Agendamento</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Novo Agendamento</h2>
        <?php if ($erro): ?><div class="alerta-erro"><?= $erro ?></div><?php endif; ?>

        <form method="POST">
            <label>Data do Agendamento:</label>
            <input type="date" name="data" required>

            <label>Tutor:</label>
            <select name="id_tutor" required>
                <option value="">Selecione o tutor...</option>
                <?php while ($t = $tutores->fetch_assoc()): ?>
                    <option value="<?= $t['id_tutor'] ?>"><?= htmlspecialchars($t['nome']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Pet (deve pertencer ao tutor escolhido):</label>
            <select name="id_pet" required>
                <option value="">Selecione o pet...</option>
                <?php while ($p = $pets->fetch_assoc()): ?>
                    <option value="<?= $p['id_pet'] ?>">
                        <?= htmlspecialchars($p['nome']) ?> (Tutor: <?= htmlspecialchars($p['dono']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Agendar</button>
            <a href="index.php" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
