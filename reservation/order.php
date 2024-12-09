<?php
  @session_start();
  if (isset($_GET['type']) && strtolower($_GET['type']) == 'entreprise') {
    $price_prefix = 'e';
    $price_sufix = 'HT';
  } else {
    $price_prefix = '';
    $price_sufix = 'TTC';
  }
  @require_once("../inc/mainfile.php");
  $box_type = mysqli_real_escape_string($conn, $_GET['box_type']);
  $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` = '$box_type'");
  $row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
  $i = 1;
  $price_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
  $duration = $_COOKIE['duration'];
  $total = $_COOKIE['number']*$price_arr[$duration - 1];

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <link rel="stylesheet" href="css/style.css?v=1.0.8">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
    
        <title>shootnbox</title>

        <style>
            :root{
                --color-accent: <?php echo $row_bornes_types['color']; ?>;
                --gradient: linear-gradient(<?php echo $row_bornes_types['color']; ?> 0%, #fff 100%);
            }
    
            :root:has(body.orange) {    
                --color-accent: #E3763E;   
                --gradient: linear-gradient(#E3763E 0%, #fff 100%); 
            }
    
            :root:has(body.blue) {    
                --color-accent: #3DA6DC; 
                --gradient: linear-gradient(#3DA6DC 0%, #fff 100%);   
            }
    
            :root:has(body.green) {    
                --color-accent: #4DAD58; 
                --gradient: linear-gradient(#4DAD58 0%, #fff 100%);   
            }
    
            :root:has(body.purple) {    
                --color-accent: #59579F;   
                --gradient: linear-gradient(#59579F 0%, #fff 100%); 
            }
        </style>
    </head>
<body>
    <div class="wrapper">
        <header class="header">
            <div class="container">
                <img src="img/logo.svg" alt="shootnbox logo">
            </div>            
        </header>
        <main class="main">
            <section class="main-section">
                <div class="container order-info__section">
                    <a href="javascript:void(0)" class="text-large" onclick="history.back(-1);">RETOUR</a>
                    <div class="order-info__wrapper">
                        <div class="cart__sidebar cart__sidebar--order">
                            <div class="cart-sidebar__item cart__info order-info__details">
                                <h3 class="title">RECAPITULATIF</h3>
                                <div class="order-info__info">
                                    <ul class="order-info__list">
                                        <li class="primary-text">Type: <span><?php echo $_COOKIE['type'] ?></span></li>
                                        <li class="primary-text">Date: <span><?php echo $_COOKIE['date'] ?></span></li>
                                        <li class="primary-text">Durée: <span>1 jour</span></li>
                                        <li class="primary-text">Lieu: <span><?php echo $_COOKIE['city'] ?>, <?php echo $_COOKIE['postcode'] ?></span></li>
                                    </ul>
                                </div>
                                <ul class="cart-item__list cart-item__list--order">
                                    <li class="cart-item__list-item primary-text">
                                    <span class="list-item__amount primary-text"><?php echo $i; ?></span>
                                        Photobooth <?php echo $row_bornes_types['title'] ?>
                                        <span class="list-item__price primary-text"><?php echo $_COOKIE['number']." x ".number_format($price_arr[$duration - 1], 2, '.', ''); ?> €</span>
                                    </li>
                                    <?php
                                      $i++;
                                      $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery` WHERE `id` IN (".$_COOKIE['delivery'].")");
                                      while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
                                        echo'<li class="cart-item__list-item primary-text">
                                            <span class="list-item__amount primary-text">'.$i.'</span>
                                            '.trim($row_delivery['title']).'
                                            <span class="list-item__price primary-text">'.($row_delivery[$price_prefix.'price'] != 0 ? $row_delivery[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                        </li>';
                                        $i++;
                                        $total = $total + $row_delivery[$price_prefix.'price'];
                                      }
                                    ?>

                                    <?php
                                      $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$_COOKIE['options'].")");
                                      while($row_options = mysqli_fetch_assoc($result_options)) {
                                        echo'<li class="cart-item__list-item primary-text">
                                            <span class="list-item__amount primary-text">'.$i.'</span>
                                            '.trim($row_options['title']).'
                                            <span class="list-item__price primary-text">'.($row_options[$price_prefix.'price'] != 0 ? $row_options[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                        </li>';
                                        $i++;
                                        $total = $total + $row_options[$price_prefix.'price'];
                                      }
                                    ?>

                                    <?php
                                      $result_recovery = mysqli_query($conn, "SELECT * FROM `recovery` WHERE `id` IN (".$_COOKIE['recovery'].")");
                                      while($row_recovery = mysqli_fetch_assoc($result_recovery)) {
                                        echo'<li class="cart-item__list-item primary-text">
                                            <span class="list-item__amount primary-text">'.$i.'</span>
                                            '.trim($row_recovery['title']).'
                                            <span class="list-item__price primary-text">'.($row_recovery[$price_prefix.'price'] != 0 ? $row_recovery[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                        </li>';
                                        $i++;
                                        $total = $total + $row_recovery[$price_prefix.'price'];
                                      }
                                    ?>
                                </ul>
                                <div class="cart-item__button-wrapper">
                                    <p class="primary-text">TOTAL</p>
                                    <button class="button text-large"><?php echo $total ?></span>€ <?php echo $price_sufix; ?></button>
                                </div>
                            </div>
                            <div class="cart-sidebar__item cart__promo">
                                <p class="primary-text">VOUS AVEZ UN CODE PROMO?</p>
                                <div class="cart-promo__input-wrapper">
                                    <input type="text" class="input primary-text">
                                    <button class="button primary-text">OK</button>
                                </div>
                            </div>
                        </div>
                        <div class="order-info__right">
                            <h3 class="title">COORDONNÉES</h3>
                            <form class="order-info__form" action="merci.php?type=<?php echo $_GET['type']; ?>&box_type=<?php echo $_GET['box_type']; ?>" method="post">
                                <?php
                                    if (isset($_GET['type']) && strtolower($_GET['type']) == 'entreprise') {
                                        echo'<div class="order-info__input-wrapper">
                                            <input type="text" class="input primary-text" id="company" name="company" placeholder="Nom de la société*" style="max-width: 100%" />
                                        </div>';
                                    }
                                ?>
                                <div class="order-info__input-wrapper">
                                    <input type="text" class="input primary-text" id="last_name" name="last_name" placeholder="NOM*" />
                                    <input type="text" class="input primary-text" id="first_name" name="first_name" placeholder="Prénom*"/>
                                </div>
                                <div class="order-info__input-wrapper">
                                    <input type="email" class="input primary-text" id="email" name="email" placeholder="Adresse mail*" />
                                    <input type="tel" class="input primary-text" id="phone" name="phone" placeholder="Téléphone*" />
                                </div>
                                <textarea class="input primary-text" name="comment" placeholder="Informations complémentaires"></textarea>
                                <button type="submit" class="button primary-text">DEMANDER UN DEVIS GRATUITEMENT</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
<script type="text/javascript">
    const form = document.querySelector('.order-info__form')
    form.addEventListener('submit', (e)=>{
        e.preventDefault();

        <?php  if (isset($_GET['type']) && strtolower($_GET['type']) == 'entreprise') { ?>
            const company = document.getElementById('company');
            if (company.value == '') {
                company.classList.add('input-error');
                return false;
            } else {
               company.classList.remove('input-error');
            }
        <?php } ?>

        const last_name = document.getElementById('last_name');
        if (last_name.value == '') {
            last_name.classList.add('input-error');
            return false;
        } else {
           last_name.classList.remove('input-error');
        }

        const first_name = document.getElementById('first_name');
        if (first_name.value == '') {
            first_name.classList.add('input-error');
            return false;
        } else {
           first_name.classList.remove('input-error');
        }

        const email = document.getElementById('email');
        if (email.value == '') {
            email.classList.add('input-error');
            return false;
        } else {
           email.classList.remove('input-error');
        }

        const phone = document.getElementById('phone');
        if (phone.value == '') {
            phone.classList.add('input-error');
            return false;
        } else {
           phone.classList.remove('input-error');
        }

        form.submit();
    });
</script>
<?php
  mysqli_close($conn);
?>