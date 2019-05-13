<?php
/**
 * Created by PhpStorm.
 * User: Kaephas
 * Date: 5/9/2019
 * Time: 23:00
 */

/**
 * Class Member represents a dating site member
 *
 * Stores profile information of a dating site member
 * @author Kaephas Kain
 * @version 1.0
 *
 */
class Member
{
    // fields
    private $_fname;
    private $_lname;
    private $_age;
    private $_gender;
    private $_phone;
    private $_email;
    private $_state;
    private $_seeking;
    private $_bio;


    /**
     * Member constructor setting values from personal info page
     * @param $_fname   member's first name
     * @param $_lname   member's last name
     * @param $_age     member's age
     * @param $_gender  member's gender
     * @param $_phone   member's phone
     * @return void
     */
    public function __construct($fname, $lname, $age, $gender, $phone)
    {
        $this->_fname = $fname;
        $this->_lname = $lname;
        $this->_age = $age;
        $this->_gender = $gender;
        $this->_phone = $phone;
    }


    // setters

    /**
     * Sets first name
     *
     * @param string $fname     new first name
     * @return void
     */
    public function setFname($fname) {
        $this->_fname = $fname;
    }

    /**
     * Sets last name
     *
     * @param string $lname     new last name
     * @return void
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * Sets age
     *
     * @param int $age  new age
     * @return void
     */
    public function setAge($age)
    {
        $this->_age = $age;
    }

    /**
     * Sets gender
     *
     * @param string $gender    new gender
     * @return void
     */
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    /**
     * Sets phone number
     *
     * @param string $phone     new phone number
     * @return void
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * Sets email address
     *
     * @param string $email     new email
     * @return void
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**Sets state
     *
     * @param string $state     new state
     * @return void
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * Sets seeking gender
     *
     * @param string $seeking   new gender
     * @return void
     */
    public function setSeeking($seeking)
    {
        $this->_seeking = $seeking;
    }

    /**
     * Sets biography
     *
     * @param string $bio  new bio
     */
    public function setBio($bio)
    {
        $this->_bio = $bio;
    }

    // getters

    /**
     * Gets the first name
     * @return string   first name
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * Gets the last name
     * @return string   last name
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * Gets age
     * @return int  age
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * Gets gender
     * @return string   gender
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * Gets phone number
     * @return string   phone number
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Gets email address
     * @return string   email
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Gets state
     * @return string   state
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * Gets seeking gender
     * @return string   seeking gender
     */
    public function getSeeking()
    {
        return $this->_seeking;
    }

    /**
     * Gets biography
     * @return string   bio
     */
    public function getBio()
    {
        return $this->_bio;
    }

}