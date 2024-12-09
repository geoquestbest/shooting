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

  foreach ($_POST as $key => $value){if ($key != "submit") {${$key}= mysqli_real_escape_string($conn, $value);}}
  foreach ($_GET as $key => $value){if ($key != "submit") {${$key} = mysqli_real_escape_string($conn, $value);}}

  $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` = '$box_type'");
  $row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
  $i = 1;
  $price_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
  $duration = $_COOKIE['duration'];
  $total = $_COOKIE['number']*$price_arr[$duration - 1];

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/style.css?v=1.0.0">
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
                                    <span class="list-item__amount primary-text"><?php echo $i; ?></span>
                                        Photobooth <?php echo $row_bornes_types['title'] ?>
                                        <span class="list-item__price primary-text"><?php echo $_COOKIE['number']." x ".number_format($price_arr[$duration - 1], 2, '.', ''); ?>€</span>
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
     