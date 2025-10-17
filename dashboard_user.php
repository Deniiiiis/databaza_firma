<?php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: index.php');
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-top: 20px;
            font-size: 36px;
        }

        .obr1 {
            margin-top: 20px;
            width: 150px;
        }

        p img {
            width: 150px;
            margin-left: 20px;
        }

        nav {
                padding: 5px;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                background-color: #333;
                display: flex;
                justify-content: center;
                align-items: center;
            }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #575757;
        }

        nav a:first-child {
            margin-left: 0;
        }

        .vitaj {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 30px auto;
            width: 60%;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            padding: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .welcome-card h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

    </style>
</head>
<body>

<nav>
    <?php if ($role === 'user'): ?>
    <a href="zamestnanec_user.php">Zamestnanci</a>
    <?php elseif ($role === 'technik'): ?>
    <a href="zamestnanec_technik.php">Zamestnanci</a>
    <?php endif; ?>

    <?php if ($role === 'user'): ?>
        <a href="nahlasit_poruchu.php">Nahlásiť poruchu</a>
    <?php elseif ($role === 'technik'): ?>
        <a href="porucha_technik.php">Poruchy</a>
    <?php endif; ?>
    <a href="logout.php" class="logout-btn">Odhlásiť sa</a>
</nav>
<br><br>
<div class="vitaj">
    <h2>Vitaj, <?php echo $_SESSION['meno']; ?>!</h2>
</div>

<footer>
    <img src="fei_logo.png" alt="Logo FEI" width="150">
    <img src="kemt_logo.png" alt="Logo KEMT" width="150">
</footer>
</body>
</html>