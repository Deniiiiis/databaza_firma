<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meno = $conn->real_escape_string($_POST['meno']);
    $priezvisko = $conn->real_escape_string($_POST['priezvisko']);
    $oddelenie = $conn->real_escape_string($_POST['oddelenie']);
    $email = $conn->real_escape_string($_POST['email']);
    $id_kluca = (int)$_POST['ID_kluca'];

    $sql = "INSERT INTO zamestnanec (meno, priezvisko, oddelenie, email, ID_kluca)
            VALUES ('$meno', '$priezvisko', '$oddelenie', '$email', $id_kluca)";

    if ($conn->query($sql) === TRUE) {
        header("Location: zamestnanec.php");
        exit();
    } else {
        echo "Chyba pri pridávaní zamestnanca: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať zamestnanca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            text-align: center;
        }

        form {
            background-color: #fff;
            width: 400px;
            margin: 40px auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        input[type="text"], input[type="number"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>

<h2>Pridať zamestnanca</h2>

<form method="post">
    <label>Meno:</label><br>
    <input type="text" name="meno" required><br>

    <label>Priezvisko:</label><br>
    <input type="text" name="priezvisko" required><br>

    <label>Oddelenie:</label><br>
    <input type="text" name="oddelenie" required><br>

    <label>Email:</label><br>
    <input type="text" name="email" required><br>

    <label>ID kľúča:</label><br>
    <input type="number" name="ID_kluca" required><br>

    <input type="submit" value="Pridať zamestnanca">
</form>

<a href="zamestnanec.php">Späť na prehľad</a>

</body>
</html>

<?php $conn->close(); ?>
