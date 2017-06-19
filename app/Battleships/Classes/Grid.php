<?php
namespace Battleships\Classes;

class Grid 
{
    private $width;
    private $height;

    private $water;
    private $miss;
    private $hit;

    private $showMode = false;

    public function __construct() 
    {
        $config = parse_ini_file(ROOT_DIR . "/config.ini", true);
        $this->width = $config["grid"]["width"];
        $this->height = $config["grid"]["height"];
        $this->water = $config["grid"]["water"];
        $this->miss = $config["grid"]["miss"];
        $this->hit = $config["grid"]["hit"];
    }


    /**
     * Draws a cli grid.
     *
     * @param      array  $shots  Shots made
     */
    public function getGrid($shots = null) 
    {
        $grid = "";

        for ($i = 0; $i < $this->width+1; $i++) {
            for ($j = 0; $j < $this->height+1; $j++) {
                $isWater = true; 

                if ($i == 0 && $j == 0) {
                    $grid .= "  ";
                    continue;
                }
                if ($i == 0) {
                    $grid .= $j . " ";
                    continue;
                }
                if ($j == 0) {
                    $grid .= chr(64 + $i) . " ";
                    continue;
                }

                if ($shots) {
                    foreach ($shots as $key => $shot) {
                        if ($shot["x"] == $i && $shot["y"] == $j) {
                            $isWater = false;

                            if (!$this->showMode) {
                                $grid .= $shot["type"] == "hit" ? $this->hit . " " : $this->miss . " ";
                            } else {
                                $grid .= $this->hit . " ";
                            }

                            unset($shot[$key]);
                            break;
                        }
                    }
                }

                if ($isWater) {
                    $grid .= !$this->showMode ? $this->water . " " : "  ";
                }
            }
            $grid .= "\n";
        }

        return $grid;
    }


    /**
     * Sets the show mode.
     */
    public function setShowMode() 
    {
        $this->showMode = true;
    }

    /**
     * Determines if show mode.
     *
     * @return     boolean  True if show mode, False otherwise.
     */
    public function isShowMode() 
    {
        return $this->showMode ? true : false;
    }

    /**
     * Sets the normal mode.
     */
    public function setNormalMode()
    {
        $this->showMode = false;
    }

    /**
     * Gets the width.
     *
     * @return     number  The width.
     */
    public function getWidth() 
    {
        return $this->width;
    }

    /**
     * Gets the height.
     *
     * @return     number  The height.
     */
    public function getHeight() 
    {
        return $this->height;
    }
}