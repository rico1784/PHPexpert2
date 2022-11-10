<?php
include '../Model/model.php';

if (isset($_POST["id_hotel"]) && !empty($_POST["id_hotel"])) {
    $id_hotel = $_POST["id_hotel"];
    $dd_reservation = $_POST["dd_reservation"];
    $df_reservation = $_POST["df_reservation"];


//echo $id_hotel;

    $ReserManager =  new ReserManager();
    $stmnt = $ReserManager->checkReservation($id_hotel,$dd_reservation,$df_reservation);
    var_dump($stmnt) ;


//    header("location: ../reservations.php");

}

