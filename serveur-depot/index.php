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

	//require_once('connect.php');
    require_once('global_data.php');
    //require_once 'Mail.php';
	error_reporting(E_ALL | E_STRICT);
  header("Content-Type: text/html; charset=UTF-8");
  
    // session
    //session_start();
  
  

  function stripAccents($string){
    
    
    $string= str_replace(
                         array(
                               'à', 'â', 'ä', 'á', 'ã', 'å',
                               'î', 'ï', 'ì', 'í',
                               'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
                               'ù', 'û', 'ü', 'ú',
                               'é', 'è', 'ê', 'ë',
                               'ç', 'ÿ', 'ñ',
                               ),
                         array(
                               'a', 'a', 'a', 'a', 'a', 'a',
                               'i', 'i', 'i', 'i',
                               'o', 'o', 'o', 'o', 'o', 'o',
                               'u', 'u', 'u', 'u',
                               'e', 'e', 'e', 'e',
                               'c', 'y', 'n',
                               ),
                         $string
                         );
    return $string;
    
  }
  
    // get params
    $user_param_list = array('prenom','nom', 'mail_login','departement', 'annee_formation', 'file');
    
    foreach($user_param_list as $item) {
        if (isset($_POST[$item])) {
            $user_data[$item]=$_POST[$item];
        } 
        else {
            if (isset($_SESSION[$item])) {
                $user_data[$item]=$_SESSION[$item];
            }
            else $user_data[$item]='';   
        }
      
       
    }
    
    isset($_POST['action']) ? $current_step=1:$current_step=0;
    
    
    function check_student_infos($user_data) {
        $user_param_list = array('prenom','nom', 'mail_login','departement', 'annee_formation');
        foreach($user_param_list as $item) {
            if ($user_data[$item]=='') return 0;
        }
        return 1;
    }
    
    
    function form_id_etudiant($table_department, $table_promotion_current_year, $user_data) {
        
        
        $text="<DIV>".htmlentities("Prénom :", ENT_QUOTES, $GLOBALS['charset'])."<INPUT TYPE='text' name='prenom' value='".$user_data['prenom']."' /></DIV>";
        $text.="<DIV>".htmlentities("Nom :", ENT_QUOTES, "UTF-8")."<INPUT TYPE='text' name='nom' value='".$user_data['nom']."'/></DIV>";
        $text.="<DIV>".htmlentities("Mail :", ENT_QUOTES, "UTF-8")."<INPUT TYPE='text' name='mail_login' value='".$user_data['mail_login']."'/>@etu.u-bourgogne.fr</DIV>";
        // list of departements
        $text.="<DIV>".htmlentities("Departement :", ENT_QUOTES, "UTF-8")."<SELECT name='departement'>";
        ($user_data['departement'] == '') ? $selectMe=' SELECTED' : $selectMe = '';
        $text.="<OPTION DISABLED".$selectMe.">".htmlentities("Selectionnez votre departement", ENT_QUOTES, "UTF-8")."</OPTION>";
        foreach ($table_department as $value=> $name) {
            ($user_data['departement'] == $value) ? $selectMe=' SELECTED' : $selectMe = '';
            $text.= "<OPTION value='$value'".$selectMe.">$name</OPTION>";
        }
        $text.="</SELECT></DIV>";
        
        // list of years
        $text.="<DIV>".htmlentities("Année de formation :", ENT_QUOTES, "UTF-8")."<SELECT name='annee_formation'>";
        ($user_data['annee_formation'] == '') ? $selectMe=' SELECTED' : $selectMe = '';
        $text.="<OPTION DISABLED".$selectMe.">".htmlentities("Selectionnez votre année", ENT_QUOTES, "UTF-8")."</OPTION>";
        foreach ($table_promotion_current_year as $value=> $name) {
            ($user_data['annee_formation'] == $value) ? $selectMe=' SELECTED' : $selectMe = '';
            $text.= "<OPTION value='$value'".$selectMe.">$name</OPTION>";
        }
        $text.="</SELECT></DIV>";
        // correction of a bug for disabled on a select list
        
        $text.="<BR/><label for='file'>Rapport (extension pdf, 30mo Max) :</label>";
        $text.="<input type='file' name='file' id='file'><br/>";
        
        return $text;
    }
    
    function check_file() {
        $cpt=0;
        $allowedExts = array("pdf", "ps", "jpg");
        foreach ($GLOBALS['_FILES'] as $fichier) {
			$nameExploded = explode(".",$fichier["name"]);
			$extension = end($nameExploded);
            $cpt++;
            if (!in_array($extension, $allowedExts)) {
                echo "Erreur : pdf seulement!<BR/>";
                return 0;
            }
            if ($fichier["size"] > 30000000) {
                echo "Erreur : taille maximum 30 Mo (reduit ca)<BR/>";
                return 0;
            }  
        }
        if ($cpt==0) {
            echo "Erreur : fichier non present<BR/>";
            return 0;
        }
        return 1;
    }
    
    function save_file_to_temp_directory($user_data){
        $allowedExts = array("pdf", "ps", "jpg");
        
        $body = "Le ".date('d-M-Y')." ont été soumis un ou plusieurs fichiers sur le serveur de dépot de l'ESIREM de la part de ".$user_data['prenom']. " ".$user_data['nom']." (adresse ip :".$_SERVER['REMOTE_ADDR'].")\n\n";
        echo htmlentities($body, ENT_QUOTES, $GLOBALS['charset'])."</BR></BR>";
        echo "Fichier(s) uploade (s)." ;
        echo "<UL>";
        foreach ($GLOBALS['_FILES'] as $fichier) {
            $nameExploded = explode(".",$fichier["name"]);
			$extension = end($nameExploded);
            
            $destination_dir=$GLOBALS['ROOT_DIR'].'/depot/'.$GLOBALS['UPLOAD_DIR'].'/'. $GLOBALS['table_department'][$user_data['departement']].'/'.$GLOBALS['year'].' - '.$user_data['annee_formation'].' - '.'ITC33-TP'.'/';
            
            if (!is_dir($destination_dir)) {
                $oldumask = umask(0);
                if (!mkdir ($destination_dir, 0777, true)) {
                    die ('something wrond happened. Plz contact admin');
                }
                umask($oldumask);
            }
            
            if (!is_dir($destination_dir.'tmp/')) {
                $oldumask = umask(0);
                if (!mkdir ($destination_dir.'tmp/', 0777, true)) {
                    die ('something wrond happened. Plz contact admin');
                }
                umask($oldumask);
            }
            
            $key = substr(uniqid ('', true), -8);
            
            
            $dest_filename = stripAccents('Rapport_TP_ITC33_Shell-ProgSys_'.$GLOBALS['year'].'_'.$GLOBALS['table_department'][$user_data['departement']].'_'.strtoupper($user_data['nom']).'_'.ucfirst(strtolower($user_data['prenom'])).".".$extension);
            $dest_filename_with_key = $dest_filename."_".$key;
            
            move_uploaded_file($fichier["tmp_name"], $destination_dir.'tmp/'.$dest_filename_with_key);
            

          switch ($fichier['error']) {

            case UPLOAD_ERR_OK : 
              echo "<LI> Nom : ".$fichier["name"]." (Taille : ".($fichier["size"]/1024)." Ko)</LI>";
              $continue = 1;
              break;
            default :
              echo "<LI> Nom : ".$fichier["name"]." (erreur d'envoi. Code erreur : ".$fichier['error'].")</LI>";
              $continue = 0;
              
          }
        }
        echo "</UL>";
      if ($continue) {
      $body="Un mail de confirmation vient de vous être envoyé à l'adresse '".$user_data['mail_login']."@etu.u-bourgogne.fr '. Cliquez sur le lien de ce mail pour confirmer votre envoi";
        echo htmlentities($body, ENT_QUOTES, $GLOBALS['charset']);

        $date =date('Y-m-d,h:m:s');
        $ip_address = $_SERVER['REMOTE_ADDR']; 
        $new_entry = array($key, $dest_filename, $destination_dir, strtoupper(stripAccents($user_data['nom'])), ucfirst(stripAccents(strtolower($user_data['prenom']))),$user_data['mail_login'], $date, $ip_address);
        
        if (file_exists($destination_dir."/".$dest_filename)) {
            $body="Important : Notez qu'une version précédente semble exister sur le dépôt. L'ancienne version sera définitivement remplacée par la nouvelle seulement lorsque vous aurez valide votre soumission avec le lien envoyé par mail";
            echo "<BR/><BR/><B>".htmlentities($body, ENT_QUOTES, $GLOBALS['charset'])."</B></BR></BR>";

        }
        
        $fp = fopen('depot/confirmation_filelist.csv', 'a');
        fputcsv($fp, $new_entry);
        fclose($fp);
        return $key;
      }
      
      $body="Une erreur s'est produite. Contactez l'administrateur pour plus d'informations, ou revenez sur la page précédente et réessayez. (Signification des codes d'erreur : http://php.net/manual/fr/features.file-upload.errors.php ) ";
      echo htmlentities($body, ENT_QUOTES, $GLOBALS['charset']);
      return -1;
      

    }
	
	function send_mail($user_data, $key) {
	$mail = new PHPMailer;

		$user_email = $user_data['mail_login']."@etu.u-bourgogne.fr";
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
        $mail->Subject = EMAIL_CONFIRMATION_SUBJECT;
	
		$message_txt = "Le ".date('d-M-Y')." ont ete soumis un ou plusieurs fichiers sur le serveur de depot de l'ESIREM de la part de ".$user_data['prenom']. " ".$user_data['nom']." (adresse ip :".$_SERVER['REMOTE_ADDR'].")\n\n";        
        $message_txt .= "Pour confirmer cette demande, veuillez cliquer sur le lien suivant (ou copiez ce dernier dans votre navigateur) : \n";
        $message_txt .= "http://localhost/serveur-depot/validation.php?key=".$key."\n";
        $message_txt .= "\n\nSi cette demande ne provenait pas de vous, ne cliquez pas sur ce lien et tenez-moi informe en me faisant forwarder ce mail";
		
        // the link to your register.php, please set this value in config/email_verification.php
        $mail->Body = $message_txt;

        if(!$mail->Send()) {
            $this->errors[] = MESSAGE_VERIFICATION_MAIL_NOT_SENT . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
	}
	
    function print_aknowledgment($user_data) {
        $key = save_file_to_temp_directory($user_data);
        if ($key != -1) send_mail($user_data, $key);
    }
    // user specific configuration
    $year='2013-14';
    
    
    
    ////////////  //////////////////////////
    //////////////////////////
    //////////////
    $main_title = "Formulaire de dépôt de rapport";
    $sub_title_1 = "Identité de l'étudiant";
    
    echo "<H3>".htmlentities($main_title, ENT_QUOTES, $GLOBALS['charset'])."</H3>";
    echo "<BLINK>".htmlentities("service actif jusqu'au 30 novembre 2013, 23h59", ENT_QUOTES, $GLOBALS['charset'])."</BLINK><BR/>";
  
  
    $dest_page= pathinfo(__FILE__,PATHINFO_FILENAME);
    
    $error=0;
    $editable_student=1;
    
    if ($current_step ==1) {
        if (($result =check_student_infos($user_data))==1 && check_file()) {
            $current_step=2;
        }
        else {
            
            $error=1;
        }
    }
    
    
    $form_student=form_id_etudiant($table_department, $table_promotion_current_year, $user_data) ;
    
    
    // step 0 and 1
    if ($current_step <2 ) {
        echo "<H3>".htmlentities($sub_title_1, ENT_QUOTES, $GLOBALS['charset'])."</H3>";
      

		echo "<FORM method='POST'  enctype='multipart/form-data' action='./".$dest_page.".php'>";
        echo $form_student;
        if ($error) {
            echo "<B>Certains champs sont absents ou errones. Corrigez et reessayez</B><BR/>";
        }
        echo "<INPUT TYPE='HIDDEN' NAME='action' VALUE='Valider'/>";
        echo "<INPUT TYPE='submit' VALUE='Valider'/><BR/>";
		echo "<a href=\"index.php?logout\">Déconnexion</a>";
        echo "<P>(l'envoi du fichier peut prendre un certain temps, inutile de raffraichir la page)</P>";
        echo "</FORM>";
    }
    // step 2
    if ($current_step == 2) {
        print_aknowledgment($user_data);
    }
    
} else {
    // the user is not logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are not logged in" view.
    include("views/not_logged_in.php");
}
?>

<!DOCTYPE html>
<html>
    <style>
        body
        {
            position:relative;
            margin:10px;
            padding:0;
            width:850px;
        }
        
        .footer {
            position:relative;
            width:100%;
           
            text-align:center;
        }
        
        input[type=submit]
        {
        
            cursor:pointer;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 24pt;
            right:0px:
            left:10px;
  
        }
        
        
        .personne select
        {
            margin:2px 2px 5px 5px;
            position:absolute;
                   
          left:170px;
        }
        input[type=text]
        {
            width: 1000px;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
        }
        
        .adresse {
            padding:10px;
            width: 390px;
            border:solid 1px #0F0D0D;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }
        
        
        .adresse input[type=text] {
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 120px;
            position:absolute;
            left:190px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        .adresse input[type=text].champadresse {
            
            width: 190px;
            
        }
        
        .adresse input[type=text].cp {
            position:absolute;
            border:solid 1px #BFBDBD;
            width: 50px;
        }
        
        .adresse .ville {
            position:absolute;
            border:solid 1px #BFBDBD;
            width: 200px;
            left:100px;      }
        
        h5{
            width:100%;
            margin-left: -1px;
            margin-top :0px;
            margin-right: -1px;
            margin-bottom: 6px;

            border: 1px solid red;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: black;
            padding-top: 0px;
            padding-bottom: 0px;
            
        }
        
        .doubleTableau {
            position:relative;
            margin-top :10px;
            margin-bottom :10px;

            width:700;
            margin: 0px;
        }
        
        
        .tableauDroite {
            position:absolute;
           
            right:0px;

            top:0px;
        }
        #doubleTableauEntreprise {
            position:relative;
            margin-top :10px;
            margin-bottom :10px;
               height:180px;
            width:700;
            margin: 0px;
        }
        
        #tableauGaucheEntreprise {
            position:absolute;
            width:390px;
           height:160px;
              top:0px;
            padding:10px;
            left:0px;
            margin-left: 0px;
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;

        }
        
        #tableauDroiteEntreprise {
            position:absolute;
            width:390px;
            right:0px;
            top:0px;
            height:160px;
            padding:10px;
            
            margin-left: 0px;
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            
        }       
        #sujetStage
        {       
            width: 490px;
            
        }
        
        .donneesStage
        {
            position:relative;
            width:808px;
            
            padding:10px;
            background-color:#eeeeee;
            
            margin-left: 0px;
            margin-bottom : 5px;
            
            border: 1px solid black;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: 9pt;
            
        }
        
        .donneesStage input[type=text]
        {
            
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 170px;
            position:absolute;
            left:310px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        
        .entreprise input[type=text]
        {
            
            background-color: #BFBDBD;
            border:solid 1px #BFBDBD;
            height: 13px;
            padding-left:10px;
            width: 200px;
            position:absolute;
            left:150px;
            box-shadow: 1px 1px 0 #828181 inset;
        }
        
        #personneSecours1 {
            
        }
        
        #personneSecours2 {
            position:absolute;
            right:0px;
            top:0px;
        }
        
        #correspondant {
            position:absolute;
            right:0px;
        }
        
        .cursus {
            position:static;
        }
        
        .section {
            background-color: #EEEEEE;   
            position:relative;
            padding:10px;
            margin:0px;
            
        }
        #infoEtudiant {
            clear:both;    
            
            
        }
        
        #infoEntreprise {
      clear:both
        }
        
        #infoStage {
           
        }
        
        
        #maitrestage {
            
        }
        
        #correspondant {
            position:absolute;
            right:0px;
            top:0px;
            
        }
        
        
        .warning {
            position:absolute;
            right:0px;
            top:0px;
            width:200px;
            height:50px;
            font-size:12pt;
            background-color: #EE0000;
            color : white;
            text-decoration:blink;
            border: 1px solid red;
            padding : 5px;
            margin : 5px;
        }

        
        </style>

</html>