<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("../../index.php");
    exit();
}

$_SESSION = [];

session_destroy();
header("Location: ../../index.php");
exit();