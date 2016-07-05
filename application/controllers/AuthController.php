<?php

/**
 * Class AuthController
 */
abstract class AuthController extends MY_Controller
{

    protected function hashPassword($password, $hash)
    {
        return password_hash($password . $hash, PASSWORD_DEFAULT);
    }

    protected function checkHashPassword($password, $hash, $password_hash)
    {
        return password_verify($password . $hash, $password_hash);
    }

    protected function generateHash()
    {
        return rand(10000, 99999);
    }
}