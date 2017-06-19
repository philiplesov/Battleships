<?php
namespace Battleships\Controllers;

use Battleships\Classes\Layout as Layout;
use Battleships\Classes\GameEngine as GameEngine;

abstract class Controller 
{
    protected $layout;
    protected $gameEngine;

    protected $currentLayoutModel = [];
    protected $specialKeywords = ["EXIT","SHOW","RESTART"];

    public function __construct() 
    {
        $this->currentLayoutModel = [
            "message" => "",
            "shots" => ""
        ];
    }

    /**
     * Starts a game.
     */
    public function startGame() 
    {
        $this->gameEngine->placeShips();
    }

    /**
     * Finishes a game.
     */
    public function finishGame() 
    {
        $layout = $this->layout->getLayout($this->currentLayoutModel);
        $layout .= $this->layout->getCongratsLayout($this->gameEngine->getShotsCount());
        $this->printLayout($layout);

        $this->gameEngine->clearData();
        $this->layout->clearData();
    }

    /**
     * Exits a game.
     */
    public function exitGame() 
    {
        $this->gameEngine->clearData();
        $this->layout->clearData();
    }

    /**
     * Activates show hack.
     */
    public function makeItRain() 
    {
        $shipsPositions = $this->gameEngine->getAllShipsPositions();
        $this->currentLayoutModel = ["shots" => $shipsPositions, "message" => "show"];
    }

    /**
     * Restarts a game.
     */
    public function restartGame() 
    {
        $this->gameEngine->clearData();
        $this->layout->clearData();

        $layout = $this->layout->getLayout();
        $this->printLayout($layout);

        $this->gameEngine->placeShips();
        $this->currentLayoutModel = [
            "message" => "",
            "shots" => ""
        ];
    }

    /**
     * Manage behavior for special keyword
     *
     * @param      string  $keyword  The keyword
     */
    protected function manageSpecialKeyword($keyword) 
    {
        switch ($keyword) {
            case "EXIT":
                $this->exitGame();
                break;
            case "RESTART":
                $this->restartGame();
                break;
            case "SHOW":
                $this->makeItRain();
                break;
            default:
                break;
        }

        return;
    }

    /**
     * Validates the user input
     *
     * @param      string   $input  The input
     *
     * @return     boolean  validated
     */
    protected function validateUserInput($input) 
    {
        if (in_array($input, $this->specialKeywords)) {
            return true;
        }
        if (!preg_match("/^[A-Za-z]{1}[0-9]{1,5}/", $input)) {
            return false;
        }
        if (ord(substr($input, 0, 1)) > ($this->layout->getGridHeight()+64) || intval(substr($input, 1)) > $this->layout->getGridWidth() || intval(substr($input, 1)) < 1) {
            return false;
        }

        return true;
    }

    /**
     * Determines if special keyword.
     *
     * @param      string   $keyword  The keyword
     *
     * @return     boolean  True if special keyword, False otherwise.
     */
    protected function isSpecialKeyword($keyword) 
    {
        if (in_array($keyword, $this->specialKeywords)) {
            return true;
        }

        return false;
    }

    /**
     * Determines if in game.
     *
     * @return     boolean  True if in game, False otherwise.
     */
    public function isInGame() 
    {
        return $this->gameEngine->isInGame();
    }


    /**
     * Prints error screen.
     */
    public function printError() {
        $this->currentLayoutModel["message"] = "error";
        $layout = $this->layout->getLayout(["message" => "error"]);

        $this->printLayout($layout);
    }
}