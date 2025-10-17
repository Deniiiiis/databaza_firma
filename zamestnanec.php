<?php
include 'db.php';

$order_column = 'ID_zamestnanca';
$order_direction = 'ASC';

if (isset($_GET['order'])) {
    $order_column = $_GET['order'];
    $order_direction = (isset($_GET['dir']) && $_GET['dir'] == 'desc') ? 'DESC' : 'ASC';
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM zamestnanec WHERE ID_zamestnanca = $id");
    header("Location: zamestnanec.php");
    exit();
}

$where = [];

if (!empty($_GET['oddelenie'])) {
    $oddelenie = $conn->real_escape_string($_GET['oddelenie']);
    $where[] = "oddelenie = '$oddelenie'";
}


$where_sql = "";
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT * FROM zamestnanec $where_sql ORDER BY $order_column $order_direction";
$result = $conn->query($sql);





?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Zamestnanci</title>
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
<h2>Zoznam zamestnancov</h2>




<form method="GET" style="margin-bottom: 20px;">

<a href="pridat_zamestnanca.php" style="padding: 10px 20px; background-color: #4CAF50; color: white; border-radius: 5px; text-decoration: none; margin-top: 0px; margin-right: 700px; display: inline-block;">Pridať nového zamestnanca</a>

<select name="oddelenie">
        <option value="">Všetky oddelenia</option>
        <option value="IT podpora">IT podpora</option>
        <option value="Manažérske oddelenie">Manažérske oddelenie</option>
        <option value="Bezpečnostné oddelenie">Bezpečnostné oddelenie</option>
        <option value="Klientské oddelenie">Klientské oddelenie</option>
        <option value="Kvalitné oddelenie">Kvalitné oddelenie</option>
        <option value="Analytické oddelenie">Analytické oddelenie</option>
        <option value="Projektové oddelenie">Projektové oddelenie</option>
        <option value="Vývojové oddelenie">Vývojové oddelenie</option>
    </select>

    <button type="submit">Filtrovať</button>
    
</form>


<table border="1">
<tr>
        <th><a href="?order=ID_zamestnanca&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&oddelenie=<?php echo urlencode($_GET['oddelenie'] ?? ''); ?>">ID Zamestnanca</a></th>
        <th><a href="?order=meno&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&oddelenie=<?php echo urlencode($_GET['oddelenie'] ?? ''); ?>">Meno</a></th>
        <th><a href="?order=priezvisko&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&oddelenie=<?php echo urlencode($_GET['oddelenie'] ?? ''); ?>">Priezvisko</a></th>
        <th><a href="?order=email&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&oddelenie=<?php echo urlencode($_GET['oddelenie'] ?? ''); ?>">Kontakt</a></th>
        <th><a href="?order=oddelenie&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&oddelenie=<?php echo urlencode($_GET['oddelenie'] ?? ''); ?>">Oddelenie</a></th>
        <th><a href="?order=ID_kluca&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&oddelenie=<?php echo urlencode($_GET['oddelenie'] ?? ''); ?>">ID Kľúča</a></th>
        <th>Akcie</th>
    </tr>


    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["ID_zamestnanca"] . "</td>
                <td>" . $row["meno"] . "</td>
                <td>" . $row["priezvisko"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["oddelenie"] . "</td>
                <td>" . $row["ID_kluca"] . "</td>
                <td class='actions'>
                    <a href='upravit_zamestnanca.php?id=" . $row["ID_zamestnanca"] . "'>Upraviť</a>
                    <a href='zamestnanec.php?delete=" . $row["ID_zamestnanca"] . "' onclick=\"return confirm('Naozaj chceš zmazať tohto zamestnanca?');\">Zmazať</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Žiadni zamestnanci nenájdení</td></tr>";
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
