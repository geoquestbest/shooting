<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Photo Cabine | Page d'autorisation</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet">
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="assets/css/animate.min.css" rel="stylesheet">
	<link href="assets/css/style.min.css" rel="stylesheet">
	<link href="assets/css/style-responsive.min.css" rel="stylesheet">
	<link href="assets/plugins/sweetalert2/css/sweetalert2.min.css" rel="stylesheet">
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme">
	<link href="assets/css/theme/alesko_login.css" rel="stylesheet">
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top bg-white">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
		<!-- begin login -->
		<div class="login login-with-news-feed">
			<!-- begin news-feed -->
			<div class="news-feed">
				<div class="news-image">
					<img src="images/login_bg.png" data-id="login-cover-image" alt="">
				</div>
				<div class="news-caption">
					<h4 class="caption-title"><i class="fa fa-diamond text-success"></i> Page d'autorisation</h4>
					<p>Pour accéder à cette section, contactez l'administrateur du service.</p>
				</div>
			</div>
			<!-- end news-feed -->
			<!-- begin right-content -->
			<div class="right-content">
				<!-- begin login-header -->
				<div class="login-header">
					<div class="brand">
						Photo Cabine
						<small>Bienvenue!</small>
					</div>
					<div class="icon">
						<i class="fa fa-sign-in"></i>
					</div>
				</div>
				<!-- end login-header -->
				<!-- begin login-content -->
				<div class="login-content">
					<form method="POST" class="margin-bottom-0 login-form">
						<div class="form-group m-b-15">
							<input type="text" class="form-control input-lg login-email" placeholder="Adresse e-mail">
						</div>
						<div class="form-group m-b-15">
							<input type="password" class="form-control input-lg login-password" placeholder="Password">
						</div>
						<div class="login-buttons">
							<button type="submit" class="btn btn-success btn-block btn-lg">Autorisation</button>
						</div>
						<hr>
						<p class="text-center text-inverse">
							<img src="assets/css/theme/images/logo-home_light.png" alt="" /><br /><br />
							&copy; 2017-<?php echo date("Y", time()) ?> Shoot'n Box Compagnie. Tous droits réservés.
						</p>
					</form>
				</div>
				<!-- end login-content -->
			</div>
			<!-- end right-container -->
		</div>
		<!-- end login -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="assets/js/login-v2.demo.min.js"></script>
	<script src="assets/js/apps.min.js"></script>
	<script src="assets/plugins/sweetalert2/js/sweetalert2.min.js"></script>
	<script src="assets/js/main.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
			LoginV2.init();

			// Обработка формы входа
			$('.login-form').submit(function(event){
				event.preventDefault();
				if ($('.login-email').val() == '') {
					swal({
						title: 'Erreur!',
						text: 'Entrez votre adresse e-mail!',
						type: 'error',
						confirmButtonColor: '#53a3d8',
						confirmButtonText: 'Fermer'
					});
					return false;
				}
				if (!checkemail($('.login-email').val())) {
					swal({
						title: 'Erreur!',
						text: 'S\'il vous plaît entrer un e-mail valide!',
						type: 'error',
						confirmButtonColor: '#53a3d8',
						confirmButtonText: 'Fermer'
					});
					return false;
				}
				if ($('.login-password').val() == '') {
					swal({
						title: 'Erreur!',
						text: 'Entrez le mot de passe!',
						type: 'error',
						confirmButtonColor: '#53a3d8',
						confirmButtonText: 'Fermer'
					});
					return false;
				}
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'login', email: $('.login-email').val(), password: $('.login-password').val()},
					cache: false,
					success: function(responce) {
						console.log(responce);
						if (responce.trim() == 'done'){
							window.location.href = './';
						} else {
							swal('Erreur!', 'Combinaison incorrecte d\'e-mail / mot de passe!', 'error');
						}
					}
				});
			});
		});
	</script>
</body>
</html>
