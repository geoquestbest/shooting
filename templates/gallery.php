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
  <link rel="stylesheet" href="css/style.min.css?v=1.0">
  <style>
    <?php
      @require_once("../inc/mainfile.php");
      $result_types =mysqli_query($conn, "SELECT * FROM `types`");
      $i =1;

      while($row_types =mysqli_fetch_assoc($result_types)) {
        if ($i ==1) {
          $first_type =$row_types['id'];
        }
        echo'
          #type'.$i.':checked~#type-'.$i.'::after {
            opacity: 1;
          }
        ';
        $i++;
      }
    ?>

    .box-right {
      align-items: start;
      padding: 0;
      width: 100%;
    }

    .open-popup {
      width: 12vw;
      height: 175px;
      margin: 1vw;
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
    <main class="main">
      <aside class="main__aside aside">
        <div class="aside__body">
          <div class="aside__container">
            <div class="aside__header hor-centered">
              <h2 class="title">Filtres de recherches</h2>
            </div>

            <div class="spoiler active">
              <div class="spoiler__header">
                <span>Thèmes</span>
                <div class="spoiler__icon">
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="spoiler__body">
                <?php
                  $result_types = mysqli_query($conn, "SELECT * FROM `types`");
                  $i = 1;
                  while($row_types = mysqli_fetch_assoc($result_types)) {
                    echo'<div class="radio-box">
                       <div class="radio-box__check">
                        <input type="radio" class="radio-box__input select-type" id="type'.$i.'" name="radio-themes" value="'.$row_types['id'].'" '.($i == 1 ? ' checked' : '').'/>
                        <label for="type'.$i.'" class="radio-box__check-l" id="type-'.$i.'"></label>
                      </div>
                      <label for="type'.$i.'">'.$row_types['title'].'</label>
                    </div>';
                    $i++;
                  }
                ?>
              </div>
            </div>

            <div class="spoiler">
              <div class="spoiler__header">
                <span>Formats</span>
                <div class="spoiler__icon">
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="spoiler__body">
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="paysage" name="radio-formats" value="1" checked />
                    <label for="paysage" class="radio-box__check-l" id="paysage-l"></label>
                  </div>
                  <label for="paysage">Paysage</label>
                </div>
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="portrait" name="radio-formats" value="2" />
                    <label for="portrait" class="radio-box__check-l" id="portrait-l"></label>
                  </div>
                  <label for="portrait">Portrait</label>
                </div>
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="strip" name="radio-formats" value="3" />
                    <label for="strip" class="radio-box__check-l" id="strip-l"></label>
                  </div>
                  <label for="strip">Strip</label>
                </div>
              </div>
            </div>

            <div class="spoiler">
              <div class="spoiler__header">
                <span>Prises de vue</span>
                <div class="spoiler__icon">
                  <div></div>
                  <div></div>
                </div>
              </div>
              <div class="spoiler__body">
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="one-vue" name="radio-vue" value="1" checked />
                    <label for="one-vue" class="radio-box__check-l" id="one-vue-l"></label>
                  </div>
                  <label for="one-vue">1 vue</label>
                </div>
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="two-vue" name="radio-vue" value="2" />
                    <label for="two-vue" class="radio-box__check-l" id="two-vue-l"></label>
                  </div>
                  <label for="two-vue">2 vues</label>
                </div>
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="three-vue" name="radio-vue" value="3" />
                    <label for="three-vue" class="radio-box__check-l" id="three-vue-l"></label>
                  </div>
                  <label for="three-vue">3 vues</label>
                </div>
                <div class="radio-box">
                  <div class="radio-box__check">
                    <input type="radio" class="radio-box__input" id="four-vue" name="radio-vue" value="4" />
                    <label for="four-vue" class="radio-box__check-l" id="four-vue-l"></label>
                  </div>
                  <label for="four-vue">4 vues</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>
      <div class="main__body body">
        <div class="body__wrapper">
          <div class="content-body">
            <div class="content-body__container box-right">
              <?php
                $result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `status` = 1  AND `photos_amount` = 1 AND `format_id` = 1 ORDER BY `id` DESC");
                while($row_templates = mysqli_fetch_assoc($result_templates)) {
                  $types_ids_arr = explode(",", $row_templates['types_ids']);
                  if (mb_strpos($row_templates['boxes'], "Vegas", 0) !== false) {
                    $row_templates['boxes'] = $row_templates['boxes'].",Vegas_800,Vegas_1200,Vegas_1600,Vegas_2000";
                  }
                  if (mb_strpos($row_templates['boxes'], "Miroir", 0) !== false) {
                    $row_templates['boxes'] = $row_templates['boxes'].",Miroir_800,Miroir_1200,Miroir_1600,Miroir_2000";
                  }
                  if ($row_templates['preview'] != "" && in_array($first_type, $types_ids_arr) && mb_strpos($row_templates['boxes'], $_SESSION['order']['box_type'], 0) !== false) {
                    list($width, $height, $type, $attr) = getimagesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '120'));
                    $add_class = $width > $height ? 'horisontal' : 'vertical';
                    echo'<a href="creator.php?template_id='.$row_templates['id'].'" class="open-popup">
                      <span class="helper"></span>
                      <img class="open-popup-img '.$add_class.'" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '640').'" alt="">
                    </a>';
                  }
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.addEventListener('click', e => {
      if (e.target.closest('.spoiler__header')) {
        e.target.closest('.spoiler').classList.toggle('active');
      }
    });

    const radio_box = document.querySelectorAll('.radio-box__input');
    radio_box.forEach((element) => {
      element.addEventListener('change', (event) => {
        const type_id = document.querySelector('input[name="radio-themes"]:checked').value,
              format_id = document.querySelector('input[name="radio-formats"]:checked').value,
              photos_amount = document.querySelector('input[name="radio-vue"]:checked').value;
        const request = new XMLHttpRequest();
        const url = '../manager/d26386b04e.php';
        const params = 'event=get_templates&type_id=' + type_id + '&format_id=' + format_id + '&photos_amount=' + photos_amount;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {
          if(request.readyState === 4 && request.status === 200) {
            const html = document.querySelector('.box-right');
            html.innerHTML = request.responseText;
          }
        });
        request.send(params);
      });
    });
  </script>
</body>

</html>