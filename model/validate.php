<?php
/**
 * Created by PhpStorm.
 * User: Kaephas
 * Date: 5/3/2019
 * Time: 18:31
 */

function validPersonal() {
    global $f3;
    $isValid = true;

    if(!validName($f3->get('first'))) {
        $isValid = false;
        $f3->set('errors["first"]', "Please enter a valid first name.");
    }

    if(!validName($f3->get('last'))) {
        $isValid = false;
        $f3->set('errors["last"]', "Please enter a valid last name.");
    }

    if(!validAge($f3->get('age'))) {
        $isValid = false;
        $f3->set('errors["age"]', "Please enter a valid age.");
    }

    if(!validGender($f3->get('gender'))) {
        $isValid = false;
        $f3->set('errors["gender"]', "An option besides the choices was chosen.");
    }

    if(!validPhone($f3->get('phone'))) {
        $isValid = false;
        $f3->set('errors["phone"]', "Please enter a valid phone number.");
    }

    return $isValid;

}

function validProfile() {

}

function validInterests() {

}

// check if alphabetic
function validName($name) {
    // required field
    return isset($name) && $name != "" && ctype_alpha($name);

}

// check if numeric and 18 - 118 inclusive
function validAge($age) {
    // required field
    return is_numeric($age) && $age >= 18 && $age <= 118;

}

// check if phone is valid
function validPhone($phone) {
    // required field
    $pattern = "/\d{3}[-]?\d{3}[-]?\d{4}/";
    return is_numeric($phone) && preg_match($pattern, $phone);

}

// check if email is valid
function validEmail($email) {
    // required field
    return filter_var($email, FILTER_VALIDATE_EMAIL);

}

// check if outdoor interests chosen are valid options
function validOutdoor($outdoor) {
    // not required
    $found = false;
//    foreach($outdoor as $interest) {
//        if(!in_array($interest, ))
//    }
    return empty($outdoor) || $found;

}

// check if indoor interests chosen are valid options
function validIndoor($indoor) {
    // not required

}

// not on requirements
function validGender($gender) {
    // make sure only male or female chosen if either
    return !isset($gender) || !in_array($gender, array('male', 'female'));

}