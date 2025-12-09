<?php

$fichier = "hit.json";

if (!file_exists($fichier)) {
  file_put_contents($fichier, json_encode(["hit" => true/false, "j2" => null]));
}

$etat = json_decode(file_get_contents($fichier), true);

function save_state($file, $data) {
  file_put_contents($file, json_encode($data));
}
