<?php
namespace Battleships\Controllers;

use Battleships\Classes\GameEngine as GameEngine;
use Battleships\Classes\Layout as Layout;

class CliController extends Controller 
{
    private $stdin;

    public function __construct() 
    {
        parent::__construct();

        $this->layout = new Layout();
        $this->gameEngine = new GameEngine();

        $this->stdin = fopen("php://stdin","r");
    }

    public function startGame() 
    {
        parent::startGame();
        $this->playingGame();
    }

    public function playingGame() 
    {
        while ($this->gameEngine->isInGame()) {
            $this->layout->clearCliLayout();
            $layout = $this->layout->getLayout($this->currentLayoutModel);
            $layout .= $this->layout->getUserInputLayout();
            $this->printLayout($layout);

            $userInput = strtoupper($this->getUserInput());

            if (!$this->validateUserInput($userInput)) {
                $this->printError();
                continue;
            }

            if ($this->isSpecialKeyword($userInput)) {
                $this->manageSpecialKeyword($userInput);
                continue;
            }

            $shot = [
                "x" => intval(ord(substr($userInput, 0 ,1))) - 64,
                "y" => intval(substr($userInput, 1))
            ];
            $this->currentLayoutModel = $this->gameEngine->processShot($shot);

            if ($this->currentLayoutModel["message"] == "end") {
                $this->finishGame();
            }
        } 
    }

    public function finishGame() 
    {
        $this->layout->clearCliLayout();
        parent::finishGame();
    }

    public function exitGame() 
    {
        $this->layout->clearCliLayout();
        parent::exitGame();
    }

    public function makeItRain() 
    {
        $this->layout->clearCliLayout();
        parent::makeItRain();
    }

    /**
     * Gets the user input.
     *
     * @return     <type>  The user input.
     */
    public function getUserInput() 
    {
        return trim(fgets($this->stdin));
    }

    /**
     * Prints layout
     *
     * @param      string  $layout  The layout
     */
    public function printLayout($layout) 
    {
        echo $layout;
    }

    public function __destruct() 
    {
        fclose($this->stdin);
    }
}