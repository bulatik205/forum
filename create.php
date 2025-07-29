<?php
require('config.php');

$is_logged_in = false;
if (isset($_COOKIE['cookieAuth'])) {
    $is_logged_in = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $isPrivacy = isset($_POST['isPrivacy']);

    if (empty($title) || empty($description)) {
        die("Заполните все поля!");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT username FROM users WHERE cookieAuth = ?");
        $stmt->execute([$_COOKIE['cookieAuth']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($isPrivacy) {
            $stmt = $pdo->prepare("INSERT INTO `posts`(`title`, `content`, `author`, `anonymous`) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $title,
                $description,
                $user['username'],
                true
            ]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO `posts`(`title`, `content`, `author`) VALUES (?, ?, ?)");
            $stmt->execute([
                $title,
                $description,
                $user['username']
            ]);
        }


        header("Location: success.php");
        exit();
    } catch (PDOException $e) {
        die("Хмм... Интересная ошибка... Но пока мы незнаем что это могло быть. P.s попробуйте перезагурзить роутер, постучать по монитору или отпинать разработчика");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Написать пост</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        root {
            --black: #000;
        }

        * {
            font-family: "Montserrat", sans-serif;
        }

        body {
            min-height: 100vh;
            width: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        form {
            min-height: 80vh;
            width: 90%;
            border: 1px solid #000;
            display: flex;
            flex-direction: column;
            padding: 100px;
            box-sizing: border-box;
        }

        label {
            margin: 1em 0.5em;
            font-weight: 400;
            font-size: 1.5rem;
        }

        input[type="text"] {
            padding: 10px;
            box-sizing: border-box;
            font-size: 1.25rem;
        }

        input[type="checkbox"] {
            height: 20px;
            width: 20px;
        }

        .title {
            min-height: 7.5vh;
            width: 50%;
        }

        textarea {
            max-width: 90%;
            min-width: 50%;
            padding: 25px;
            box-sizing: border-box;
            font-size: 1.25rem;
        }

        .isPrivacy {
            padding: 25px;
            display: flex;
            align-items: center;
        }

        button {
            height: 10vh;
            width: 50%;
            font-size: 1.25rem;
            color: #fff;
            background-color: #000;
            border: 0;
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <?php if ($is_logged_in == true): ?>
        <form action="create.php" method="POST">
            <label for="title">Придумайте красивое название:</label>
            <input type="text" name="title" placeholder="Название" class="title">

            <label for="description">Основная часть поста:</label>
            <textarea id="description" name="description" rows="5" cols="33" placeholder="Начните писать..."></textarea>

            <div class="isPrivacy">
                <input type="checkbox" name="isPrivacy" id="isPrivacy">
                <label for="isPrivacy">Отправить анонимно</label>
            </div>

            <button type="submit">Готово</button>
        </form>
    <?php else: ?>
        <h1>У вас нет доступа к этой странице!</h1>
    <?php endif ?>
</body>

</html>