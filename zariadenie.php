<?php
include 'db.php';

$order_column = 'ID_zariadenia';
$order_direction = 'ASC';

if (isset($_GET['order'])) {
    $order_column = $_GET['order'];
    $order_direction = (isset($_GET['dir']) && $_GET['dir'] == 'desc') ? 'DESC' : 'ASC';
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $conn->query("DELETE FROM bezpecnostny_log WHERE ID_zariadenia = $id");
    $conn->query("DELETE FROM prihlasenie WHERE ID_zariadenia = $id");

    $conn->query("DELETE FROM zariadenie WHERE ID_zariadenia = $id");

    header("Location: zariadenie.php");
    exit();
}


$where = [];

if (!empty($_GET['typ_zariadenia'])) {
    $typ = $conn->real_escape_string($_GET['typ_zariadenia']);
    $where[] = "typ_zariadenia = '$typ'";
}

if (!empty($_GET['operacny_system'])) {
    $os = $conn->real_escape_string($_GET['operacny_system']);
    $where[] = "operacny_system = '$os'";
}

$where_sql = "";
if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT * FROM zariadenie $where_sql ORDER BY $order_column $order_direction";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Zariadenia</title>
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
    <h2>Zariadenia</h2>

<form method="GET" style="margin-bottom: 20px;">

<a href="pridat_zariadenie.php" style="padding: 10px 20px; background-color: #4CAF50; color: white; border-radius: 5px; text-decoration: none; margin-top: 0px; margin-right: 600px; display: inline-block;">Pridať nové zariadenie</a>

<select name="typ_zariadenia">
        <option value="">Všetky zariadenia</option>
        <option value="mobil">mobil</option>
        <option value="laptop">laptop</option>
        <option value="počítač">počítač</option>
        <option value="tlačiareň">tlačiareň</option>
    </select>
    <select name="operacny_system">
        <option value="">Všetky operačné systémy</option>
        <option value="linux">linux</option>
        <option value="iOS">iOS</option>
        <option value="android">android</option>
        <option value="windows">windows</option>

    </select>

    <button type="submit">Filtrovať</button>
    
</form>

    <table border="1">
    <tr>
        <th><a href="?order=ID_zariadenia&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&typ_zariadenia=<?php echo urlencode($_GET['typ_zariadenia'] ?? ''); ?>&operacny_system=<?php echo urlencode($_GET['operacny_system'] ?? ''); ?>">ID Zariadenia</a></th>
        <th><a href="?order=typ_zariadenia&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&typ_zariadenia=<?php echo urlencode($_GET['typ_zariadenia'] ?? ''); ?>&operacny_system=<?php echo urlencode($_GET['operacny_system'] ?? ''); ?>">Typ Zariadenia</a></th>
        <th><a href="?order=ip_adresa&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&typ_zariadenia=<?php echo urlencode($_GET['typ_zariadenia'] ?? ''); ?>&operacny_system=<?php echo urlencode($_GET['operacny_system'] ?? ''); ?>">IP Adresa</a></th>
        <th><a href="?order=operacny_system&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&typ_zariadenia=<?php echo urlencode($_GET['typ_zariadenia'] ?? ''); ?>&operacny_system=<?php echo urlencode($_GET['operacny_system'] ?? ''); ?>">Operačný Systém</a></th>
        <th><a href="?order=datum_poslednej_udrzby&dir=<?php echo $order_direction == 'ASC' ? 'desc' : 'asc'; ?>&typ_zariadenia=<?php echo urlencode($_GET['typ_zariadenia'] ?? ''); ?>&operacny_system=<?php echo urlencode($_GET['operacny_system'] ?? ''); ?>">Dátum Poslednej Údržby</a></th>
        <th>Akcie</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["ID_zariadenia"] . "</td>
                <td>" . $row["typ_zariadenia"] . "</td>
                <td>" . $row["ip_adresa"] . "</td>
                <td>" . $row["operacny_system"] . "</td>
                <td>" . $row["datum_poslednej_udrzby"] . "</td>
                <td>
                    <a href='upravit_zariadenie.php?id=" . $row["ID_zariadenia"] . "'>Upraviť</a>
                    <a href='zariadenie.php?delete=" . $row["ID_zariadenia"] . "' onclick=\"return confirm('Naozaj chceš zmazať toto zariadenie?');\">Zmazať</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Žiadne zariadenia nenájdené</td></tr>";
    }
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
