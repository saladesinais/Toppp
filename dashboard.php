<?php
session_start();
$db = new PDO('sqlite:database.sqlite');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Processa o upload de imagens
if (isset($_POST['upload'])) {
    $image = $_FILES['image'];

    if ($image['error'] == 0) {
        $path = "uploads/" . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $path)) {
            $stmt = $db->prepare("INSERT INTO images (user_id, image_path) VALUES (:user_id, :image_path)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':image_path', $path);
            $stmt->execute();
            echo "Imagem enviada com sucesso!";
        } else {
            echo "Erro ao enviar a imagem.";
        }
    }
}

// Verifica as imagens do usuário
$stmt = $db->prepare("SELECT * FROM images WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Bem-vindo ao painel do usuário</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" required><br>
    <button type="submit" name="upload">Enviar Imagem</button>
</form>

<h3>Suas Imagens:</h3>
<ul>
    <?php foreach ($images as $image): ?>
        <li><img src="<?= $image['image_path'] ?>" width="100"></li>
    <?php endforeach; ?>
</ul>

<a href="logout.php">Sair</a>