<?php
$db = new PDO('sqlite:database.sqlite');

// Lista todos os usuários
$users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Bloquear/desbloquear contas
if (isset($_GET['block'])) {
    $id = $_GET['block'];
    $db->exec("UPDATE users SET is_blocked = 1 WHERE id = $id");
    echo "Usuário bloqueado!";
}

if (isset($_GET['unblock'])) {
    $id = $_GET['unblock'];
    $db->exec("UPDATE users SET is_blocked = 0 WHERE id = $id");
    echo "Usuário desbloqueado!";
}

// Apagar imagens
if (isset($_GET['delete_image'])) {
    $id = $_GET['delete_image'];
    $db->exec("DELETE FROM images WHERE id = $id");
    echo "Imagem apagada!";
}
?>

<h2>Painel do Administrador</h2>

<h3>Usuários:</h3>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= $user['username'] ?> - 
            <a href="?block=<?= $user['id'] ?>">Bloquear</a> | 
            <a href="?unblock=<?= $user['id'] ?>">Desbloquear</a>
        </li>
    <?php endforeach; ?>
</ul>