<?php
session_start();

$fichier = "hit.json";

if (!file_exists($fichier)) {
    file_put_contents($fichier, json_encode([
        "j1" => null,      
        "j2" => null,       
        "dernierCoup" => [
            "joueur" => null,  
            "touche" => false  // true = touché, false = manqué
        ]
    ]));
}

$etat = json_decode(file_get_contents($fichier), true);


if (isset($_POST["joueur1"])) {
    if ($etat["j1"] === null) {
        $etat["j1"] = session_id();
        $_SESSION["role"] = "Joueur 1";
        save_state($fichier, $etat);
    }
}
if (isset($_POST["joueur2"])) {
    if ($etat["j2"] === null) {
        $etat["j2"] = session_id();
        $_SESSION["role"] = "Joueur 2";
        save_state($fichier, $etat);
    }
}

//  Quand un joueur tire
if (isset($_POST["tirer"])) {
    $coordX = $_POST["x"] ?? null;
    $coordY = $_POST["y"] ?? null;
    $color = $case['checked'] == 1 ? 'blue' : 'grey';
    if ($case['checked'] == 1 && $case['boat'] > 0) {
        $color = 'red';
    }   
        
        $id_grid = $case['id_grid'];
    
    $etat["dernierCoup"] = [
        "joueur" => $_SESSION["role"],
        "x" => $coordX,
        "y" => $coordY,
        "touche" => $touche
    ];
    
    file_put_contents($fichier, json_encode($etat));
}

