<?php
namespace Battleships\Controllers;

use Battleships\Classes\GameEngine as GameEngine;
use Battleships\Classes\Layout as Layout;

class WebController extends Controller 
{
    public function __construct() 
    {
        parent::__construct();

        if (empty($_SESSION)) {
            $this->layout = new Layout();
            $this->gameEngine = new GameEngine();
        } else {
            $this->layout = unserialize($_SESSION["layout"]);
            $this->gameEngine = unserialize($_SESSION["gameEngine"]);
        }
    }

    public function startGame() 
    {
        parent::startGame();

        $layout = $this->layout->getLayout($this->currentLayoutModel);
        $this->printLayout($layout);
    }

    public function playingGame($input) 
    {
        $layout = "";

        if (empty($input["coord"])) {
            $this->printError();
            return;
        }

        $userInput = strtoupper($input["coord"]);

        if (!$this->validateUserInput($userInput)) {
            $this->printError();
            return;
        }

        if ($this->isSpecialKeyword($userInput)) {
            $this->manageSpecialKeyword($userInput);
            return;
        }

        $shot = [
            "x" => intval(ord(substr($userInput, 0 ,1))) - 64,
            "y" => intval(substr($userInput, 1))
        ];
        $this->currentLayoutModel = $this->gameEngine->processShot($shot);

        if ($this->currentLayoutModel["message"] == "end") {
            $this->finishGame();
            return;
        }
        
        $layout = $this->layout->getLayout($this->currentLayoutModel);

        $this->printLayout($layout);
        return;
    }

    public function exitGame() 
    {
        $layout = $this->layout->getLayout($this->currentLayoutModel);
        $this->printLayout($layout);

        parent::exitGame();
    }

    public function makeItRain() 
    {
        parent::makeItRain();

        $layout = $this->layout->getLayout($this->currentLayoutModel);
        $this->printLayout($layout);
    }

    public function __destruct() {
        $_SESSION["layout"] = serialize($this->layout);
        $_SESSION["gameEngine"] = serialize($this->gameEngine);
    }

    /**
     * Prints layout
     *
     * @param      string  $layout  The layout
     */
    public function printLayout($layout) 
    {
        echo "<pre>" . $layout . "</pre>";
    }
}