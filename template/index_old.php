<?php
@session_start();
//if (isset($_SESSION['order'])) {header("Location: templates.php"); exit;}
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
    <title>Entrance</title>
    <link rel="stylesheet" href="css/style.min.css"></head>

<body>
    <div class="entrance">

        <header class="header">
            <div class="container">
                <div class="header__wrapp">
                    <a href="#" class="logo">
                        <img src="img/logo.svg" alt="" class="logo-img">
                    </a>
                </div>
            </div>
        </header>
        <div class="entrance__form">
            <div class="entrance__form-logo">
                Connexion
            </div>
            <div class="entrance__form-title">
                Votre identifiant
            </div>
            <input type="text" class="entrance__form-input login" placeholder="Numéro de devis">
            <input type="text" class="entrance__form-input password" placeholder="Mot de passe">
            <a href="#" class="entrance__form-btn">Se connecter</a>
        </div>    <div class="footer">
            <div class="footer__wrapp">
                01.45.01.66.66 |
                <a class="footer__wrapp-link" href="mailto:contact@shootnbox.fr">contact@shootnbox.fr </a>
            </div>
        </div></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/app.min.js"></script>
</body>
  <link href="../manager/assets/plugins/sweetalert2/css/sweetalert2.min.css" rel="stylesheet">
  <script src="../manager/assets/plugins/sweetalert2/js/sweetalert2.min.js"></script>
  <!-- ================== END PAGE LEVEL JS ================== -->

  <script>
    $(document).ready(function() {

      // Обработка формы входа
      $('.entrance__form-btn').click(function(event){
        event.preventDefault();
        if ($('.login').val() == '') {
          swal({
            title: 'Erreur!',
            text: 'Entrez le numéro de devis!',
            type: 'error',
            confirmButtonColor: '#53a3d8',
            confirmButtonText: 'Fermer'
          });
          return false;
        }
        if ($('.password').val() == '') {
          swal({
            title: 'Erreur!',
            text: 'Entrez le mot de passe!',
            type: 'error',
            confirmButtonColor: '#53a3d8',
            confirmButtonText: 'Fermer'
          });
          return false;
        }
        $.ajax({
          type: 'POST',
          url: '../manager/d26386b04e.php',
          data: {event: 'login_order', login: $('.login').val(), password: $('.password').val()},
          cache: false,
          success: function(responce) {
            console.log(responce);
            if (responce.trim() == 'done') {
              window.location.href = 'templates.php';
            } else {
              swal('Erreur!', 'Oups ! Il semblerait que vous nous ayez déjà transmis votre création. Si vous souhaitez modifier votre contour photo, merci de contacter notre service graphisme par mail à : <a href="mailto:contact@shootnbox.fr">contact@shootnbox.fr</a>', 'error');
            }
          }
        });
      });

    });
  </script>
</html>