<?php
 if (!isset($_GET['file']) && !isset($_GET['hash'])) { header("Location: ../"); exit; }
 if (isset($_GET['hash'])) {
    @require_once("../inc/mainfile.php");
    $hash = mysqli_real_escape_string($conn, $_GET['hash']);
    $result_short_links = mysqli_query($conn, "SELECT * FROM `short_links` WHERE `hash` LIKE '$hash'");
    if (mysqli_num_rows($result_short_links) == 0) {
        header("Location: ../"); exit;
    } else {
        $row_short_links = mysqli_fetch_assoc($result_short_links);
        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$row_short_links['order_id']);
        $row_orders = mysqli_fetch_assoc($result_orders);
    }
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
    <title>ShootnBox</title>

    <style>
        .page {
            overflow: hidden;
        }

        body {
            margin: 0;
        }

        .img-continer {
            margin-top: 6vh;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;

        }

        .img-row {
            margin: 20px auto;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        .img-row-btn {
            width: 35px;
            height: 35px;
            border-radius: 35px;
            margin: 10px;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 0;

        }

        .img-row-btn img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .img-row-link {
            display: none;
        }

        .img-row.--active .img-row-link {
            display: flex;
        }

        .img-row.--active .btn-open {
            display: none;
        }


        .img-box img,
        .img-box .open-popup-video {

            width: 80vw;
            height: 70vh;

            object-fit: contain;
        }

        .header {
            padding-top: 19px;
            padding-bottom: 18px;
            border-bottom: 1px solid #bcbcbb;
            box-shadow: 0 0 30px #bcbcbb;
            background: #fff
        }

        .header__wrapp {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        @media(max-width:572px) {

            .img-row-btn {
                width: 25px;
                height: 25px;
            }
        }

        @media(max-height:696px) {

            .img-box img,
            .img-box .open-popup-video {
                height: 65vh;
            }
        }

        @media(max-height:576px) {

            .img-box img,
            .img-box .open-popup-video {
                height: 60vh;
            }
        }

        @media(max-height:496px) {

            .img-box img,
            .img-box .open-popup-video {
                height: 55vh;
            }
        }

        @media(max-height:432px) {

            .img-box img,
            .img-box .open-popup-video {
                height: 52vh;
            }
        }
    </style>
</head>

<body>

    <div class="page">
        <header class="header">
            <div class="container">
                <div class="header__wrapp">
                    <a href="#" class="logo">
                        <img src="img/logo.svg" alt="" class="logo-img">
                    </a>
                </div>
            </div>
        </header>

        <div class="img-continer">
            <div class="img-box">
              <?php
                if (strpos($row_short_links['url'],".mov") === false && strpos($row_short_links['url'],".mp4") === false) {
              ?>
                  <img src="../uploads/<?php echo $row_short_links['url']; ?>" alt="" class="img-box-img">
              <?php
                } else {
              ?>
                  <video class="open-popup-video" src="../uploads/<?php echo $row_short_links['url']; ?>" autoplay="" loop="" controls="" muted="muted"></video>
              <?php
                }
              ?>
            </div>
            <div class="img-row">
                <a href="#" class="img-row-btn img-row-link">
                    <img src="img/in.png" alt="">
                </a>
                <a href="#" class="img-row-btn img-row-link">
                    <img src="img/in2.png" alt="">
                </a>
                <a href="#" class="img-row-btn img-row-link">
                    <img src="img/in3.png" alt="">
                </a>
                <a href="whatsapp://send?text=https://shootnbox.fr/uploads/<?php echo $row_short_links['url']; ?>" class="img-row-btn img-row-link">
                    <img src="img/in4.png" alt="">
                </a>
                <a href="http://www.facebook.com/sharer.php?https://shootnbox.fr/uploads/<?php echo $row_short_links['url']; ?>" class="img-row-btn img-row-link">
                    <img src="img/in5.png" alt="">
                </a>
                <button class="img-row-btn btn-open">
                    <img src="img/in6.png" alt="">
                </button>
                <a href="get_file.php?file=<?php echo $row_short_links['url']; ?>" class="img-row-btn">
                    <img src="img/in7.png" alt="">
                </a>
            </div>
        </div>
    </div>
    <script>
        let btnOpen = document.querySelector('.btn-open');
        let imgRow = document.querySelector('.img-row');
        btnOpen.addEventListener('click', () => {
            imgRow.classList.toggle('--active');
        })
    </script>
</body>

</html>
<!--
    <?php echo base64_decode($row_orders['data']); ?>
-->