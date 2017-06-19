<?php
use Battleships\Controllers\CliController;
use Battleships\Controllers\WebController;

// Get initialization
require_once 'init.php';

if (php_sapi_name() === "cli") {
    $controller = new CliController();
    $controller->startGame();   
} else {
    session_start();
    $controller = new WebController();

    empty($_POST["coord"]) && empty($_SESSION) ? $controller->startGame() : $controller->playingGame($_POST);

    include(ROOT_DIR . "/public/form.php");
}

