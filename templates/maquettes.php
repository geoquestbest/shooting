<?php
@session_start();
if (!isset($_SESSION['order'])) {header("Location:./"); exit;}
if (isset($_GET['event_id'])) {
  $_SESSION['order']['event_id'] = $_GET['event_id'];
}
@require_once("../inc/mainfile.php");
foreach ($_GET as $key => $value){if ($key != "submit") {$$key = mysqli_real_escape_string($conn, $value);}}

if ($event == "delete") {
    if (!isset($id)) {
        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
        $row_orders = mysqli_fetch_assoc($result_orders);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image']);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120'));
        mysqli_query($conn, "UPDATE `orders_new` SET `image` = '', `template_status` = 0, `data` = '' WHERE `id` = ".$_SESSION['order']['id']);
    } else {
        $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `id` = ".$id);
        $row_orders_images = mysqli_fetch_assoc($result_orders_images);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image']);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120'));
        mysqli_query($conn, "DELETE FROM `orders_images` WHERE `id` = ".$id);
    }
    header("Location: maquettes.php");
    exit;
}

if ($event == "valider") {
    if (!isset($id)) {
        mysqli_query($conn, "UPDATE `orders_new` SET  `template_status` = 1  WHERE `id` = ".$_SESSION['order']['id']);
    } else {
        mysqli_query($conn, "UPDATE `orders_images` SET  `template_status` = 1  WHERE `id` = ".$id);
    }
    header("Location: maquettes.php");
    exit;
}
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
                <a href="#" class="header-logo">
                    <img src="./img/editing/logo.svg" alt="logotype">
                </a>
            </div>
        </header>
        <main class="main main_white-bg">
            <div class="main__body body">
                <div class="body__wrapper">
                    <div class="content-body">
                        <div class="content-body__container">
                            <div class="content-body__header hor-centered">
                                <h2 class="title title_big">Mes <br> maquettes</h2>
                            </div>
                            <div class="content-body__content">
                                <div class="modify centered">
                                    <div class="modify__menu">
                                        <?php
                                            $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
                                            $row_orders = mysqli_fetch_assoc($result_orders);
                                            if ($row_orders['template_status'] != 1) {
                                                $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$row_orders['template_id']);
                                                $row_templates = mysqli_fetch_assoc($result_templates);

                                                echo'<div class="modify-box">
                                                    <div class="modify-box__preview">
                                                        <div class="modify-box__preview-frame">
                                                            <img src='.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120').'" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="modify-box__tools">
                                                        <a href="../manager/editor/?image='.($_SESSION['order']['pay'] == 0 ? $row_templates['third_image'] : $row_templates['image']).'&sample_image='.$row_templates['second_image'].'&json='.$row_templates['data'].'&order_id='.$_SESSION['order']['id'].'&template_id='.$row_templates['id'].'" class="modify-box__tool">Modifier</a>
                                                        <a href="maquettes.php?event=delete" class="modify-box__tool">Supprimer</a>
                                                        '.($row_orders['template_status'] == 0 ? '<a href="maquettes.php?event=valider" class="modify-box__tool">Valider</a>' : '').'
                                                    </div>
                                                </div>';
                                            }

                                            $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']." AND `template_status` != 1");
                                            while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
                                                $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$row_orders_images['template_id']);
                                                $row_templates = mysqli_fetch_assoc($result_templates);
                                                echo'<div class="modify-box">
                                                    <div class="modify-box__preview">
                                                        <div class="modify-box__preview-frame">
                                                            <img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120').'" alt="">
                                                        </div>
                                                    </div>
                                                    <div class="modify-box__tools">
                                                        <a href="../manager/editor/?image='.($_SESSION['order']['pay'] == 0 ? $row_templates['third_image'] : $row_templates['image']).'&sample_image='.$row_templates['second_image'].'&json='.$row_templates['data'].'&order_id='.$_SESSION['order']['id'].'&template_id='.$row_templates['id'].'" class="modify-box__tool">Modifier</a>
                                                        <a href="maquettes.php?event=delete&id='.$row_orders_images['id'].'" class="modify-box__tool">Supprimer</a>
                                                        '.($row_orders_images['template_status'] == 0 ? '<a href="maquettes.php?event=valider&id='.$row_orders_images['id'].'" class="modify-box__tool">Valider</a>' : '').'
                                                    </div>
                                                </div>';
                                            }
                                        ?>
                                    </div>
                                    <a href ="gallery.php" class="modify__add-btn">
                                        <span>Créer nouveau</span>
                                        <img src="./img/editing/icons/plus-icon.svg" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>