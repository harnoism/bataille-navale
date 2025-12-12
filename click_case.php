<?php 
session_start();
include('./sql-connect.php');

if (isset($_POST["case"])) {

    $sql = new SqlConnect();

    // On cible la grille de l'autre joueur
    $player = ($_SESSION["role"] === 'joueur1') ? 'joueur2' : 'joueur1';
    $idgrid = $_POST["case"];

    $querySelect = "SELECT checked, boat FROM $player WHERE idgrid = :cell";
    $reqSelect = $sql->db->prepare($querySelect);
    $reqSelect->execute(['cell' => $idgrid]);
    $case = $reqSelect->fetch(PDO::FETCH_ASSOC);

    $queryUpdate = "
        UPDATE $player
        SET checked = CASE WHEN checked = 0 THEN 1 ELSE 0 END
        WHERE idgrid = :cell
    ";
    $reqUpdate = $sql->db->prepare($queryUpdate);
    $reqUpdate->execute(['cell' => $idgrid]);


    if ($case["checked"] == 0) {

        if ($case["boat"] > 0) {

            $boatId = $case["boat"];
            
            $queryBoat = "SELECT checked FROM $player WHERE boat = :boat";
            $reqBoat = $sql->db->prepare($queryBoat);
            $reqBoat->execute(['boat' => $boatId]);
            $boatCells = $reqBoat->fetchAll(PDO::FETCH_ASSOC);

            $allTouched = true;

            foreach ($boatCells as $bcell) {
                if ($bcell["checked"] == 0) {
                    $allTouched = false;
                    break;
                }
            }

            if ($allTouched) {
                $_SESSION["battle_message"] = "🚢 Bateau coulé !";
            } else {
                $_SESSION["battle_message"] = "🔥 Touché !";
            }

        } else {
            $_SESSION["battle_message"] = "💧 Raté !";
        }

    } else {
        $_SESSION["battle_message"] = null;
    }

    header("Location: ../index.php");
    exit;
}