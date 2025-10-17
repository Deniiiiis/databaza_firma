<?php
session_start();

include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Nemáte oprávnenie na túto stránku.";
    exit();
}

$where_sql = "";
if (isset($_GET['role']) && !empty($_GET['role'])) {
    $role_filter = $_GET['role'];
    $where_sql = "WHERE role = '$role_filter'";
}

$sql = "SELECT * FROM Pouzivatelia $where_sql";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Správa používateľov</title>
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


    <h2>Správa používateľov</h2>

    <form style="margin-bottom: 0px;margin-left: 1025px; padding: 10px 20px"action="pouzivatelia.php" method="GET">
    <label for="role"></label>
    <select name="role" id="role">
        <option value="">Všetky role</option>
        <option value="admin" <?= isset($_GET['role']) && $_GET['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="user" <?= isset($_GET['role']) && $_GET['role'] == 'user' ? 'selected' : '' ?>>User</option>
        <option value="technik" <?= isset($_GET['role']) && $_GET['role'] == 'technik' ? 'selected' : '' ?>>Technik</option>
    </select>
    <button type="submit">Filtrovať</button>
</form>

    <table>
        <tr>
            <th>ID</th>
            <th>Meno</th>
            <th>Heslo</th>
            <th>Rola</th>
            <th>Odstránenie používateľa</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['ID']) ?></td>
            <td><?= htmlspecialchars($row['meno']) ?></td>
            <td><?= htmlspecialchars($row['heslo']) ?></td>
            <td>
            <form action="update_rola.php" method="POST">
                <select name="rola">
                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="user" <?= $row['role'] == 'user' ? 'selected' : '' ?>>User</option>
                    <option value="technik" <?= $row['role'] == 'technik' ? 'selected' : '' ?>>Technik</option>
                </select>
                <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                <input type="submit" value="Upravit">
                
            </form>
            </td>
            <td>
            <form action="update_rola.php" method="POST" onsubmit="return confirm('Ste si istí, že chcete vymazať tohto používateľa?');">
                <input type="hidden" name="delete_id" value="<?= $row['ID'] ?>">
                <input type="submit" value="Vymazať">
            </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><br><br>

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

<?php $conn->close(); ?>
