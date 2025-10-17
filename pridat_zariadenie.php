<?php 
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typ_zariadenia = $conn->real_escape_string($_POST['typ_zariadenia']);
    $ip_adresa = $conn->real_escape_string($_POST['ip_adresa']);
    $operacny_system = $conn->real_escape_string($_POST['operacny_system']);
    $datum_poslednej_udrzby = $conn->real_escape_string($_POST['datum_poslednej_udrzby']);

    $sql = "INSERT INTO zariadenie (typ_zariadenia, ip_adresa, operacny_system, datum_poslednej_udrzby) 
            VALUES ('$typ_zariadenia', '$ip_adresa', '$operacny_system', '$datum_poslednej_udrzby')";

    if ($conn->query($sql) === TRUE) {
        header("Location: zariadenie.php");
        exit();
    } else {
        echo "Chyba: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Pridať Zariadenie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            text-align: center;
        }

        h2 {
            margin-top: 20px;
            font-size: 36px;
            color: #333;
        }

        .form-container {
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }

        input[type="text"], input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h2>Pridanie Nového Zariadenia</h2>

    <div class="form-container">
        <form action="pridat_zariadenie.php" method="post">
            <label for="typ_zariadenia">Typ Zariadenia:</label>
            <input type="text" id="typ_zariadenia" name="typ_zariadenia" required>

            <label for="ip_adresa">IP Adresa:</label>
            <input type="text" id="ip_adresa" name="ip_adresa" required>

            <label for="operacny_system">Operačný Systém:</label>
            <input type="text" id="operacny_system" name="operacny_system" required>

            <label for="datum_poslednej_udrzby">Dátum Poslednej Údržby:</label>
            <input type="date" id="datum_poslednej_udrzby" name="datum_poslednej_udrzby" required>

            <button type="submit">Pridať Zariadenie</button>
        </form>

        <a href="zariadenie.php" class="back-btn">Späť na Zariadenia</a>
    </div>

</body>
</html>
