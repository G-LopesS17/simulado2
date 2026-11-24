<?php
session_start();
$erro = "";

if (isset($_GET['erro'])) {
    if ($_GET['erro'] === 'expirado') {
        $erro = "Sua sessão expirou por inatividade. Faça login novamente.";
    } elseif ($_GET['erro'] === 'invalido') {
        $erro = "CPF ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Clínica Vet</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="container login-box">
        <h2>Acesso ao Sistema</h2>
        
        <?php if (!empty($erro)): ?>
            <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        
        <form action="autenticar.php" method="POST">
            <label>CPF:</label>
            <input type="text" name="cpf" maxlength="14" placeholder="000.000.000-00" required>

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="Digite sua senha" required>

            <button type="submit">Entrar</button>
        </form>
        
    </div>
</body>
</html>
