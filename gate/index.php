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
        sleep(1);
        if ($_GET['api'] == "load") {
            foreach ($user->getUserData()['groups'] as $group) {
                if (in_array($group, $admins)) {
                    $data = [
                        "data" => [
                            "cpu" => cpu_load(),
                            "mem" => mem_used(),
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

function mem_used() {
    return exec("free|grep 'Mem:'|awk '{print $3/$2*100}'");
}

function cpu_load() {
    return exec("vmstat 1 2|tail -1|awk '{print 100-$15}'");
}
