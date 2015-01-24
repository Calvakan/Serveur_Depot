<?php
include_once('global_data.php');
// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once('libraries/password_compatibility_library.php');
}
// include the config
require_once('config/config.php');

// include the PHPMailer library
require_once('libraries/PHPMailer.php');

// load the Depot_rapport class
require_once('classes/Depot_rapport.php');

// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$depot_rapport = new Depot_rapport();
$id_depot = $depot_rapport->id_depot;

// showing the register view (with the registration form, and messages/errors)
include("views/depot_rapport.php");
