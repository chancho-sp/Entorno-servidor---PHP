<?php
session_start();

if (!isset($_COOKIE['usuario'])) {
    header("Location: index.php");
    exit;
}

$usuarioCookie = $_COOKIE['usuario'];

?>