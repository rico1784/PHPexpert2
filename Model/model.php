<?php
class dbconnect
{

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'reshotels';

    //Déclaration de l'attribut statique $pdo
    private static $pdo;

    //Connexion à la base de données
    protected function connect()
    {
        $dsn = 'mysql:host  =' . $this->host . ';dbname=' . $this->dbname;
        $pdo = new PDO($dsn, $this->user, $this->pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
//    Déconnexion de la base de données
    protected function disconnect()
    {
        $pdo = NULL;
    }


}


class Reservation
{
    protected $_id_reservation;
    protected $_dc_reservation;
    protected $_dd_reservation;
    protected $_df_reservation;
    protected $_client_id;
    protected $_chambre_id;

    //Déclaration de l'attribut statique $error
    protected static $_error;

    //Déclaration des messages d'erreur dans les constantes
    const MSG_ERROR_ID = 'ID doit être un entier.';
    const MSG_ERROR_DATE = 'Date doit être au format YYYY-MM-DD';
    const MSG_ERROR_END = 'L\'objet n\'a pas pu être créé';

    public function __construct(array $data)
    {

        $this->setDateCreation($data['dc_reservation']);
        $this->setDatedebut($data['dd_reservation']);
        $this->setDateFin($data['df_reservation']);
        $this->setClientId($data['client_id']);
        $this->setChambreId($data['chambre_id']);

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

    //Setter
    public function setId($id_reservation)
    {
        if (is_int($id_reservation) and $id_reservation > 0) {
            $this->_id_reservation = $id_reservation;
        } else {
            $this->setError(self::MSG_ERROR_ID);
        }
    }


    public function setDateCreation($dc_reservation)
    {
        list($y, $m, $d) = explode('-', $dc_reservation);
        if (checkdate($m, $d, $y)) {
            $this->_dc_reservation = $dc_reservation;
        } else {
            $this->setError(self::MSG_ERROR_DATE);
        }
    }

    public function setDatedebut($dd_reservation)
    {
        list($y, $m, $d) = explode('-', $dd_reservation);
        if (checkdate($m, $d, $y)) {
            $this->_dd_reservation = $dd_reservation;
        } else {
            $this->setError(self::MSG_ERROR_DATE);
        }
    }


    public function setDateFin($df_reservation)
    {
        list($y, $m, $d) = explode('-', $df_reservation);
        if (checkdate($m, $d, $y)) {
            $this->_df_reservation = $df_reservation;
        } else {
            $this->setError(self::MSG_ERROR_DATE);
        }
    }

    public function setClientId($client_id)
    {
        if (is_int($client_id) and $client_id > 0) {
            $this->_client_id = $client_id;
        } else {
            $this->setError(self::MSG_ERROR_ID);
        }
    }



    public function setChambreId($chambre_id)
    {
        if (is_int($chambre_id) and $chambre_id > 0) {
            $this->_chambre_id = $chambre_id;
        } else {
            $this->setError(self::MSG_ERROR_ID);
        }
    }


//Getter
    public function getIdReservation()
    {
        return $this->_id_reservation;
    }

    public function getDateCreation()
    {
        return $this->_dc_reservation;
    }

    public function getDateDebut()
    {
        return $this->_dd_reservation;
    }

    public function getDateFin()
    {
        return $this->_df_reservation;
    }

    public function getClientId()
    {
        return $this->_client_id;
    }

    public function getChambreId()
    {
        return $this->_chambre_id;
    }




}



class ReserManager extends dbconnect
{

    private $ADD_RESERVATION = "INSERT INTO reservations (dc_reservation, dd_reservation, df_reservation, client_id, chambre_id) VALUES (:dc_reservation, :dd_reservation, :df_reservation, :client_id, :chambre_id)";
    private $CHECK_RESERVATION = "SELECT ch.id_chambre, ch.num_chambre, ch.hotel_id, re.dd_reservation, re.df_reservation FROM chambres ch LEFT JOIN reservations re on ch.id_chambre = re.chambre_id LEFT JOIN hotels ho on ch.hotel_id = ho.id_hotel WHERE re.dd_reservation IS NULL OR (re.dd_reservation <> :dd_reservation AND re.df_reservation >= :df_reservation)
                                    HAVING ch.hotel_id = :id_hotel";

    private $LIST_HOTEL_CHAMBRES = "SELECT ho.id_hotel, ho.nom_hotel, ho.adresse_hotel, COUNT(ch.id_chambre) AS Nbre_chambre FROM  hotels ho INNER JOIN chambres ch on ho.id_hotel = ch.hotel_id
                                     GROUP BY nom_hotel";
    private $LIST_HOTEL = "SELECT nom_hotel, id_hotel FROM hotels";


    //Ajout d'une réservation
    public function addReservation(Reservation $reservation)
    {

        $dc_reservation = $reservation->getDateCreation();
        $dd_reservation = $reservation->getDateDebut();
        $df_reservation = $reservation->getDateFin();
        $client_id = $reservation->getClientId();
        $chambre_id = $reservation->getChambreId();


        $stmnt = $this->connect()->prepare($this->ADD_RESERVATION);
        $stmnt->bindParam(':dc_reservation', $dc_reservation);
        $stmnt->bindParam(':dd_reservation', $dd_reservation);
        $stmnt->bindParam(':df_reservation', $df_reservation);
        $stmnt->bindParam(':client_id', $client_id);
        $stmnt->bindParam(':chambre_id', $chambre_id);
        $stmnt->execute();
        $stmnt = $this->disconnect();




    }


    //Contrôle des réservations possible pour chaque hôtels
    public function checkReservation($id_hotel, $dd_reservation, $df_reservation)
    {
        $stmnt = $this->connect()->prepare($this->CHECK_RESERVATION);
        $stmnt->bindParam(':id_hotel', $id_hotel);
        $stmnt->bindParam(':dd_reservation', $dd_reservation);
        $stmnt->bindParam(':df_reservation', $df_reservation);
        $stmnt->execute();
        if($stmnt) {
            while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
                return $result;
            }

        }
        $stmnt = $this->disconnect();


    }


    //Liste des chambres de libre par hôtels
    public function listHotelChambre(){

        $stmnt = $this->connect()->prepare($this->LIST_HOTEL_CHAMBRES);
        $stmnt->execute();

    while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = $row;

    }    return $result;
        $stmnt = $this->disconnect();

}
    //Liste tous les hôtels
    public function listHotel(){

        $this->connect();
        $stmnt = $this->connect()->prepare($this->LIST_HOTEL);
        $stmnt->execute();

        while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
        $stmnt = $this->disconnect();
    }




}



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


    //Getter
    public function getIdClient()
    {
        return $this->_id_client;
    }

    public function getNomClient()
    {
        return $this->_name_client;
    }

    public function getEmailClient()
    {
        return $this->_email_client;
    }


    //Setter
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


class clientManager extends dbconnect
{

    private $ADD_CLIENT = 'INSERT INTO  clients (nom_client, email_client) VALUES (:nom_client, :email_client)';
    private $ID_CLIENT = 'SELECT id_client FROM clients WHERE email_client = :email_client';

    //Ajout du client dans la DB
    public function addClients($nom_client,$email_client)
    {
        $stmnt = $this->connect()->prepare($this->ADD_CLIENT);
        $stmnt->bindParam(':nom_client',$nom_client);
        $stmnt->bindParam(':email_client', $email_client);
        $stmnt->execute();
        $stmnt = $this->disconnect();

    }
    //Check client existant par l'email
    public function getIdClient($email_client)
    {
        $stmnt = $this->connect()->prepare($this->ID_CLIENT);
        $stmnt->bindParam(':email_client', $email_client);
        $stmnt->execute();
        $stmnt = $stmnt->fetchall(0);
        return $stmnt;
        $stmnt = $this->disconnect();
    }





}


class Hotels
{
    protected $_id_hotel;
    protected $_name;
    protected $_adresse;

    //Déclaration de l'attribut statique $error
    protected static $_error;

    //Déclaration des messages d'erreur dans les constantes
    const MSG_ERROR_ID = 'ID doit être un entier.';
    const MSG_ERROR_TEXT = 'Doit être une chaine de caratères';
    const MSG_ERROR_END = 'L\'objet n\'a pas pu être créé';


    public function __construct(array $data)
    {
        $this->setIdHotel = $data['id_hotel'];
        $this->setNameHotel = $data['name_hotel'];
        $this->setAdresseHotel = $data['adresse_hotel'];


    //  Accès à l'erreur via self::
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

    //Setter
    public function setIdHotel($id_hotel)
    {
        if (is_int($id_hotel) and $id_hotel > 0) {
            $this->_id_hotel = $id_hotel;
        } else {
            $this->setError(self::MSG_ERROR_ID);
        }
    }

    public function setNomHotel($nom_hotel)
    {
        if (is_string($nom_hotel)) {
            $this->_nom_hotel = $nom_hotel;
        } else {
            $this->setError(self::MSG_ERROR_TEXT);
        }
    }


    public function setAdresseHotel($adresse_hotel)
    {
        if (is_string($adresse_hotel)) {
            $this->_adresse_hotel = $adresse_hotel;
        } else {
            $this->setError(self::MSG_ERROR_TEXT);
        }
    }

    //Getter
    public function getIdHotel()
    {
        return $this->_id_hotel;
    }

    public function getNomHotel()
    {
        return $this->_nom_hotel;
    }

    public function getAdresseHotel()
    {
        return $this->_adresse_hotel;
    }


}

class HotelsManager extends dbconnect
{


    private $LIST_HOTEL = "SELECT ho.id_hotel, ho.nom_hotel, ho.adresse_hotel, COUNT(ch.id_chambre) AS Nbre_chambre FROM  hotels ho INNER JOIN chambres ch on ho.id_hotel = ch.hotel_id
                            GROUP BY nom_hotel";

//List des hôtels par nom_hotel
    public function listHotel()
    {

        $stmnt = $this->connect()->prepare($this->LIST_HOTEL);
        $stmnt->execute();
        $stmnt = $this->disconnect();
        while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
        $stmnt = $this->disconnect();
    }

}