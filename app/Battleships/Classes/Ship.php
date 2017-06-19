<?php
namespace Battleships\Classes;

abstract class Ship 
{
    protected $size;
    protected $name;

    protected $usedPositions = [];

    private $hits = 0;

    public function __construct($size = null, $name = null) 
    {
        $this->size = $size;
        $this->name = $name;
    }

    /**
     * Records a hit
     */
    public function recordHit() 
    {
        $this->hits++;
    }

    /**
     * Gets the size.
     *
     * @return     number  The size.
     */
    public function getSize() 
    {
        return $this->size;
    }

    /**
     * Gets the used positions.
     *
     * @return     array  The used positions.
     */
    public function getUsedPositions() 
    {
        return $this->usedPositions;
    }

    /**
     * Sets used position to the array of used positions.
     *
     * @param      array  $position  The position
     */
    public function setUsedPosition($position) 
    {
        $this->usedPositions[] = $position;
    }

    /**
     * Determines if sunk.
     *
     * @return     boolean  True if sunk, False otherwise.
     */
    public function isSunk() 
    {
        return $this->hits >= $this->size;
    }

    /**
     * Clears data
     */
    public function clearData() 
    {
        $this->usedPositions = [];
        $this->hits = 0;
    }

}