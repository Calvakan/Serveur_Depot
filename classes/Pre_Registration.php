<?php

class Pre_Registration
{
    /**
     * @var object $db_connection The database connection
     */
    private $db_connection            = null;
    /**
     * @var bool success state of registration
     */
    public  $pre_registration_successful  = false;
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

        // if we have such a POST request, call the registerNewUser() method
        if (isset($_POST["pre_register"])) {
            $this->pre_registerNewUser($_POST['num_etu']);
            // if we have such a GET request, call the verifyNewUser() method
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
     * handles the entire pre_registration process.
     */
    private function pre_registerNewUser($num_etu)
    {
        if (empty($num_etu)) {
            $this->errors[] = "Veuillez entrez votre numéro d'étudiant !";
        } else if ($this->databaseConnection()) {
            // check if username or email already exists
            $query_check_user_name = $this->db_connection->prepare('SELECT id FROM authorized WHERE id=:num_etu');
            $query_check_user_name->bindValue(':num_etu', $num_etu, PDO::PARAM_STR);
            $query_check_user_name->execute();
            
            $result = $query_check_user_name->fetch();
            $id_trouve = $result[0];

            // if id not found in the database
            if ($result == "") {
                $this->errors[] = "Vous n'Ãªtes pas autorisÃ© Ã  vous inscrire !";
            } else {header("Location: register.php?num_etu=$num_etu"); }
        }
    }

}
