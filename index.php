<?php
session_start();
include 'db.php';
$error = "";
if (isset($_POST['login'])) {
    $meno = $_POST['meno'];
    $heslo = $_POST['heslo'];

    $sql = "SELECT * FROM Pouzivatelia WHERE meno='$meno' AND heslo='$heslo'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        $_SESSION['meno'] = $meno;
        $_SESSION['role'] = $user['role'];
        
        if ($_SESSION['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard_user.php");
        }
        exit();
    } else {
        $incident = "neúspešný login";
    $cas = date('Y-m-d H:i:s');
    
    $sql = "SELECT ID_zariadenia FROM zariadenie ORDER BY RAND() LIMIT 1";
    $res = $conn->query($sql);
    $random_id = $res && $res->num_rows > 0 ? $res->fetch_assoc()['ID_zariadenia'] : NULL;

    if ($random_id !== null) {
        $log = $conn->prepare("INSERT INTO bezpecnostny_log (ID_zariadenia, typ_incidentu, datum_cas) VALUES (?, ?, ?)");
        $log->bind_param("iss", $random_id, $incident, $cas);
    } else {
        $log = $conn->prepare("INSERT INTO bezpecnostny_log (ID_zariadenia, typ_incidentu, datum_cas) VALUES (NULL, ?, ?)");
        $log->bind_param("ss", $incident, $cas);
    }
    $log->execute();

    $error = "Nesprávne meno alebo heslo.";
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Prihlásenie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-top: 40px;
            font-size: 32px;
        }

        form {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
            padding: 30px 40px;
            margin-top: 30px;
        }

        input[type="text"], input[type="password"] {
            width: 250px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 20px;
            color: #333;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #333;
            padding: 5px;
            color: white;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .error {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h2>Prihlásenie</h2>
    <form method="POST">
        <input type="text" name="meno" placeholder="Meno" required><br>
        <input type="password" name="heslo" placeholder="Heslo" required><br>
        <button type="submit" name="login">Prihlásiť sa</button>
    </form>
    <p>Nemáte účet? <a href="register.php">Registrujte sa</a></p>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <footer>
    <img src="fei_logo.png" alt="Logo FEI" width="150">
    <img src="kemt_logo.png" alt="Logo KEMT" width="150">
</footer>
</body>
</html>
