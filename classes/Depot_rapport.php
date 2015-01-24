<?php

class Depot_rapport
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection            = null;
    /**
     * @var bool success state of registration
     */
    public  $depot_rapport_successful  = false;
    /**
     * @var bool success state of verification
     */
    public  $verification_successful  = false;
    /**
     * @var array collection of error messages
     */
    public  $errors                   = array();
    /**
     * @var array collection of success / neutral messages
     */
    
    public  $liste_binome              = array();
    
    public  $messages                 = array();
    
    public $user_data                 = array();
    
    public $id_depot                  = null;
    
    public $nb_eleves                 = null;
    
    public $mail_prof                  = null;
    
    public $nom_depot                  = null;
    
    public  $depot_perime  = false;
    
    public  $binome  = false;

    /**
     * La fonction "__construct()" est le constructeur de la classe.
     */
    public function __construct()
    {
        session_start();
        
        if (isset($_GET["depot"])) {
            if($this->databaseConnection()) {
                $this->id_depot = $_GET["depot"];
                
                //récupère la date du dépôt
                $query_date_depot = $this->db_connection->prepare('SELECT date FROM depots WHERE id=:id');
                $query_date_depot->bindValue(':id', $this->id_depot, PDO::PARAM_STR);
                $query_date_depot->execute();
                $result_date = $query_date_depot->fetch();
                $date = $result_date[0];
                
                //récupère le nom du dépôt                
                $query_nom_depot = $this->db_connection->prepare('SELECT nom FROM depots WHERE id=:id');
                $query_nom_depot->bindValue(':id', $this->id_depot, PDO::PARAM_STR);
                $query_nom_depot->execute();
                $result2 = $query_nom_depot->fetch();
                $this->nom_depot = $result2[0];
                
                //le rapport est il a faire en binome
                $query_binome = $this->db_connection->prepare('SELECT binome FROM depots WHERE id=:id');
                $query_binome->bindValue(':id', $this->id_depot, PDO::PARAM_STR);
                $query_binome->execute();
                $result_binome = $query_binome->fetch();
                $binome = $result_binome[0];
                
                //si oui
                if($binome) {
                    $this->binome = true;
                    
                    //on récupère le département et l'année
                    $departement =$_SESSION['departement'];
                    $annee = $_SESSION['annee_formation'];
                    $nom = $_SESSION['nom'];
                    $prenom = $_SESSION['prenom'];
                    
                    //on récupère la liste des personnes dans ce département et cette année
                    $query_choix_binome = $this->db_connection->prepare('SELECT nom, prenom FROM users WHERE departement=:departement AND annee_formation=:annee AND nom<>:nom AND prenom<>:prenom ORDER BY nom');
                    $query_choix_binome->bindValue(':departement', $departement, PDO::PARAM_STR);
                    $query_choix_binome->bindValue(':annee', $annee, PDO::PARAM_STR);
                    $query_choix_binome->bindValue(':nom', $nom, PDO::PARAM_STR);
                    $query_choix_binome->bindValue(':prenom', $prenom, PDO::PARAM_STR);
                    $query_choix_binome->execute();
                    $this->liste_binome=$query_choix_binome->fetchAll();
                    $this->nb_eleves=count($this->liste_binome);
                    
                }
                
                $query_mail_prof = $this->db_connection->prepare('SELECT users.user_email
                                                         FROM users
                                                         LEFT JOIN depots ON (users.user_name = depots.nom_prof)
                                                         WHERE depots.id=:id
                                                         ');
                $query_mail_prof->bindValue(':id', $this->id_depot, PDO::PARAM_STR);
                $query_mail_prof->execute();
                
                $result_mail = $query_mail_prof->fetch();
                $this->mail_prof = $result_mail[0];
                
                if($date < date("Y-m-d")) {
                    $this->depot_perime = true;
                }
            }
        }

        // if we have such a POST request, call the registerNewRapport() method
        if (isset($_POST["rapport_rendu"])) {
        
            $user_data['prenom'] = $_SESSION['prenom'];
            $user_data['nom'] = $_SESSION['nom'];
            $user_data['mail_login'] = $_SESSION['user_email'];
            $user_data['departement'] = $_SESSION['departement'];
            $user_data['annee_formation'] = $_SESSION['annee_formation'];
            $user_data['id_depot'] = $_POST['id_depot'];
            if(isset($_POST['binome'])) {
                if($_POST['binome'] != "") {
                    $user_data['binome'] = $_POST['binome'];
                } else {
                    $this->messages = "Choisissez un binome.";
                }
            }
                
            
            $this->print_aknowledgment($user_data);
        // if we have such a GET request, call the confirmationRapport() method
        } else if (isset($_GET["key"])) {
            $this->depot_rapport_successful = true;
            $this->confirmationRapport($_GET["key"]);
        }
    }
    
    

    /**
     * Checks if database connection is opened and open it if not
     */
    private function databaseConnection()
    {
        // connection already opened
        if ($this->db_connection != null) {
            return true;
        } else {
            // create a database connection, using the constants from config/config.php
            try {
                // Generate a database connection, using the PDO connector
                // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
                // Also important: We include the charset, as leaving it out seems to be a security issue:
                // @see http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Connecting_to_MySQL says:
                // "Adding the charset to the DSN is very important for security reasons,
                // most examples you'll see around leave it out. MAKE SURE TO INCLUDE THE CHARSET!"
                $this->db_connection = new PDO('mysql:host='. DB_HOST .';dbname='. DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            // If an error is catched, database connection failed
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }

    /**
     * Remplace les lettres avec accent par des lettres sans accent
     */
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
    
    /**
     * Vérifie que le fichier est bien un pdf, de moins de 30 Mo.
     */
    function check_file() {
        $cpt=0;
        $allowedExts = array("pdf", "ps", "jpg");
        foreach ($GLOBALS['_FILES'] as $fichier) {
            $nameExploded = explode(".",$fichier["name"]);
            $extension = end($nameExploded);
            $cpt++;
            if (!in_array($extension, $allowedExts)) {
                $this->errors[] = "Erreur : pdf seulement!<BR/>";
                return 0;
            }
            if ($fichier["size"] > 30000000) {
                $this->errors[] = "Erreur : la taille maximale autoris�e est de 30 Mo<BR/>";
                return 0;
            }
        }
        if ($cpt==0) {
            $this->errors[] = "Erreur : fichier non present<BR/>";
            return 0;
        }
        return 1;
    }
    
    /**
     * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
     * everything is fine
     */
    private function registerNewRapport($user_data) {
        include_once('global_data.php');
        $allowedExts = array("pdf", "ps", "jpg");
        
        // check provided data validity
        // TODO: check for "return true" case early, so put this first
        if (empty($user_data['prenom'])) {
            $this->errors[] = "Veuillez renseigner votre prénom !";
        } elseif (empty($user_data['nom'])) {
            $this->errors[] = "Veuillez renseigner votre nom !";
        } elseif (empty($user_data['nom'])) {
            $this->errors[] = "Veuillez renseigner votre nom !";
        } elseif (empty($user_data['id_depot'])) {
            $this->errors[] = "Erreur, veuillez accéder à cette page par la liste des dépôts.";
        } elseif (empty($user_data['departement'])) {
            $this->errors[] = "Veuillez indiquer votre département !";
        } elseif (empty($user_data['annee_formation'])) {
            $this->errors[] = "Veuillez indiquer votre année de formation !";
        } else if ($this->check_file()) {
            $this->messages[] = "Bonjour, ";
            $this->messages[] = "le ".date('d-M-Y')." ont été soumis un ou plusieurs fichiers sur le serveur de dépot de l'ESIREM de la part de ".$user_data['prenom']. " ".$user_data['nom']." (adresse ip :".$_SERVER['REMOTE_ADDR'].")\n\n";
            $this->messages[] = "Fichier uploadé.<br>";
            
            $this->databaseConnection();
            
            if (isset($user_data['binome'])) {
                $nom_complet_binome = explode(" ", $user_data['binome']);
                $binome_nom = $nom_complet_binome[0];
                $binome_prenom = $nom_complet_binome[1];
                
                $query_new_binome_insert = $this->db_connection->prepare('INSERT INTO binome (nom, prenom, id_depot, nom_binome, prenom_binome)
                                                                        VALUES(:nom, :prenom, :id_depot, :nom_binome, :prenom_binome)');
                $query_new_binome_insert->bindValue(':nom', $user_data['nom'], PDO::PARAM_STR);
                $query_new_binome_insert->bindValue(':prenom', $user_data['prenom'], PDO::PARAM_STR);
                $query_new_binome_insert->bindValue(':id_depot', $user_data['id_depot'], PDO::PARAM_STR);
                $query_new_binome_insert->bindValue(':nom_binome', $binome_nom, PDO::PARAM_STR);
                $query_new_binome_insert->bindValue(':prenom_binome', $binome_prenom, PDO::PARAM_STR);
                $query_new_binome_insert->execute();
            }
            
            $this->depot_rapport_successful = true;
            
            $query_nom_rapport = $this->db_connection->prepare('SELECT nom FROM depots WHERE id=:id');
            $query_nom_rapport->bindValue(':id', $user_data['id_depot'], PDO::PARAM_STR);
            $query_nom_rapport->execute();
            
            $result = $query_nom_rapport->fetch();
            $nom_depot = $result[0];
            $this->nom_depot = $nom_depot;
            
            echo "<UL>";
            foreach ($GLOBALS['_FILES'] as $fichier) {
                $nameExploded = explode(".",$fichier["name"]);
                $extension = end($nameExploded);
                $destination_dir=$GLOBALS['ROOT_DIR'].'/depot/'.$GLOBALS['UPLOAD_DIR'].'/'.$user_data['departement'].'/'.$user_data['annee_formation'].'/'.$nom_depot.'/';
            
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
                
                $prenom_without_accent = $this->stripAccents($user_data['prenom']);
            
                if(isset($user_data['binome'])) {
                    $prenom_without_accent = $this->stripAccents($user_data['prenom']);
                    $prenom_binome_without_accent = $this->stripAccents($binome_prenom);
                    $dest_filename = $this->stripAccents($nom_depot.'_'.$user_data['annee_formation'].'_'.$user_data['departement'].'_'.strtoupper($user_data['nom']).'_'.ucfirst($prenom_without_accent).'_'.strtoupper($binome_nom).'_'.ucfirst($prenom_binome_without_accent).".".$extension);
                    $dest_filename_with_key = $dest_filename."_".$key;
                } else {
                    $prenom_without_accent = $this->stripAccents($user_data['prenom']);
                    $dest_filename = $this->stripAccents($nom_depot.'_'.$user_data['annee_formation'].'_'.$user_data['departement'].'_'.strtoupper($user_data['nom']).'_'.ucfirst($prenom_without_accent).".".$extension);
                    $dest_filename_with_key = $dest_filename."_".$key;
                }
            
                move_uploaded_file($fichier["tmp_name"], $destination_dir.'tmp/'.$dest_filename_with_key);
            
            
                switch ($fichier['error']) {
            
                    case UPLOAD_ERR_OK :
						$this->messages[] = "<br>";
                        $this->messages[] = "<LI> Nom : ".$fichier["name"]." (Taille : ".($fichier["size"]/1024)." Ko)</LI>";
						$this->messages[] = "<br>";
                        $continue = 1;
                        break;
                    default :
                        $this->messages[] = "<LI> Nom : ".$fichier["name"]." (erreur d'envoi. Code erreur : ".$fichier['error'].")</LI>";
                        $continue = 0;
            
                }
            }
            echo "</UL>";
            if ($continue) {
                $this->messages[] = "Un mail de confirmation vient de vous être envoyé à l'adresse '".$user_data['mail_login']."'. Cliquez sur le lien de ce mail pour confirmer votre envoi.";
                $this->messages[] = "<br>";
            
                $date =date('Y-m-d,h:m:s');
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $new_entry = array($key, $dest_filename, $destination_dir, strtoupper($this->stripAccents($user_data['nom'])), ucfirst($this->stripAccents(strtolower($user_data['prenom']))),$user_data['mail_login'], $date, $ip_address);
            
                if (file_exists($destination_dir."/".$dest_filename)) {
                    echo("<br>");
                    echo("<br>");
                    echo("Important : Notez qu'une version précédente semble exister sur le dépôt. L'ancienne version sera définitivement remplacée par la nouvelle seulement lorsque vous aurez validé votre soumission avec le lien envoyé par mail.");
                    echo("<br>");
                    echo("<br>");
                }
            
                $fp = fopen('depot/confirmation_filelist.csv', 'a');
                fputcsv($fp, $new_entry);
                fclose($fp);
                return $key;
            }
            
            $this->errors[] = "Une erreur s'est produite. Contactez l'administrateur pour plus d'informations, ou revenez sur la page pr�c�dente et r�essayez. (Signification des codes d'erreur : http://php.net/manual/fr/features.file-upload.errors.php ) ";
            return -1;
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
                    $this->messages[] = "Validation du fichier ".$dest_filename;
					$this->messages[] = "<br>";
					$this->messages[] = "<br>";
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
                $this->errors[] = "Clé de validation inconnue. Votre fichier doit avoir été validé et un accusé de reception envoyé. Si ce n'est pas le cas, contactez l'administrateur.<br>" ;    
                unlink ($filetmp);
            }
            else {
                $this->messages[] = "Fichier(s) validé(s). Un accusé de réception vous a été envoyé par mail.";
				$this->messages[] = "<br>";
                rename($filetmp, $file);
                $this->send_confirmation_mail($mail_login, $key, $tableau_fichiers);
                
                //on regarde s'il y a déjà un enregistrement
                $this->databaseConnection();
                $query_verif_doublon = $this->db_connection->prepare('SELECT nom FROM rapportsrendus WHERE id_depot=:id_depot AND nom=:nom AND prenom=:prenom');
                $query_verif_doublon->bindValue(':id_depot', $this->id_depot, PDO::PARAM_STR);
                $query_verif_doublon->bindValue(':nom', $_SESSION['nom'], PDO::PARAM_STR);
                $query_verif_doublon->bindValue(':prenom', $_SESSION['prenom'], PDO::PARAM_STR);
                $query_verif_doublon->execute();
                $result_verif_doublon = $query_verif_doublon->fetch();
                $doublon = $result_verif_doublon[0];
                
                if($doublon==null) {
                    //on enregistre le fait que le depot a été rendu
                    $query_rapport_rendu = $this->db_connection->prepare('INSERT INTO rapportsrendus (id_depot, nom, prenom, rendu) VALUES(:id_depot, :nom, :prenom, :rendu)');
                    $query_rapport_rendu->bindValue(':id_depot', $this->id_depot, PDO::PARAM_STR);
                    $query_rapport_rendu->bindValue(':nom', $_SESSION['nom'], PDO::PARAM_STR);
                    $query_rapport_rendu->bindValue(':prenom', $_SESSION['prenom'], PDO::PARAM_STR);
                    $query_rapport_rendu->bindValue(':rendu', 1, PDO::PARAM_STR);
                    $query_rapport_rendu->execute();
                    
                    //on cherche un éventuel binome
                    $query_nom_binome = $this->db_connection->prepare('SELECT nom_binome FROM binome WHERE nom=:nom AND prenom=:prenom AND id_depot=:id_depot');
                    $query_nom_binome->bindValue(':nom', $_SESSION['nom'], PDO::PARAM_STR);
                    $query_nom_binome->bindValue(':prenom', $_SESSION['prenom'], PDO::PARAM_STR);
                    $query_nom_binome->bindValue(':id_depot', $this->id_depot, PDO::PARAM_STR);
                    $query_nom_binome->execute();
                    
                    $query_prenom_binome = $this->db_connection->prepare('SELECT prenom_binome FROM binome WHERE nom=:nom AND prenom=:prenom AND id_depot=:id_depot');
                    $query_prenom_binome->bindValue(':nom', $_SESSION['nom'], PDO::PARAM_STR);
                    $query_prenom_binome->bindValue(':prenom', $_SESSION['prenom'], PDO::PARAM_STR);
                    $query_prenom_binome->bindValue(':id_depot', $this->id_depot, PDO::PARAM_STR);
                    $query_prenom_binome->execute();
                    
                    $result_nom_binome = $query_nom_binome->fetch();
                    $result_prenom_binome = $query_prenom_binome->fetch();
                    
                    $nom_binome = $result_nom_binome[0];
                    $prenom_binome = $result_prenom_binome[0];
                    
                    //on enregistre aussi pour le binome
                    if($nom_binome != null && $prenom_binome != null) {
                        $query_rapport_rendu_binome = $this->db_connection->prepare('INSERT INTO rapportsrendus (id_depot, nom, prenom, rendu) VALUES(:id_depot, :nom, :prenom, :rendu)');
                        $query_rapport_rendu_binome->bindValue(':id_depot', $this->id_depot, PDO::PARAM_STR);
                        $query_rapport_rendu_binome->bindValue(':nom', $nom_binome, PDO::PARAM_STR);
                        $query_rapport_rendu_binome->bindValue(':prenom', $prenom_binome, PDO::PARAM_STR);
                        $query_rapport_rendu_binome->bindValue(':rendu', 1, PDO::PARAM_STR);
                        $query_rapport_rendu_binome->execute();
                    }
                }
                
                return 1;
            }
        }
        else {
    
            $this->errors[] = "Erreur inconnue.";
            return 0;
        }
    }

    /**
     * sends an email to the provided email address
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    public function sendVerificationEmail($user_data, $key)
    {   
	    $mail = new PHPMailer;
	    $user_email = $user_data['mail_login'];
        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
        $message_txt .= "http://localhost/serveur-depot/depot_rapport.php?key=".$key."&depot=".$user_data['id_depot']."\n";
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
    
    /**
     * sends an email to confirm the depot
     * @param unknown $mail_login
     * @param unknown $key
     * @param unknown $tableau_fichiers
     * @return boolean
     */
    function send_confirmation_mail($mail_login, $key, $tableau_fichiers) {
        //include_once("PHPMailer.php");
        //include_once("config.php");
        $mail = new PHPMailer;
        $user_email = $mail_login; // Déclaration de l'adresse de destination.
        // please look into the config/config.php for much more info on how to use this!
        // use SMTP or use mail()
        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
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
        $message_txt.="Contenu du dépôt :\n";
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

    /**
     * checks the id/verification code combination and set the user's activation status to true (=1) in the database
     */
    public function confirmationRapport($key)
    {
        if  ($key!= "") {
            $result = $this->save_file_to_final_directory($key);
            if ($result) {
            }
        } else{
            $this->errors[] = "Erreur : aucune clé fournie.";
        }
    }
    
    function print_aknowledgment($user_data) {
        $key = $this->registerNewRapport($user_data);
        if ($key != -1 && $key != null) $this->sendVerificationEmail($user_data, $key);
    }
}
