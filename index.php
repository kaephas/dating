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

//create an instance of the Base class
$f3 = Base:: instance();

//Turn on Fat-free error reporting
$f3 -> set('DEBUG', 3);

//Define a default route (dating splash page)
$f3->route('GET /', FUNCTION()
{
    $view = new Template();
    echo $view->render('views/home.html');
});

// personal information form
$f3->route('POST /personal', FUNCTION()
{
    $view = new Template();
    echo $view->render('views/personal_info.html');
});

// profile information form
$f3->route('POST /profile', FUNCTION()
{
    // get post variables from previous form
    $_SESSION['first'] = $_POST['first'];
    $_SESSION['last'] = $_POST['last'];
    $_SESSION['age'] = $_POST['age'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['phone'] = $_POST['phone'];

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
