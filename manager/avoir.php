<?php
	$page_title = "Avoir";
	include("header.php");

	if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", strtotime("31.12.".(date("Y", time()) + 2)));
  }
?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title">Avoir</h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" style="position: absolute; left: 45vw;">
			<div class="form-group">
				<div class="col-md-3">
		      <label class="checkbox-inline">
						<input type="checkbox" class="avoir_date"<?php if ($_GET['avoir_date'] == 1) echo 'checked="checked"'; ?> />
						Date de avoir
					</label>
				</div>
	      <div class="col-md-3">
	        <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
	      </div>
	      <div class="col-md-3">
	        <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
	      </div>
	      <div class="col-md-3">
	        <button class="btn btn-sm btn-success show">Afficher</button>
	      </div>
	    </div>
	   </form>
		<table id="data-table" class="table table-striped table-bordered nowrap widgets-table" width="100%">
			<thead>
				<tr>
					<th style="width: 120px;">Numéro commande</th>
          <th style="width: auto;">Utilisateur</th>
          <th style="width: auto;">Facture</th>
          <th style="width: auto;">Avoir</th>
          <th style="width: auto;">Date de avoir</th>
          <th style="width: auto;">Date de l’évènement</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ($_GET['avoir_date'] == 1) {
					$rq = "WHERE `created_at` >= ".strtotime($start_date)." AND `created_at` <= ".strtotime($end_date);
				} else {
					$rq = "";
				}
				$result_avoir = mysqli_query($conn, "SELECT DISTINCT(order_id) FROM `avoir` ".$rq." ORDER BY `order_id` DESC");
				$i = 1;
				while($row_avoir = mysqli_fetch_assoc($result_avoir)) {
					$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$row_avoir['order_id']);
					$row_orders = mysqli_fetch_assoc($result_orders);
					if ($_GET['avoir_date'] == 1 || ((!isset($_GET['avoir_date']) || $_GET['avoir_date'] == 0) && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date))) {
						$result_avoir2 = mysqli_query($conn, "SELECT * FROM `avoir` WHERE `order_id` = ".$row_avoir['order_id']." ORDER BY `id` DESC");
					  while($row_avoir2 = mysqli_fetch_assoc($result_avoir2)) {
		          echo'<tr class="'; if ($i%2==0) {echo'odd';} else {echo'even';} echo' gradeX">
		 						<td class="text-center">'.$row_avoir['order_id'].'</td>
		 						<td class="text-left">
		              '.( $row_orders['societe'] ? '<b>'. $row_orders['societe']. '</b><br />' : '' ).'
										<p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>
										E-mail : <a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a><br />
										Téléphone : <a href="tel:'.$row_orders['phone'].'" title="Envoyer un e-mail">'.$row_orders['phone'].'</a><br /><br />
										Lieu de l’évènement : <b>'.$row_orders['event_place'].'</b><br />
										Type d’événement : <b>' . $row_orders['event_type'] . '</b><br />
		                '.($row_orders['description'] != "" ? '<!--Informations complémentaires : <b>'.$row_orders['description'].'</b-->' : '').'
		                <!--br />Comment avez-vous connu ShootnBox : <b>'.$row_orders['about'].'</b-->
		            </td>
		            <td class="text-center">'.$row_orders['num_id'].'</td>
		            <td class="text-center">
						    	<a class="avoir-list" data-url="'.$row_orders['num_id'].'/'.$row_avoir2['pdf'].'" href="../uploads/Factures/'.$row_orders['num_id'].'/'.$row_avoir2['pdf'].'" target="_blank">'.$row_avoir2['pdf'].'</a>
						    </td>
						    <td class="text-center" data-sort="'.$row_avoir2['created_at'].'">'.date("d.m.Y H:i", $row_avoir2['created_at']).'</td>
						    <td class="text-center" data-sort="'.strtotime($row_orders['event_date']).'">'.$row_orders['event_date'].'</td>
		 					</tr>';
	 						$i++;
	 					}
	 				}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<!-- end panel -->

<?php include("footer.php"); ?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets\plugins\DataTables\media\css\dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Select\css\select.bootstrap.min.css" rel="stylesheet">

<link href="assets\plugins\DataTables\extensions\RowReorder\css\rowReorder.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\DataTables\extensions\Buttons\css\buttons.bootstrap.min.css" rel="stylesheet">

<link href="assets\plugins\DataTables\extensions\Responsive\css\responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets\plugins\fancyBox\source\jquery.fancybox.css?v=2.1.6" rel="stylesheet" type="text/css" media="screen" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets\plugins\DataTables\media\js\jquery.dataTables.js"></script>
<script src="assets\plugins\DataTables\media\js\dataTables.bootstrap.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Select\js\dataTables.select.min.js"></script>
<script src="assets\plugins\DataTables\extensions\RowReorder\js\dataTables.rowReorder.min.js"></script>

<script src="assets\plugins\DataTables\extensions\Buttons\js\dataTables.buttons.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.bootstrap.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.flash.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\jszip.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\pdfmake.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\vfs_fonts.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.html5.min.js"></script>
<script src="assets\plugins\DataTables\extensions\Buttons\js\buttons.print.min.js"></script>

<script src="assets\plugins\DataTables\extensions\Responsive\js\dataTables.responsive.min.js"></script>
<script src="assets\plugins\fancyBox\source\jquery.fancybox.pack.js?v=2.1.6"></script>
<script src="assets\plugins\fancyBox\source\helpers\jquery.fancybox-media.js?v=2.1.6"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.fr-CH.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
	$(document).ready(function() {

		App.init();

		$('.start_date, .end_date').datepicker({
      todayHighlight:!0,
      format: 'dd.mm.yyyy',
      language: 'fr-FR'
    });

		var table = $('#data-table').DataTable({
			responsive: true,
			order: [[ 0, 'desc' ]],
			columnDefs: [
				{
					targets: 'no-sort',
					orderable: false,
				}
			],
			dom: 'Bfrtip',
			buttons: [
				'csv', 'excel', 'pdf', 'print',
				{
					text: '<i class="fa fa-download"></i> Télécharger',
					action: function (e, dt, node, config) {
						var avoir_list = '';
						$('.avoir-list').each(function(){
	            avoir_list += $(this).attr('data-url') + ';'
	          });
	          if (avoir_list != '') {
		          avoir_list = avoir_list.slice(0, -1);
							window.location.href = 'get_factures.php?urls=' + avoir_list;
						}
					}
				}
			]
		});

		$('.show').on('click', function(event) {
      event.preventDefault();
      if ($('.start_date').val() != '' && $('.end_date').val() != '') {
        window.location.href = 'avoir.php?start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&avoir_date=' + ($('.avoir_date').is(':checked') ? 1 : 0);
      }
    });
	});
</script>

<?php include("end.php"); ?>