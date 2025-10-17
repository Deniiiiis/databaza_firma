<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Nemáte oprávnenie na túto stránku.";
    exit();
}

$sql_login = "SELECT z.ID_zamestnanca, z.meno, z.priezvisko, COUNT(*) AS login_count
              FROM zamestnanec z
              JOIN prihlasenie p ON z.ID_zamestnanca = p.ID_zamestnanca
              WHERE HOUR(p.cas_prihlasenia) < 7 OR HOUR(p.cas_prihlasenia) > 18
              GROUP BY z.ID_zamestnanca
              ORDER BY login_count DESC";
$result_login = $conn->query($sql_login);

$sql_device_faults = "SELECT zariadenie.ID_zariadenia, COUNT(*) AS fault_count
                      FROM porucha
                      JOIN zariadenie ON porucha.ID_zariadenia = zariadenie.ID_zariadenia
                      WHERE YEAR(porucha.datum_hlasenia) = YEAR(CURDATE()) - 1
                      GROUP BY zariadenie.ID_zariadenia
                      ORDER BY fault_count DESC";
$result_device_faults = $conn->query($sql_device_faults);

$sql_security_incidents = "SELECT * FROM bezpecnostny_log
                           ORDER BY datum_cas DESC
                           LIMIT 10";
$result_security_incidents = $conn->query($sql_security_incidents);
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Reporty - Carousel</title>
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

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        .carousel-container {
            width: 90%;
            margin: auto;
            overflow: hidden;
        }

        .carousel-slide {
            display: none;
        }

        .carousel-slide.active {
            display: block;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px 15px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        th {
            background-color: #ddd;
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

        .nav-buttons {
    position: sticky;
    top: 48px;
    background-color: #f4f4f9;
    z-index: 999;
    text-align: center;
    padding: 10px 0;

}

        .nav-buttons button {
            background-color: #444;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .nav-buttons button:hover {
            background-color: #777;
        }
        tr:hover {
    background-color: #dff0d8;
    transition: background-color 0.3s ease;
}
        footer {
            position: sticky;
            bottom: 0;
            width: 99.35%;
            background-color: #333;
            padding: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        footer img {
            width: 150px;
            margin-left: 5px;
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


<div class="nav-buttons">
    <button style="margin-right: 900px"onclick="prevSlide()">&#8592; Predchádzajúci</button>
    <button onclick="nextSlide()">Nasledujúci &#8594;</button>
</div>
<div class="carousel-container">

<h2 style="text-align: center;">Prehľad Reportov</h2>

    <div class="carousel-slide active">
        <h2 style="text-align: center;">Najčastejšie prihlásenia mimo pracovnej doby</h2>
        <table>
            <tr><th>ID</th><th>Meno</th><th>Priezvisko</th><th>Počet prihlásení</th></tr>
            <?php while ($row = $result_login->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ID_zamestnanca'] ?></td>
                    <td><?= $row['meno'] ?></td>
                    <td><?= $row['priezvisko'] ?></td>
                    <td><?= $row['login_count'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="carousel-slide">
        <h2 style="text-align: center;">Zariadenia s najviac poruchami (minulý rok)</h2>
        <table>
            <tr><th>ID zariadenia</th><th>Počet porúch</th></tr>
            <?php while ($row = $result_device_faults->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ID_zariadenia'] ?></td>
                    <td><?= $row['fault_count'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="carousel-slide">
        <h2 style="text-align: center;">Posledných 10 bezpečnostných incidentov</h2>
        <table>
            <tr><th>ID logu</th><th>Dátum a čas</th><th>Typ incidentu</th></tr>
            <?php while ($row = $result_security_incidents->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ID_logu'] ?></td>
                    <td><?= $row['datum_cas'] ?></td>
                    <td><?= $row['typ_incidentu'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <br><br><br><br>
    </div>
    

</div>


<footer>
    <img src="fei_logo.png" alt="Logo FEI" width="150">
    <img src="kemt_logo.png" alt="Logo KEMT" width="150">
</footer>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll(".carousel-slide");

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove("active");
            if (i === index) {
                slide.classList.add("active");
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }
</script>
<script>
    window.addEventListener("load", function () {
        document.body.classList.add("loaded");
    });
</script>
</body>
</html>

<?php $conn->close(); ?>
