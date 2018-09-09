<?php

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../auth/user.php";

$admins = ["Domain Admins", "Enterpise Admins"];

$user = new auth\user();

if ($user->isLogged()) {

    $userData = $user->getUserData();

    if (isset($_GET['vpn'])) {
        $back = true;
        include __DIR__ . "/../template/vpn.phtml";

    } else if (isset($_GET['logout'])) {
        $user->logout();

    } else if (isset($_GET['api'])) {
        if ($_GET['api'] == "load") {
            foreach ($user->getUserData()['groups'] as $group) {
                if (in_array($group, $admins)) {
                    $data = [
                        "data" => [
                            "cpu" => sys_getloadavg(),
                            "mem" => getSystemMemInfo(),
                        ],
                        "status" => "success"
                    ];
                    exit(json_encode(array_merge($data)));
                }
            }
            exit(json_encode(["status" => "error"]));
        }
        exit(json_encode(["status" => "error"]));
        
    } else {
        include __DIR__ . "/../template/app.phtml";
    }

} else {
    header("Location: login.php");

}

function getSystemMemInfo() {
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = [];
    foreach ($data as $line) {
        list($key, $val) = explode(":", $line);
        $meminfo[$key] = trim($val);
    }
    return $meminfo;
}
