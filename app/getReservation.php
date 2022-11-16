<?php
include '../Model/model.php';



if (isset($_POST["id_hotel"]) && isset($_POST["nom"]) && isset($_POST["email"]) && !empty($_POST["id_hotel"]) && !empty($_POST["nom"]) && !empty($_POST["email"])) {
    $id_hotel = $_POST["id_hotel"];
    $dd_reservation = $_POST["dd_reservation"];
    $df_reservation = $_POST["df_reservation"];
    $email_client = $_POST["nom"];
    $nom_client = $_POST["email"];

//Check des hotels avec des chambres de libres
    $ReserManager =  new ReserManager();

    $stmnt = $ReserManager->checkReservation($id_hotel, $dd_reservation, $df_reservation);
    if ($stmnt){
        $idHotel = $stmnt[0]['hotel_id'];
        $chambre_id = (int)$stmnt[0]['id_chambre'];
    }else{
        exit(header("location: ../reservations.php?error=1"));

    }



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




 header("location: ../reservations.php");





}header("location: ../reservations.php?error=2");

