<?php
	$page_title = "Modifier une Maintenance";
	$breadcrumbs = '<a href="repair_list.php" title="Articleы">Articleы</a>';
	include("header.php");
  $error = 0;
  if (isset($_GET['repair_id'])) {
    $repair_id = $_GET['repair_id'];
    $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `id` = ".$repair_id);
    if (mysqli_num_rows($result_repair) == 0) {
      $error = 1;
    } else {
      $row_repair = mysqli_fetch_assoc($result_repair);
    }
  } else {
    $error = 1;
  }
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Modifier une Maintenance</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal edit-repair">
			<div class="form-group">
        <label class="col-md-3 control-label">Agence</label>
        <div class="col-md-4">
          <select class="form-control agency_id">
            <option value="0"<?php if (isset($row_repair['agency_id']) && $row_repair['agency_id'] == 0) {echo" selected";} ?>>Choisissez une agence ...</option>
            <option value="1"<?php if (isset($row_repair['agency_id']) && $row_repair['agency_id'] == 1) {echo" selected";} ?>>Paris</option>
            <option value="2"<?php if (isset($row_repair['agency_id']) && $row_repair['agency_id'] == 2) {echo" selected";} ?>>Bordeaux</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Numéro de la borne</label>
        <div class="col-md-3">
          <select class="form-control box_id">
            <option value="">Numéro de la borne...</option>
            <?php
              $prefix = $row_repair['agency_id'] == 1 ? "P" : "B";
              $html .= '<optgroup label="Ring">';
              for ($i = 1; $i <= $row_settings['ring']; $i++) {
                $html .= '<option value="R'.$i.'/'.$prefix.'"'.($row_repair['box_id'] == 'R'.$i.'/'.$prefix ? ' selected' : '').'>R'.$i.'/'.$prefix.'</option>';
              }
              $html .= '</optgroup>
              <optgroup label="Vegas">';
              for ($i = 1; $i <= $row_settings['vegas']; $i++) {
                $html .= '<option value="V'.$i.'/'.$prefix.'"'.($row_repair['box_id'] == 'V'.$i.'/'.$prefix ? ' selected' : '').'>V'.$i.'/'.$prefix.'</option>';
              }
              $html .= '</optgroup>
              <optgroup label="Vegas_Slim">';
              for ($i = 1; $i <= $row_settings['vegas_slim']; $i++) {
                $html .= '<option value="vspace'.$i.'/'.$prefix.'"'.($row_repair['box_id'] == 'VS'.$i.'/'.$prefix ? ' selected' : '').'>VS'.$i.'/'.$prefix.'</option>';
              }
              $html .= '</optgroup>
              <optgroup label="Miroir">';
              for ($i = 1; $i <= $row_settings['miroir']; $i++) {
                $html .= '<option value="M'.$i.'/'.$prefix.'"'.($row_repair['box_id'] == 'M'.$i.'/'.$prefix ? ' selected' : '').'>M'.$i.'/'.$prefix.'</option>';
              }
              $html .= '</optgroup>
              <optgroup label="Spinner_360">';
              for ($i = 1; $i <= $row_settings['spinner']; $i++) {
                $html .= '<option value="S'.$i.'/'.$prefix.'"'.($row_repair['box_id'] == 'S'.$i.'/'.$prefix ? ' selected' : '').'>S'.$i.'/'.$prefix.'</option>';
              }
              $html .= '</optgroup>
              <optgroup label="Réalité_Virtuelle">';
              for ($i = 1; $i <= $row_settings['vr']; $i++) {
                $html .= '<option value="VR'.$i.'/'.$prefix.'"'.($row_repair['box_id'] == 'VR'.$i.'/'.$prefix ? ' selected' : '').'>VR'.$i.'/'.$prefix.'</option>';
              }
              $html .= '</optgroup>';
              echo $html;
            ?>
          </select>
        </div>
      </div>
			<div class="form-group">
				<label class="col-md-3 control-label"></label>
				<div class="col-md-9">
					<button type="submit" class="btn btn-sm btn-success">Sauvegarder</button>
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
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

	$(document).ready(function() {

		App.init();

		// Добавление страницы
		$('.edit-repair').on('submit', function(event){
			event.preventDefault();
			if ($('.agency_id').val() == 0) {
				showError('Sélectionnez une agence !');
				return false;
			}

			if ($('.box_id').val() == 0) {
        showError('Le numéro borne est obligatoire !');
        return false;
      }

			$.ajax({
				type: 'POST',
				url: 'd26386b04e.php',
				data: {event: 'edit_repair', id: <?php echo $row_repair['id'] ?>, agency_id: $('.agency_id').val(), box_id: $('.box_id').val()},
				success: function(responce){
					if (responce == 'done'){
						swal({
							title: 'Fait!',
              text: 'L\'information a été mise à jour avec succès!',
              type: 'success',
              confirmButtonColor: '#348fe2',
              confirmButtonText: 'ОК',
              closeOnConfirm: true
						}).then(function() {
							window.location.href = 'repair_list.php';
						});
					} else {
						showError(responce);
					}
				}
			});
		});

    $('.agency_id').on('change', function(event) {
      getBoxIDList();
    });

    function getBoxIDList() {
      $.ajax({
          type: 'POST',
          url: 'd26386b04e.php',
          data: {
            event: 'box_id_list',
            agency_id: $('.agency_id').val(),
            box_type: 0,
          },
          cache: false,
          success: function(response) {
            $('.box_id').html(response);
          }
      });
    }

	});

</script>

<?php include("end.php"); ?>