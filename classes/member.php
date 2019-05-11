<?php
/**
 * Created by PhpStorm.
 * User: Kaephas
 * Date: 5/9/2019
 * Time: 23:00
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
     * Member constructor.
     * @param $_fname
     * @param $_lname
     * @param $_age
     * @param $_gender
     * @param $_phone
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
     * @param string $fname
     */
    public function setFname($fname) {
        $this->_fname = $fname;
    }

    /**
     * @param string $lname
     */
    public function setLname($lname)
    {
        $this->_lname = $lname;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->_age = $age;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * @param string $seeking
     */
    public function setSeeking($seeking)
    {
        $this->_seeking = $seeking;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->_bio = $bio;
    }

    // getters

    /**
     * @return string
     */
    public function getFname()
    {
        return $this->_fname;
    }

    /**
     * @return string
     */
    public function getLname()
    {
        return $this->_lname;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * @return string
     */
    public function getSeeking()
    {
        return $this->_seeking;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->_bio;
    }

}