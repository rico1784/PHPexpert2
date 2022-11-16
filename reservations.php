<?php
require_once("./Model/model.php");

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css"


</head>
<body>

    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">TONhotel.com</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"href="hotels.php">Liste des hotels</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="reservations.php" >Réserver un hotel</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main class="">

        <img class="ImgAccueil" src="img/hotel.jpg" alt="">
        <form method="post" action="app/getReservation.php" class="row g-3 mt-5 mb-5 center">

            <div class="col-md-3">
                <label for="inputState" class="form-label">Sélection de l'hôtel</label>
                <select name="id_hotel" class="form-select">
                    <option>Sélectionner hôtels</option>
                    <?php

                    try {

                    $ReserManager =  new ReserManager();
                    //Récupération des hôtels
                    $stmnt = $ReserManager->listHotel();

                    foreach ($stmnt as $value) {
                        ?>
                        <option value="<?php echo $value['id_hotel']; ?>"><?php echo $value['nom_hotel']; ?></option>


                    <?php }
                    }catch (Exception $e){
                        echo '<tr><td colspan="3" class="text-danger">Erreur :</td></tr>';
                        echo '<tr><td colspan="3" class="text-danger">'.$e->getMessage().'</td></tr>';

                    }


                    ?>




                </select>
            </div>

            <div class="col-md-3">
                <label for="dd_reservation" class="form-label">Date début</label>
                <input type="date" class="form-control" name="dd_reservation" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">S'il vous plaît remplir ce champ.</div>
            </div>

            <div class="col-md-3">
                <label for="df_reservation" class="form-label">Date fin</label>
                <input type="date" class="form-control" name="df_reservation" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">S'il vous plaît remplir ce champ.</div>
            </div>

            <div class="col-md-6">
                <label for="text" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">S'il vous plaît remplir ce champ.</div>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">S'il vous plaît remplir ce champ.</div>
            </div>


            <div class="col-12">
                <button type="submit" class="btn btn-primary">Réserver une chambre</button>
            </div>

    </main>
<?php
if (isset($_GET["error"])){
    if($_GET["error"] == 1){
        echo '<h3 class="center">Pas de chambre de libre pour cet hôtel, veuillez en choisir un autre</h3>';
    }elseif ($_GET["error"] == 2){
         echo '<h3 class="center">Votre chambre est réservé</h3>';
    }
}

?>



    <footer class="bg-dark text-center text-white">

        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Form -->
            <section class="">
                <form action="">
                    <!--Grid row-->
                    <div class="row d-flex justify-content-center">
                        <!--Grid column-->
                        <div class="col-auto">
                            <p class="pt-2">
                                <strong>Sign up for our newsletter</strong>
                            </p>
                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-md-5 col-12">
                            <!-- Email input -->
                            <div class="form-outline form-white mb-4">
                                <input type="email" id="form5Example29" class="form-control" />
                                <label class="form-label" for="form5Example29">Email address</label>
                            </div>
                        </div>
                        <!--Grid column-->

                        <!--Grid column-->
                        <div class="col-auto">
                            <!-- Submit button -->
                            <button type="submit" class="btn btn-outline-light mb-4">
                                Subscribe
                            </button>
                        </div>
                        <!--Grid column-->
                    </div>
                    <!--Grid row-->
                </form>
            </section>
            <!-- Section: Form -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2020 Copyright:
            <a class="text-white" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>








<script src="js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>

</body>
</html>
