<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . "/auth/user.php";

$user = new auth\user();

if (!$user->isLogged()) {
    $user->login();
}

header("Location: index.php");
