<?php
  include('./sql-connect.php');

  $sql = new SqlConnect();

  $role = $_SESSION["role"];
  if ($role === "joueur1") {
        $table = "joueur2";  // J1 regarde la grille du J2
  } else {
        $table = "joueur1";  // J2 regarde la grille du J1
    }

  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1';
  $query = 'SELECT * FROM '.$player;

  $req = $sql->db->prepare($query);
  $req->execute();
  $rows = $req->fetchAll(PDO::FETCH_ASSOC);
  
  $colsPerRow = 10;
?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Game</title>
  </head>
  <body>
    <h1>La partie commence: </h1>
    <?php
      for ($i = 0; $i < count($rows); $i += $colsPerRow) {
        echo '<div class="row">';
        for ($j = 0; $j < $colsPerRow; $j++) {
            if (isset($rows[$i + $j])) {
                $case = $rows[$i + $j];
                $color = $case['checked'] == 1 ? 'blue' : 'grey';
                if ($case['checked'] == 1 && $case['boat'] > 0) {
                  $color = 'red';
                }            

                $idgrid = $case['idgrid'];

                echo '<div class="col border">';
                echo '<form method="post" action="./click_case.php">';
                echo '<button name="case" value="'.$idgrid.'"></button>';
                echo '</form>';
                echo '</div>';
            }
        }
        echo '</div>';
      }
    ?>

    <form method="post" action="index.php">
      <button type="submit" name="reset_total">
        ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>