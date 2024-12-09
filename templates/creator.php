<?php
@session_start();
if (!isset($_SESSION['order'])) {header("Location:./"); exit;}
if (isset($_GET['event_id'])) {
  $_SESSION['order']['event_id'] = $_GET['event_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrance</title>
    <link rel="stylesheet" href="css/editing.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <div class="header__container">
                <a href="#" class="header-logo">
                    <img src="./img/editing/logo.svg" alt="logotype">
                </a>
            </div>
        </header>
        <main class="main main_dark-bg">
            <aside class="main__aside aside">
                <div class="aside__body">
                    <div class="aside__container">
                        <div class="aside__header hor-centered">
                            <h2 class="title">Éléments <br> de personnalisation</h2>
                        </div>
                        <?php
                            @require_once("../inc/mainfile.php");
                            $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$_GET['template_id']);
                            $row_templates = mysqli_fetch_assoc($result_templates);
                            echo'<a href="../manager/editor/?image='.($_SESSION['order']['pay'] == 0 ? $row_templates['third_image'] : $row_templates['image']).'&sample_image='.$row_templates['second_image'].'&json='.$row_templates['data'].'&order_id='.$_SESSION['order']['id'].'&template_id='.$row_templates['id'].'" class="spoiler">
                                <div class="spoiler__header">
                                    <span>Ajouter du texte</span>
                                    <div class="spoiler__icon">
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="spoiler__body"></div>
                            </a>';
                        ?>
                    </div>
                </div>
            </aside>
            <div class="main__body body">
                <div class="body__wrapper">
                    <div class="content-body">
                        <div class="content-body__container">
                            <div class="image-edited">
                                <?php
                                    echo'<img class="img" src="'.ADMIN_UPLOAD_IMAGES_DIR.($_SESSION['order']['pay'] = 0 ? $row_templates['preview'] : $row_templates['preview2']).'" alt="" style="width: 100%;" />';
                                    echo'<img class="blank hidden" src="'.ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image'].'" alt="" style="width: 100%;" />';
                                ?>
                            </div>
                            <div class="radio-box radio-box_light editor-checkbox" style="left: 60%;">
                                <div class="radio-box__check">
                                    <input type="checkbox" class="radio-box__input" id="voir">
                                    <label for="voir" class="radio-box__check-l" id="voir-l"></label>
                                </div>
                                <label for="voir">Voir sans image de présentation</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>



        <div class="popup popup-valider"> <!-- adding "popup_opened" class will open the popup -->
            <div class="popup__card">
                <div class="popup__header">
                    <h2 class="popup__title title">Êtes-vous sûr ?</h2>
                </div>
                <p class="popup__subtitle">La validation d’une maquette <br> est définitive</p>

                <div class="popup__buttons">
                    <button class="popup__button button button_filled" onclick="openPopup('.popup-merci')">
                        <span>Valider</span>
                        <img src="./img/editing/icons/at-icon.svg" alt="">
                    </button>
                    <button class="popup__button button button_grey" onclick="closePopup()">
                        <span>Annuler</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="popup popup-merci"> <!-- adding "popup_opened" class will open the popup -->
            <div class="popup__card">
                <div class="popup__header">
                    <h2 class="popup__title title">Maquette validée</h2>
                </div>
                <p class="popup__subtitle">Merci !</p>

                <div class="popup__buttons">
                    <button class="popup__button button button_filled" onclick="closePopup()">
                        <span>Ajouter une maquette</span>
                    </button>
                    <a href="#">Retour à mon espace client</a>
                </div>
            </div>
        </div>

        <div class="popup popup-brouillons"> <!-- adding "popup_opened" class will open the popup -->
            <div class="popup__card">
                <div class="popup__header">
                    <h2 class="popup__title title">Magnifique !</h2>
                </div>
                <p class="popup__subtitle">Votre maquette est maintenant <br> enregistrée en brouillons !</p>

                <div class="popup__buttons">
                    <button class="popup__button button button_filled" onclick="closePopup()">
                        <span>Créer nouveau</span>
                    </button>
                    <a href="#">Retour à mon espace client</a>
                </div>

                <div class="popup-bottom">
                    <div class="popup-bottom__icon">
                        <img src="./img/editing/icons/warn-icon.svg" alt="">
                    </div>
                    <p class="popup-bottom__text">
                        Une maquette enregistrée en brouillons et non-validée <br> ne sera pas prise en compte par nos préparateurs.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const radio_box = document.querySelector('.radio-box__input'),
              img = document.querySelector('.img')
              blank = document.querySelector('.blank');
        radio_box.addEventListener('change', (event) => {
            if (radio_box.checked){
                img.classList.add('hidden');
                blank.classList.remove('hidden');
            } else {
                img.classList.remove('hidden');
                blank.classList.add('hidden');
            }
        });
        /*document.addEventListener('click', e => {
            if (e.target.closest('.spoiler__header')) {
                e.target.closest('.spoiler').classList.toggle('active');
                document.querySelector('.aside__mod').classList.toggle('aside__mod_disabled');
            }
        })

        function openPopup(el) {
            document.querySelectorAll('.popup').forEach(element => {
                element.classList.remove('popup_opened')
            })
            document.querySelector(el).classList.add('popup_opened');
        }
        function closePopup() {
            document.querySelectorAll('.popup').forEach(element => {
                element.classList.remove('popup_opened')
            })
        }*/
    </script>
</body>
</html>