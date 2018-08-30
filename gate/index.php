<?php

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../auth/user.php";

$user = new auth\user();

if ($user->isLogged()) {

    $userData = $user->getUserData();

    if (isset($_GET['vpn'])) {
        include __DIR__ . "/../template/vpn.phtml";
    } else if (isset($_GET['logout'])) {
        $user->logout();
    } else {
        include __DIR__ . "/../template/app.phtml";
    }
} else {

    if (isset($_GET['login'])) {
        header("Location: login.php");
    } else {
        include __DIR__ . "/../template/login.phtml";
    }
}
