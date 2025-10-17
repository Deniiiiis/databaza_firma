<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
include 'db.php';

$sql = "
    SELECT ID_zamestnanca, COUNT(*) AS pocet_neopravenych
    FROM porucha
    WHERE opravena = 0  -- Neopravené poruchy
    GROUP BY ID_zamestnanca
    ORDER BY pocet_neopravenych ASC
    LIMIT 1";
$result = $conn->query($sql);

$technici = $result->fetch_assoc();

if ($technici) {
    $id_technika = $technici['ID_zamestnanca'];
} else {
    $id_technika = null;
}

$id_zariadenia = rand(1, 100); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $popis = $_POST['popis'];
    $datum = date('Y-m-d');
    $opravena = 0;

    $stmt = $conn->prepare("INSERT INTO porucha (ID_zariadenia, popis, datum_hlasenia, ID_zamestnanca, opravena) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $id_zariadenia, $popis, $datum, $id_technika, $opravena);


    $stmt->execute();
    echo "Porucha bola úspešne nahlásená a priradený technik: " . $technici['ID_zamestnanca'];
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Nahlásiť poruchu</title>
    <style>
            body {
                opacity: 0.7;
                transition: opacity 0.3s ease-in-out;
            }

            body.loaded {
                opacity: 1;
            }

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

            table {
                width: 80%;
                margin: 50px auto;
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 12px 20px;
                text-align: left;
            }

            th {
                background-color:rgb(200, 200, 200);
                color: black;
            }
            a {
                color: black;
            }

            td {
                background-color: #f9f9f9;
            }

            tr:hover {
    background-color: #dff0d8;
    transition: background-color 0.3s ease;
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
            .container {
            width: 500px;
            margin: 100px auto;
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 20px;
        }

        input[type="number"],
        textarea {
            width: 50%;
            padding: 30px;
            margin-top: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            margin-top: 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 10%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 200px;
            font-weight: bold;
            color: #007700;
            text-align: center;
        }
        </style>
</head>
<body>
<nav>
    <a href="zamestnanec_user.php">Zamestnanci</a>
    <a href="nahlasit_poruchu.php">Nahlásiť poruchu</a>
    <a href="logout.php" class="logout-btn">Odhlásiť sa</a>
</nav>
<br><br><br>
<h2>Nahlásiť poruchu</h2>
    <form method="POST">
        <label for="popis">Popis poruchy:</label><br>
        <textarea name="popis" required></textarea><br>
        <input type="submit" value="Odoslať">
    </form>

    <br><br>

<footer>
    <img src="fei_logo.png" alt="Logo FEI" width="150">
    <img src="kemt_logo.png" alt="Logo KEMT" width="150">
</footer>


<script>
    window.addEventListener("load", function () {
        document.body.classList.add("loaded");
    });
</script>
</body>
</html>