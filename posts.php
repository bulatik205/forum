<?php
require('config.php');

$postIsReal = false;
$author;
$likes;
if (isset($_GET['post']) && is_numeric($_GET['post'])) {
    $postNumber = (int)$_GET['post'];

    $stmt = $pdo->prepare("SELECT title, content, author, anonymous, created_at, likes FROM posts WHERE id = ?");
    $stmt->execute([$postNumber]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data != []) {
        $postIsReal = true;
    }

    if ($data['anonymous'] === 0) {
        $author = $data['author'];
    } elseif ($data['anonymous'] === 1) {
        $author = "Аноним";
    }

    $likes = $data['likes'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if ($postIsReal): ?>
        <title><?php echo htmlspecialchars($data['title']); ?> - Форум Anarh Empire</title>
    <?php else: ?>
        <title>Пост Anarh Empire</title>
    <?php endif ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Montserrat", sans-serif;
        }

        body {
            min-height: 100vh;
            width: 100%;
            margin: 0;
            padding: 50px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .post {
            min-height: 80%;
            width: 90%;
            padding: 50px;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            border: 1px solid #333;
        }

        h1 {
            font-size: 1.5rem;
        }

        p {
            font-size: 1.1rem;
            margin-left: 2em;
        }

        .info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
        }

        .margin {
            margin-left: 2em;
        }

        button {
            height: 5vh;
            width: 50vh;
        }
    </style>
</head>
<body>
    <?php if ($postIsReal): ?>
        <div class="post">
            <h1><?php echo ($data['title']) ?></h1>
            <p><?php echo '<div style="white-space: pre-line">' . $data['content'] . '</div>'; ?></p>
            <form action="posts.php" method="POST">
                <button type="submit"><?php echo($likes) ?> Помогло 👍</button>
            </form>
            <div class="info">
                <p>Опубликованно:</p>
                <p class="margin"><?php echo ($data['created_at']) ?></p>
                <h3 class="margin"><?php echo($author)?></h3>
            </div>
        </div>
    <?php else: ?>
        <h1>Такого поста не существует!</h1>
        <a href="create.php">Созадть</a>
        <a href="posts.php?post=1">Первый пост</a>
    <?php endif ?>
</body>
</html>