<?php
  include('./sql-connect.php');

  $sql = new SqlConnect();

  $role = $_SESSION["role"];

  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1';
  $query = 'SELECT * FROM '.$player;

  $req = $sql->db->prepare($query);
  $req->execute();
  $rows = $req->fetchAll(PDO::FETCH_ASSOC);

  $etat = json_decode(file_get_contents($GLOBALS['fichier']), true);

  if (isset($_POST["reset_total"])) {
    $etat = ["j1" => null, "j2" => null];
    file_put_contents($GLOBALS['fichier'], json_encode($etat));
    $sql->db->exec("UPDATE joueur1 SET checked = 0");
    $req->execute();
    $sql->db->exec("UPDATE joueur2 SET checked = 0");
    $req->execute();

    session_unset();
    session_destroy();

    header("Location: index.php");
    exit;
  }

  $colsPerRow = 12;

?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Game</title>
      <link rel="stylesheet" href="style2.css">
  </head>
  <body>
    <h1>La partie commence:</h1>
    
    <div class="grid-container">
      <?php
        foreach ($rows as $case) {
          $color = $case['checked'] == 1 ? 'blue' : 'grey';
          if ($case['checked'] == 1 && $case['boat'] > 0) {
            $color = 'red';
          }
          
          $idgrid = $case['idgrid'];
          
          echo '<form method="post" action="./click_case.php" class="cell-form">';
          echo '<button class="cell '.$color.'" name="case" value="'.$idgrid.'"></button>';
          echo '</form>';
        }

      $hits = 0;
      $totalBoats = 0;

      foreach ($rows as $case) {
          if ($case['boat'] > 0) {
              $totalBoats++;
              if ($case['checked'] == 1) {
                  $hits++;
              }
          }
      }
      if($_SESSION["role"] === 'joueur1'){
        if ($totalBoats > 0 && $hits === $totalBoats) {
            echo "<div class='win-message'>🎉 Vous avez gagné ! Tous les bateaux sont coulés.</div>";
        }else{
            $_SESSION["role"] === 'joueur2';
        } 
      }
      if($_SESSION["role"] === 'joueur2'){
        if ($totalBoats > 0 && $hits === $totalBoats) {
            echo "<div class='win-message'>🎉 Vous avez gagné ! Tous les bateaux sont coulés.</div>";
        }else{
            $_SESSION["role"] === 'joueur1';
        } 
      }
      ?>
    </div>

    <form method="post" class="reset-form">
      <button type="submit" name="reset_total" class="reset-button">
        ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>