<?php
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
    <?php for ( $i = 0; $i < count($grid); $i++ ) :?>
        <div class="row"> 
            <?php for ($j = 0; $j < count($grid[$i]); $j++ ): ?> 
                <div class="col border">
                    <form method="post" action="./click_case.php">
                        <button name="case" value="<?= $i ?>-<?= $j ?>"></button>
                    </form>
                </div>
            <?php endfor; ?>
        </div>
    <?php endfor; ?>

    <form method="post" action="index.php">
        <button type="submit" name="reset_total">
            ❌ Fin de partie (RESET)
        </button>
    </form>

  </body>
</html>