<?php

require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/../../auth/user.php";

$admins = ["Domain admins, Enterpise admins"];

$user = new auth\user();

if ($user->isLogged()) {

    foreach ($user->getUserData()['groups'] as $group) {
        if (in_array($group, $admins)) {
            download(__DIR__ . "/../../vpnstore/admin.ovpn", "Textilní škola (admins).ovpn");
        }
    }
    download(__DIR__ . "/../../vpnstore/default.ovpn", "Textilní škola.ovpn");

} else {
    header("Location: ../");
}

function download($file, $name) {
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"" . $name . "\""); 
    readfile($file);
    exit;
}
