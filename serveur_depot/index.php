<?php
    
//    require_once('connect.php');
    require_once('global_data.php');
    //require_once 'Mail.php';
	error_reporting(E_ALL | E_STRICT);
  header("Content-Type: text/html; charset=UTF-8");
  
    // session
    session_start();
  
  

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
            
            $destination_dir=$GLOBALS['ROOT_DIR'].'/'.$GLOBALS['UPLOAD_DIR'].'/'. $GLOBALS['table_department'][$user_data['departement']].'/'.$GLOBALS['year'].' - '.$user_data['annee_formation'].' - '.'ITC33-TP'.'/';
            
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
        
        $fp = fopen('confirmation_filelist.csv', 'a');
        fputcsv($fp, $new_entry);
        fclose($fp);
        return $key;
      }
      
      $body="Une erreur s'est produite. Contactez l'administrateur pour plus d'informations, ou revenez sur la page précédente et réessayez. (Signification des codes d'erreur : http://php.net/manual/fr/features.file-upload.errors.php ) ";
      echo htmlentities($body, ENT_QUOTES, $GLOBALS['charset']);
      return -1;
      

    }
    
	function send_mail($user_data, $key) {
		$mail = $user_data['mail_login']."@etu.u-bourgogne.fr"; // Déclaration de l'adresse de destination.
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}
		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Le ".date('d-M-Y')." ont ete soumis un ou plusieurs fichiers sur le serveur de depot de l'ESIREM de la part de ".$user_data['prenom']. " ".$user_data['nom']." (adresse ip :".$_SERVER['REMOTE_ADDR'].")\n\n";        
        $message_txt .= "Pour confirmer cette demande, veuillez cliquer sur le lien suivant (ou copiez ce dernier dans votre navigateur) : \n";
        $message_txt .= "http://localhost/validation.php?key=".$key."\n";
        $message_txt .= "\n\nSi cette demande ne provenait pas de vous, ne cliquez pas sur ce lien et tenez-moi informe en me faisant forwarder ce mail";
		//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
		//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
		//==========
		 
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		//==========
		 
		//=====Définition du sujet.
		$sujet = "[esirem] /!\ CONFIRMATION REQUISE /!\ :validation de depot de fichier";
		//=========
		 
		//=====Création du header de l'e-mail.
		$header = "From: \"Cédric Berte\"<berte.cedric@gmail.com>".$passage_ligne;
		$header.= "Reply-to: \"Cédric Berte\"<berte.cedric@gmail.com>".$passage_ligne;
		$header.= "MIME-Version: 1.0".$passage_ligne;
		$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
		//==========
		 
		//=====Création du message.
		$message = $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format texte.
		$message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
		$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		$message.= $passage_ligne.$message_txt.$passage_ligne;
		//==========
		$message.= $passage_ligne."--".$boundary.$passage_ligne;
		//=====Ajout du message au format HTML
		//$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
		//$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
		//$message.= $passage_ligne.$message_html.$passage_ligne;
		//==========
		//$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		//$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
		//==========
		 
		//=====Envoi de l'e-mail.
		mail($mail,$sujet,$message,$header);
		//==========
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
        echo "<P>(l'envoi du fichier peut prendre un certain temps, inutile de raffraichir la page)</P>";
        echo "</FORM>";
    }
    // step 2
    if ($current_step == 2) {
        print_aknowledgment($user_data);
    }
    
    
    
    ?>

