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
