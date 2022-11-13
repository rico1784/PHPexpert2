<?php
include '../Model/model.php';

$email_client = 'test@rte.ch';
$nom_client = 'test1';

if (isset($_POST["id_hotel"]) && !empty($_POST["id_hotel"])) {
    $id_hotel = $_POST["id_hotel"];
    $dd_reservation = $_POST["dd_reservation"];
    $df_reservation = $_POST["df_reservation"];

//Check des hotels avec des chambres de libres
    $ReserManager =  new ReserManager();
    $stmnt = $ReserManager->checkReservation($id_hotel, $dd_reservation, $df_reservation);

    $idHotel = $stmnt[0]['hotel_id'];
    $chambre_id = (int)$stmnt[0]['id_chambre'];
    echo $idHotel;


//    Enregistrement de la réservation de la 1ère chambre de libre

    if(isset($idHotel)){

        $clientManager =  new clientManager();

//    Controle si déjà client
        $stmnt = $clientManager->getIdClient($email_client);

        if(empty($stmnt)){
            $addClient = $clientManager->addClients($nom_client, $email_client );
            $stmnt = $clientManager->getIdClient($email_client);
            $IdClient = (int)$stmnt[0]['id_client'];
        }else {
            $IdClient = (int)$stmnt[0]['id_client'];
        }






//        Récupération de la 1er chambre libre



        $dc_reservation = date("Y-m-d");
        $reservation_data = array(
            'dc_reservation' =>$dc_reservation,
            'dd_reservation' =>$dd_reservation,
            'df_reservation' =>$df_reservation,
            'client_id' => $IdClient,
            'chambre_id' => $chambre_id,

        );

        $Reservation = new Reservation($reservation_data);

        $Manager = NEW ReserManager();

        $Manager->addReservation($Reservation);








    }




//    header("location: ../reservations.php");





}

