<?php

namespace auth;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . "/user.php";

$user = new user();

if ($user->isLogged()) {
    if (is_string($_GET['groups'])) {
        $min_groups = explode(",", $_GET['groups']);
        foreach ($user->getUserData()['groups'] as $group) {
            if (in_array($group, $min_groups)) {
                http_response_code(200);
                exit;
            }
        }
        http_response_code(403);
        exit;
    } else {
        http_response_code(200);
        exit;
    }
} else {
    http_response_code(403);
    exit;
}
