<?php
session_start();

include 'db.php';

$order_column = 'datum_cas';
$order_direction = 'ASC';
$incident_filter = '';

if (isset($_GET['order'])) {
    $order_column = $_GET['order'];
    $order_direction = (isset($_GET['dir']) && $_GET['dir'] == 'desc') ? 'DESC' : 'ASC';
}

if (isset($_GET['incident_type'])) {
    $incident_filter = $_GET['incident_type'];
}

$where_sql = "";
if (!empty($incident_filter)) {
    $where_sql = "WHERE typ_incidentu = '" . $conn->real_escape_string($incident_filter) . "'";
}

$sql = "SELECT * FROM bezpecnostny_log $where_sql ORDER BY $order_column $order_direction";
$result = $conn->query($sql);

$incident_types_query = "SELECT DISTINCT typ_incidentu FROM bezpecnostny_log";
$incident_types_result = $conn->query($incident_types_query);

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Bezpečnostné Logy</title>
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
    <h2>Bezpečnostné logy</h2>

    <form style="margin-bottom: 0px;margin-left: 840px; padding: 10px 20px"method="GET" action="bezpecnostny_log.php">
        <label for="incident_type"></label>
        <select name="incident_type" id="incident_type">
            <option value="">Všetky typy incidentu</option>
            <?php while ($row = $incident_types_result->fetch_assoc()): ?>
                <option value="<?= $row['typ_incidentu'] ?>" <?= $incident_filter === $row['typ_incidentu'] ? 'selected' : '' ?>>
                    <?= $row['typ_incidentu'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <input type="submit" value="Filtrovať">
    </form>

    <table border="1">
        <tr>
            <th><a href="?order=ID_logu&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>&incident_type=<?= $incident_filter ?>">ID Logu</a></th>
            <th><a href="?order=ID_zariadenia&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>&ID_zariadenia=<?= $incident_filter ?>">ID Zariadenia</a></th>
            <th><a href="?order=datum_cas&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>&incident_type=<?= $incident_filter ?>">Dátum Incidentu</a></th>
            <th><a href="?order=typ_incidentu&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>&incident_type=<?= $incident_filter ?>">Typ Incidentu</a></th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['ID_logu'] ?></td>
            <td><?= $row['ID_zariadenia'] ?></td>
            <td><?= $row['datum_cas'] ?></td>
            <td><?= htmlspecialchars($row['typ_incidentu']) ?></td>
        </tr>
        <?php endwhile; ?>
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
