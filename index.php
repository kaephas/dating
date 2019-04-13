<?php
/**
 * Created by PhpStorm.
 * User: Kaephas Kain
 * Date: 2019-04-12
 * Filename: index.php
 * Description: loads error reporting, composer, fat free, setting default route to views/home.html
 */

//Turn on error reporting
ini_set('display_errors' ,1);
error_reporting(E_ALL);

//require autoload file
require_once('vendor/autoload.php');

//create an instance of the Base class
$f3 = Base:: instance();

//Turn on Fat-free error reporting
$f3 -> set('DEBUG', 3);

//Define a default route
$f3->route('GET /', FUNCTION()
{
    $view = new Template();
    echo $view-> render('views/home.html');
});

//run Fat-free
$f3 -> run();
