<?php

class Hotels
{
    Protected $_id_hotel;
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
        $this->setNameHotel= $data['name_hotel'];
        $this->setAdresseHotel = $data['adresse_hotel'];


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




