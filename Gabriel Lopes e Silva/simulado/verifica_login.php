<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tempo_expiracao = 1800; // 30 minutos em segundos

if (!isset($_SESSION['id_tutor'])) {
    header("Location: /saep_pets/login.php");
    exit();
}

if (isset($_SESSION['ultimo_acesso']) && (time() - $_SESSION['ultimo_acesso']) > $tempo_expiracao) {
    session_unset();
    session_destroy();
    header("Location: /saep_pets/login.php?erro=expirado");
    exit();
}

$_SESSION['ultimo_acesso'] = time();
?>
