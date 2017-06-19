<?php
namespace Battleships\Classes;

class Layout 
{
    private $grid;

    private $currentMessage = "";
    private $prevMessage = "";

    private $currentLayout = [];
    private $prevLayout = [];

    public function __construct() 
    {
        $this->grid = new Grid();
    }

    /**
     * Gets the layout.
     *
     * @param      array  $layoutModel  The layout model
     */
    public function getLayout($layoutModel = null) 
    {
        $layout = "";

        $this->currentMessage = $layoutModel["message"];

        if ($this->currentMessage != "error") {
            $this->currentLayout = $layoutModel["shots"];
        } else {
            $this->currentLayout = $this->prevLayout;
        }

        if ($this->currentMessage == "show") {
            $this->grid->setShowMode();
        } 

        if ($this->currentMessage && $this->currentMessage != "show" && $this->currentMessage != "error") {
            $this->prevLayout = $this->currentLayout;
        }

        $layout .= $this->getMessageLayout();

        if ($this->currentMessage == "error" && $this->prevMessage == "show") {
            $this->currentLayout = $this->prevLayout;
        }

        $layout .= $this->grid->getGrid($this->currentLayout);

        if ($this->grid->isShowMode()) {
            $this->grid->setNormalMode();
        }

        if ($this->currentMessage != $this->prevMessage && $this->currentMessage != "error") {
            $this->prevMessage = $this->currentMessage;
        }

        return $layout;
    }

    /**
     * Gets the user input layout.
     *
     * @return     string  The user input layout.
     */
    public function getUserInputLayout() 
    {
        return "\nEnter coordinates (row, col), e.g. A5 = ";
    }

    /**
     * Gets the message layout.
     *
     * @param      string  $message  The message
     *
     * @return     string  The message layout.
     */
    public function getMessageLayout() 
    {
        $messageLayout = "";

        if ($this->currentMessage) {
            $messageLayout .= "*** " . ucfirst($this->currentMessage) . " ***";
        }

        $messageLayout .= "\n\n";
        return $messageLayout;
    }

    /**
     * Clears Cli Layout
     */
    public function clearCliLayout() 
    {
        system("clear");
    }

    /**
     * Draws congrats.
     *
     * @param      string  $shots  The shots
     */
    public function getCongratsLayout($shots) 
    {
        return "\nWell done! You completed the game in " . $shots . " shots\n";
    }

    /**
     * Gets the grid width.
     *
     * @return     number  The grid width.
     */
    public function getGridWidth() 
    {
        return $this->grid->getWidth();
    }

    /**
     * Gets the grid height.
     *
     * @return     number  The grid height.
     */
    public function getGridHeight() 
    {
        return $this->grid->getHeight();
    }


    /**
     * Clear stored data.
     */
    public function clearData() 
    {
        $this->currentMessage = "";
        $this->prevMessage = "";

        $this->currentLayout = [];
        $this->prevLayout = [];
    }
}