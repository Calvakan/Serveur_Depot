<?php
    
    require_once('global_data.php');
    //require_once 'Mail.php';
    
    
    isset($_GET['key']) ? $key = $_GET["key"] : $key="";
    
    
	function send_confirmation_mail($mail_login, $key, $tableau_fichiers) {
		$mail = $mail_login."@etu.u-bourgogne.fr"; // Déclaration de l'adresse de destination.
		if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}
		//=====Déclaration des messages au format texte et au format HTML.
		$message_txt = "Votre dépot a été validé le  ".date('d-M-Y')." via l'adresse IP ".$_SERVER['REMOTE_ADDR']."\n";
        $message_txt.="contenu du dépôt :\n";
		foreach ($tableau_fichiers as $entree_fichier) {
            $message_txt.="- ".$entree_fichier['dest_filename'];
        }
		//$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
		//$message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
		//==========
		 
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		//==========
		 
		//=====Définition du sujet.
		$sujet = "[esirem] confirmation de depot de fichier";
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
    
    
    function save_file_to_final_directory($key2check){
        $cpt=0;
        $tableau_fichiers = array();
        $file = 'confirmation_filelist.csv';
        
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
                $body="cle de validation inconnue. Votre fichier doit avoir été validé et un accusé de reception envoye. Si ce n'est pas le cas, contactez benoit.darties" ;
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
    
    
    
    ?>

