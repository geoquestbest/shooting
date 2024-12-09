<!DOCTYPE html>
<html lang="fr">

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
    <link rel="stylesheet" href="css/style.min.css">
</head>
<style>
    @font-face {
        font-family: 'ITC Avant Garde Pro Bk';
        src: url('font/ITCAvantGardePro-Bk.eot');
        src: url('font/ITCAvantGardePro-Bk.eot?#iefix') format('embedded-opentype'),
            url('font/ITCAvantGardePro-Bk.woff2') format('woff2'),
            url('font/ITCAvantGardePro-Bk.woff') format('woff'),
            url('font/ITCAvantGardePro-Bk.ttf') format('truetype'),
            url('font/ITCAvantGardePro-Bk.svg#ITCAvantGardePro-Bk') format('svg');
        font-weight: 300;
        font-style: normal;
        font-display: swap;
    }

    .entrance-wrapp {
        max-width: 490px;
        margin: 50px auto 10px;
    }

    .entrance__form {
        margin-top: 25px;
    }

    .entrance-box {
        max-width: 490px;
        margin: 0 auto;
        background: #d34b8b;
        display: flex;
        padding: 15px 7px 15px 7px;
        box-shadow: 4px 4px 4px rgba(0, 0, 0, .5);


    }

    .entrance-box-img img {
        width: 72px;
        height: 60px;
    }

    .entrance-content {
        font-family: 'ITC Avant Garde Pro Bk';
        font-weight: 300;
        font-size: 20px;
        text-align: center;
        line-height: 1.3;
        color: #fff;
        margin-left: 2px;
    }

    .entrance-content-link {
        font-family: 'ITC Avant Garde Pro Bk';
        font-weight: 300;
        font-size: 20px;
        line-height: 1.3;
        color: #fff;
        text-decoration: underline;
    }

    .entrance__form {
        padding-bottom: 35px;
        margin-bottom: 0;
    }

    .entrance__form-input.password {
        margin-bottom: 30px;

    }

    @media(max-height: 955px) {
        .entrance__form-btn {
            margin-top: 15px;
        }

        .entrance__form {
            padding-bottom: 35px;
        }
    }

    @media(max-height: 905px) {
        .entrance__form-btn {
            margin-top: 15px;
        }

        .entrance__form {
            padding-bottom: 25px;
            margin-bottom: 10px;
        }

    }
</style>

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
        <div class="entrance-wrapp">
            <div class="entrance__form">
                <div class="entrance__form-logo">
                    Bienvenue
                </div>
                <div class="entrance__form-title">
                    Votre identifiant
                </div>
                <form class="login-form">
                  <input type="text" name="email" class="entrance__form-input login-email" value="" placeholder="E-mail / Devis N°">
                  <input type="password" name="password" class="entrance__form-input login-password" value="" placeholder="Mot de passe">
                  <button type="submit" class="entrance__form-btn">Se connecter</button>
                </form>
            </div>
        </div>
        <div class="footer">
            <div class="footer__wrapp">
                01.45.01.66.66 |
                <a class="footer__wrapp-link" href="mailto:contact@shootnbox.fr">contact@shootnbox.fr </a>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
<link href="assets/plugins/sweetalert2/css/sweetalert2.min.css" rel="stylesheet">
<script src="assets/plugins/sweetalert2/js/sweetalert2.min.js"></script>
<script src="assets/js/main.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
    $(document).ready(function () {

      // Обработка формы входа
      $('.login-form').on('submit', function(event){
        event.preventDefault();
        if ($('.login-email').val() == '') {
          swal({
            title: 'Erreur!',
            text: 'Entrez votre adresse e-mail / Devis N° !',
            type: 'error',
            confirmButtonColor: '#53a3d8',
            confirmButtonText: 'Fermer'
          });
          return false;
        }
        if ($('.login-password').val() == '') {
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
          url: 'd26386b04e.php',
          data: {event: 'login', email: $('.login-email').val(), password: $('.login-password').val()},
          cache: false,
          success: function(responce) {
            console.log(responce);
            if (responce.trim() == 'done'){
              window.location.href = './';
            } else {
              swal('Erreur!', 'Combinaison incorrecte d\'e-mail / mot de passe!', 'error');
            }
          }
        });
      });

    });
</script>

</html>