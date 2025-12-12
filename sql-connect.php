<?php

class SqlConnect { //connexion PDO à MySQL. //Les propriétés private sont accessibles seulement dans la classe.
  public object $db;
  private string $host;
  private string $port;
  private string $dbname; 
  private string $password;
  private string $user;

  public function __construct() {
    $this->host = '127.0.0.1';
    $this->port = '3306';
    $this->dbname = 'bataille_navale';
    $this->user = 'root';
    $this->password = 'root';

    $this->db = new PDO( //Création de l'objet PDO
      'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbname,
      $this->user,
      $this->password  // "this" devient l'objet PDO
    );

    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Active les exceptions en cas d’erreur SQL.
    $this->db->setAttribute(PDO::ATTR_PERSISTENT, false); //Désactive les connexions persistantes.
  }

  public function transformDataInDot($data) {
    $dataFormated = []; //Crée un tableau vide qui contiendra les données formatées.

    foreach ($data as $key => $value) {
      $dataFormated[':' . $key] = $value;
    }

    return $dataFormated;
  }
}