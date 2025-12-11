<?php
  include('./sql-connect.php');

  $sql = new SqlConnect();

  $role = $_SESSION["role"];

  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1';
  $query = 'SELECT * FROM '.$player;

  $req = $sql->db->prepare($query);
  $req->execute();
  $rows = $req->fetchAll(PDO::FETCH_ASSOC);
  

  if (isset($_POST["reset_total"])) {
  $sql->db->exec("UPDATE $table SET checked = 0, boat = 0");
  save_state($GLOBALS['fichier'], $db);

  session_unset();
  session_destroy();

  header("Location: index.php");
  exit;
}
  
  $colsPerRow = 10;

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
      ?>
    </div>

    <form method="post" action="index.php" class="reset-form">
      <button type="submit" name="reset_total" class="reset-button">
        ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>