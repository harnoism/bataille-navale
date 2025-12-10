<?php

define("HOST","Localhost");
define("USER","bataille_navale");
define("PASSWORD","");
define("DB_NAME","");

try {
       $pdo = new PDO("mysql:host=...", "user", "password");
   } catch (PDOException $e) {
       echo "Erreur de connexion : " . $e->getMessage();
   }
