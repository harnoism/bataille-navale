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
    <div class="main-container">
      <div class="col-labels">
        <div>1</div>
        <div>2</div>
        <div>3</div>
        <div>4</div>
        <div>5</div>
        <div>6</div>
        <div>7</div>
        <div>8</div>
        <div>9</div>
        <div>10</div>
      </div>
        
<div class="row-and-grid">
  <div class="row-labels">
      <div>A</div>
      <div>B</div>
      <div>C</div>
      <div>D</div>
      <div>E</div>
      <div>F</div>
      <div>G</div>
      <div>H</div>
      <div>I</div>
      <div>J</div>
  </div>
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
</div>

<?php

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
        echo "<div class='win-message'>🎉 Vous avez gagné ! Tous les bateaux sont coulés. 🎉</div>";
    }else{
        $_SESSION["role"] === 'joueur2';
    } 
  }
  if($_SESSION["role"] === 'joueur2'){
    if ($totalBoats > 0 && $hits === $totalBoats) {
        echo "<div class='win-message'>🎉 Vous avez gagné ! Tous les bateaux sont coulés. 🎉</div>";
    }else{
        $_SESSION["role"] === 'joueur1';
    } 
  }
    ?>
    
    </div>
  </div>
  <form method="post" class="reset-form">
    <button type="submit" name="reset_total" class="reset-button">
      ❌ Fin de partie (RESET)
    </button>
  </form>
  </body>
</html>