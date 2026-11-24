<?php
require_once '../verifica_login.php';
require_once '../conexao.php';

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($busca !== '') {
    $stmt = $conn->prepare("SELECT id_tutor, nome FROM tutor WHERE nome LIKE ?");
    $termo = "%" . $busca . "%";
    $stmt->bind_param("s", $termo);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT id_tutor, nome FROM tutor");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Tutores</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <nav class="navbar">
        <a href="/simulado/index.php">Início</a>
        <a href="/simulado/tutor/index.php">Tutores</a>
        <a href="/simulado/pet/index.php">Pets</a>
        <a href="/simulado/agendamento/index.php">Agendamentos</a>
        <a href="/simulado/logout.php" class="btn-sair">Sair</a>
    </nav>

    <div class="container">
        <h2>Gerenciar Tutores</h2>
        
        <form method="GET" class="busca-form">
            <input type="text" name="busca" placeholder="Buscar tutor pelo nome..." value="<?= htmlspecialchars($busca) ?>">
            <button type="submit">Buscar</button>
            <?php if ($busca !== ''): ?>
                <a href="index.php" class="btn">Limpar</a>
            <?php endif; ?>
        </form>

        <a href="criar.php" class="btn">+ Novo Tutor</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>CPF (Protegido LGPD)</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id_tutor'] ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td>***.***.***-** (Criptografado)</td>
                <td>
                    <a href="editar.php?id=<?= $row['id_tutor'] ?>" class="btn btn-editar">Editar</a>
                    <a href="excluir.php?id=<?= $row['id_tutor'] ?>" class="btn btn-excluir" onclick="return confirm('Excluir este tutor?')">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
