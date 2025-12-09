<?php

if (isset($_POST["case"])) {
    echo $_POST["case"];

    header('Location: ./index.php');
    exit;
}