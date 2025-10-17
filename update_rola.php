<?php
session_start();

include 'db.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Nemáte oprávnenie na túto stránku.";
    exit();
}

if (isset($_POST['rola']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $rola = $_POST['rola'];

    $sql = "UPDATE Pouzivatelia SET role = ? WHERE ID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $rola, $id);

        if ($stmt->execute()) {
            header("Location: pouzivatelia.php");
            exit();
        } else {
            echo "Chyba pri aktualizácii roly.";
        }
        $stmt->close();
    } else {
        echo "Chyba pri príprave dotazu.";
    }
}

if (isset($_POST['delete_id'])) {
    $id = (int)$_POST['delete_id'];

    $sql = "DELETE FROM Pouzivatelia WHERE ID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: pouzivatelia.php");
            exit();
        } else {
            echo "Chyba pri vymazávaní používateľa.";
        }
        $stmt->close();
    } else {
        echo "Chyba pri príprave dotazu na vymazanie.";
    }
}
$conn->close();
?>
