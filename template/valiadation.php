<?php
@session_start();
if (isset($_SESSION['order']['event_id'])) {
     header("Location: https://shootnbox.fr/manager/");
     exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Useful meta tags -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow, noodp">
    <meta name="googlebot" content="index, follow">
    <meta name="google" content="notranslate">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Card</title>
    <link rel="stylesheet" href="css/style.min.css"></head>

<body>
    <div class="page">
        <header class="header">
            <div class="container">
                <div class="header__wrapp">
                    <a href="#" class="logo">
                        <img src="img/logo.svg" alt="" class="logo-img">
                    </a>
                </div>
            </div>
        </header>    <div class="card">
            <div>
                <div class="card-title">
                    Votre maquette a été enregistrée.
                </div>
                <div class="card-text">
                    Vous allez recevoir un mail récapitulatif
                </div>
            </div>
            <div>
                <div class="card__box-img">
                    <img src="../uploads/images/<?php echo $_SESSION['order']['image'] ?>" alt="" class="card-img">

                    <div class="card-marker">
                        <img class='card-marker-img' src="img/card-mark.svg" alt="">
                        <?php
                            //unset($_SESSION['order']);
                        ?>
                    </div>
                </div>
                <div class="card-container">
                    <a href="../cabinet/contour_photo.php">
                    <button class="card-btn">
                        Retour à l’accueil
                    </button></a>
                </div>
            </div>
        </div>    <div class="footer">
            <div class="footer__wrapp">
                01.45.01.66.66 |
                <a class="footer__wrapp-link" href="mailto:contact@shootnbox.fr">contact@shootnbox.fr </a>
            </div>
        </div></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/app.min.js"></script>
</body>

</html>