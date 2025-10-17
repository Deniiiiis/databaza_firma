<?php
include 'db.php';

if (isset($_POST['register'])) {
    $meno = trim($_POST['meno']);
    $heslo = $_POST['heslo'];

    $stmt = $conn->prepare("SELECT id FROM Pouzivatelia WHERE meno = ?");
    $stmt->bind_param("s", $meno);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Používateľ s týmto menom už existuje.";
    } else {

        $stmt = $conn->prepare("INSERT INTO Pouzivatelia (meno, heslo) VALUES (?, ?)");
        $stmt->bind_param("ss", $meno, $heslo);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Chyba pri registrácii.";
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Registrácia</title>
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
    <h2>Registrácia</h2>
    <form method="POST">
        <input type="text" name="meno" placeholder="Meno" required><br>
        <input type="password" name="heslo" placeholder="Heslo" required><br>
        <button type="submit" name="register">Registrovať</button>
    </form>
    <p>Máte už účet? <a href="index.php">Prihláste sa</a></p>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <footer>
    <img src="fei_logo.png" alt="Logo FEI" width="150">
    <img src="kemt_logo.png" alt="Logo KEMT" width="150">
</footer>
</body>
</html>
