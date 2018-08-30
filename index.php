<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . "/auth/user.php";

$user = new user();

if ($user->isLogged()) {

    $userData = $user->getUserData();

    if (isset($_GET['vpn'])) {
        include "template/vpn.phtml";
    } else {
        include "template/app.phtml";   
    }
} else {

    if (isset($_GET['login'])) {
        $user->login();
    } else {
        include "template/login.phtml";
    }
}
