<?php
@session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrance</title>
    <link rel="stylesheet" href="css/editing.css">
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <div class="header__container">
                <a href="#" class="header-logo header-logo_centered">
                    <img src="./img/editing/logo.svg" alt="logotype">
                </a>
            </div>
        </header>
        <main class="main main_start-bg">
            <div class="main__body body">
                <div class="body__wrapper">
                    <div class="content-body">
                        <div class="content-body__container">
                            <div class="content-body__header hor-centered">
                                <h2 class="title title_big">Bienvenue</h2>
                                <p>sur votre outil de création en ligne</p>
                            </div>
                            <div class="content-body__content">
                                <p>
                                    Sur cet outil, vous pourrez laisser parler votre créativité et réaliser le contour idéal <br> pour votre évènement !
                                </p>

                                <div class="content-body__main-buttons ver-centered">
                                    <!--button class="button button_filled">Créer à partir d’un modèle vierge</button-->
                                    <a href="gallery.php" class="button button_filled">Créer à partir de modèles existants</a>
                                </div>
                                <?php
                                    if (isset($_SESSION['order'])) {
                                        @require_once("../inc/mainfile.php");
                                        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
                                        $row_orders = mysqli_fetch_assoc($result_orders);
                                        $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$_SESSION['order']['id']);
                                        if ($row_orders['image'] != "" || mysqli_num_rows($result_orders_images) != 0) {
                                            echo'<a href="maquettes.php" class="button button_stroke hor-centered">Reprendre mes maquettes en cours</a>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>