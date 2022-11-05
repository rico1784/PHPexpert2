<?php
include 'dbconnect.php';

class Clients
{

    protected $_id_client;
    protected $_name_client;
    protected $_email_client;

    //Déclaration de l'attribut statique $error
    protected static $_error;

    //Déclaration des messages d'erreur dans les constantes
    const MSG_ERROR_ID = 'ID doit être un entier.';
    const MSG_ERROR_TEXT = 'Doit être une chaine de caratères';
    const MSG_ERROR_EMAIL = 'Doit être un email valable';
    const MSG_ERROR_END = 'L\'objet n\'a pas pu être créé';

    public function __construct(array $data)
    {
    $this->_id_client = $data['id_client'];
    $this->_name_client = $data['name_client'];
    $this->_email_client = $data['email_client'];

        //       Accès à l'erreur via self::
        if (!empty(self::$error)) {
            throw new Exception (self::$error . self::MSG_ERROR_END);
        }

    }


    //Gestion des ereurs
    public function setError($msg)
    {
        self::$error = $msg;
    }

    public function getError()
    {
        return self::$error;
    }


    public function setIdClient($id_client)
    {
        if (is_int($id_client) and $id_client > 0) {
            $this->_id_client = $id_client;
        } else {
            $this->setError(self::MSG_ERROR_ID);
        }
    }

    public function setNomClient($nom_client)
    {
     if (is_string($nom_client)){
         $this->_name_client = $nom_client;
     }else {
         $this->setError(self::MSG_ERROR_TEXT);
     }

    }

    public function setEmailClient($email_client)
    {
        if (!filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
            $this->_email_client = $email_client;
        }else {
            $this->setError(self::MSG_ERROR_EMAIL);
        }

    }


}


class clientManager extends db
{

private $ADD_CLIENT = 'INSERT INTO  clients (nom_client, email_client) VALUES (:nom_client, :email_client)';





    public function addClients(Clients $clients)
    {
        $stmnt = $this->connect()->prepare($this->ADD_CLIENT);
        $nom_client = $clients->setNomClient();
        $email_client = $clients->setEmailClient();
        $stmnt->bindParam(':nom_client',$nom_client);
        $stmnt->bindParam(':email_client', $email_client);
        $stmnt->execute();

    }







}
