<?php

if (isset($_POST["A1"])) {
    echo "A1"; 
    exit;
}

$coords = $_POST['case']; // "3-7"
list($row, $col) = explode('-', $coords);