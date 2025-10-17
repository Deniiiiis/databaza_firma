<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM zamestnanec WHERE ID_zamestnanca = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Zamestnanec nenájdený.";
        exit();
    }
} else {
    echo "ID zamestnanca nie je zadané.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meno = $_POST['meno'];
    $priezvisko = $_POST['priezvisko'];
    $oddelenie = $_POST['oddelenie'];
    $email = $_POST['email'];
    $id_kluca = $_POST['ID_kluca'];

    $update_sql = "UPDATE zamestnanec SET meno = '$meno', priezvisko = '$priezvisko', oddelenie = '$oddelenie', email = '$email', ID_kluca = $id_kluca WHERE ID_zamestnanca = $id";
    
    if ($conn->query($update_sql) === TRUE) {
        echo "Údaje boli úspešne upravené!";
        header("Location: zamestnanec.php");
        exit();
    } else {
        echo "Chyba pri úprave údajov: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Upraviť Zamestnanca</title>
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

        .welcome-card {
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

        form {
            margin: 20px auto;
            width: 40%;
            background-color: #fff;
            padding: 20px;
            border-radius: 30px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form input[type="text"], form input[type="number"] {
            width: 96%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>


<h2>Upraviť Zamestnanca</h2>

<form method="POST" action="">
    <label for="meno">Meno:</label>
    <input type="text" id="meno" name="meno" value="<?php echo $row['meno']; ?>" required><br><br>
    
    <label for="priezvisko">Priezvisko:</label>
    <input type="text" id="priezvisko" name="priezvisko" value="<?php echo $row['priezvisko']; ?>" required><br><br>
    
    <label for="oddelenie">Oddelenie:</label>
    <input type="text" id="oddelenie" name="oddelenie" value="<?php echo $row['oddelenie']; ?>" required><br><br>
    
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?php echo $row['email']; ?>" required><br><br>

    <label for="ID_kluca">ID Kľúča:</label>
    <input type="number" id="ID_kluca" name="ID_kluca" value="<?php echo $row['ID_kluca']; ?>" required><br><br>

    <input type="submit" value="Uložiť zmeny">
   
</form>
<a href="zamestnanec.php">Späť na prehľad</a>
<br><br><br>
<footer>
    <img src="fei_logo.png" alt="Logo FEI" width="150">
    <img src="kemt_logo.png" alt="Logo KEMT" width="150">
</footer>

</body>
</html>
