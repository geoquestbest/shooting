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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css?v=1.1.6">
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
                <div class="container cart-section">
                    <div class="cart__sidebar">
                        <div class="cart-sidebar__item cart__info">
                            <h3 class="title">VOTRE DEVIS</h3>
                            <ul class="cart-item__list">
                                <?php
                                    $prices_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
                                ?>
                                <li class="cart-item__list-item primary-text" data-type="bornes_types" data-id="<?php echo $row_bornes_types['id'] ?>" data-price="<?php echo $row_bornes_types['price'] ?>">
                                    <span class="list-item__amount primary-text">1</span>
                                    Photobooth <?php echo $row_bornes_types['title'] ?>
                                    <span class="list-item__price primary-text box_price"><?php echo $prices_arr[0] ?>€</span>
                                </li>
                            </ul>
                            <div class="cart-item__button-wrapper">
                                <p class="primary-text"><span>Remise</span> <span class="discount">0.00</span> €</p>
                                <p class="primary-text">TOTAL</p>
                                <button class="button text-large"><span class="total"><?php echo $prices_arr[0] ?></span>€ <?php echo $price_sufix; ?></button>
                            </div>
                        </div>
                        <div class="cart-sidebar__item cart__promo">
                            <p class="primary-text">VOUS AVEZ UN CODE PROMO?</p>
                            <div class="cart-promo__input-wrapper">
                                <input type="text" class="input primary-text promo_input">
                                <button class="button primary-text" onClick="checkPromo()">OK</button>
                                <p class="success">Remise  <span class="discount">0.00</span> €</p>
                                <p class="error">Code promotionnel invalide</p>
                            </div>
                        </div>
                        <div class="cart-sidebar__button-wrapper">
                            <!--a href="order.php?type=<?php echo $_GET['type']; ?>&box_type=<?php echo $_GET['box_type']; ?>" class="button text-large">CONTINUER</a-->
                            <!-- <a data-modal="modal-extra" href="order.php?type=entreprise&box_type=2" class="button text-large">CONTINUER</a> -->
                            <button class="button text-large" onClick="doReservation('<?php echo $_GET['type']; ?>', <?php echo $_GET['box_type']; ?>)">CONTINUER</button>
                            <a href="boxes.php?type=<?php echo $_GET['type']; ?>" class="text-large">RETOUR</a>
                        </div>
                    </div>
                    <div class="cart__right">
                        <div class="cart__product-info">
                            <img src="<?php echo ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image2']; ?>" alt="" class="procut-info__img">
                            <div class="product-info__content">
                                <h2 class="title">Inclus dans la location</h2>
                                <div class="product-info__advantages">
                                    <?php
                                    $descriptions_arr = explode("|||", $row_bornes_types['description']);
                                    for ($j = 0; $j < count($descriptions_arr) - 1; $j++){
                                        for ($i = 0; $i < count($descriptions_arr) - $j - 1; $i++){
                                            // если текущий элемент больше следующего
                                            $description_arr = explode("||", $descriptions_arr[$i]);
                                            $description_arr2 = explode("||", $descriptions_arr[$i + 1]);
                                            if ($description_arr[2] > $description_arr2[2]){
                                                // меняем местами элементы
                                                $tmp_var = $descriptions_arr[$i + 1];
                                                $descriptions_arr[$i + 1] = $descriptions_arr[$i];
                                                $descriptions_arr[$i] = $tmp_var;
                                            }
                                        }
                                    }
                                    foreach ($descriptions_arr as $key => $value) {
                                      $description_arr = explode("||", $value);
                                      echo'<div class="product-info__advantages-item">
                                        <img src="'.$description_arr[0].'" alt="">
                                        <p class="primary-text">'.$description_arr[1].'</p>
                                    </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="cart__personalization">
                            <h2 class="title">PERSONNALISER VOTRE DEVIS</h2>
                            <div class="cart-personalization__item">
                                <button class="cart-personalization__header">
                                    <h2 class="title">1 - INFORMATIONS ÉVÈNEMENT*</h2>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="58.485" height="33.485" viewBox="0 0 58.485 33.485">
                                        <path id="Vector_4" data-name="Vector 4" d="M50,25,25,0,0,25" transform="translate(54.243 29.243) rotate(180)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="6"/>
                                    </svg>
                                </button>
                                <div class="cart-personalization__dropdown personalization-dropdown__form-item">
                                    <div class="personalization-form__wrapper">
                                        <form class="personalization__form">
                                            <div class="personalization__input-wrapper">
                                                <label for="date" class="primary-text input__header">Date de l’évènement*</label>
                                                <input type="date" id="date" class="input" placeholder="01-01-2024">
                                                <div class="personalization__input-button" onclick="this.previousElementSibling.showPicker()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" height="25" viewBox="0 0 22 25">
                                                        <defs>
                                                          <clipPath id="clip-path">
                                                            <rect id="Calque_1_Frame_Clip_Content_Mask_" data-name="Calque_1 [Frame Clip Content Mask]" width="22" height="25" transform="translate(1135 1030)" fill="none"/>
                                                          </clipPath>
                                                        </defs>
                                                        <g id="Calque_1_Clip_Content_" data-name="Calque_1 (Clip Content)" transform="translate(-1135 -1030)" clip-path="url(#clip-path)">
                                                          <rect id="Calque_1_Frame_Background_" data-name="Calque_1 [Frame Background]" width="22" height="25" transform="translate(1135 1030)" fill="none"/>
                                                          <path id="Vector" d="M14.056,20a2.918,2.918,0,0,1-2.176-.913,3.22,3.22,0,0,1,0-4.45,3.05,3.05,0,0,1,4.351,0,3.22,3.22,0,0,1,0,4.45A2.946,2.946,0,0,1,14.056,20ZM2.444,25a2.329,2.329,0,0,1-1.723-.737A2.435,2.435,0,0,1,0,22.5V5A2.435,2.435,0,0,1,.721,3.237,2.329,2.329,0,0,1,2.444,2.5H3.667V0H6.111V2.5h9.778V0h2.444V2.5h1.222a2.329,2.329,0,0,1,1.723.737A2.435,2.435,0,0,1,22,5V22.5a2.435,2.435,0,0,1-.721,1.763A2.329,2.329,0,0,1,19.556,25Zm0-2.5H19.556V10H2.444Zm0-15H19.556V5H2.444Z" transform="translate(1135 1030)" fill="#4f4f4f"/>
                                                          <text id="durée_de_l_évènement" data-name="durée de l’évènement" transform="translate(804 1109.91)" fill="#da3a8d" font-size="18" font-family="Nominee-ExtraBold, Nominee" font-weight="800"><tspan x="0" y="14">DURÉE DE L’ÉVÈNEMENT</tspan></text>
                                                        </g>
                                                      </svg>
                                                </div>
                                            </div>
                                            <div class="personalization__input-wrapper">
                                                <?php
                                                    if (isset($_GET['type']) && strtolower($_GET['type']) == 'entreprise') {
                                                        $events_arr = array("Animation boutique", "Salon professionnel", "Evènement sportif", "Festival / Concert", "Inauguration", "Lancement de produit", "Location longue durée", "Opération marketing", "Soirée interne", "Team Building", "Autre");
                                                    } else {
                                                        $events_arr = array("Soirée privée", "Mariage", "Anniversaire", "Baby Shower", "Autre");
                                                    }
                                                ?>
                                                <label for="type" class="primary-text input__header">TYPE D’évènement*</label>
                                                <div class="select-wrapper">
                                                    <div class="select-head">
                                                        <div class="value" id="type"><?php echo $events_arr[0]; ?></div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 58.485 33.485">
                                                            <path d="M50,25,25,0,0,25" transform="translate(54.243 29.243) rotate(180)" fill="none" stroke="#DA3A8D" stroke-linecap="round" stroke-width="6"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="select-dropdown">
                                                        <?php
                                                            foreach ($events_arr as $key => $value) {
                                                                echo'<div class="select-item">'.$value.'</div>';
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="form-wrapper__container">
                                        <div class="personalization-form__wrapper">
                                            <form class="personalization__form personalization__form--radio duration-radio">
                                                <p class="primary-text input__header">durée de l’évènement*</p>
                                                <div class="personalization__input-wrapper personalization__input-wrapper--radio">
                                                    <input type="radio" id="duration-1" name="duration" class="radio-input" value="1" />
                                                    <label class="primary-text radio-input--style" for="duration-1">1</label>
                                                    <input type="radio" id="duration-2" name="duration" class="radio-input" value="2" />
                                                    <label class="primary-text radio-input--style" for="duration-2">2</label>
                                                    <input type="radio" id="duration-3" name="duration" class="radio-input" value="3" />
                                                    <label class="primary-text radio-input--style" for="duration-3">3</label>
                                                    <input type="radio" id="duration-4" name="duration" class="radio-input" value="4" />
                                                    <label class="primary-text radio-input--style" for="duration-4">4 ou +</label>
                                                </div>
                                                <p class="error">La durée est obligatoire</p>
                                            </form>
                                            <form class="personalization__form personalization__form--radio number-radio">
                                                <p class="primary-text input__header">NOMBRE DE BORNES*</p>
                                                <div class="personalization__input-wrapper personalization__input-wrapper--radio">
                                                    <input type="radio" id="number-1" name="number" class="radio-input" value="1" />
                                                    <label class="primary-text radio-input--style" for="number-1">1</label>
                                                    <input type="radio" id="number-2" name="number" class="radio-input" value="2" />
                                                    <label class="primary-text radio-input--style" for="number-2">2</label>
                                                    <input type="radio" id="number-3" name="number" class="radio-input" value="3" />
                                                    <label class="primary-text radio-input--style" for="number-3">3</label>
                                                    <input type="radio" id="number-4" name="number" class="radio-input" value="4" />
                                                    <label class="primary-text radio-input--style" for="number-4">4 ou +</label>
                                                </div>
                                                <p class="error">Le nombre de borne est obligatoire</p>
                                            </form>
                                        </div>
                                        <div class="personalization-form__wrapper">
                                            <form class="personalization__form">
                                                <div class="personalization__input-wrapper">
                                                    <label for="" class="primary-text input__header">LIEU de l’évènement*</label>
                                                    <input type="number" id="postcode" class="input" placeholder="95640" value="<?php echo $_COOKIE['postcode']; ?>" disabled>
                                                    <input type="city" id="city" class="input" placeholder="Ville*">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-personalization__item">
                                <button class="cart-personalization__header">
                                    <h2 class="title">2 - OPTIONS de LOCATION</h2>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="58.485" height="33.485" viewBox="0 0 58.485 33.485">
                                        <path id="Vector_4" data-name="Vector 4" d="M50,25,25,0,0,25" transform="translate(54.243 29.243) rotate(180)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="6"/>
                                    </svg>
                                </button>
                                <div class="cart-personalization__dropdown">
                                    <div class="dropdown-card__wrapper">
                                        <?php
                                          $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$row_bornes_types[$price_prefix.'options_ids'].") AND `is_personal` = 0 AND `status` = 1");
                                          while($row_options = mysqli_fetch_assoc($result_options)) {
                                            echo'<div class="dropdown-card" data-type="option" data-id="'.$row_options['id'].'" data-conflicting="'.$row_options['conflicting_options_ids'].'">
                                            <div class="dropdown-card__content">
                                                <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_options['image'].'" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                                <span class="primary-text dropdown-card__price" data-type="options" data-id="'.$row_options['id'].'"  data-price="'.$row_options[$price_prefix.'price'].'">'.($row_options[$price_prefix.'price'] != 0 ? $row_options[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                                    <g id="Сгруппировать_5" data-name="Сгруппировать 5" transform="translate(-1174 -2522)">
                                                      <g id="Ellipse_18" data-name="Ellipse 18" transform="translate(1174 2522)" fill="none" stroke="#4f4f4f" stroke-width="1">
                                                        <circle cx="11" cy="11" r="11" stroke="none"/>
                                                        <circle cx="11" cy="11" r="10.5" fill="none"/>
                                                      </g>
                                                      <g id="Сгруппировать_4" data-name="Сгруппировать 4" transform="translate(870 2163.5)">
                                                        <line id="Линия_1" data-name="Линия 1" x1="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Линия_2" data-name="Линия 2" x2="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                      </g>
                                                    </g>
                                                </svg>
                                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                                 <p class="primary-text">'.$row_options['description'].'</p>
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>';
                                          }
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <div class="cart-personalization__item">
                                <button class="cart-personalization__header">
                                    <h2 class="title">3 - OPTIONS de PERSONNALISATION*</h2>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="58.485" height="33.485" viewBox="0 0 58.485 33.485">
                                        <path id="Vector_4" data-name="Vector 4" d="M50,25,25,0,0,25" transform="translate(54.243 29.243) rotate(180)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="6"/>
                                    </svg>
                                </button>
                                <div class="cart-personalization__dropdown">
                                    <h2 class="text-large">CONTOUR PHOTO PERSONNALISÉ*</h2>
                                    <div class="dropdown-card__wrapper">
                                        <?php
                                          $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$row_bornes_types[$price_prefix.'options_ids'].") AND `is_personal` = 1 AND `status` = 1");
                                          while($row_options = mysqli_fetch_assoc($result_options)) {
                                            echo'<div class="dropdown-card" data-type="option" data-id="'.$row_options['id'].'" data-conflicting="'.$row_options['conflicting_options_ids'].'">
                                            <div class="dropdown-card__content">
                                                <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_options['image'].'" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                                <span class="primary-text dropdown-card__price" data-type="options" data-id="'.$row_options['id'].'"  data-price="'.$row_options[$price_prefix.'price'].'">'.($row_options[$price_prefix.'price'] != 0 ? $row_options[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                                    <g id="Сгруппировать_5" data-name="Сгруппировать 5" transform="translate(-1174 -2522)">
                                                      <g id="Ellipse_18" data-name="Ellipse 18" transform="translate(1174 2522)" fill="none" stroke="#4f4f4f" stroke-width="1">
                                                        <circle cx="11" cy="11" r="11" stroke="none"/>
                                                        <circle cx="11" cy="11" r="10.5" fill="none"/>
                                                      </g>
                                                      <g id="Сгруппировать_4" data-name="Сгруппировать 4" transform="translate(870 2163.5)">
                                                        <line id="Линия_1" data-name="Линия 1" x1="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Линия_2" data-name="Линия 2" x2="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                      </g>
                                                    </g>
                                                </svg>
                                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                                 <p class="primary-text">'.$row_options['description'].'</p>
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>';
                                          }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="cart-personalization__item">
                                <button class="cart-personalization__header">
                                    <h2 class="title">4 - RÉCUPÉRATION DE VOTRE BORNE*</h2>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="58.485" height="33.485" viewBox="0 0 58.485 33.485">
                                        <path id="Vector_4" data-name="Vector 4" d="M50,25,25,0,0,25" transform="translate(54.243 29.243) rotate(180)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="6"/>
                                    </svg>
                                </button>
                                <div class="cart-personalization__dropdown cart-personalization__dropdown-delivery">
                                    <p class="error">Vous devez sélectionner un mode de livraison ou de retrait</p>
                                    <div class="dropdown-card__wrapper">
                                        <?php
                                          $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery` WHERE `id` IN (".$row_bornes_types['delivery_ids'].") AND `status` = 1");
                                          while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
                                            echo'<div class="dropdown-card" data-type="delivery">
                                            <div class="dropdown-card__content">
                                                <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_delivery['image'].'" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">'.trim($row_delivery['title']).'</h3>
                                                <span class="primary-text dropdown-card__price" data-type="delivery" data-id="'.$row_delivery['id'].'" data-price="'.$row_delivery[$price_prefix.'price'].'">'.($row_delivery[$price_prefix.'price'] != 0 ? $row_delivery[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                                    <g id="Сгруппировать_5" data-name="Сгруппировать 5" transform="translate(-1174 -2522)">
                                                      <g id="Ellipse_18" data-name="Ellipse 18" transform="translate(1174 2522)" fill="none" stroke="#4f4f4f" stroke-width="1">
                                                        <circle cx="11" cy="11" r="11" stroke="none"/>
                                                        <circle cx="11" cy="11" r="10.5" fill="none"/>
                                                      </g>
                                                      <g id="Сгруппировать_4" data-name="Сгруппировать 4" transform="translate(870 2163.5)">
                                                        <line id="Линия_1" data-name="Линия 1" x1="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                        <line id="Линия_2" data-name="Линия 2" x2="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                      </g>
                                                    </g>
                                                </svg>
                                                <h3 class="primary-text">'.trim($row_delivery['title']).'</h3>
                                                 <p class="primary-text">'.$row_delivery['description'].'</p>
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>';
                                          }
                                        ?>

                                        <?php
                                            $result_recovery = mysqli_query($conn, "SELECT * FROM `recovery`");
                                            while($row_recovery = mysqli_fetch_assoc($result_recovery)) {
                                                $department_arr = explode(" ", $row_recovery['department']);
                                                if (in_array(substr($_COOKIE['postcode'], 0, 2), $department_arr)) {
                                                    echo'<div class="dropdown-card" data-type="delivery">
                                                        <div class="dropdown-card__content">
                                                            <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_recovery['image'].'" alt="" class="dropdown-card__img">
                                                            <h3 class="primary-text">'.trim($row_recovery['title']).'</h3>
                                                            <span class="primary-text dropdown-card__price" data-type="recovery" data-id="'.$row_recovery['id'].'" data-price="'.$row_recovery[$price_prefix.'price'].'">'.($row_recovery[$price_prefix.'price'] != 0 ? $row_recovery[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                                            <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                                        </div>
                                                        <div class="dropdown-card__details-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                                                <g id="Сгруппировать_5" data-name="Сгруппировать 5" transform="translate(-1174 -2522)">
                                                                  <g id="Ellipse_18" data-name="Ellipse 18" transform="translate(1174 2522)" fill="none" stroke="#4f4f4f" stroke-width="1">
                                                                    <circle cx="11" cy="11" r="11" stroke="none"/>
                                                                    <circle cx="11" cy="11" r="10.5" fill="none"/>
                                                                  </g>
                                                                  <g id="Сгруппировать_4" data-name="Сгруппировать 4" transform="translate(870 2163.5)">
                                                                    <line id="Линия_1" data-name="Линия 1" x1="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                                    <line id="Линия_2" data-name="Линия 2" x2="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                                                  </g>
                                                                </g>
                                                            </svg>
                                                            <h3 class="primary-text">'.trim($row_recovery['title']).'O</h3>
                                                             <p class="primary-text">'.$row_recovery['description'].'</p>
                                                        </div>
                                                        <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                                    </div>';
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="dropdown__delivery">
                                        <form action="" class="dropdown-delivery__form">
                                            <h3 class="primary-text">ADRESSE DE LIVRAISON*</h3>
                                            <p class="error">Vous devez indiquer votre adresse de livraison</p>
                                            <input type="text" class="input" id="delivery_address" placeholder="Adresse">
                                            <div class="dropdown-delivery__input-wrapper">
                                                <input type="text" class="input" id="delivery_postal_code" placeholder="Code postal">
                                                <input type="text" class="input" id="delivery_city" placeholder="Ville, commune">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div class="modal-wrapper" id="modal-extra">
        <div class="modal__banner">
            <div class="modal-banner__header-wrapper">
                <a href="#" class="text-large close-modal">RETOUR</a>
                <h2 class="title">À NE PAS MANQUER</h2>
            </div>
            <div class="dropdown-card__wrapper">
                <?php
                    $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$row_bornes_types[$price_prefix.'options_ids'].") AND `is_personal` = 0 AND `price` > 0 AND `status` = 1");
                    while($row_options = mysqli_fetch_assoc($result_options)) {
                        echo'<div class="dropdown-card" data-id="modal-'.$row_options['id'].'" data-type="option" data-conflicting="'.$row_options['conflicting_options_ids'].'">
                            <div class="dropdown-card__content">
                                <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_options['image'].'" alt="" class="dropdown-card__img">
                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                <span class="primary-text dropdown-card__price" data-type="options" data-id="'.$row_options['id'].'"  data-price="'.$row_options[$price_prefix.'price'].'">'.($row_options[$price_prefix.'price'] != 0 ? $row_options[$price_prefix.'price'].' €' : 'Gratuit').'</span>
                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                            </div>
                            <div class="dropdown-card__details-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                    <g id="Сгруппировать_5" data-name="Сгруппировать 5" transform="translate(-1174 -2522)">
                                        <g id="Ellipse_18" data-name="Ellipse 18" transform="translate(1174 2522)" fill="none" stroke="#4f4f4f" stroke-width="1">
                                            <circle cx="11" cy="11" r="11" stroke="none"/>
                                            <circle cx="11" cy="11" r="10.5" fill="none"/>
                                        </g>
                                        <g id="Сгруппировать_4" data-name="Сгруппировать 4" transform="translate(870 2163.5)">
                                            <line id="Линия_1" data-name="Линия 1" x1="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                            <line id="Линия_2" data-name="Линия 2" x2="9" y2="10" transform="translate(310.5 364.5)" fill="none" stroke="#4f4f4f" stroke-linecap="round" stroke-width="2"/>
                                        </g>
                                    </g>
                                </svg>
                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                <p class="primary-text">'.$row_options['description'].'</p>
                            </div>
                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                        </div>';
                   }
                ?>
            </div>
            <button class="button primary-text js-next" onclick="window.location.href = 'order.php?type=<?php echo $_GET['type']; ?>&box_type=<?php echo $_GET['box_type']; ?>'">JE NE SUIS PAS INTÉRÉSSÉE</button>
        </div>
    </div>

    <script src="js/main.js?v=1.5.7"></script>
    <script src="js/phone-mask.js"></script>
    <script>
        const   promo_wrapper = document.querySelector('.cart-promo__input-wrapper'),
                promo_input = document.querySelector('.promo_input'),
                discount = document.querySelectorAll('.discount');
        function checkPromo() {
            if (promo_input.value == '') {
                promo_input.classList.add('input-error');
                return false;
            } else {
                promo_input.classList.remove('input-error');
            }

            const request = new XMLHttpRequest();
            const url = '../manager/d26386b04e.php';
            const params = 'event=check_promo&promocode=' + promo_input.value + '&event_date=' + date.value;
            request.open("POST", url, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.addEventListener("readystatechange", () => {
                if(request.readyState === 4 && request.status === 200) {
                    console.log(request.responseText);
                  if (request.responseText != 'error' && request.responseText != 'outdate') {
                    promo_input.classList.remove('input-error');
                    promo_wrapper.classList.remove('error');
                    promo_wrapper.classList.add('success');
                    discount.forEach((ele, index) => {
                        ele.innerHTML = (+request.responseText).toFixed(2);
                    });
                    total_discount = +request.responseText;
                    setCookie('discount', total_discount);
                    calc();
                  } else {
                    promo_input.classList.add('input-error');
                    promo_wrapper.classList.remove('success');
                    promo_wrapper.classList.add('error');
                    total_discount = 0;
                    setCookie('discount', total_discount);
                    calc();
                  }
                }
            });
            request.send(params);
        }

        function doReservation(reservation_type, box_type) {

            const date = document.getElementById('date');
            if (date.value == '') {
                date.classList.add('input-error');
                date.scrollIntoView({ block: "start", behavior: "smooth" });
                return false;
            } else {
                date.classList.remove('input-error');
            }

            const type = document.getElementById('type');
            if (type.value == '') {
                type.classList.add('input-error');
                type.scrollIntoView({ block: "start", behavior: "smooth" });
                return false;
            } else {
                type.classList.remove('input-error');
            }

            var duration = false;
            const durations = document.querySelectorAll('[name="duration"]')
            durations.forEach((e) => {
                if (e.checked) {
                    duration = true;
                }
            })

            const durationRadio = document.querySelector('.duration-radio');
            if (!duration) {
                durationRadio.classList.add('error');
                durationRadio.scrollIntoView({ block: "start", behavior: "smooth" });
                return false;
            } else {
                durationRadio.classList.remove('error');
            }


            var number = false;
            const numbers = document.querySelectorAll('[name="number"]')
            numbers.forEach((e) => {
                if (e.checked) {
                    number = true;
                }
            })

            const numberRadio = document.querySelector('.number-radio');
            if (!number) {
                numberRadio.classList.add('error');
                numberRadio.scrollIntoView({ block: "start", behavior: "smooth" });
                return false;
            } else {
                numberRadio.classList.remove('error');
            }

            const city = document.getElementById('city');
            if (city.value == '') {
                city.classList.add('input-error');
                city.scrollIntoView({ block: "start", behavior: "smooth" });
                return false;
            } else {
                city.classList.remove('input-error');
            }

            const items = document.querySelectorAll('.cart-item__list-item');
            var delivery_exist = false;
            items.forEach((element) => {
                if (element.dataset.type == 'delivery' || element.dataset.type == 'recovery') {
                    delivery_exist = true;
                }
            });
            if (!delivery_exist) {
                console.log('error');
                document.querySelector('.cart-personalization__dropdown-delivery').classList.add('error');
                document.querySelector('.cart-personalization__dropdown-delivery').scrollIntoView({ block: "start", behavior: "smooth" });
                return false;
            } else {
                document.querySelector('.cart-personalization__dropdown-delivery').classList.remove('error');
            }

            const dropdown_delivery = document.querySelector('.dropdown__delivery');
            if (dropdown_delivery.classList.contains("active")) {
                const delivery_form = document.querySelector('.dropdown-delivery__form'),
                        delivery_address = document.getElementById('delivery_address');
                if (delivery_address.value == '') {
                    delivery_form.classList.add('error');
                    delivery_address.classList.add('input-error');
                    delivery_address.scrollIntoView({ block: "start", behavior: "smooth" });
                    return false;
                } else {
                    delivery_form.classList.remove('error');
                    delivery_address.classList.remove('input-error');
                }
            }

            document.querySelector('.modal-wrapper').classList.add('active');
        }
    </script>
</body>
</html>
<?php
  mysqli_close($conn);
?>