<?php
include 'db.php';

if (!isset($_GET['id'])) {
    echo "Chýbajúce ID zariadenia.";
    exit();
}

$id = (int)$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typ = $conn->real_escape_string($_POST['typ_zariadenia']);
    $ip = $conn->real_escape_string($_POST['ip_adresa']);
    $os = $conn->real_escape_string($_POST['operacny_system']);
    $datum = $conn->real_escape_string($_POST['datum_poslednej_udrzby']);

    $sql = "UPDATE zariadenie SET 
                typ_zariadenia='$typ', 
                ip_adresa='$ip', 
                operacny_system='$os', 
                datum_poslednej_udrzby='$datum' 
            WHERE ID_zariadenia=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: zariadenie.php");
        exit();
    } else {
        echo "Chyba pri úprave: " . $conn->error;
    }
}

$sql = "SELECT * FROM zariadenie WHERE ID_zariadenia = $id";
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    echo "Zariadenie neexistuje.";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Upraviť zariadenie</title>
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

        input[type="text"],
        input[type="date"] {
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

<h2>Upraviť zariadenie</h2>

<form method="post">
    <label>Typ zariadenia:</label><br>
    <input type="text" name="typ_zariadenia" value="<?php echo htmlspecialchars($row['typ_zariadenia']); ?>" required><br>

    <label>IP adresa:</label><br>
    <input type="text" name="ip_adresa" value="<?php echo htmlspecialchars($row['ip_adresa']); ?>" required><br>

    <label>Operačný systém:</label><br>
    <input type="text" name="operacny_system" value="<?php echo htmlspecialchars($row['operacny_system']); ?>" required><br>

    <label>Dátum poslednej údržby:</label><br>
    <input type="date" name="datum_poslednej_udrzby" value="<?php echo htmlspecialchars($row['datum_poslednej_udrzby']); ?>" required><br>

    <input type="submit" value="Uložiť zmeny">
</form>

<a href="zariadenie.php">Späť na prehľad</a>

</body>
</html>

<?php $conn->close(); ?>
