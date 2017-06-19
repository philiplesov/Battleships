<?php
namespace Battleships\Classes;

class GameEngine 
{

    private $width;
    private $height;

    private $ships = [];
    private $shotsFired = [];

    private $inGame;

    public function __construct() 
    {
        $config = parse_ini_file(ROOT_DIR . "/config.ini", true);

        $this->width = $config["grid"]["width"];
        $this->height = $config["grid"]["height"];

        foreach ($config["gameplay"]["ships"] as $ship) {
            $shipClassName = __NAMESPACE__ . "\\" . ucfirst($ship);
            $this->ships[] = new $shipClassName(intval($config["gameplay"][strtolower($ship)]["size"]), $ship);
        }
    }

    /**
     * Set positions for all of the ships
     */
    public function placeShips() 
    {
        shuffle($this->ships);
        foreach ($this->ships as $ship) {
            if(!$ship->getUsedPositions()) {
                $this->findCoordinatesForShip($ship);
            }
        }
        $this->inGame = true;
    }

    /**
     * Find free coordinates for all needed positions for a ship to exist
     *
     * @param      <type>  $ship   The ship
     */
    private function findCoordinatesForShip($ship) 
    {
        $initialPosition = $this->getFreePosition();

        $directions = ["+", "-"];
        $axisTypes = ["x", "y"];
        shuffle($directions);
        shuffle($axisTypes);

        foreach ($directions as $direction) {
            foreach ($axisTypes as $axis) {
                $tmpPositions = [];

                // Calculate last position value according to direction and axis
                $lastCoordinateValue = $direction == "+" ? $initialPosition[$axis] + $ship->getSize() - 1 : $initialPosition[$axis] - $ship->getSize() + 1;
                
                // Check if last position in this sequel is out of bounds
                $lastPosition = [
                    "x" => $axis == "x" ? $lastCoordinateValue : $initialPosition["x"],
                    "y" => $axis == "x" ? $initialPosition["y"] : $lastCoordinateValue
                ];
                
                if ($this->checkOutOfBoundsPosition($lastPosition)) {
                    continue;
                }

                $allValues = $axis == "x" ? range($initialPosition["x"], $lastPosition["x"]) : range($initialPosition["y"], $lastPosition["y"]);

                foreach ($allValues as $value) {
                    $currentPosition = [
                        "x" => $axis == "x" ? $value : $initialPosition["x"],
                        "y" => $axis == "x" ? $initialPosition["y"] : $value
                    ];

                    if (!$this->checkFreePosition($currentPosition)) {
                        break;
                    }

                    $tmpPositions[] = $currentPosition;
                }

                // Found positions equals needed size, the job is done and we exit the method
                if (count($tmpPositions) == $ship->getSize()) {
                    foreach ($tmpPositions as $position) {
                        $ship->setUsedPosition($position);
                    }
                    return;
                }
            }
        }

        $this->findCoordinatesForShip($ship);
    }


    /**
     * Processes a shot
     *
     * @param      array  $shot   The shot
     *
     * @return     array  Message and all shots fired
     */
    public function processShot($shot) 
    {
        $message = $shot["type"] = "miss";

        // Checks if shot's already been fired
        foreach ($this->shotsFired as $shotFired) {
            if ($shotFired["x"] == $shot["x"] && $shotFired["y"] == $shot["y"]) {
                $message = "error";
                return ["message" => $message, "shots" => $this->shotsFired];
            }
        }

        // Checks if a ship is hit
        foreach ($this->ships as $ship) {
            foreach ($ship->getUsedPositions() as $position) {
                if ($position["x"] != $shot["x"] || $position["y"] != $shot["y"]) {
                    continue;
                }

                $message = $shot["type"] = "hit";

                $ship->recordHit($shot);
                if ($ship->isSunk()) {
                    $message = "sunk";
                }
            }
        }

        $this->shotsFired[] = $shot;

        if ($this->isAllSunk()) {
            $message = "end";
        }

        return ["message" => $message, "shots" => $this->shotsFired];
    }

    /**
     * Gets the free position.
     *
     * @return     array  The free position.
     */
    private function getFreePosition() 
    {
        $tmpPosition["x"] = rand(1, $this->width);
        $tmpPosition["y"] = rand(1, $this->height);

        if (!$this->checkFreePosition($tmpPosition)) {
            $this->getFreePosition();
        }

        return $tmpPosition;
    }

    /**
     * Checks for free position in taken positions array
     *
     * @param      array   $position  The position
     *
     * @return     boolean 
     */
    private function checkFreePosition($position) 
    {
        foreach ($this->ships as $ship) {
            foreach ($ship->getUsedPositions() as $notFreePos) {
                if ($notFreePos["x"] == $position["x"] && $notFreePos["y"] == $position["y"]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks if position is out of bounds
     *
     * @param      array   $position  The position
     *
     * @return     boolean
     */
    private function checkOutOfBoundsPosition($position) 
    {
        if ($position["x"] > $this->width || $position["x"] < 1) {
            return true;
        }
        if ($position["y"] > $this->width || $position["y"] < 1) {
            return true;
        }

        return false;
    }

    /**
     * Determines if all ships sunk.
     *
     * @return     boolean  True if all sunk, False otherwise.
     */
    private function isAllSunk()
    {
        foreach ($this->ships as $ship) {
            if (!$ship->isSunk()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Clears fired shots and ships' data
     */
    public function clearData() 
    {
        foreach ($this->ships as $ship) {
            $ship->clearData();
        }

        $this->shotsFired = [];
        $this->inGame = false;
    }

    /**
     * Gets the shots count.
     *
     * @return     number  The shots count.
     */
    public function getShotsCount() 
    {
        return count($this->shotsFired);
    }

    /**
     * Gets all ships positions.
     *
     * @return     array  All ships positions.
     */
    public function getAllShipsPositions() 
    {
        $shipsPositions = [];

        foreach ($this->ships as $ship) {
            if ($ship->isSunk()) {
                continue;
            }
            foreach ($ship->getUsedPositions() as $position) {
                $shipsPositions[] = $position;
            }
        }

        return $shipsPositions;
    }

    /**
     * Determines if in game.
     *
     * @return     boolean  True if in game, False otherwise.
     */
    public function isInGame() 
    {
        return $this->inGame;
    }
}