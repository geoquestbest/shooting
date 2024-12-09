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

    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">

    <title>shootnbox</title>

    <style>
        :root{
            --color-accent: <?php echo $row_bornes_types['color']; ?>;
            --gradient: linear-gradient(<?php echo $row_bornes_types['color']; ?> 0%, #fff 100%);
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
                                    Photobooth VEGAS SLIM
                                    <span class="list-item__price primary-text">299€</span>
                                </li>
                                <li class="cart-item__list-item primary-text">
                                    <span class="list-item__amount primary-text">1</span>
                                    Assurance dégradation
                                    <span class="list-item__price primary-text">39€</span>
                                </li>
                                <li class="cart-item__list-item primary-text">
                                    <button class="list-item__delete primary-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 12.68 14.265">
                                            <path id="delete_24dp_5F6368_FILL0_wght400_GRAD0_opsz24" d="M162.378-825.735a1.526,1.526,0,0,1-1.119-.466,1.526,1.526,0,0,1-.466-1.119v-10.3H160v-1.585h3.963V-840h4.755v.792h3.963v1.585h-.793v10.3a1.526,1.526,0,0,1-.466,1.119,1.526,1.526,0,0,1-1.119.466Zm7.925-11.888h-7.925v10.3H170.3Zm-6.34,8.718h1.585v-7.133h-1.585Zm3.17,0h1.585v-7.133h-1.585Zm-4.755-8.718v0Z" transform="translate(-160 840)" fill="#5f6368"/>
                                        </svg>                                          
                                    </button>
                                    Activation double face
                                    <span class="list-item__price primary-text">99€</span>
                                </li>
                                <li class="cart-item__list-item primary-text">
                                    <button class="list-item__delete primary-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 12.68 14.265">
                                            <path id="delete_24dp_5F6368_FILL0_wght400_GRAD0_opsz24" d="M162.378-825.735a1.526,1.526,0,0,1-1.119-.466,1.526,1.526,0,0,1-.466-1.119v-10.3H160v-1.585h3.963V-840h4.755v.792h3.963v1.585h-.793v10.3a1.526,1.526,0,0,1-.466,1.119,1.526,1.526,0,0,1-1.119.466Zm7.925-11.888h-7.925v10.3H170.3Zm-6.34,8.718h1.585v-7.133h-1.585Zm3.17,0h1.585v-7.133h-1.585Zm-4.755-8.718v0Z" transform="translate(-160 840)" fill="#5f6368"/>
                                        </svg>                                          
                                    </button>
                                    Un graphiste réalise mon contour photo
                                    <span class="list-item__price primary-text">39€</span>
                                </li>
                                <li class="cart-item__list-item primary-text">
                                    <button class="list-item__delete primary-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="14" viewBox="0 0 12.68 14.265">
                                            <path id="delete_24dp_5F6368_FILL0_wght400_GRAD0_opsz24" d="M162.378-825.735a1.526,1.526,0,0,1-1.119-.466,1.526,1.526,0,0,1-.466-1.119v-10.3H160v-1.585h3.963V-840h4.755v.792h3.963v1.585h-.793v10.3a1.526,1.526,0,0,1-.466,1.119,1.526,1.526,0,0,1-1.119.466Zm7.925-11.888h-7.925v10.3H170.3Zm-6.34,8.718h1.585v-7.133h-1.585Zm3.17,0h1.585v-7.133h-1.585Zm-4.755-8.718v0Z" transform="translate(-160 840)" fill="#5f6368"/>
                                        </svg>                                          
                                    </button>
                                    Livraison dans votre point relais
                                    <span class="list-item__price primary-text">150€</span>
                                </li>
                            </ul>
                            <div class="cart-item__button-wrapper">
                                <p class="primary-text">TOTAL</p>
                                <button class="button text-large">626€TTC</button>
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
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg1.png" alt="">
                                        <p class="primary-text">400 impressions <br>10x15cm</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg2.png" alt="">                                                                           
                                        <p class="primary-text">Eclairage led <br> ajustable</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg3.png" alt="">                                                                                                                                                                               
                                        <p class="primary-text">Personnalisation graphique du contour</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg4.png" alt="">                                                                                                                                                                               
                                        <p class="primary-text">Accessoires <br> numériques</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg5.png" alt="">                                                                                                                                                                               
                                        <p class="primary-text">Partage par mail <br>illimités</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">                                                                                                                                                                              
                                        <p class="primary-text">Filtres de couleur</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg6.png" alt="">                                                                                                                                                                               
                                        <p class="primary-text">Photos, vidéos, gifs, boomerangs</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg7.png" alt="">                                                                                                                                                                               
                                        <p class="primary-text">Compatible <br> double face</p>                                          
                                    </div>
                                    <div class="product-info__advantages-item">
                                        <img src="img/product-svg8.png" alt="">                                                                                                                                                                               
                                        <p class="primary-text">Galerie Web</p>                                          
                                    </div>
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
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown1.png" alt="" class="dropdown-card__img" style>
                                                <h3 class="primary-text">DOUBLE FACE / <br> DOUBLE VEGAS</h3>
                                                <span class="primary-text dropdown-card__price">99€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button dropdown-card__button--active button primary-text">AJOUTER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown2.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">RETRAIT DU LOGO SHOOTNBOX</h3>
                                                <span class="primary-text dropdown-card__price">39€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button button primary-text">AJOUTER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown3.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">PASTILLES <br>MAGNÉTIQUES</h3>
                                                <span class="primary-text dropdown-card__price">59€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button button primary-text">AJOUTER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown4.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">MULTIPLE IMPRESSION</h3>
                                                <span class="primary-text dropdown-card__price">29€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button button primary-text">AJOUTER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown5.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">ACCESSOIRES SELFIES</h3>
                                                <span class="primary-text dropdown-card__price">19€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button button primary-text">AJOUTER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown6.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">PACK DÉGUISEMENTSS</h3>
                                                <span class="primary-text dropdown-card__price">60€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button button primary-text">AJOUTER</button>
                                        </div>
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
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown7.png" alt="" class="dropdown-card__img" style>
                                                <h3 class="primary-text">un graphiste realise <br>mon contour PHOTO</h3>
                                                <span class="primary-text dropdown-card__price">39€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                            <button class="dropdown-card__button dropdown-card__button--active button primary-text">SÉLECTIONNER</button>
                                        </div>
                                        <div class="dropdown-card">
                                            <div class="dropdown-card__content">
                                                <img src="img/dropdown8.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">JE REALISE MON PROPRE CONTOUR PHOTO</h3>
                                                <span class="primary-text dropdown-card__price">GRATUIT</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <img src="img/dropdown9.png" alt="" class="dropdown-card__img">
                                                <h3 class="primary-text">JE NE SOUHAITE PAS DE CONTOUR PHOTO</h3>
                                                <span class="primary-text dropdown-card__price">GRATUIT</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <span class="primary-text dropdown-card__price">150€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <span class="primary-text dropdown-card__price">GRATUIT</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <span class="primary-text dropdown-card__price">GRATUIT</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <span class="primary-text dropdown-card__price">GRATUIT</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <span class="primary-text dropdown-card__price">150€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
                                                <span class="primary-text dropdown-card__price">200€</span>
                                                <button class="primary-text dropdown-card__details">Cliquez ici pour en savoir +</button>
                                            </div>
                                            <div class="dropdown-card__details-info" style="display: none;">
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
    
    <script src="js/main.js"></script>
    <script src="js/phone-mask.js"></script>
</body>
</html>
<?php
  mysqli_close($conn);
?>