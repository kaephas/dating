<?php
/**
 * Created by PhpStorm.
 * User: Kaephas Kain
 * Date: 2019-04-12
 * Filename: index.php
 * Description: loads error reporting, composer, fat free, setting default route to views/home.html
 */

session_start();

//Turn on error reporting
ini_set('display_errors' ,1);
error_reporting(E_ALL);

//require autoload file
require_once('vendor/autoload.php');
require_once('model/validate.php');

//create an instance of the Base class
$f3 = Base::instance();

$f3->set('states', array("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware",
    "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky",
    "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi",
    "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico",
    "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania",
    "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont",
    "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"));

$f3->set('outInterests', array("hiking", "biking", "swimming", "collecting", "walking", "climbing"));

//Turn on Fat-free error reporting
$f3->set('DEBUG', 3);

//Define a default route (dating splash page)
$f3->route('GET /', function()
{
    $view = new Template();
    echo $view->render('views/home.html');
});

// personal information form
$f3->route('GET|POST /personal', function($f3)
{
    // reset session for first form
    $_SESSION = array();
    // if any values have been posted
    if(!empty("$_POST")) {
        // first, last, age, gender, phone
        $first = $_POST['first'];
        $last = $_POST['last'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];

        // add to f3 hive
        $f3->set('first', $first);
        $f3->set('last', $last);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);

        // validate
        if(validPersonal()) {
            // set session variables
            $_SESSION['first'] = $first;
            $_SESSION['last'] = $last;
            $_SESSION['age'] = $age;
            $_SESSION['gender'] = $gender;
            $_SESSION['phone'] = $phone;

            // to next form page
            $f3->reroute('/profile');
        }

    }
    // GET  or  any invalid form data
    $view = new Template();
    echo $view->render('views/personal_info.html');
});

// profile information form
$f3->route('GET|POST /profile', function($f3)
{
    // email, state, seeking, bio



    $view = new Template();
    echo $view->render('views/profile.html');
});

// interests form
$f3->route('POST /interests', FUNCTION()
{
    // get post variables from previous form
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['state'] = strtoupper($_POST['state']);
    $_SESSION['seeking'] = $_POST['seeking'];
    $_SESSION['bio'] = $_POST['bio'];

    $view = new Template();
    echo $view->render('views/interests.html');
});

// profile summary
$f3->route('POST /summary', FUNCTION()
{
    // get post variables from last form
    $_SESSION['indoor'] = $_POST['indoor'];
    $_SESSION['outdoor'] = $_POST['outdoor'];
    $interests = "";
    foreach($_POST['indoor'] as $interest) {
        $interests .= $interest . " ";
    }
    foreach($_POST['outdoor'] as $interest) {
        $interests .= $interest . " ";
    }
    $_SESSION['interests'] = substr($interests,0, -1);

    $view = new Template();
    echo $view->render('views/summary.html');
});

//run Fat-free
$f3 -> run();
