<?php
@session_start();
@header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['event']) && $_GET['event'] == "logout") {unset($_SESSION['user']); unset($_SESSION['order']);}
if (!isset($_SESSION['user']) && !isset($_SESSION['order'])) {header("Location: login.php"); exit;}

@require_once("../inc/mainfile.php");

$scrip_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME);
foreach ($_POST as $key => $value){if ($key != "submit") {$$key = mysqli_real_escape_string($conn, $value);}}

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?php if (isset($page_title)) {echo $page_title." - ";} ?>Section de l'administrateur</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">

	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet">
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/css/animate.min.css" rel="stylesheet">
	<link href="assets/css/style.min.css?v=1.2" rel="stylesheet">
	<link href="assets/css/style-responsive.min.css" rel="stylesheet">
	<link href="assets/css/theme/alesko.css?v=1.0" rel="stylesheet" id="theme">
	<!-- ================== END BASE CSS STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->

	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-fixed-top navbar-inverse">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="./" class="navbar-brand"><span class="navbar-logo"></span></a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->

				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo ADMIN_UPLOAD_IMAGES_DIR.'avatar.png'; ?>" alt="" />
							<span class="hidden-xs"><?php echo isset($_SESSION['user']) ? $_SESSION['user']['name'] : $_SESSION['order']['name'] ?></span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<?php if ($_SESSION['user']['role'] == 1) { ?>
							<li><a href="edit_user.php?user_id=<?php echo $_SESSION['user']['id'] ?>">Modifier le profil</a></li>
							<li class="divider"></li>
							<?php } ?>
							<li><a href="./?event=logout">Déconnexion</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->

		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<div class="image">
							<a href="javascript:;"><img src="<?php echo ADMIN_UPLOAD_IMAGES_DIR.'avatar.png'; ?>" alt=""></a>
						</div>
						<div class="info">
							<?php echo isset($_SESSION['user']) ? $_SESSION['user']['name'] : $_SESSION['order']['name'] ?>
							<small><?php echo isset($_SESSION['user']) ? $_SESSION['user']['email'] : $_SESSION['order']['email'] ?></small>
						</div>
					</li>
				</ul>
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->
				<ul class="nav">
					<li class="nav-header">Section de l'administrateur</li>
					<li<?php if ($scrip_name == "index.php") {echo' class="active"';} ?>>
						<a href="./" title="Accueil">
							<i class="fa fa-home"></i>
							<span>Accueil</span>
						</a>
					</li>
					<?php if ($_SESSION['user']['role'] == 1) { ?>
						<li<?php if (strpos($scrip_name, 'user') !== false) {echo' class="active"';} ?>>
							<a href="users_list.php" title="Administrateurs">
								<i class="fa fa-users"></i>
								<span>Administrateurs</span>
							</a>
						</li>
					<?php }
            $result_users = mysqli_query($conn, "SELECT `accesses` FROM `users` WHERE `id` = ".$_SESSION['user']['id']);
            $row_users = mysqli_fetch_assoc($result_users);
            $accesses_arr = explode(",", $row_users['accesses']);
          ?>

          <?php if ($_SESSION['user']['role'] == 1 || in_array(1, $accesses_arr)) { ?>
            <li class="has-sub<?php if (strpos($scrip_name, 'order') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Commandes</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'order') !== false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == 0 && !isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=0" title="Demandes">Demandes</a></li>
                <!--li<?php if (isset($_GET['status']) && $_GET['status'] == 1) {echo' class="active"';} ?>><a href="orders_list.php?status=1" title="Attentes">Attentes</a></li-->
                <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == 2 && !isset($_GET['arch'])  && $_GET['long_duration'] != 1) {echo' class="active"';} ?>><a href="orders_list.php?status=2" title="Réservations">Réservations</a></li>
                <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == 2 && $_GET['long_duration'] == 1 && !isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=2&long_duration=1" title="Location longue durée">Location longue durée</a></li>
                <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == -1 && !isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=-1" title="Refusé">Refusé</a></li>
                <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == -2 && !isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=-2" title="Erreurs">Erreurs</a></li>
                <li class="has-sub<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['arch'])) {echo' expand';} ?>">
                  <a href="javascript:;">
                    <b class="caret pull-right"></b>
                    <span>Archives</span>
                  </a>
                  <ul class="sub-menu"<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['arch'])) {echo' style="display: block;"';} ?>>
                    <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == 0 && isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=0&arch=true" title="Demandes archives">Demandes</a></li>
                    <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == 2 && isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=2&arch=true" title="Réservations archives">Réservations</a></li>
                     <li<?php if (strpos($scrip_name, 'order') !== false && isset($_GET['status']) && $_GET['status'] == -1 && isset($_GET['arch'])) {echo' class="active"';} ?>><a href="orders_list.php?status=-1&arch=true" title="Refusé archives">Refusé</a></li>
                  </ul>
                </li>
              </ul>
            </li>
          <?php } ?>
          <?php if ($_SESSION['user']['role'] == 1 || in_array(2, $accesses_arr)) { ?>
            <li class="has-sub<?php if (strpos($scrip_name, 'ipad') !== false || strpos($scrip_name, 'desktop') !== false || strpos($scrip_name, 'gallery') !== false || strpos($scrip_name, 'settings') !== false || (strpos($scrip_name, "configure") !== false && $_GET['order_id'] == 4182)) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Configuration bornes</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'ipad') !== false || strpos($scrip_name, 'desktop') !== false  || strpos($scrip_name, 'gallery') !== false || strpos($scrip_name, 'settings') !== false || (strpos($scrip_name, "configure") !== false && $_GET['order_id'] == 4182)) {echo' style="display: block;"';} ?>>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Ring Ipad</li>
                <li<?php if (strpos($scrip_name, 'ipad_list') !== false && isset($_GET['status']) && $_GET['status'] == 0) {echo' class="active"';} ?>><a href="ipad_list.php?status=0" title="Ipad - Non configuré">Non configuré</a></li>
                <li<?php if (strpos($scrip_name, 'ipad_list') !== false && isset($_GET['status']) && $_GET['status'] == 1) {echo' class="active"';} ?>><a href="ipad_list.php?status=1" title="Ipad - Configuré">Configuré</a></li>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Vegas IPad</li>
                <!--li<?php if (strpos($scrip_name, 'ipad_vegas') !== false && isset($_GET['status']) && $_GET['status'] == 0) {echo' class="active"';} ?>><a href="ipad_vegas_list.php?status=0" title="Vegas - Non configuré">Non configuré</a></li-->
                <li<?php if (strpos($scrip_name, 'ipad_vegas') !== false) {echo' class="active"';} ?>><a href="ipad_vegas_list.php?status=1" title="Vegas - Configuré">Configuré</a></li>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Vegas Desktop</li>
                <li<?php if (strpos($scrip_name, 'desktop_vegas') !== false && isset($_GET['status']) && $_GET['status'] == 0) {echo' class="active"';} ?>><a href="desktop_vegas_list.php?status=0" title="Vegas - Non configuré">Non configuré</a></li>
                <li<?php if (strpos($scrip_name, 'desktop_vegas') !== false && isset($_GET['status']) && $_GET['status'] == 1) {echo' class="active"';} ?>><a href="desktop_vegas_list.php?status=1" title="Vegas - Configuré">Configuré</a></li>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Props</li>
                <li<?php if (strpos($scrip_name, 'gallery') !== false) {echo' class="active"';} ?>><a href="gallery_list.php" title="Props">Props</a></li>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Paramètres</li>
                <li<?php if (strpos($scrip_name, 'settings') !== false && isset($_GET['type']) && $_GET['type'] == 1) {echo' class="active"';} ?>><a href="settings.php?type=1" title="iPad">iPad</a></li>
                <li<?php if (strpos($scrip_name, 'settings') !== false && isset($_GET['type']) && $_GET['type'] == 2) {echo' class="active"';} ?>><a href="settings.php?type=2" title="Vegas iPad">Vegas iPad</a></li>
                <li<?php if (strpos($scrip_name, 'settings') !== false && isset($_GET['type']) && $_GET['type'] == 3) {echo' class="active"';} ?>><a href="settings.php?type=3" title="Vegas Desktop">Vegas Desktop</a></li>
                <li<?php if (strpos($scrip_name, "configure") !== false && $_GET['order_id'] == 4182) {echo' class="active"';} ?>>
                   <a href="configure.php?order_id=4182&back_url=<?php echo $_SERVER['REQUEST_URI']; ?>" title="Référence">
                     <span>Référence</span>
                   </a>
                 </li>
              </ul>
            </li>
            <?php } ?>
            <?php if ($_SESSION['user']['role'] == 1 || in_array(3, $accesses_arr)) { ?>
            <li class="has-sub<?php if (strpos($scrip_name, 'readiness') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Préparation</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'readiness') !== false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'readiness') !== false && !isset($_GET['arch'])) {echo' class="active"';} ?>><a href="readiness.php" title="Préparation">Préparation</a></li>
                <li<?php if (strpos($scrip_name, 'readiness') !== false && isset($_GET['arch'])) {echo' class="active"';} ?>><a href="readiness.php?arch=true" title="Préparation archives">Archives</a></li>
              </ul>
            </li>
            <?php } ?>
            <?php if ($_SESSION['user']['role'] == 1 || in_array(4, $accesses_arr)) { ?>
            <li class="has-sub<?php if (strpos($scrip_name, 'epayment', 0) !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Paiements</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'payment', 0) !== false) {echo' style="display: block;"';} ?>>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Entreprise</li>
                <li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 0) {echo' class="active"';} ?>><a href="epayments_list2.php?status=0" title="Sans Acompte">Nonsoldés</a></li>
                <!--li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 1) {echo' class="active"';} ?>><a href="epayments_list2.php?status=1" title="Soldés">Soldés</a></li-->
                <li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 0) {echo' class="active"';} ?>><a href="epayments_list.php?status=0" title="Sans Acompte">Sans Acompte</a></li>
                <li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 1) {echo' class="active"';} ?>><a href="epayments_list.php?status=1" title="Avec Acompte">Avec Acompte</a></li>
                <li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 2) {echo' class="active"';} ?>><a href="epayments_list.php?status=2" title="Soldés">Soldés</a></li>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Factor</li>
                <li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 0 && isset($_GET['facteur']) && $_GET['facteur'] == 1) {echo' class="active"';} ?>><a href="epayments_list.php?facteur=1&status=0" title="Nonsoldés">Nonsoldés</a></li>
                <li<?php if (strpos($scrip_name, 'epayment', 0) !== false && isset($_GET['status']) && $_GET['status'] == 2 && isset($_GET['facteur']) && $_GET['facteur'] == 1) {echo' class="active"';} ?>><a href="epayments_list.php?facteur=1&status=2" title="Soldés">Soldés</a></li>
                <li class="nav-header" style="color: #ff0; font-weight: bold;">Particulier</li>
                <li<?php if (($scrip_name == "payments_list.php" || $scrip_name == "edit_payment.php") && isset($_GET['status']) && $_GET['status'] == 0) {echo' class="active"';} ?>><a href="payments_list.php?status=0" title="Sans Acompte">Sans Acompte</a></li>
                <li<?php if (($scrip_name == "payments_list.php" || $scrip_name == "edit_payment.php") && isset($_GET['status']) && $_GET['status'] == 1) {echo' class="active"';} ?>><a href="payments_list.php?status=1" title="Avec Acompte">Avec Acompte</a></li>
                <li<?php if (($scrip_name == "payments_list.php" || $scrip_name == "edit_payment.php") && isset($_GET['status']) && $_GET['status'] == 2) {echo' class="active"';} ?>><a href="payments_list.php?status=2" title="Soldés">Soldés</a></li>
                <li class="nav-header<?php if ($scrip_name == "payments.php") {echo' active';} ?>" style="margin-left: -20px;"><a href="payments.php" title="Tous paiments" style="color: #ff0; margin-left: -10px;">Tous paiments</a></li>

              </ul>
            </li>
            <?php } ?>
            <?php if ($_SESSION['user']['role'] == 1 || in_array(5, $accesses_arr)) { ?>
            <li<?php if (strpos($scrip_name, 'albums') !== false) {echo' class="active"';} ?>>
              <a href="albums_list.php" title="Albums">
                <i class="fa fa-calendar-o"></i>
                <span>Albums</span>
              </a>
           </li>
           <?php } ?>
          <?php if ($_SESSION['user']['role'] == 1 || in_array(6, $accesses_arr)) { ?>
            <li class="has-sub<?php if (strpos($scrip_name, 'statistics') !== false || strpos($scrip_name, 'data') !== false || strpos($scrip_name, 'seo') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Statistiques</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'statistics') !== false || strpos($scrip_name, 'data') !== false || strpos($scrip_name, 'seo') !== false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'statistics') !== false && isset($_GET['type']) && $_GET['type'] == 1) {echo' class="active"';} ?>><a href="statistics.php?type=1" title="Entreprises">Entreprises</a></li>
                <li<?php if (strpos($scrip_name, 'statistics') !== false && isset($_GET['type']) && $_GET['type'] == 2) {echo' class="active"';} ?>><a href="statistics.php?type=2" title="Particuliers">Particuliers</a></li>
                <li<?php if (strpos($scrip_name, 'statistics') !== false && isset($_GET['type']) && $_GET['type'] == 0) {echo' class="active"';} ?>><a href="statistics.php?type=0" title="Total">Total</a></li>
                <li<?php if (strpos($scrip_name, 'statistics_commercial') !== false) {echo' class="active"';} ?>><a href="statistics_commercial.php" title="Commercial">Commercial</a></li>
                <li<?php if (strpos($scrip_name, 'data') !== false) {echo' class="active"';} ?>><a href="data.php" title="Data">Data</a></li>
                <li<?php if (strpos($scrip_name, 'seo') !== false) {echo' class="active"';} ?>><a href="seo.php?search_type=1" title="Provenance">Provenance</a></li>
              </ul>
            </li>
            <?php } ?>
          <?php if ($_SESSION['user']['role'] == 1 || in_array(7, $accesses_arr)) { ?>
            <li class="has-sub<?php if (strpos($scrip_name, 'conversion') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Conversion</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'conversion') !== false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'conversion') !== false && isset($_GET['type']) && $_GET['type'] == 1) {echo' class="active"';} ?>><a href="conversion.php?type=1" title="Entreprises">Entreprises</a></li>
                <li<?php if (strpos($scrip_name, 'conversion') !== false && isset($_GET['type']) && $_GET['type'] == 2) {echo' class="active"';} ?>><a href="conversion.php?type=2" title="Particuliers">Particuliers</a></li>
                <li<?php if (strpos($scrip_name, 'conversion') !== false && isset($_GET['type']) && $_GET['type'] == 0) {echo' class="active"';} ?>><a href="conversion.php?type=0" title="Total">Total</a></li>
              </ul>
            </li>
            <?php } ?>
          <?php if ($_SESSION['user']['role'] == 1 || in_array(8, $accesses_arr)) { ?>
            <li class="has-sub<?php if ((strpos($scrip_name, 'type') !== false || strpos($scrip_name, 'template') !== false) && strpos($scrip_name, 'bornes_type') === false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Templates</span>
              </a>
              <ul class="sub-menu"<?php if ((strpos($scrip_name, 'type') !== false || strpos($scrip_name, 'template') !== false) && strpos($scrip_name, 'bornes_type') === false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'type') !== false) {echo' class="active"';} ?>><a href="types_list.php" title="Thèmes">Thèmes</a></li>
                <li<?php if (strpos($scrip_name, 'template') !== false && !isset($_GET['arch'])) {echo' class="active"';} ?>><a href="templates_list.php" title="Calalogue" onclick="setCookie('page', 0)">Calalogue</a></li>
                <li<?php if (strpos($scrip_name, 'template') !== false && isset($_GET['arch'])) {echo' class="active"';} ?>><a href="templates_list.php?arch=true" title="Archives" onclick="setCookie('page', 0)">Archives</a></li>
              </ul>
            </li>
            <?php } ?>
          <?php if ($_SESSION['user']['role'] == 1 || in_array(9, $accesses_arr)) { ?>
            <li<?php if (strpos($scrip_name, 'sms') !== false) {echo' class="active"';} ?>>
              <a href="edit_sms.php" title="SMS">
                <i class="fa fa-calendar-o"></i>
                <span>SMS</span>
              </a>
           </li>
           <?php } ?>
          <?php if ($_SESSION['user']['role'] == 1 || in_array(10, $accesses_arr)) { ?>
           <li class="has-sub<?php if (strpos($scrip_name, 'article') !== false || strpos($scrip_name, 'facture') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Facturation</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'article') !== false || strpos($scrip_name, 'facture') !== false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'facture') !== false && isset($_GET['type']) && $_GET['type'] == 0) {echo' class="active"';} ?>>
                    <a href="factures_list.php?type=0" title="Devis">
                      <span>Devis</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, 'facture') !== false && isset($_GET['type']) && $_GET['type'] == 2) {echo' class="active"';} ?>>
                    <a href="factures_list.php?type=2" title="Factures">
                      <span>Factures</span>
                    </a>
                 </li>
                <li<?php if (strpos($scrip_name, 'article') !== false) {echo' class="active"';} ?>>
                    <a href="articles_list.php" title="Articles">
                      <span>Articles</span>
                    </a>
                 </li>
              </ul>
            </li>
            <li class="has-sub<?php if (strpos($scrip_name, 'history') !== false || strpos($scrip_name, 'avoir') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Avoir</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'history') !== false || strpos($scrip_name, 'avoir') !== false) {echo' style="display: block;"';} ?>>
                 <li<?php if (strpos($scrip_name, 'history') !== false) {echo' class="active"';} ?>>
                    <a href="history.php" title="Historique">
                      <span>Historique</span>
                    </a>
                 </li>
                <li<?php if (strpos($scrip_name, 'avoir') !== false) {echo' class="active"';} ?>>
                    <a href="avoir.php" title="Avoir">
                      <span>Avoir</span>
                    </a>
                 </li>
              </ul>
            </li>
            <?php } ?>
            <?php if ($_SESSION['user']['role'] == 1) { ?>
           <li class="has-sub<?php if (strpos($scrip_name, 'stocks') !== false || strpos($scrip_name, 'calendar') !== false || strpos($scrip_name, 'repair') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Gestion des stocks</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'stocks') !== false || strpos($scrip_name, 'calendar') !== false || strpos($scrip_name, 'box_list') !== false || strpos($scrip_name, 'repair') !== false) {echo' style="display: block;"';} ?>>
                 <li<?php if (strpos($scrip_name, 'stocks') !== false && isset($_GET['type']) && $_GET['type'] == 2) {echo' class="active"';} ?>>
                    <a href="stocks.php" title="Stocks">
                      <span>Stocks</span>
                    </a>
                 </li>
                <li<?php if (strpos($scrip_name, 'calendar') !== false || strpos($scrip_name, 'box_list') !== false) {echo' class="active"';} ?>>
                    <a href="calendar.php" title="Calendrier">
                      <span>Calendrier</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, 'repair') !== false) {echo' class="active"';} ?>>
                    <a href="repair_list.php" title="Maintenance">
                      <span>Maintenance</span>
                    </a>
                 </li>
              </ul>
            </li>
            <li class="has-sub<?php if (strpos($scrip_name, 'parametrs') !== false || strpos($scrip_name, 'box_list') !== false || strpos($scrip_name, 'pdf_doc') !== false || strpos($scrip_name, 'time') !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Paramètres</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'parametrs') !== false || strpos($scrip_name, 'box_list') !== false || strpos($scrip_name, 'pdf_doc') !== false || strpos($scrip_name, 'time') !== false) {echo' style="display: block;"';} ?>>
                 <li<?php if (strpos($scrip_name, 'email') !== false) {echo' class="active"';} ?>>
                    <a href="email_parametrs.php" title="Email">
                      <span>Email</span>
                    </a>
                 </li>
                <li<?php if (strpos($scrip_name, "pdf_doc") !== false && strpos($scrip_name, "ppdf_doc") === false) {echo' class="active"';} ?>>
                    <a href="pdf_doc.php" title="Plaquette Entreprises">
                      <span>Plaquette Entreprises</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, "ppdf_doc") !== false) {echo' class="active"';} ?>>
                    <a href="ppdf_doc.php" title="Plaquette Particuliers">
                      <span>Plaquette Particuliers</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, "time") !== false) {echo' class="active"';} ?>>
                    <a href="times_list.php" title="Plaquette Particuliers">
                      <span>Créneaux horaire</span>
                    </a>
                 </li>
              </ul>
            </li>

            <li class="has-sub<?php if (strpos($scrip_name, 'bornes_type') !== false || strpos($scrip_name, 'option') !== false || strpos($scrip_name, "delivery") !== false || strpos($scrip_name, "event") !== false|| strpos($scrip_name, "promo") !== false || strpos($scrip_name, "deliveris") !== false || strpos($scrip_name, "promo") !== false) {echo' expand';} ?>">
              <a href="javascript:;">
                <b class="caret pull-right"></b>
                <i class="fa fa-calendar-o"></i>
                <span>Annuaire</span>
              </a>
              <ul class="sub-menu"<?php if (strpos($scrip_name, 'bornes_type') !== false || strpos($scrip_name, 'option') !== false || strpos($scrip_name, "delivery") !== false || strpos($scrip_name, "event") !== false || strpos($scrip_name, "promo") !== false || strpos($scrip_name, "deliveris") !== false) {echo' style="display: block;"';} ?>>
                <li<?php if (strpos($scrip_name, 'bornes_type') !== false || strpos($scrip_name, "promo") !== false) {echo' class="active"';} ?>>
                  <a href="bornes_types_list.php" title="Types de bornes">
                    <span>Types de bornes</span>
                  </a>
                </li>
                <li<?php if (strpos($scrip_name, "option") !== false) {echo' class="active"';} ?>>
                    <a href="options_list.php" title="Options de bornes">
                      <span>Options de bornes</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, "delivery") !== false) {echo' class="active"';} ?>>
                    <a href="delivery_list.php" title="Livraison pour les bornes">
                      <span>Livraison pour les bornes</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, "recovery") !== false) {echo' class="active"';} ?>>
                    <a href="recovery_list.php" title="Récuperation">
                      <span>Récuperation</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, "deliveris") !== false) {echo' class="active"';} ?>>
                    <a href="deliveris_list.php" title="Livreur">
                      <span>Livreur</span>
                    </a>
                 </li>
                 <li<?php if (strpos($scrip_name, "promo") !== false) {echo' class="active"';} ?>>
                    <a href="promo_list.php" title="Codes promotionnels">
                      <span>Codes promotionnels</span>
                    </a>
                 </li>
              </ul>
            </li>


            <?php } ?>





					<!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
					<!-- end sidebar minify button -->
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->

		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="./" title="Accueil">Section de l'administrateur</a></li>
				<?php if (isset($breadcrumbs)) echo"<li>".$breadcrumbs."</li>"; ?>
				<li class="active"><?php if (isset($page_title)) {echo $page_title;} else {echo"Accueil";} ?></li>
			</ol>
			<div class="clearfix"></div>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?php if (isset($page_title)) {echo $page_title;} ?></h1>
			<!-- end page-header -->