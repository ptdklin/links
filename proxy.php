<?php
session_start();

// Массив ссылок и их паролей
$links = [
    'kk' => [
        'url' => 'http://217.107.102.71:90/kk/',
        'password' => '54321',
        'name' => 'Контроль качества'
    ],
    'sops' => [
        'url' => 'https://drive.google.com/drive/folders/16ngpPCk6BV8Ox9nzzk90loGw3auIalVz?usp=sharing',
        'password' => '54321',
        'name' => 'СОПы'
    ],
    'prikazy' => [
        'url' => 'https://drive.google.com/drive/folders/17x2EOKr5kdkNsT-QZfaAvRtVHtUEcy7n?usp=sharing',
        'password' => '54321',
        'name' => 'Приказы'
    ]
];

$error = '';
$link_key = $_GET['link'] ?? '';

// Если ссылка не существует
if (!isset($links[$link_key])) {
    die('Ссылка не найдена');
}

$current_link = $links[$link_key];

// Проверяем, отправлена ли форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    
    if ($password === $current_link['password']) {
        // Сохраняем в сессию, что пароль для этой ссылки введен
        $_SESSION['access_' . $link_key] = true;
        // Перенаправляем на целевой URL
        header('Location: ' . $current_link['url']);
        exit;
    } else {
        $error = 'Неверный пароль';
    }
}

// Если уже есть доступ в сессии - сразу перенаправляем
if (isset($_SESSION['access_' . $link_key]) && $_SESSION['access_' . $link_key] === true) {
    header('Location: ' . $current_link['url']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Вход в раздел</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('romashki.png');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 360px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(5px);
        }
        
        h2 {
            color: #333;
            margin-bottom: 16px;
            font-weight: 500;
            font-size: 24px;
            text-align: center;
        }
        
        p {
            color: #666;
            margin-bottom: 24px;
            text-align: center;
            font-size: 16px;
        }
        
        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s;
            margin-bottom: 20px;
        }
        
        input:focus {
            border-color: #4a90e2;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: #357abd;
        }
        
        .error {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #fdf0ef;
            border-radius: 6px;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link:hover {
            color: #4a90e2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Введите пароль</h2>
        <p>Для доступа к разделу:<br><strong><?php echo htmlspecialchars($current_link['name']); ?></strong></p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="password" name="password" placeholder="Пароль" autofocus>
            <button type="submit">Войти</button>
        </form>
        
        <a href="content.html" class="back-link">← Вернуться на главную</a>
    </div>
</body>
</html>
