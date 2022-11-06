<?php
include 'dbconnect.php';



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
        $this->setId = $data['id_reservation'];
        $this->setDateCreation = $data['dc_reservation'];
        $this->setDateDebut = $data['dd_reservation'];
        $this->setDateFin = $data['df_reservation'];
        $this->setClientId = $data['client_id'];
        $this->setChambreId = $data['chambre_id'];

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
        if (checkdate($y, $m, $d)) {
            $this->_dc_reservation = $dc_reservation;
        } else {
            $this->setError(self::MSG_ERROR_DATE);
        }
    }

    public function setDatedebut($dd_reservation)
    {
        list($y, $m, $d) = explode('-', $dd_reservation);
        if (checkdate($y, $m, $d)) {
            $this->_dd_reservation = $dd_reservation;
        } else {
            $this->setError(self::MSG_ERROR_DATE);
        }
    }


    public function setDateFin($df_reservation)
    {
        list($y, $m, $d) = explode('-', $df_reservation);
        if (checkdate($y, $m, $d)) {
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
    private $ADD_RESERVATION = "INSERT INTO reservations (dc_reservation, dd_reservation, df_reservation, client_id, chambre_id) VALUES (:dc_reservation, :dd_reservation, :df_reservation, :client_id, :hotel_id)";
    private $GET_ALL_RESERVATION = "SELECT * FROM reservations";
    private $GET_RESERVATION = "SELECT * FROM reservations WHERE id_reservation = :id_reservation";
    private $DELETE_RESERVATION = "DELETE FROM reservations WHERE id_reservation = :id_reservation";




    public function addReservation(Reservation $reservation)
    {
        $stmnt = $this->connect()->prepare($this->ADD_RESERVATION);
        $dc_reservation = $reservation->setDateReservation();
        $dd_reservation = $reservation->setDateDebut();
        $df_reservation = $reservation->setDateFin();
        $client_id = $reservation->setClientId();
        $chambre_id = $reservation->setChambreId();
        $stmnt->bindParam(':datereservation', $dc_reservation);
        $stmnt->bindParam(':datedebut', $dd_reservation);
        $stmnt->bindParam(':datefin', $df_reservation);
        $stmnt->bindParam(':clientid', $client_id);
        $stmnt->bindParam(':chambreid', $chambre_id);
        $stmnt->execute();


    }



    public function getReservation($id_reservation)
    {
        if (empty($id_reservation)) {
            $stmnt = $this->connect()->prepare($this->GET_ALL_RESERVATION);
        } elseif (is_numeric($id_reservation)) {
            $stmnt = $this->connect()->prepare($this->GET_RESERVATION);
            $stmnt->bindParam(':id_reservation', $id_reservation);
        }
        $stmnt->execute();
        while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }


    public function deleteReservation($id_reservation)
    {
        $stmnt = $this->connect()->prepare($this->DELETE_RESERVATION);
        $stmnt->bindParam(':id_reservation', $id_reservation);
        $stmnt->execute();
        $count = $stmnt->rowCount();
        return $count;
    }


}

