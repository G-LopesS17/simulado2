<?php
session_start();
if (!isset($_GET['primeiro_acesso'])) {
    require_once '../verifica_login.php';
}
require_once '../conexao.php';

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $cpf_formatado = trim($_POST['cpf']); // Mantém os símbolos 000.000.000-00
    $senha_plana = $_POST['senha'];

    // Verifica duplicidade de CPF percorrendo os hashes existentes
    $res = $conn->query("SELECT cpf FROM tutor");
    $cpf_duplicado = false;
    
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            if (password_verify($cpf_formatado, $r['cpf'])) {
                $cpf_duplicado = true;
                break;
            }
        }
    }

    if ($cpf_duplicado) {
        $erro = "Este CPF já está cadastrado no sistema!";
    } else {
        // Gera o hash tanto do CPF (com símbolos) quanto da senha
        $cpf_hash = password_hash($cpf_formatado, PASSWORD_DEFAULT);
        $senha_hash = password_hash($senha_plana, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO tutor (nome, cpf, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $cpf_hash, $senha_hash);

        if ($stmt->execute()) {
            $destino = isset($_GET['primeiro_acesso']) ? "../login.php" : "index.php";
            header("Location: $destino");
            exit();
        } else {
            $erro = "Erro ao cadastrar tutor.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Novo Tutor</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <div class="container">
        <h2>Cadastrar Tutor</h2>
        <?php if ($erro): ?><div class="alerta-erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        
        <form method="POST">
            <label>Nome Completo:</label>
            <input type="text" name="nome" placeholder="Ex: Maria da Silva" required>

            <label>CPF:</label>
            <input type="text" name="cpf" maxlength="14" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-00" title="Digite no formato 000.000.000-00" required>

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="Crie uma senha" required>

            <button type="submit">Salvar</button>
            <a href="<?= isset($_GET['primeiro_acesso']) ? '../login.php' : 'index.php' ?>" class="btn">Voltar</a>
        </form>
    </div>
</body>
</html>
