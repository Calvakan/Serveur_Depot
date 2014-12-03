<?php

/**
 * A simple PHP Login Script / ADVANCED VERSION
 * For more versions (one-file, minimal, framework-like) visit http://www.php-login.net
 *
 * @author Panique
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login-advanced/
 * @license http://opensource.org/licenses/MIT MIT License
 */

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

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/en.php');

// include the PHPMailer library
require_once('libraries/PHPMailer.php');

// load the login class
require_once('classes/Login.php');

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();
//$login->doLogout();

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    //include("serveur_depot/index.php");

    require_once('global_data.php');
    //require_once 'Mail.php';
    
    
    isset($_GET['key']) ? $key = $_GET["key"] : $key="";
    
	function send_confirmation_mail($mail_login, $key, $tableau_fichiers) {
		//include_once("PHPMailer.php");
		//include_once("config.php");
		$mail = new PHPMailer;
		$user_email = $mail_login."@etu.u-bourgogne.fr"; // Déclaration de l'adresse de destination.
        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            // Enable SMTP authentication
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            // Enable encryption, usually SSL/TLS
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            // Specify host server
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Port = EMAIL_SMTP_PORT;
        } else {
            $mail->IsMail();
        }

        $mail->From = EMAIL_VERIFICATION_FROM;
        $mail->FromName = EMAIL_VERIFICATION_FROM_NAME;
        $mail->AddAddress($user_email);
        $mail->Subject = EMAIL_CONFIRMATION_FINALE_SUBJECT;

		$message_txt = "Votre dépot a été validé le  ".date('d-M-Y')." via l'adresse IP ".$_SERVER['REMOTE_ADDR']."\n";
        $message_txt.="contenu du dépôt :\n";
		foreach ($tableau_fichiers as $entree_fichier) {
            $message_txt.="- ".$entree_fichier['dest_filename'];
        }
		
        $mail->Body = $message_txt;

        if(!$mail->Send()) {
            $this->errors[] = MESSAGE_VERIFICATION_MAIL_NOT_SENT . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
	}
    
    function save_file_to_final_directory($key2check){
        $cpt=0;
        $tableau_fichiers = array();
        $file = 'depot/confirmation_filelist.csv';
        
        $filetmp = 'confirmation_filelist.csv.tmp';
        
        if ((($handle = fopen($file, "r")) !== FALSE) &&
            (($handletmp = fopen($filetmp, "w")) !== FALSE)
            ) {
            $found=0;
            
            while (($lineArray = fgetcsv($handle, 4000)) !== FALSE) {
                       
                $key = $lineArray[0];
                $dest_filename = $lineArray[1];
                $destination_dir = $lineArray[2];
                $nom = $lineArray[3];
                $prenom = $lineArray[4];
                $mail_login = $lineArray[5];
                $date = $lineArray[6];
                $ip_address = $lineArray[7];
                if ($key == $key2check) {
                    $tableau_fichiers[$cpt]['key']=$key;
                    $tableau_fichiers[$cpt]['destination_dir']=$destination_dir;
                    $tableau_fichiers[$cpt]['dest_filename']=$dest_filename;
                    rename($destination_dir."tmp/".$dest_filename."_".$key, $destination_dir.$dest_filename);
                    $body="validation du fichier ".$dest_filename;
                    echo  htmlentities($body, ENT_QUOTES, $GLOBALS['charset'])."<BR/><BR/>";
                    $found=1;
                    $cpt++; 
                }
                else {
                    fputcsv($handletmp, $lineArray);
                }
            }
            fclose($handle);
            fclose($handletmp);
            if (!$found) {
                $body="cle de validation inconnue. Votre fichier doit avoir été validé et un accusé de reception envoyé. Si ce n'est pas le cas, contactez l'administrateur" ;
                echo  htmlentities($body, ENT_QUOTES, $GLOBALS['charset'])."<BR/><BR/>";
                
                unlink ($filetmp);
            }
            else {
                $body="Fichier(s) validé(s). Un accusé de réception vous a été envoyé par mail.";
                echo  htmlentities($body, ENT_QUOTES, $GLOBALS['charset'])."<BR/><BR/>";
                rename($filetmp, $file);
                send_confirmation_mail($mail_login, $key, $tableau_fichiers);

                return 1;
                
            }
        }
        else {
            
        echo "erreur inconnue";
        return 0;
    }
    }
    
    
    ////////////  //////////////////////////
    //////////////////////////
    //////////////
    $main_title = "Formulaire de dépôt de rapport de stages";
    
    
    if  ($key!= "") {
        $result = save_file_to_final_directory($key);
        if ($result) {
        }
    } else{
        echo "aucune cle fournie";
        
    }

    
} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("views/not_logged_in.php");
}
