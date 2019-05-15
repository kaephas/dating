<?php
/**
 * File contains a class that represents a dating site premium member.
 * @author Kaephas Kain
 * @version 1.0
 */

/**
 * Class PremiumMember represents a premium member
 * that has the ability to choose interests
 *
 * @author Kaephas Kain
 * @version 1.0
 */
class PremiumMember extends Member
{
    private $_inDoorInterests = array();
    private $_outDoorInterests = array();
    //private $_image;

    // constructor

    /**
     * PremiumMember constructor, profile image set to default
     * @param string $fname     first name
     * @param string $lname     last name
     * @param int $age          age
     * @param string $gender    gender
     * @param string $phone     phone number
     * @return void
     */
    public function __construct($fname, $lname, $age, $gender, $phone)
    {
        parent::__construct($fname, $lname, $age, $gender, $phone);
        //$this->_image = 'images/profile.jpg';
    }

    // setters

    /**
     * Sets indoor interests choices
     * @param array $inDoorInterests    new choices
     * @return void
     */
    public function setIndoorInterests($inDoorInterests)
    {
        $this->_inDoorInterests = $inDoorInterests;
    }

    /**
     * Sets outdoor interests choices
     * @param array $outDoorInterests   new choices
     * @return void
     */
    public function setOutdoorInterests($outDoorInterests)
    {
        $this->_outDoorInterests = $outDoorInterests;
    }

    /**
     * Sets the filepath of the profile image
     * @param string $image     path of the image
     * @return void
     */
//    public function setImage($image)
//    {
//        $this->_image = $image;
//    }

    // getters

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

    /**
     * Gets the path of the profile image
     * @return string   image path
     */
//    public function getImage()
//    {
//        return $this->_image;
//    }




}
