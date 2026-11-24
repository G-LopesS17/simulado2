<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$id = intval($_GET['id']);
$erro = "";

$stmt = $conn->prepare("SELECT id_tutor, nome FROM tutor WHERE id_tutor = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$tutor = $stmt->get_result()->fetch_assoc();

if (!$tutor) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $novo_cpf = trim($_POST['cpf']);
    $nova_senha = $_POST['senha'];

    // 1. Se um novo CPF foi informado, verifica se já pertence a OUTRO tutor
    $cpf_duplicado = false;
    if (!empty($novo_cpf)) {
        $res = $conn->prepare("SELECT cpf FROM tutor WHERE id_tutor != ?");
        $res->bind_param("i", $id);
        $res->execute();
        $outros = $res->get_result();

        while ($r = $outros->fetch_assoc()) {
            if (password_verify($novo_cpf, $r['cpf'])) {
                $cpf_duplicado = true;
                break;
            }
        }
    }

    if ($cpf_duplicado) {
        $erro = "Este CPF já está cadastrado para outro tutor!";
    } else {
        // 2. Monta a atualização dinamicamente conforme os campos preenchidos
        if (!empty($novo_cpf) && !empty($nova_senha)) {
            $cpf_hash = password_hash($novo_cpf, PASSWORD_DEFAULT);
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE tutor SET nome = ?, cpf = ?, senha = ? WHERE id_tutor = ?");
            $up->bind_param("sssi", $nome, $cpf_hash, $senha_hash, $id);
        } elseif (!empty($novo_cpf)) {
            $cpf_hash = password_hash($novo_cpf, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE tutor SET nome = ?, cpf = ? WHERE id_tutor = ?");
            $up->bind_param("ssi", $nome, $cpf_hash, $id);
        } elseif (!empty($nova_senha)) {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $up = $conn->prepare("UPDATE tutor SET nome = ?, senha = ? WHERE id_tutor = ?");
            $up->bind_param("ssi", $nome, $senha_hash, $id);
        } else {
            $up = $conn->prepare("UPDATE tutor SET nome = ? WHERE id_tutor = ?");
            $up->bind_param("si", $nome, $id);
        }

        if ($up->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $erro = "Erro ao atualizar os dados do tutor.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Tutor</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Editar Tutor</h2>
        
        <?php if (!empty($erro)): ?>
            <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($tutor['nome']) ?>" required>

            <label>Novo CPF (deixe em branco para manter o atual):</label>
            <input type="text" name="cpf" maxlength="14" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-00" title="Digite no formato 000.000.000-00">

            <label>Nova Senha (deixe em branco para manter a atual):</label>
            <input type="password" name="senha" placeholder="Digite a nova senha">

            <button type="submit">Atualizar</button>
            <a href="index.php" class="btn">Cancelar</a>
        </form>
    </div>
</body>
</html>
