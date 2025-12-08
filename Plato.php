<?php
    function initialisation():array{
        $nomsBateaux = [
            1 => 'Torpilleur',
            2 => 'Sous-marin',
            3 => 'Croiseur',
            4 => 'Porte-avions'
        ];
        $grid = [
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        ];
        return $grid;

    }
    function init_bdd(PDO $pdo, array $gridJ1, array $gridJ2)
    {
        // Requête préparée une seule fois  /ajoute une nouvelle case de bateau dans la table positions.
        $stmt = $pdo->prepare("
            INSERT INTO positions (id, joueur, ligne, colonne, bateau, touche)
            VALUES (NULL, ?, ?, ?, ?, 0) 
        ");
        // --- J1 ---
        foreach ($gridJ1 as $l => $ligne) {
            foreach ($ligne as $c => $type) {
                if ($type > 0) {   //insère les cases avec un bateau ($type > 0)
                    // +1 car index PHP commence à 0 alors que la grille commence à 1
                    $stmt->execute(['J1', $l + 1, $c + 1, $type]); //on exécute la requête SQL, en envoyant les valeurs
                }
            }
        }
        // --- J2 ---
        foreach ($gridJ2 as $l => $ligne) {
            foreach ($ligne as $c => $type) {
                if ($type > 0) {
                    $stmt->execute(['J2', $l + 1, $c + 1, $type]);
                }
            }
        }
    }

    $etat = $_SESSION["prêt"] ?? "Pas prêt";


?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Choix</title>
  </head>
  <body>
    <h1>Phase de choix </h1>
    <h2>Votre choix : <strong><?= $etat ?></strong></h2>

    <td class="case" data-row="1" data-col="A"></td>
    <td class="case" data-row="2" data-col="B"></td>
    <td class="case" data-row="3" data-col="C"></td>
    <td class="case" data-row="4" data-col="D"></td>
    <td class="case" data-row="5" data-col="E"></td>
    <td class="case" data-row="6" data-col="F"></td>
    <td class="case" data-row="7" data-col="G"></td>
    <td class="case" data-row="8" data-col="H"></td>
    <td class="case" data-row="9" data-col="I"></td>
    <td class="case" data-row="10" data-col="J"></td>
  </body>
</html>