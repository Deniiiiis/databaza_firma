<?php
session_start();

include 'db.php';

$order_column = $_GET['order'] ?? 'ID_poruchy';
$order_direction = ($_GET['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';

$sql = "SELECT * FROM porucha ORDER BY $order_column $order_direction";

$result = $conn->query($sql);

if (!$result) {
    die("Chyba pri získavaní dát: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Poruchy</title>
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
    <script>
        function toggleOpravena(id, currentValue) {
            fetch('aktualizuj_stav.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}&opravena=${currentValue ? 0 : 1}`
            })
            .then(response => response.text())
            .then(() => location.reload());
        }
    </script>

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
    <h2>Poruchy</h2>

    <table border="1">
    <tr>
        <th><a href="?order=ID_poruchy&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>">ID poruchy</a></th>
        <th><a href="?order=ID_zariadenia&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>">ID Zariadenia</a></th>
        <th><a href="?order=popis&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>">Popis</a></th>
        <th><a href="?order=datum_hlasenia&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>">Dátum hlásenia</a></th>
        <th><a href="?order=id_technika&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>">ID Technika</a></th>
        <th><a href="?order=opravena&dir=<?= $order_direction === 'ASC' ? 'desc' : 'asc' ?>">Opravená</a></th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['ID_poruchy'] ?></td>
            <td><?= $row['ID_zariadenia'] ?></td>
            <td><?= htmlspecialchars($row['popis']) ?></td>
            <td><?= $row['datum_hlasenia'] ?></td>
            <td><?= $row['ID_zamestnanca'] ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'technik'): ?>
                    <span class="toggle-icon" onclick="toggleOpravena(<?= $row['ID_poruchy'] ?>, <?= $row['opravena'] ?>)">
                        <?= $row['opravena'] ? '✅' : '❌' ?>
                    </span>
                <?php else: ?>
                    <?= $row['opravena'] ? '✅' : '❌' ?>
                <?php endif; ?>
            </td>
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
