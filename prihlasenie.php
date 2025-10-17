<?php
include 'db.php';


$order_column = 'ID_prihlasenia';
$order_direction = 'ASC';
$sql = "SELECT p.ID_prihlasenia, p.ID_zariadenia, p.ID_zamestnanca, p.cas_prihlasenia, p.sposob_pristupu 
        FROM prihlasenie p
        JOIN zariadenie z ON p.ID_zariadenia = z.ID_zariadenia";
$result = $conn->query($sql);

if (isset($_GET['order'])) {
    $order_column = $_GET['order'];
    $order_direction = (isset($_GET['dir']) && $_GET['dir'] == 'desc') ? 'DESC' : 'ASC';
}


if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $conn->query("DELETE FROM prihlasenie WHERE ID_prihlasenia = $id");


    header("Location: prihlasenie.php");
    exit();
}
$where = [];

if (!empty($_GET['sposob_pristupu'])) {
    $sposob = $conn->real_escape_string($_GET['sposob_pristupu']);
    $where[] = "sposob_pristupu = '$sposob'";
}

$where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT * FROM prihlasenie $where_sql ORDER BY $order_column $order_direction";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Prihlásenia</title>
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
                background-color: #f1f1f1;
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
        </style>
</head>
<body>
<nav>
    <a href="zariadenie.php">Zariadenia</a> |
    <a href="zamestnanec.php">Zamestnanci</a> |
    <a href="prihlasenie.php">Prihlásenia</a> |
    <a href="porucha.php">Poruchy</a> |
    <a href="bezpecnostny_log.php">Bezpečnostné logy</a> |
    <a href="pouzivatelia.php">Používatelia</a> |
    <a href="report.php">Report</a> |
    <a href="logout.php"class="logout-btn">Odhlásiť sa</a>
</nav>
<br><br><br>
    <h2>

    Prehľad Prihlásení

</h2>

<form method="GET" style="margin-bottom: 0px;margin-left: 945px; padding: 10px 20px">

<select name="sposob_pristupu">
        <option value="">Všetky spôsoby prístupu</option>
        <option value="PIN">PIN</option>
        <option value="karta">karta</option>
        <option value="USB token">USB token</option>
    </select>

    <button type="submit">Filtrovať</button>
    
</form>

<table border="1">
<tr>
        <th><a href="?order=ID_prihlasenia&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&sposob_pristupu=<?php echo urlencode($_GET['sposob_pristupu'] ?? ''); ?>">ID Prihlásenia</a></th>
        <th><a href="?order=ID_zamestnanca&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&sposob_pristupu=<?php echo urlencode($_GET['sposob_pristupu'] ?? ''); ?>">ID Zamestnanca</a></th>
        <th><a href="?order=ID_zariadenia&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&sposob_pristupu=<?php echo urlencode($_GET['sposob_pristupu'] ?? ''); ?>">ID Zariadenia</a></th>
        <th><a href="?order=sposob_pristupu&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&sposob_pristupu=<?php echo urlencode($_GET['sposob_pristupu'] ?? ''); ?>">Spôsob prístupu</a></th>
        <th><a href="?order=cas_prihlasenia&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&sposob_pristupu=<?php echo urlencode($_GET['sposob_pristupu'] ?? ''); ?>">Čas prihlásenia</a></th>
        <th>Akcie</th>
    </tr>



    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["ID_prihlasenia"] . "</td>
                <td>" . $row["ID_zariadenia"] . "</td>
                <td>" . $row["ID_zamestnanca"] . "</td>
                <td>" . $row["cas_prihlasenia"] . "</td>
                <td>" . $row["sposob_pristupu"] . "</td>
                <td class='actions'>
                    <a href='prihlasenie.php?delete=" . $row["ID_prihlasenia"] . "' onclick=\"return confirm('Naozaj chceš zmazať toto prihlásenie?');\">Zmazať</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Žiadne prihlásenia nenájdené</td></tr>";
    }

    $conn->close();
    ?>

</table>

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
