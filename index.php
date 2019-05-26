<?php
/**
 * File contains routing for dating website using fat-free framework
 *
 * @author Kaephas Kain
 * @version 1.0
 * @see https://fatfreeframework.com/3.6/home
 */

//Turn on error reporting
ini_set('display_errors' ,1);
ini_set('file_uploads', 1);
error_reporting(E_ALL);

//require autoload file
require_once 'vendor/autoload.php';
require_once 'model/validate.php';

session_start();

//create an instance of the Base class
$f3 = Base::instance();



//$f3->set('outInterests',
//    array("hiking", "biking", "swimming", "collecting", "walking", "climbing"));
//$f3->set('inInterests',
//    array("tv", "movies", "cooking", "board games", "puzzles", "reading", "playing cards", "video games"));

//Turn on Fat-Free error reporting
//set_exception_handler(function($obj) use($f3){
//    $f3->error(500,$obj->getmessage(),$obj->gettrace());
//});
//set_error_handler(function($code,$text) use($f3)
//{
//    if (error_reporting())
//    {
//        $f3->error(500,$text);
//    }
//});
$f3->set('DEBUG', 3);

$db = new Database();

$interests = $db->getInterests();

$f3->set('inInterests', $interests[0]);
$f3->set('outInterests', $interests[1]);
$f3->set('maxInt', $interests[2]);

//$f3->set('states',
//    array("Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware",
//        "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky",
//        "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi",
//        "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico",
//        "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania",
//        "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont",
//        "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"));

$f3->set('states',
    array("Alabama" => "AL", "Alaska" => "AK", "Arizona" => "AZ", "Arkansas" => "AR", "California" => "CA",
        "Colorado" => "CO", "Connecticut" => "CT", "Delaware" => "DE", "Florida" => "FL", "Georgia" => "GA",
        "Hawaii" => "HI", "Idaho" => "ID", "Illinois" => "IL", "Indiana" => "IN", "Iowa" => "IA", "Kansas" => "KS",
        "Kentucky" => "KY", "Louisiana" => "LA", "Maine" => "ME", "Maryland" => "MD", "Massachusetts" => "MA",
        "Michigan" => "MI", "Minnesota" => "MN", "Mississippi" => "MS", "Missouri" => "MO", "Montana" => "MT",
        "Nebraska" => "NE", "Nevada" => "NV", "New Hampshire" => "NH", "New Jersey" => "NJ", "New Mexico" => "NM",
        "New York" => "NY", "North Carolina" => "NC", "North Dakota" => "ND", "Ohio" => "OH", "Oklahoma" => "OK",
        "Oregon" => "OR", "Pennsylvania" => "PA", "Rhode Island" => "RI", "South Carolina" => "SC",
        "South Dakota" => "SD", "Tennessee" => "TN", "Texas" => "TX", "Utah" => "UT", "Vermont" => "VT",
        "Virginia" => "VA", "Washington" => "WA", "West Virginia" => "WV", "Wisconsin" => "WI", "Wyoming" => "WY"));

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
        $premium = $_POST['premium'];

        // add to f3 hive
        $f3->set('first', $first);
        $f3->set('last', $last);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);
        $f3->set('premium', $premium);

        // validate
        if(validPersonal()) {

            // store in class
            if($premium == 'true') {
                $member = new PremiumMember($first, $last, $age, $gender, $phone);
            } else {
                $member = new Member($first, $last, $age, $gender, $phone);
            }
            $_SESSION['member'] = $member;


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

            // store new data in class
            $_SESSION['member']->setEmail($email);
            $_SESSION['member']->setState($state);
            $_SESSION['member']->setSeeking($seeking);
            $_SESSION['member']->setBio($bio);

            // check if premium member
            if(get_class($_SESSION['member']) == 'PremiumMember') {
            //if($_SESSION['premium'] == true) {
                $f3->reroute('/interests');
            } else {
                // skip interests
                global $db;
                $db->insertMember($_SESSION['member']);
                $f3->reroute('/summary');
            }
        }
    }
    $view = new Template();
    echo $view->render('views/profile.html');
});

// if 1st load of interests
$f3->route('GET /interests', function()
{
    $view = new Template();
    echo $view->render('views/interests.html');
});

// after post interests
$f3->route('POST /interests', function($f3)
{
    // check if any values in interests and if so, are valid

    // no values selected = ok
    $validate = true;

    // validate outdoor values
    if(sizeof($_POST['outdoor']) > 0) {
        // store in hive for stickiness
        $outdoor = $_POST['outdoor'];
        $f3->set('outdoor', $outdoor);
        $validate = validOutdoor($outdoor);
    }
    // validate indoor values
    if(sizeof($_POST['indoor']) > 0) {
        // store in hive for stickiness
        $indoor = $_POST['indoor'];
        $f3->set('indoor', $indoor);
        $validate = $validate && validIndoor($indoor);
    }
    // image validation
    if(!empty($_FILES['image']['name'])) {
        // can't make image info sticky (path is not on server)
        $image = $_FILES['image'];
        $f3->set('image', $_FILES['image']);

        // get storage path to attempt
        $path = 'uploads/' . basename($image["name"]);

        $validate =  $validate && validImage($image, $path);


    }

    // all options selected are in the original arrays (or none selected)
    // and image is either not set or has been uploaded
    if ($validate) {
        // if file upload success
        $upload = true;
        if(!empty($_FILES['image']['name'])) {
            if (move_uploaded_file($image['tmp_name'], $path)) {
                $_SESSION['member']->setImage($path);
            } else {
                $upload = false;
                $f3->set('errors["image"]', 'Error. Upload failed. Please try a different file.');
            }
        }
        if($upload) {
            // add interests to object in session
            $outInt = array();
            $inInt = array();
            $outOpt = $f3->get('outInterests');
            $inOpt = $f3->get('inInterests');

            foreach ($outdoor as $key) {
                $outInt[] = $outOpt[$key];
            }
            foreach ($indoor as $key) {
                $inInt[] = $inOpt[$key];
            }
            $_SESSION['member']->setOutdoorInterests($outInt);
            $_SESSION['member']->setIndoorInterests($inInt);

            // add to database
            global $db;
            $db->insertMember($_SESSION['member']);

            // go to summary page
            $f3->reroute('/summary');
        }
    }
    // didn't validate
    $view = new Template();
    echo $view->render('views/interests.html');
});

// profile summary
$f3->route('GET /summary', function($f3)
{
    if(get_class($_SESSION['member']) == 'PremiumMember') {
        $indoor = $_SESSION['member']->getIndoorInterests();
        $outdoor = $_SESSION['member']->getOutdoorInterests();
        $interests = "";
        // make sure there's something to iterate over
        if(sizeof($indoor) > 0) {
            foreach ($indoor as $interest) {
                $interests .= $interest . ', ';
            }
        }
        if(sizeof($outdoor) > 0) {
            foreach ($outdoor as $interest) {
                $interests .= $interest . ', ';
            }
        }

        $interests = substr($interests, 0, -2);
        $f3->set('allInterests', $interests);
    }
    $f3->set('class', get_class($_SESSION['member']));
    $view = new Template();
    echo $view->render('views/summary.html');
});

$f3->route('GET /admin', function($f3) {

    global $db;
    $f3->set('members', $db->getMembers());
    $view = new Template();
    echo $view->render('views/admin.html');
});

//run Fat-free
$f3 -> run();
