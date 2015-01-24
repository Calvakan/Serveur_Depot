  <?php

// include the config
require_once('config/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/fr.php');

// load the pre_registration class
require_once('classes/Pre_Registration.php');

// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$pre_registration = new Pre_Registration();

// showing the register view (with the registration form, and messages/errors)
include("views/pre_register.php");
