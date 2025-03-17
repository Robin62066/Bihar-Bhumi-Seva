<?php
include_once __DIR__ . DIRECTORY_SEPARATOR . "../init.php";
include_once "config.php";
include_once "constant.php";
include_once "session.php";
include_once "database.php";
include_once "functions.php";
include_once "user_function.php";
include_once "restapi.class.php";
include_once "Eko.php";
include_once "razorpay/Razorpay.php";
include_once "Permission.php";

$db = new Database(DATABASE, USERNAME, PASSWORD, HOSTNAME);
function db_connect()
{
    $db = Database::instance();
    return $db;
}
