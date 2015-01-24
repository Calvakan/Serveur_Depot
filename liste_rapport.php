 <?php
include_once 'classes/Login.php';

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
}
// include the config
require_once('config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/fr.php');

// load the registration class
require_once('classes/Liste_rapport.php');

// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$liste_rapport = new Liste_rapport();
$nb_rapports = $liste_rapport->nb_rapports;
$result = $liste_rapport->rapports;

// showing the register view (with the registration form, and messages/errors)
include("views/liste_rapport.php");