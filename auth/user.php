<?php

namespace auth;

require __DIR__ . "/../config/creds.php";

session_start();

use Jumbojett\OpenIDConnectClient;

class user {

    private $oidc;
    private $auth;

    function __construct() {
        if (is_string($_SESSION['user'])) {

            $this->getUserSession();
            $this->auth = true;
        }
    }

    function login() {
        if (!$this->auth) {

            $this->oidc = new OpenIDConnectClient(creds::AUTH_URL, creds::APP_ID, creds::APP_KEY);
            $this->oidc->addAuthParam(array('response_mode' => 'form_post'));
            $this->oidc->setRedirectURL(creds::REDIRECT);

            $this->oidc->authenticate();

            $this->setUserData();
            $this->setUserSession();
            $this->auth = true;
        }
    }

    function isLogged() {
        return $this->auth;
    }

    function logout() {

        $context = stream_context_create([
            'http' => [
                'header' => "Cookie: " . http_build_query($_COOKIE, '', '; ') . "\r\n"
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ]);

        // freya logout
        file_get_contents("https://10.0.10.20:5001//webapi/auth.cgi?api=SYNO.API.Auth&version=3&method=logout", false, $context);

        if (isset($_COOKIE[session_name()]))
            setcookie( session_name(), "", time()-3600, "/");
        $_SESSION = array();
        session_destroy();
        header("Location: " . creds::LOGOUT);
        die();
    }

    function setUserData() {
        $this->user = [
            "name"   => $this->oidc->getVerifiedClaims('name'),
            "uid"    => $this->oidc->getVerifiedClaims('unique_name'),
            "groups" => $this->oidc->getVerifiedClaims('groups'),
            "email"  => $this->oidc->getVerifiedClaims('mail')
        ];
    }

    function getUserSession() {
        $this->user["uid"]    = $_SESSION['user'];
        $this->user["name"]   = $_SESSION['user_name'];
        $this->user["groups"] = \json_decode($_SESSION['user_groups'], true);
        $this->user["email"]  = $_SESSION['user_email'];
    }

    function setUserSession() {
        $_SESSION['user']        = $this->user["uid"];
        $_SESSION['user_name']   = $this->user["name"];
        $_SESSION['user_groups'] = \json_encode($this->user["groups"]);
        $_SESSION['user_email']  = $this->user["email"];
    }

    function getUserData() {
        return [
            "name" => $_SESSION['user_name'],
            "uid" =>  $_SESSION['user'],
            "groups" => json_decode($_SESSION['user_groups'], true),
            "email" => $_SESSION['user_email']
        ];
    }
}
