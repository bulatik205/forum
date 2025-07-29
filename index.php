<?php
require('config.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форум</title>
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
            height: 100vh;
            width: 100%;
            margin: 0;
        }

        main {
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        h3 {
            margin: 3em 2em;
            font-size: 1.5rem;
        }

        li {
            font-size: 1.25rem;
            padding: 5px;
        }

        a {
            color: var(--black);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main>
        <h3>Разделы:</h3>
        <ul>
            <li><a href="posts.php">Все посты</a></li>
            <li><a href="">Системные посты</a></li>
            <li><a href="create.php">Написать пост</a></li>
            <li><a href="">Админка</a></li>
        </ul>
    </main>
</body>
</html>