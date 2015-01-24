<?php
include_once 'Login.php';

class Creation_depot
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection            = null;
    /**
     * @var bool success state of registration
     */
    public  $creation_successful  = false;
    /**
     * @var array collection of error messages
     */
    public  $errors                   = array();
    /**
     * @var array collection of success / neutral messages
     */
    public  $messages                 = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        session_start();

        // if we have such a POST request, call the registerNewDepot() method
        if (isset($_POST["create"])) {
            if( isset($_POST['binome']) ) {
                $binome = 1;
            } else {
                $binome = 0;
            }
            $this->registerNewDepot($_POST['nom'], $_POST['departement'], $_POST['annee'], $_POST['date'], $binome);
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
     * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
     * everything is fine
     */
    private function registerNewDepot($nom, $departement, $annee, $date, $binome)
    {
        $nom_prof=$_SESSION['user_name'];
        // check provided data validity
        // TODO: check for "return true" case early, so put this first
        if (empty($nom)) {
            $this->errors[] = "Entrez un nom pour le rapport.";
        } elseif (empty($departement)) {
            $this->errors[] = "Choisissez un département.";
        } elseif (empty($annee)) {
            $this->errors[] = "Choisissez une année.";
        } elseif (empty($date)) {
            $this->errors[] = "Entrez une date d'expiration.";
        } else if ($this->databaseConnection()) {
                $query_check_depot_name = $this->db_connection->prepare('SELECT nom FROM depots WHERE nom=:nom');
                $query_check_depot_name->bindValue(':nom', $nom, PDO::PARAM_STR);
                $query_check_depot_name->execute();
                
                $result = $query_check_depot_name->fetch();
                $nom_depot = $result[0];
                
                if($nom_depot != "") {
                    $this->errors[] = "Il existe déjà un rapport qui porte le même nom !";
                }else {
                
                // write new depot data into database
                $query_new_depot_insert = $this->db_connection->prepare('INSERT INTO depots (nom, departement, annee, date, nom_prof, binome) VALUES(:nom, :departement, :annee, :date, :nom_prof, :binome)');
                $query_new_depot_insert->bindValue(':nom', $nom, PDO::PARAM_STR);
                $query_new_depot_insert->bindValue(':departement', $departement, PDO::PARAM_STR);
                $query_new_depot_insert->bindValue(':annee', $annee, PDO::PARAM_STR);
                $query_new_depot_insert->bindValue(':date', $date);
                $query_new_depot_insert->bindValue(':nom_prof', $nom_prof, PDO::PARAM_STR);
                $query_new_depot_insert->bindValue(':binome', $binome, PDO::PARAM_INT);
                $query_new_depot_insert->execute();

                // id of new user
                $user_id = $this->db_connection->lastInsertId();

                if ($query_new_depot_insert) {
                    // when mail has been send successfully
                    $this->messages[] = "Le dépôt a bien été créé !<br><br>";
                    $this->creation_successful = true;
                } else {
                    $this->errors[] = "Erreur erreur erreur !!!!";
                }
            }
        }
    }
} 
