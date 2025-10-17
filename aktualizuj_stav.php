<?php
session_start();

include 'db.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'technik')) {
    die("Nemáte oprávnenie na túto akciu.");
}

if (isset($_POST['id']) && isset($_POST['opravena'])) {
    $id = (int) $_POST['id'];
    $opravena = (int) $_POST['opravena'];

    $sql = "UPDATE porucha SET opravena = ? WHERE ID_poruchy = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $opravena, $id);

        if ($stmt->execute()) {
            echo "Stav poruchy bol úspešne aktualizovaný.";
        } else {
            echo "Chyba pri aktualizácii stavu poruchy.";
        }
        $stmt->close();
    } else {
        echo "Chyba pri príprave dotazu.";
    }
} else {
    echo "Nedostatok údajov.";
}

$conn->close();
?>