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
$f3->set('inInterests',
    array("tv", "movies", "cooking", "board games", "puzzles", "reading", "playing cards", "video games"));

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
    if(!empty($_POST)) {
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
    if(!empty($_POST)) {
        // email, state, seeking, bio
        $email = $_POST['email'];
        $state = $_POST['state'];
        $seeking = $_POST['seeking'];
        $bio = $_POST['bio'];

        $f3->set('email', $email);
        $f3->set('state', $state);
        $f3->set('seeking', $seeking);
        $f3->set('bio', $bio);

        if(validProfile()) {
            $_SESSION['email'] = $email;
            $_SESSION['state'] = $state;
            $_SESSION['seeking'] = $seeking;
            $_SESSION['bio'] = $bio;

            $f3->reroute('/interests');
        }
    }

    $view = new Template();
    echo $view->render('views/profile.html');
});

// interests form
$f3->route('POST /interests', function($f3)
{
    // submit button has been pressed
    $outdoor = $_POST['outdoor'];
    $indoor = $_POST['indoor'];

    $f3->set('outdoor', $outdoor);
    $f3->set('indoor', $indoor);

    // all options selected are in the original arrays (or none selected)
    if (validInterests()) {
        $_SESSION['outdoor'] = $outdoor;
        $_SESSION['indoor'] = $indoor;

        // combine interests
        $interests = "";
        if (!empty($_SESSION['indoor'])) {
            foreach ($_SESSION['indoor'] as $interest) {
                $interests .= $interest . ", ";
            }
        }
        if (!empty($_SESSION['outdoor'])) {
            foreach ($_SESSION['outdoor'] as $interest) {
                $interests .= $interest . ", ";
            }
        }
        // remove trailing comma and space
        $_SESSION['interests'] = substr($interests, 0, -2);

        // go to summary page
        $f3->reroute('/summary');
    }
    // didn't validate
    $view = new Template();
    echo $view->render('views/summary.html');
});

// if 1st load or errors after posting
$f3->route('GET /interests', function($f3)
{

    $view = new Template();
    echo $view->render('views/interests.html');
});

// profile summary
$f3->route('GET /summary', function()
{
    $view = new Template();
    echo $view->render('views/summary.html');
});

//run Fat-free
$f3 -> run();
