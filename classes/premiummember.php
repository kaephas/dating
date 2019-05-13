<?php

/**
 * Class PremiumMember represents a premium Member
 * that has the ability to choose interests
 *
 * @author Kaephas Kain
 * @version 1.0
 */
class PremiumMember extends Member
{
    private $_inDoorInterests = array();
    private $_outDoorInterests = array();
    // private $_image;

    // getters

    /**
     * Sets indoor interests choices
     * @param array $inDoorInterests    new choices
     */
    public function setIndoorInterests($inDoorInterests)
    {
        $this->_inDoorInterests = $inDoorInterests;
    }

    /**
     * Sets outdoor interests choices
     * @param array $outDoorInterests   new choices
     */
    public function setOutdoorInterests($outDoorInterests)
    {
        $this->_outDoorInterests = $outDoorInterests;
    }

    // setters

    /**
     * Gets indoor interests choices
     * @return array    indoor interests
     */
    public function getIndoorInterests()
    {
        return $this->_inDoorInterests;
    }

    /**
     * Gets outdoor interests choices
     * @return array    outdoor choices
     */
    public function getOutdoorInterests()
    {
        return $this->_outDoorInterests;
    }
}
