<?php
session_start();
include('./sql-connect.php');

if (isset($_POST["case"])) {
  $sql = new SqlConnect();

  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1';
  var_dump($player);
  $query = '
    UPDATE '.$player.'
    SET checked = CASE WHEN checked = 0 THEN 1 ELSE 0 END
    WHERE idgrid = :cell;
  ';

  $req = $sql->db->prepare($query);           //On prépare la requête.
  $req->execute(['cell' => $_POST["case"]]);          //Exécute en donnant la valeur de la case.

  header("Location: ../index.php");

  exit;
}