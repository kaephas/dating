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
    global $f3;
    $isValid = true;
    // email, state, seeking
    if(!validEmail($f3->get('email'))) {
        $isValid = false;
        $f3->set('errors["email"]', "Please enter a valid email.");
    }

    if(!validState($f3->get('state'))) {
        $isValid = false;
        $f3->set('errors["state"]', "Please choose a state.");
    }

    if(!validGender($f3->get('seeking'))) {
        $isValid = false;
        $f3->set('errors["seeking"]', "An option besides the choices was chosen.");
    }
    return $isValid;
}

function validInterests() {
    global $f3;
    $isValid = true;
    if(!validIndoor($f3->get('indoor'))) {
        $isValid = false;
        $f3->set('errors["indoor"]', "Please choose a valid indoor option.");
    }
    if(!validOutdoor($f3->get('outdoor'))) {
        $isValid = false;
        $f3->set('errors["outdoor"]', "Please choose a valid outdoor option.");
    }

    return $isValid;
    //return false;
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
    $pattern = "/^\(?\d{3}\)?[ .-]?\d{3}[ .-]?\d{4}$/";
    return preg_match($pattern, $phone);
}

// check if email is valid
function validEmail($email) {
    // required field
    return filter_var($email, FILTER_VALIDATE_EMAIL);

}

// check if outdoor interests chosen are valid options
function validOutdoor($out) {
    // not required field
    global $f3;
    $valid = true;

    // empty ok, if not, make sure the value is in original array
    if(!empty($out)) {
        foreach($out as $interest) {
            if(!in_array($interest, $f3->get('outInterests'))) {
                $valid = false;
            }
        }
    }
    if(!$valid) {
        $f3->set('errors["outdoor"]', "Please choose a valid outdoor option.");
    }
    return $valid;

}

// check if indoor interests chosen are valid options
function validIndoor($in) {
    // not required field
    global $f3;
    $valid = true;

    // empty ok, if not, make sure the value is in original array
    if(!empty($in)) {
        foreach($in as $interest) {
            if(!in_array($interest, $f3->get('inInterests'))) {
                $valid = false;
            }
        }
    }
    if(!$valid) {
        $f3->set('errors["indoor"]', "Please choose a valid indoor option.");
    }
    return $valid;
}

// not on assignment requirements requirements
function validGender($gender) {
    // either not chosen OR one of the radio gender options
    return !isset($gender) || in_array($gender, array('Male', 'Female'));
}

function validState($state) {
    global $f3;
    // original array normal capitalization, (all caps on page to match example)
    return in_array(ucfirst(strtolower($state)), $f3->get('states'));
}

// $image = $_FILES['imageFile']]
function validImage($image) {
    $path = 'uploads/' . $image["name"];
    $upload = true;
    $type = strtolower(pathinfo($path,PATHINFO_EXTENSION));
    $check = getimagesize($image['tmp_name']);
    if($check !== false) {
        // file is an image
    } else {
        $upload = false;
    }
    if(file_exists($path)) {
        $upload = false;
    }
    if(!($type == 'jpg' || $type == 'png' || $type == 'jpeg')) {
        $upload = false;
    }

    if($upload) {
        if(move_uploaded_file($image['tmp_name'], $path)) {
            // success
        } else {
            $upload = false;
        }
    }
    return $upload;
}