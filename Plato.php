<?php
  include('./sql-connect.php');

  $sql = new SqlConnect();    //Instancie l’objet SQL -> $sql->db permettra d’exécuter des requêtes.

  $role = $_SESSION["role"];

  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1'; //la grille de l’autre joueur
  $query = 'SELECT * FROM '.$player;

  $req = $sql->db->prepare($query);
  $req->execute();
  $rows = $req->fetchAll(PDO::FETCH_ASSOC); //On récupère toutes les cases sous forme de tableaux associatifs 

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
<?php
  if (!empty($_SESSION["battle_message"])) {
    echo '<div class="info-message">'.$_SESSION["battle_message"].'</div>';
  }
?>
    <div class="main-container">
      <div class="col-labels">
        <div id="t1">1</div>
        <div id="t2">2</div>
        <div id="t3">3</div>
        <div id="t4">4</div>
        <div id="t5">5</div>
        <div id="t6">6</div>
        <div id="t7">7</div>
        <div id="t8">8</div>
        <div id="t9">9</div>
        <div id="t10">10</div>
      </div>
        
<div class="row-and-grid">
  <div class="row-labels">
      <div id="a1">A</div>
      <div id="a2">B</div>
      <div id="a3">C</div>
      <div id="a4">D</div>
      <div id="a5">E</div>
      <div id="a6">F</div>
      <div id="a7">G</div>
      <div id="a8">H</div>
      <div id="a9">I</div>
      <div id="a10">J</div>
  </div>
  <div class="grid-container">
    <?php
      foreach ($rows as $case) {        //Parcourir chaque case du joueur adverse.
        $color = $case['checked'] == 1 ? 'blue' : 'grey';
        if ($case['checked'] == 1 && $case['boat'] > 0) {
          $color = 'red';
        }
        $idgrid = $case['idgrid'];

        echo '<form method="post" action="./click_case.php" class="cell-form">';
        echo '<button class="cell '.$color.'" name="case" value="'.$idgrid.'"></button>'; //envoie case = idgrid à click_case.php.
        echo '</form>';
      }
    ?>
  </div>
</div>

<?php
  // if (!empty($_SESSION["message"])) {
  //   echo "<div class='message'>".$_SESSION["message"]."</div>";
  //   unset($_SESSION["message"]); // pour que le message disparaisse après refresh
  // }

  $hits = 0;
  $totalBoats = 0;

  foreach ($rows as $case) { //On compte les bateaux non touchés / touchés.
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
    <button type="submit"  name="reset_total" class="reset-button"> <!--submit = envoie les données au serveur-->
      ❌ Fin de partie (RESET)
    </button>
  </form>
  </body>
</html>