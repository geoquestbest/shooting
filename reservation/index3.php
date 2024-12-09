<?php
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

    <link rel="stylesheet" href="css/style.css?v=1.0">
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
                                <li class="cart-item__list-item primary-text">
                                    <span class="list-item__amount primary-text">1</span>
                                    Photobooth <?php echo $row_bornes_types['title'] ?>
                                    <span class="list-item__price primary-text"><?php echo $row_bornes_types['price'] ?>€</span>
                                </li>
                            </ul>
                            <div class="cart-item__button-wrapper">
                                <p class="primary-text">TOTAL</p>
                                <button class="button text-large"><?php echo $row_bornes_types['price'] ?>€<?php echo $price_sufix; ?></button>
                            </div>
                        </div>
                        <div class="cart-sidebar__item cart__promo">
                            <p class="primary-text">VOUS AVEZ UN CODE PROMO?</p>
                            <div class="cart-promo__input-wrapper">
                                <input type="text" class="input primary-text">
                                <button class="button primary-text">OK</button>
                            </div>
                        </div>
                        <div class="cart-sidebar__button-wrapper">
                            <a href="#" class="button text-large">CONTINUER</a>
                            <a href="#" class="text-large">RETOUR</a>
                        </div>
                    </div>
                    <div class="cart__right">
                        <div class="cart__product-info">
                            <img src="img/product.png" alt="" class="procut-info__img">
                            <div class="product-info__content">
                                <h2 class="title">Inclus dans la location</h2>
                                <div class="product-info__advantages">
                                    <?php
                                    $descriptions_arr = explode("|||", $row_bornes_types['description']);
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
                                                <input type="number" id="date" class="input" placeholder="01-01-2024">
                                                <button class="personalization__input-button">
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
                                                </button>
                                            </div>
                                            <div class="personalization__input-wrapper">
                                                <label for="type" class="primary-text input__header">TYPE D’évènement*</label>
                                                <input type="text" id="type" class="input" placeholder="Mariage">
                                            </div>
                                        </form>
                                    </div>
                                    <div class="form-wrapper__container">
                                        <div class="personalization-form__wrapper">
                                            <form class="personalization__form personalization__form--radio">
                                                <p class="primary-text input__header">durée de l’évènement*</p>
                                                <div class="personalization__input-wrapper personalization__input-wrapper--radio">
                                                    <input type="radio" id="duration-1" name="duration" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="duration-1">1</label>
                                                    <input type="radio" id="duration-2" name="duration" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="duration-2">2</label>
                                                    <input type="radio" id="duration-3" name="duration" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="duration-3">3</label>
                                                    <input type="radio" id="duration-4" name="duration" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="duration-4">4 ou +</label>
                                                </div>
                                            </form>
                                            <form class="personalization__form personalization__form--radio">
                                                <p class="primary-text input__header">NOMBRE DE BORNES*</p>
                                                <div class="personalization__input-wrapper personalization__input-wrapper--radio">
                                                    <input type="radio" id="number-1" name="number" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="number-1">1</label>
                                                    <input type="radio" id="number-2" name="number" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="number-2">2</label>
                                                    <input type="radio" id="number-3" name="number" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="number-3">3</label>
                                                    <input type="radio" id="number-4" name="number" class="radio-input">
                                                    <label class="primary-text radio-input--style" for="number-4">4 ou +</label>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="personalization-form__wrapper">
                                            <form class="personalization__form">
                                                <div class="personalization__input-wrapper">
                                                    <label for="" class="primary-text input__header">LIEU de l’évènement*</label>
                                                    <input type="number" id="postcode" class="input" placeholder="95640" disabled>
                                                    <input type="city" id="type" class="input" placeholder="Santeuil">
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
                                          $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery` WHERE `id` IN (".$row_bornes_types['delivery_ids'].") AND `status` = 1");
                                          while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
                                            echo'<div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_delivery['image'].'" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">'.trim($row_delivery['title']).'</h3>
                                                <span class="primary-text dropdown-card__price" data-price="'.$row_delivery[$price_prefix.'price'].'">'.($row_delivery[$price_prefix.'price'] != 0 ? $row_delivery[$price_prefix.'price'].' €' : 'Gratuit').'</span>
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
                                          $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$row_bornes_types[$price_prefix.'options_ids'].") AND `status` = 1");
                                          while($row_options = mysqli_fetch_assoc($result_options)) {
                                            echo'<div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_options['image'].'" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">'.trim($row_options['title']).'</h3>
                                                <span class="primary-text dropdown-card__price" data-price="'.$row_options[$price_prefix.'price'].'">'.($row_options[$price_prefix.'price'] != 0 ? $row_options[$price_prefix.'price'].' €' : 'Gratuit').'</span>
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
                                <div class="cart-personalization__dropdown cart-personalization__dropdown-adress">
                                    <div class="dropdown-card__wrapper">
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown10.png" alt="" class="dropdown-card__img" style>
                                                <h3 class="primary-text">LIVRAISON dans <br> votre point relais</h3>
                                                <span class="primary-text dropdown-card__price" data-price="150">150€</span>
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
                                                <h3 class="primary-text">JE RÉALISE MON PROPRE CONTOUR PHOTO</h3>
                                                 <p class="primary-text">Retrouvez notre catalogue de contour photo via notre outil en ligne gratuit. Vous pouvez ainsi choisir votre contour et ajouter le texte de votre choix.</p>                                                                                
                                            </div>
                                            <button class="dropdown-card__button dropdown-card__button--active button primary-text">DÉSELECTIONNER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown11.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">RETRAIT AU SHOWROOM <br> DE MONTREUIL (93)</h3>
                                                <span class="primary-text dropdown-card__price" data-price="0">GRATUIT</span>
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
                                                <h3 class="primary-text">JE RÉALISE MON PROPRE CONTOUR PHOTO</h3>
                                                 <p class="primary-text">Retrouvez notre catalogue de contour photo via notre outil en ligne gratuit. Vous pouvez ainsi choisir votre contour et ajouter le texte de votre choix.</p>                                                                                
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown12.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">RETRAIT AU SHOWROOM <br> DE BÈGLES (33)</h3>
                                                <span class="primary-text dropdown-card__price" data-price="0">GRATUIT</span>
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
                                                <h3 class="primary-text">JE RÉALISE MON PROPRE CONTOUR PHOTO</h3>
                                                 <p class="primary-text">Retrouvez notre catalogue de contour photo via notre outil en ligne gratuit. Vous pouvez ainsi choisir votre contour et ajouter le texte de votre choix.</p>                                                                                
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>
                                    </div>  
                                    <div class="dropdown-adress__wrapper">
                                        <h3 class="primary-text">TROUVEZ LE POINT RELAIS LE PLUS PROCHE DE VOUS</h3>
                                        <input type="number" class="input input__postal-search" placeholder="Ville, code postal">
                                        <div class="dropdown-adress__detail">
                                            <p class="primary-text">Date d’évènement: <span>25.10.2024</span></p>
                                            <p class="primary-text">Livraison garantie avant le: <span>24.10.2024</span></p>
                                            <p class="primary-text">Retour impératif le: <span>27.10.2024</span></p>
                                            <p class="primary-text"><span>Attention, en cas de retour du matériel en retard, une pénalité de 150€/ jour sera appliquée.</span></p>
                                        </div>
                                        <ul class="dropdown-adress__list">
                                            <li class="primary-text">
                                                <p>Point relais 1</p>
                                                <span>Adresse</span>
                                                <button class="button primary-text">SÉLECTIONNER</button>
                                            </li>
                                            <li class="primary-text">
                                                <p>Point relais 2</p>
                                                <span>Adresse</span>
                                                <button class="button primary-text">SÉLECTIONNER</button>
                                            </li>
                                            <li class="primary-text">
                                                <p>Point relais 3</p>
                                                <span>Adresse</span>
                                                <button class="button primary-text">SÉLECTIONNER</button>
                                            </li>
                                            <li class="primary-text">
                                                <p>Point relais 4</p>
                                                <span>Adresse</span>
                                                <button class="button primary-text">SÉLECTIONNER</button>
                                            </li>
                                            <li class="primary-text">
                                                <p>Point relais 5</p>
                                                <span>Adresse</span>
                                                <button class="button primary-text">SÉLECTIONNER</button>
                                            </li>
                                            <li class="primary-text">
                                                <p>Point relais 6</p>
                                                <span>Adresse</span>
                                                <button class="button primary-text">SÉLECTIONNER</button>
                                            </li>
                                        </ul>
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
                                    <div class="dropdown-card__wrapper dropdown-card__wrapper--delviery">
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown13.png" alt="" class="dropdown-card__img" style>
                                                <h3 class="primary-text">LIVRAISON <br>installation</h3>
                                                <span class="primary-text dropdown-card__price" data-price="0">GRATUIT</span>
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
                                                <h3 class="primary-text">JE RÉALISE MON PROPRE CONTOUR PHOTO</h3>
                                                 <p class="primary-text">Retrouvez notre catalogue de contour photo via notre outil en ligne gratuit. Vous pouvez ainsi choisir votre contour et ajouter le texte de votre choix.</p>
                                            </div>                                                                                
                                        </div>
                                        <div class="dropdown__delivery">
                                            <form action="" class="dropdown-delivery__form">
                                                <h3 class="primary-text">ADRESSE DE LIVRAISON*</h3>
                                                <input type="text" class="input" placeholder="Adresse">
                                                <div class="dropdown-delivery__input-wrapper">
                                                    <input type="text" class="input" placeholder="Code postal">
                                                    <input type="text" class="input" placeholder="Ville, commune">
                                                </div>
                                            </form>
                                        </div>  
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
                                    <div class="dropdown-card__wrapper">
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown13.png" alt="" class="dropdown-card__img" style>
                                                <h3 class="primary-text">LIVRAISON + installa-<br>tion “STANDARD”</h3>
                                                <span class="primary-text dropdown-card__price" data-price="150">150€</span>
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
                                                <h3 class="primary-text">JE RÉALISE MON PROPRE CONTOUR PHOTO</h3>
                                                 <p class="primary-text">Retrouvez notre catalogue de contour photo via notre outil en ligne gratuit. Vous pouvez ainsi choisir votre contour et ajouter le texte de votre choix.</p>                                                                                
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown14.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">LIVRAISON + installa-<br>tion PREMIUM</h3>
                                                <span class="primary-text dropdown-card__price" data-price="200">200€</span>
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
                                                <h3 class="primary-text">JE RÉALISE MON PROPRE CONTOUR PHOTO</h3>
                                                 <p class="primary-text">Retrouvez notre catalogue de contour photo via notre outil en ligne gratuit. Vous pouvez ainsi choisir votre contour et ajouter le texte de votre choix.</p>                                                                                
                                            </div>
                                            <button class="dropdown-card__button button primary-text">SÉLECTIONNER</button>
                                        </div>
                                    </div> 
                                    <div class="dropdown__delivery">
                                        <form action="" class="dropdown-delivery__form">
                                            <h3 class="primary-text">ADRESSE DE LIVRAISON*</h3>
                                            <input type="text" class="input" placeholder="Adresse">
                                            <div class="dropdown-delivery__input-wrapper">
                                                <input type="text" class="input" placeholder="Code postal">
                                                <input type="text" class="input" placeholder="Ville, commune">
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
    
    <script src="js/main.js?v=1.0"></script>
    <script src="js/phone-mask.js"></script>
</body>
</html>
<?php
  mysqli_close($conn);
?>