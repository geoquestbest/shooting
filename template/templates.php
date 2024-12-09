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
    <!-- Useful meta tags -->
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow, noodp">
    <meta name="googlebot" content="index, follow">
    <meta name="google" content="notranslate">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>Start</title>
    <link rel="stylesheet" href="css/style.min.css">
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

        .box__tab {
            display: none;
        }

        .menu__list-link {
            width: 100%;
            cursor: pointer;
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0.25px solid #616160;
            background: #fff;
            box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.4);
            transition: 0.4s;
            border-radius: 5px;
            height: 29px;
        }

        .menu__list-link:hover {
            background: #e7388c;
            box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.04);
            color: #fff;
        }

        .menu__list-link.active {
            background: #e7388c;
            box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.04);
            color: #fff;
        }

        .menu__list-inner {
            border: none;
            margin: 0 auto 7px;
            background: #fff;
            box-shadow: none;
            transition: 0.4s;
            border-radius: 5px;
            cursor: pointer;
        }

        .menu__list-inner:hover {
            background: none;
            box-shadow: none;
        }



        .box__tab.active {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            justify-content: flex-start;

        }

        .box-right {
            align-items: start;
        }

        .open-popup {
            width: 12vw/*175px*/;
            height: 175px;
            margin: 1vw /*15px*/;
            /*border: 1px solid #ccc;*/
            text-align: center;
        }

        .open-popup .helper {
            display: inline-block;
            height: 100%;
            vertical-align: middle;
        }

        .open-popup img {
            vertical-align: middle;
            max-height: 175px;
        }

        .horisontal {
            width: 195px;
        }

        .box-wrapp {
            margin-top: auto;

        }

        .box-wrapp .entrance-box {
            width: 270px;
            margin: 0 auto;
            background: #d34b8b;
            display: flex;
            align-items: center;
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 40px;



        }

        .box-wrapp .entrance-box img {
            width: 50px;
            height: 40px;
        }

        .box-wrapp .entrance-content {
            font-family: 'ITC Avant Garde Pro Bk';
            font-weight: 300;
            font-size: 15px;
            text-align: center;
            line-height: 1.2;
            color: #fff;
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="box-left">
            <header class="header">
                <div class="container">
                    <div class="header__wrapp">
                        <a href="#" class="logo">
                            <img src="img/logo.svg" alt="" class="logo-img">
                        </a>
                    </div>
                </div>
            </header>
            <div class="menu">
                <div class="menu-title">
                    Parcourir par thèmes
                </div>
                <nav class="menu__nav">
                    <ul class="menu__list">
                        <?php
                            @require_once("../inc/mainfile.php");
                            $result_types = mysqli_query($conn, "SELECT * FROM `types`");
                            $i = 1;
                            while($row_types = mysqli_fetch_assoc($result_types)) {
                                echo'<li class="menu__list-inner">
                                    <button class="menu__list-link'.(((isset($_COOKIE['tab']) && $_COOKIE['tab'] == $i) || (!isset($_COOKIE['tab']) && $i == 1)) ? " active" : "").'" data-tab="#tab_'.$i.'" onClick="setCookie(\'tab\', '.$i.')">
                                        '.$row_types['title'].'
                                    </button>
                                </li>';
                                $i++;
                            }
                        ?>
                    </ul>
                </nav>
            </div>
            <div class="box-wrapp">
                <a href="https://youtube.com/watch?v=0NltGV11wv4&feature=share" class="entrance-box" target="_blank">
                    <div class="entrance-box-img">
                        <img src="./img/Picto-2.png" alt="" class="entrance-img">
                    </div>
                    <div class="entrance-content">
                        Une question ? Regardez le tutoriel vidéo en cliquant ICI
                    </div>
                </a>
                <div class="footer">
                    <div class="footer__wrapp">
                        01.45.01.66.66 |
                        <a class="footer__wrapp-link" href="mailto:contact@shootnbox.fr">contact@shootnbox.fr </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-right">
            <?php
              $result_types = mysqli_query($conn, "SELECT * FROM `types`");
              $i = 1;
              $j = 1;
              $html = "";
              while($row_types = mysqli_fetch_assoc($result_types)) {
                echo'<div id="tab_'.$i.'" class="box__tab'.(((isset($_COOKIE['tab']) && $_COOKIE['tab'] == $i) || (!isset($_COOKIE['tab']) && $i == 1)) ? " active" : "").'">';
                  $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `status` = 1 ORDER BY `id` DESC");
                  $idx = 0;
                  while($row_templates = mysqli_fetch_assoc($result_templates)) {
                    $types_ids_arr = explode(",", $row_templates['types_ids']);
                    if (mb_strpos($row_templates['boxes'], "Vegas", 0) !== false) {
                      $row_templates['boxes'] = $row_templates['boxes'].",Vegas_800,Vegas_1200,Vegas_1600,Vegas_2000";
                    }
                    if (mb_strpos($row_templates['boxes'], "Miroir", 0) !== false) {
                      $row_templates['boxes'] = $row_templates['boxes'].",Miroir_800,Miroir_1200,Miroir_1600,Miroir_2000";
                    }
                    if ($row_templates['preview'] != "" && in_array($row_types['id'], $types_ids_arr) && mb_strpos($row_templates['boxes'], $_SESSION['order']['box_type'], 0) !== false) {
                        list($width, $height, $type, $attr) = getimagesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '120'));
                        $add_class = $width > $height ? 'horisontal' : 'vertical';
                      echo'<div class="open-popup" data-id="popup'.$i.'" data-popup="#popup-'.$j.'" data-idx="'.$idx.'">
                        <span class="helper"></span>
                          <img class="open-popup-img '.$add_class.'" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '640').'" alt="">
                      </div>';
                      $html .= '<div id="popup-'.$j.'" class="popup popup'.$i.'">
                          <button class="buttonPrev buttonPrev_popup'.$i.'">
                              <img src="img/prev.svg" alt="" class="button-control">
                          </button>
                          <button class="buttonNext buttonNext_popup'.$i.'">
                              <img src="img/next.svg" alt="" class="button-control">
                          </button>
                          <button class="close-popup">
                              <img src="img/close.svg" alt="" class="close-popup-img">
                          </button>
                          <div class="popup__box-img">';
                            if (file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '640'))) {
                              $html .= '<img class="open-popup-img" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '640').'" alt="" />';
                            } else {
                              $html .= '<img class="open-popup-img" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '120').'" alt="" />';
                            }
                          $html .= '</div>
                          <a href="../manager/editor/?image='.($_SESSION['order']['pay'] == 0 ? $row_templates['third_image'] : $row_templates['image']).'&sample_image='.$row_templates['second_image'].'&json='.$row_templates['data'].'&order_id='.$_SESSION['order']['id'].'&template_id='.$row_templates['id'].'" class="popup-btn to-edit">Personnaliser ce modèle</a>
                      </div>';
                      $j++;
                      $idx++;
                    }
                  }
                echo'</div>';
                $i++;
              }
            ?>
        </div>
    </div>
    <div class="popup__bg">
        <?php
          echo $html;
        ?>
    </div>
    <script src="js/app.js?v=1.2.0"></script>
    <script>
        const menuListLink = document.querySelectorAll('.menu__list-link');
        const boxTab = document.querySelectorAll('.box__tab');
        menuListLink.forEach((item) => {
            item.addEventListener('click', function () {
                let activeBtn = item;
                let activeId = activeBtn.getAttribute('data-tab');
                let activeTab = document.querySelector(activeId);


                if (!activeBtn.classList.contains('active')) {
                    menuListLink.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    boxTab.forEach(function (item) {
                        item.classList.remove('active');
                    });

                    activeBtn.classList.add('active');
                    activeTab.classList.add('active');

                }
            });


        });

        function setCookie(name, value, expires, path, domain, secure) {
            document.cookie = name + '=' + escape(value) +
                ((expires) ? '; expires=' + expires : '') +
                ((path) ? '; path=' + path : '') +
                ((domain) ? '; domain=' + domain : '') +
                ((secure) ? '; secure' : '');
        }

    </script>
</body>

</html>