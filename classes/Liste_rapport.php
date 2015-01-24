<?php

class Liste_rapport
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
    
    public  $rapports                = array();
    
    public  $nb_rapports                = 0;
	
	public  $rapportsrendus            = array();
    
    public  $nb_rapports_rendus          = 0;
    
    public  $depot               = "";

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
    */
    public function __construct()
    {
        session_start();

        // if we have such a POST request, call the registerNewUser() method
        /*if (isset($_GET["depot"])) {
            $this->showDepot($_GET["depot"]);
        } else {*/
            $this->showListeRapports();
        //}
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
     * handles the entire pre_registration process.
     */
    private function showDepot($id_depot)
    {
        echo("Voici le dépôt.");
    }
    
    private function showListeRapports()
    {
        //$login = new Login();
        //$nom_prof=$login->getUsername();
        
        if ($this->databaseConnection()) {
            $query_lister_rapports = $this->db_connection->prepare('SELECT * FROM depots WHERE departement=:departement AND annee=:annee ORDER BY date');
            $query_lister_rapports->bindValue(':departement', $_SESSION['departement'], PDO::PARAM_STR);
            $query_lister_rapports->bindValue(':annee', $_SESSION['annee_formation'], PDO::PARAM_STR);
            $query_lister_rapports->execute();
			
			$query_rapports_rendus = $this->db_connection->prepare('SELECT * FROM rapportsrendus WHERE nom=:nom AND prenom=:prenom');
            $query_rapports_rendus->bindValue(':nom', $_SESSION['nom'], PDO::PARAM_STR);
            $query_rapports_rendus->bindValue(':prenom', $_SESSION['prenom'], PDO::PARAM_STR);
            $query_rapports_rendus->execute();
            
            $this->rapports = $query_lister_rapports->fetchAll();
            $this->nb_rapports = count($this->rapports);
			$this->rapportsrendus = $query_rapports_rendus->fetchAll();
			$this->nb_rapports_rendus = count($this->rapportsrendus);
        }    
    }
}
