<?php
	$page_title = "Ajouter un utilisateur";
	$breadcrumbs = '<a href="users_list.php" title="Liste des utilisateurs">Utilisateurs</a>';
	include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Formulaire d'ajout d'un utilisateur</h4>
	</div>
	<div class="panel-body">
		<div id="fullsize-pos"></div>
		<form class="form-horizontal add-user">
			<div class="form-group">
				<label class="col-md-3 control-label required">Le rôle de</label>
				<div class="col-md-4">
					<select class="form-control role">
						<option value="1">Administrateur</option>
						<option value="2" selected>Utilisateur</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">E-mail</label>
				<div class="col-md-4">
					<input type="text" class="form-control email" placeholder="E-mail" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Password</label>
				<div class="col-md-4">
					<input type="text" class="password form-control m-b-5" placeholder="Password" />
					<div class="passwordStrength is0 m-t-5"></div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label required">Prenom</label>
				<div class="col-md-4">
					<input type="text" class="form-control first_name" placeholder="Prenom" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Nom</label>
				<div class="col-md-4">
					<input type="text" class="form-control last_name" placeholder="Nom" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Accède</label>
				<div class="col-md-4">
					<select class="form-control accesses" multiple="multiple">
            <option value="1">Commandes</option>
            <option value="2">Configuration Ipad</option>
            <option value="3">Préparation</option>
            <option value="4">Paiements</option>
            <option value="5">Album</option>
            <option value="6">Statistiques</option>
            <option value="7">Conversion</option>
            <option value="8">Templates</option>
            <option value="9">SMS</option>
            <option value="10">Facturation</option>
          </select>
				</div>
			</div>
      <div class="form-group">
        <label class="col-md-3 control-label"></label>
        <div class="col-md-6">
          <label class="checkbox-inline">
            <input type="checkbox" class="is_commercial" value="1" />
            Commercial
          </label>
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label">Signature</label>
				<div class="col-md-9">
					<textarea type="text" class="form-control" id="signature" placeholder="Signature"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success">Ajouter</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- end panel -->


<?php
	include("footer.php");
?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet">
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets\plugins\ckeditor\ckeditor.js"></script>
<script src="assets\plugins\ckfinder\ckfinder.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		$('.password').passwordStrength({targetDiv:'.passwordStrength'});

		$('.accesses').select2({placeholder: 'Accède...'});

    var editor = CKEDITOR.replace('signature');
    CKFinder.setupCKEditor(editor, 'assets/plugins/ckfinder/');


		elasticArea('.address');

		$('.add-user').on('submit', function(event) {
			event.preventDefault();

			if ($('.email').val() == '' || !checkemail($('.email').val())) {
				showError('S\'il vous plaît entrer un email valide !');
				return false;
			}

			if ($('.password').val().length < 6) {
				showError('Le mot de passe ne peut pas être inférieur à 6 caractères !');
				return false;
			}

			if ($('.first_name').val() == '') {
				showError('Entrez votre prénom !');
				return false;
			}

			var accesses = '';
      $('.accesses').each(function(index, brand){
        accesses += $(this).val() + ',';
      });
      accesses = accesses.slice(0, -1);

      if ($('.is_commercial').is(':checked')) {var is_commercial = 1;} else {var is_commercial = 0;}

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'add_user', role: $('.role').val(), email: $('.email').val(), password: $('.password').val(), accesses: accesses, first_name: $('.first_name').val(), last_name: $('.last_name').val(), signature: CKEDITOR.instances.signature.getData(), is_commercial: is_commercial},
				cache: false,
				success: function(responce) {
					if (responce == 'done'){
						swal({
							title: 'Fait!',
							text: 'L\'utilisateur a été ajouté avec succès.',
							type: 'success',
							confirmButtonColor: '#348fe2',
							confirmButtonText: 'Fermer',
							closeOnConfirm: true
						}).then(function() {
							window.location.href = 'users_list.php';
						});
					} else {
						showError(responce);
					}
				}
			});
		});
	});
</script>

<?php include("end.php"); ?>