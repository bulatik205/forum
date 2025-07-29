<?php
require('config.php');

$is_logged_in = false;
if (isset($_COOKIE['cookieAuth'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE cookieAuth = ?");
    $stmt->execute([$_COOKIE['cookieAuth']]);
    $is_logged_in = (bool)$stmt->fetch();
    header("Location: home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        die("Заполните все поля!");
        exit();
    }


    try {
        function getUserIP()
        {
            $ip_keys = [
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'
            ];

            foreach ($ip_keys as $key) {
                if (!empty($_SERVER[$key])) {
                    $ip_list = explode(',', $_SERVER[$key]);
                    foreach ($ip_list as $ip) {
                        $ip = trim($ip);
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                            return $ip;
                        }
                    }
                }
            }

            return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        }

        $user_ip = getUserIP();

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetchColumn() > 0) {
            die("Такой ник уже зарегистрирован! Введите другой ник!");
            header("Location: reg.php");
            exit();
        }


        $string = '';
        $number = rand(10000000, 99999999);
        $alfa = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];

        for ($i = 0; $i < 16; $i++) {
            $randomString = $alfa[array_rand($alfa)];
            $string .= $randomString;
        }

        $cookieAuth = $number . $string;

        $stmt = $pdo->prepare("INSERT INTO users (username, password, ip, cookieAuth) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $username,
            $password,
            $user_ip,
            $cookieAuth
        ]);

        setcookie('cookieAuth', $cookieAuth, time() + 60 * 60 * 24 * 30, '/');
        header("Location: home.php");
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
    <title>Регистрация</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Montserrat", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            margin: 0;
        }

        h1 {
            margin: 3em 2em;
        }

        form {
            height: 60vh;
            width: 30%;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 50px;
            box-sizing: border-box;
            border-radius: 50px;
        }

        input {
            height: 10%;
            width: 50%;
            font-size: 1.25rem;
            padding: 10px;
            box-sizing: border-box;
        }

        label {
            font-size: 1.5rem;
            margin: 2em 0em 1em 0em;
        }

        button {
            margin: 4em 0em 0em 0em;
            height: 15%;
            width: 50%;
            font-size: 1.5rem;
            background-color: #000;
            color: #fff;
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <h1>Зарегистрируйтесь</h1>
    <form action="reg.php" method="POST">
        <label for="username">Придумайте ник</label>
        <input type="text" placeholder="Username" name="username">

        <label for="password">Придумайте пароль</label>
        <input type="password" placeholder="Password" name="password">

        <button type="submit">Регистрация</button>
    </form>

</html>