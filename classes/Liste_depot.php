<?php

class Liste_depot
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection            = null;
    /**
     * @var bool success state of registration
     */
    public  $depot_precis  = false;
    /**
     * @var array collection of error messages
     */
    public  $errors                   = array();
    /**
     * @var array collection of success / neutral messages
    */
    public  $messages                 = array();
    
    public  $result                 = array();
    
    public  $tableau_final_eleves                = array();
	
    public  $tableau_final_rendu                = array();
    
    public  $nb_depots                = 0;
    
    public  $nb_personnes             = 0;
	
	public  $nb_rendus            = 0;
    
    public  $nom_depot               = "";
	
	public  $dest_filename               = "";
    
    public  $nom_delete               = "";
    
    public  $delete  = false;
    
    public  $id_depot                = 0;

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
    */
    public function __construct()
    {
        session_start();

        // if we have such a POST request, call the registerNewUser() method
        if (isset($_GET["delete"])) {
            $this->deleteDepot($_GET["delete"]);
        } else if (isset($_GET["depot"])) {
            $this->showDepot($_GET["depot"]);
        } else {
            $this->showListeDepots();
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
     * handles the entire pre_registration process.
     */
    private function showDepot($id_depot)
    {
        $this->depot_precis = true;
		$this->dest_filename = $this->downloadDepot($id_depot);
        $this->id_depot=$id_depot;
        
        if($this->databaseConnection()) {
            
            //on récupère l'année de formation associée au dépôt
            $query_promotion = $this->db_connection->prepare('SELECT annee FROM depots WHERE id=:id');
            $query_promotion->bindValue(':id', $id_depot, PDO::PARAM_STR);
            $query_promotion->execute();
            
            //on récupère le département associé au dépôt
            $query_departement = $this->db_connection->prepare('SELECT departement FROM depots WHERE id=:id');
            $query_departement->bindValue(':id', $id_depot, PDO::PARAM_STR);
            $query_departement->execute();
            
            //nom du dépôt
            $query_nom_depot = $this->db_connection->prepare('SELECT nom FROM depots WHERE id=:id');
            $query_nom_depot->bindValue(':id', $id_depot, PDO::PARAM_STR);
            $query_nom_depot->execute();
            
            $result1 = $query_promotion->fetch();
            $result2 = $query_departement->fetch();
            $result3 = $query_nom_depot->fetch();
            
            $annee_formation = $result1[0];
            $departement = $result2[0];
            $this->nom_depot = $result3[0];
   
            //on trouve la liste des élèves
            $query_liste_eleves = $this->db_connection->prepare('SELECT nom, prenom FROM users WHERE departement=:departement AND annee_formation=:annee_formation ORDER BY nom');
            $query_liste_eleves->bindValue(':departement', $departement, PDO::PARAM_STR);
            $query_liste_eleves->bindValue(':annee_formation', $annee_formation, PDO::PARAM_STR);
            $query_liste_eleves->execute();
            
            //on trouve les eleves qui ont rendus le rapport
            $query_liste_eleves_rendu = $this->db_connection->prepare('SELECT nom, prenom, rendu FROM rapportsrendus WHERE id_depot=:id_depot');
            $query_liste_eleves_rendu->bindValue(':id_depot', $this->id_depot, PDO::PARAM_STR);
            $query_liste_eleves_rendu->execute();
            
            $this->tableau_final_eleves = $query_liste_eleves->fetchAll();
            $this->tableau_final_rendu = $query_liste_eleves_rendu->fetchAll();
            
            $this->nb_personnes = count($this->tableau_final_eleves);
			$this->nb_rendus = count($this->tableau_final_rendu);  
        }
    }
    
    private function downloadDepot($id_depot)
    {
        if($this->databaseConnection()) {
        
            //on récupère l'année de formation associée au dépôt
            $query_annee = $this->db_connection->prepare('SELECT annee FROM depots WHERE id=:id');
            $query_annee->bindValue(':id', $id_depot, PDO::PARAM_STR);
            $query_annee->execute();
        
            //on récupère le département associé au dépôt
            $query_departement = $this->db_connection->prepare('SELECT departement FROM depots WHERE id=:id');
            $query_departement->bindValue(':id', $id_depot, PDO::PARAM_STR);
            $query_departement->execute();
        
            //nom du dépôt
            $query_nom_depot = $this->db_connection->prepare('SELECT nom FROM depots WHERE id=:id');
            $query_nom_depot->bindValue(':id', $id_depot, PDO::PARAM_STR);
            $query_nom_depot->execute();
        
            $result_annee = $query_annee->fetch();
            $result_departement = $query_departement->fetch();
            $result_nom = $query_nom_depot->fetch();
        
            $annee_formation = $result_annee[0];
            $departement = $result_departement[0];
            $this->nom_depot = $result_nom[0];
			
			//nom du dossier à compresser et nom de l'archive
            $dest_filename = $this->stripAccents('./depot/'.$this->nom_depot.'_'.$annee_formation.'_'.$departement.'.zip');
			$dir = './depot/'.$GLOBALS['UPLOAD_DIR']."/".$departement."/".$annee_formation."/".$this->nom_depot;
			
			function Zip($source, $destination)
			{
				if (!extension_loaded('zip') || !file_exists($source)) {
					return false;
				}

				$zip = new ZipArchive();
				if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
					return false;
				}

				$source = str_replace('\\', '/', realpath($source));

				if (is_dir($source) === true)
				{
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

					foreach ($files as $file)
					{
						$file = str_replace('\\', '/', $file);

						// Ignore "." and ".." folders
						if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
							continue;

						$file = realpath($file);

						if (is_dir($file) === true)
						{
							$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
						}
						else if (is_file($file) === true)
						{
							$zip->addFromString(basename($file), file_get_contents($file));
						}
					}
				}
				else if (is_file($source) === true)
				{
					$zip->addFromString(basename($source), file_get_contents($source));
				}

				return $zip->close();
			}
						
			Zip($dir, $dest_filename);
			
			return $dest_filename;
			
		}
    }
    
    private function showListeDepots()
    {
        $nom_prof=$_SESSION['user_name'];
        
        if ($this->databaseConnection()) {
            $query_lister_depots = $this->db_connection->prepare('SELECT * FROM depots 
																WHERE nom_prof=:nom_prof ORDER BY date');
            $query_lister_depots->bindValue(':nom_prof', $nom_prof, PDO::PARAM_STR);
            $query_lister_depots->execute();
            
            $query_nb_depots = $this->db_connection->prepare('SELECT COUNT(*) FROM depots 
																WHERE nom_prof=:nom_prof');
            $query_nb_depots->bindValue(':nom_prof', $nom_prof, PDO::PARAM_STR);
            $query_nb_depots->execute();
            
            $this->result = $query_lister_depots->fetchAll();            
            $this->nb_depots = count($this->result);
        }    
    }
    
    private function deleteDepot($id)
    {
        if($this->databaseConnection()) {
            $query_nom_depot = $this->db_connection->prepare('SELECT nom FROM depots WHERE id=:id');
            $query_nom_depot->bindValue(':id', $id, PDO::PARAM_STR);
            $query_nom_depot->execute();
            
            $query_binome = $this->db_connection->prepare('SELECT binome FROM depots WHERE id=:id');
            $query_binome->bindValue(':id', $id, PDO::PARAM_STR);
            $query_binome->execute();
            $result_binome = $query_binome->fetch();
            $binome = $result_binome[0];
            
            $query_supress_depots = $this->db_connection->prepare('DELETE FROM depots WHERE id=:id');
            $query_supress_depots->bindValue(':id', $id, PDO::PARAM_INT);
            $query_supress_depots->execute();
            
            $query_supress_rapports_rendus = $this->db_connection->prepare('DELETE FROM rapportsrendus WHERE id_depot=:id_depot');
            $query_supress_rapports_rendus->bindValue(':id_depot', $id, PDO::PARAM_INT);
            $query_supress_rapports_rendus->execute();
            
            if($binome) {
                $query_supress_binome = $this->db_connection->prepare('DELETE FROM binome WHERE id_depot=:id_depot');
                $query_supress_binome->bindValue(':id_depot', $id, PDO::PARAM_INT);
                $query_supress_binome->execute();
            }
            
            $result_nom = $query_nom_depot->fetch();
            $this->nom_delete = $result_nom[0];
            $this->delete = true;
        }
    }
}
