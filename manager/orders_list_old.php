<?php
	switch($_GET['status']) {
		case 0: $page_title = "Liste des demandes"; break;
		case 1: $page_title = "Liste des attentes"; break;
		case 2: $page_title = "Liste des réservations"; break;
    case -1: $page_title = "Liste des refusé"; break;
    case -2: $page_title = "Liste des erreurs"; break;
		default: $page_title = "Liste des Archives"; break;
	}
  if (isset($_GET['long_duration']) && $_GET['long_duration'] != 0) {
    $page_title .= " (Location longue durée)";
  }

  include("header.php");
	if (isset($_GET['status'])) {
		$rq = "WHERE `status` = ".mysqli_real_escape_string($conn, $_GET['status']);
	} else {
		$rq = "";
	}

  if (isset($_GET['ids'])) {
    if ($rq == "") {
      $rq = "WHERE `id` IN (".mysqli_real_escape_string($conn, $_GET['ids']).")";
    } else {
      $rq .= " AND `id` IN (".mysqli_real_escape_string($conn, $_GET['ids']).")";
    }
  }

	switch($_GET['status']) {
    case 0:
      $title = "Devis";
    break;
    case 2:
      $title = "Facture";
    break;
  }

	if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);
  } else {
    $start_date = date("d.m.Y", strtotime("01.01.".date("Y", time())));
    $end_date = date("d.m.Y", strtotime("31.12.".(date("Y", time()) + 2)));
  }

  if (isset($_GET['user_id']) && $_GET['user_id'] != 0) {
    $rq .= ($rq != "" ? " AND `user_id` = ".mysqli_real_escape_string($conn, $_GET['user_id']) : "WHERE `user_id` = ".mysqli_real_escape_string($conn, $_GET['user_id']));
  }

  if (isset($_GET['long_duration']) && $_GET['long_duration'] != 0) {
    $rq .= ($rq != "" ? " AND `long_duration` = ".mysqli_real_escape_string($conn, $_GET['long_duration']) : "WHERE `long_duration` = ".mysqli_real_escape_string($conn, $_GET['long_duration']));
  }

  if (isset($_GET['search_type']) && $_GET['search_type'] == 1) {
     $rq .= ($rq != "" ? " AND `created_at` >= ".strtotime($start_date." 00:00:00")." AND `created_at` <= ".strtotime($end_date." 23:59:59") : "WHERE `created_at` >= ".strtotime($start_date." 00:00:00")." AND `created_at` <= ".strtotime($end_date." 23:59:59"));
  }

  if (isset($_GET['facteur']) && $_GET['facteur'] == 1) {
     $rq .= ($rq != "" ? " AND `facteur` = 1" : "WHERE `facteur` = 1");
  }

?>
<!-- begin panel -->
<div class="panel panel-inverse">
	<div class="panel-heading">
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
		</div>
		<h4 class="panel-title"><?php echo $page_title ?></h4>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" style="position: absolute; left: 28vw;">
			<div class="form-group">
        <div class="col-md-2<?php if ($_GET['status'] == 0) { echo' hide'; } ?>">
            <select class="form-control facteur">
              <option value="0"<?php if (!isset($_GET['facteur']) || $_GET['facteur'] == 0) {echo" selected";} ?>>Tous</option>
              <option value="1"<?php if (isset($_GET['facteur']) && $_GET['facteur'] == 1) {echo" selected";} ?>>Factor</option>
            </select>
          </div>
         <div class="col-md-2">
            <select class="form-control user_id">
              <option value="0"<?php if (!isset($_GET['user_id']) || $_GET['user_id'] == 0) {echo" selected";} ?>>Choisissez une commercial ...</option>
              <?php
                $result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `is_commercial` = 1 ORDER BY `last_name`");
                while($row_users = mysqli_fetch_assoc($result_users)) {
                  echo'<option value="'.$row_users['id'].'" '.(isset($_GET['user_id']) && $_GET['user_id'] == $row_users['id'] ? "selected" : "").'>'.$row_users['first_name'].'</option>';
                }
              ?>
            </select>
          </div>
          <div class="col-md-2">
            <select class="form-control search_type">
              <option value="0"<?php if (!isset($_GET['search_type']) || $_GET['search_type'] == 0) {echo" selected";} ?>>Date évenement</option>
              <option value="1"<?php if (isset($_GET['search_type']) && $_GET['search_type'] == 1) {echo" selected";} ?>>Date demande</option>
            </select>
          </div>
          <label class="col-md-1 control-label">Période</label>
	        <div class="col-md-2">
	          <input type="text" class="form-control start_date" value="<?php echo $start_date; ?>" placeholder="Début de période" />
	        </div>
	        <div class="col-md-2">
	          <input type="text" class="form-control end_date" value="<?php echo $end_date; ?>" placeholder="Fin de période" />
	        </div>
	        <div class="col-md-1">
	          <button class="btn btn-sm btn-success show">Afficher</button>
	        </div>
	    </div>
	  </form>
		<table id="data-table" class="table table-striped table-bordered orders-table nowrap" width="100%">
			<thead>
				<tr>
					<th>ID</th>
          <th<?php if ($_GET['status'] == 0) { echo' class="hide"'; } ?>>Factor</th>
          <th style="width: 75px;">Prov</th>
          <th style="width: auto;">Commercial</th>
          <th style="width: auto;">Agence</th>
					<th style="width: auto;">Date</th>
					<th>Utilisateur</th>
          <th>E-mail</th>
          <th>Téléphone</th>
					<th>Borne</th>
					<th>Type</th>
					<th>Date de l’évènement</th>
          <th>Date et l’heure du retour</th>
          <th style="width: auto;">Relance</th>
					<th style="width: auto;">J-X</th>
					<th>Livraison</th>
					<th>Mes options</th>
					<th>Tarif HT</th>
          <th>Tarif TTC</th>
          <th>Remise</th>
          <th>Code promo</th>
					<th style="width: 75px;"<?php if ($_GET['status'] == 0) { echo' class="hide"'; } ?>>Devis N°</th>
					<th class="no-sort" style="width: 120px;">Templates</th>
					<!--th style="width: auto;">FA</th-->
          <!--th style="width: auto;">V</th-->
          <th class="no-sort" style="width: 50px;"><?php echo $title ?></th>
          <?php if ($_GET['status'] == 0) { echo'<th style="width: 50px;">Refusé</th>'; } ?>
          <?php if ($_GET['status'] == -1) { echo'<th style="width: auto">Refusé titre</th>'; } ?>
					<th class="no-sort" style="width: 160px!important;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` $rq ORDER BY `id` DESC");
				$i=1; $total = 0; $ids = "";
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					if (((!isset($_GET['arch']) && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0 && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date)) && (!isset($_GET['search_type']) || $_GET['search_type'] == 0) || (isset($_GET['arch']) && floor((strtotime($row_orders['event_date']) + 24*3600 - time())/(3600*24)) < 0) && strtotime($row_orders['event_date']) >= strtotime($start_date) && strtotime($row_orders['event_date']) <= strtotime($end_date) && (!isset($_GET['search_type']) || $_GET['search_type'] == 0)) || ($_GET['search_type'] == 1 && !isset($_GET['arch']) && floor((strtotime($row_orders['event_date'])  + 24*3600 - time())/(3600*24)) >= 0) || ($_GET['search_type'] == 1 && isset($_GET['arch']) && floor((strtotime($row_orders['event_date']) + 24*3600 - time())/(3600*24)) < 0)) {
            $ids .= $row_orders['id'].",";
						$delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
            if (strpos(strtolower($row_orders['select_type']), 'entreprise') === false) {
						  $total = $total + str_replace(",", ".", $row_orders['total']);
            } else {
              $total = $total + str_replace(",", ".", $row_orders['total'])*1.2;
            }
						echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
							<td>'.$row_orders['id'].'</td>
              <td class="text-center'.($_GET['status'] == 0 ? ' hide' : '').'" data-sort="'.$row_orders['facteur'].'">
                <a href="javascript:void(0)" class="btn '.($row_orders['facteur'] == 0 ? 'btn-default' : 'btn-danger').' btn-icon btn-circle facteur'.$row_orders['id'].'" title="Facteur" onClick="Facteur('.$row_orders['id'].')""><i class="fa fa-check-square"></i></a>
              </td>
              <td class="text-center">';
                if ($row_orders['invite'] == 1) {
                  echo"CLIENT";
                } else if ($row_orders['invite'] != 1 && trim($row_orders['gclid']) != "") {
                  echo"ADS";
                } else {
                  echo"SEO";
                }
              echo'</td>
              <td class="text-center">';
                if ($row_orders['user_id'] != 0) {
                  $result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = ".$row_orders['user_id']);
                  $row_users = mysqli_fetch_assoc($result_users);
                  echo $row_users['first_name'];
                } else {
                  echo"-";
                }
              echo'</td>
              <td class="text-center">';
                switch($row_orders['agency_id']) {
                  case 1: echo "Paris"; break;
                  case 2: echo "Bordeaux"; break;
                  default: echo "-"; break;
                }
              echo'</td>
							<td>'.date("d.m.Y", $row_orders['created_at']).'</td>
							 <td class="text-left">
                '.( $row_orders['societe'] ? '<b>'. $row_orders['societe']. '</b><br />' : '' ).'
                <p><b>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</b></p>
                Lieu de l’évènement : <b>'.$row_orders['event_place'].'</b><br />
                Type d’événement : <b>' . $row_orders['event_type'] . '</b><br />
                '.($row_orders['description'] != "" ? '<!--Informations complémentaires : <b>'.$row_orders['description'].'</b-->' : '').'
                <!--br />Comment avez-vous connu ShootnBox : <b>'.$row_orders['about'].'</b-->
              </td>
              <td class="text-center">
                <a href="mailto:'.$row_orders['email'].'" title="Envoyer un e-mail">'.$row_orders['email'].'</a
              </td>
              <td class="text-center">
                <a href="tel:'.$row_orders['phone'].'" title="Envoyer un e-mail">'.$row_orders['phone'].'</a>
              </td>
							<td class="text-center">
                '.$row_orders['box_type'];
                $result_bornes = mysqli_query($conn, "SELECT * FROM `bornes` WHERE `order_id` = ".$row_orders['id']);
                while($row_bornes = mysqli_fetch_assoc($result_bornes)) {
                  echo', '.$row_bornes['box_type'];
                }
              echo'</td>
							<td>'.$row_orders['select_type'].'</td>
							<td data-sort="'.strtotime($row_orders['event_date']).'">'.$row_orders['event_date'].'</td>
              <td data-sort="'.strtotime($row_orders['return_date']).'">'.$row_orders['return_date'].'</td>
              <td class="text-center"><a href="#" class="btn '.($row_orders['relaunch'] == 0 ? 'btn-default' : 'btn-success').' btn-icon btn-circle relaunch" data-id="'.$row_orders['id'].'" title="Validation"><i class="fa fa-refresh"></i></a></td>
							 <td class="text-center">'.(floor((strtotime($row_orders['event_date']) - time())/(3600*24)) > 0 ? floor((strtotime($row_orders['event_date']) - time())/(3600*24)) : "-").'</td>
							<td>'.$delivery.'</td>
							<td>'.str_replace(",Livraison", "", str_replace(",Retrait boutique", "", str_replace(", Livraison", "", str_replace(", Retrait boutique", "", $row_orders['selected_options'])))).'</td>
							<td class="text-center">'.($row_orders['select_type'] == 'Une entreprise' ? number_format(str_replace(",", ".", $row_orders['total']), 2, '.', '') : number_format(str_replace(",", ".", ($row_orders['total']/1.2)), 2, ',', '')).'€</td>
              <td class="text-center">'.($row_orders['select_type'] == 'Une entreprise' ? number_format(str_replace(",", ".", ($row_orders['total'] + $row_orders['total']*0.2)), 2, '.', '') : number_format(str_replace(",", ".", ($row_orders['total'])), 2, ',', '')).'€</td>
              <td class="text-center">'.($row_orders['discount'] != 0 ? $row_orders['discount'].'€' : '').'</td>
              <td class="text-center">'.$row_orders['promocode'].'</td>
							<td class="text-center'.($_GET['status'] == 0 ? ' hide' : '').'">
                '.($row_orders['devis'] != 0 ? '<a href="to_pdf.php?order_id='.$row_orders['id'].'&devis='.$row_orders['devis'].'" title="Devis" target="_blank">'."DE".$row_orders['devis'].'</a>' : '').'
                <a href="#" class="btn '.($row_orders['to_mail'] == 0 ? 'btn-default' : 'btn-success').' btn-icon btn-circle to-mail" data-id="'.$row_orders['id'].'" title="Mail"><i class="fa fa-envelope-o"></i></a></td>
              <td class="text-center">';
                if ($row_orders['image'] != "") {
                  echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120').'" alt="" /></a>
                  <a href="be/?image='.$row_orders['image'].'&order_id='.$row_orders['id'].'&data='.$row_orders['data'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier Template"><i class="fa fa-edit"></i></a>';
                }
                $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']);
                while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
                  echo'<br /><a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image'].'"><img src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120').'" alt="" /></a>
                  <a href="#" class="btn btn-danger btn-icon btn-circle btn-sm delete-image" data-id="'.$row_orders_images['id'].'" title="Supprimer le template"><i class="fa fa-close"></i></a>';
                }
                $result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `order_id` = ".$row_orders['id']);
                if (mysqli_num_rows($result_template_images) > 0) {
                  echo"<br /><small>";
                  $j = 1;
                  while($row_template_images = mysqli_fetch_assoc($result_template_images)) {
                    echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_template_images['image'].'">'.$j.'</a>';
                    if($j < mysqli_num_rows($result_template_images)) {
                      echo", ";
                    }
                    $j++;
                  }
                  echo"</small>";
                }
                if ($row_orders['without_photo_frame'] == 1) {
                  echo"Sans cadre photo";
                }
              echo'</td>
              <!--td class="text-center">'.(($row_orders['fa'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="fa_send text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="fa_send text-danger">NON</a>').'</td-->
              <!--td class="text-center"><a href="#" class="btn '.($row_orders['validation'] == 0 ? 'btn-default' : 'btn-success').' btn-icon btn-circle validation" data-id="'.$row_orders['id'].'" title="Validation"><i class="fa fa-check-square"></i></a></td-->
              <td class="text-center">';
                  echo'<a href="to_pdf.php?order_id='.$row_orders['id'].'" title="Facture" target="_blank">'.$row_orders['num_id'].'</a>';
                  if ($row_orders['status'] == 2) {
                    echo'<br /><a href="to_pdf.php?order_id='.$row_orders['id'].'&refund=true" class="btn btn-danger btn-icon btn-circle btn-sm" title="Remboursement" target="_blank"><i class="fa fa-close"></i></a>';
                  }
               echo'</td>';
              if ($_GET['status'] == 0) {
                echo'<td class="text-center">
                  <a href="javascript:void()" class="btn btn-warning btn-icon btn-circle btn-sm" title="Refusé" onClick="refuseOrder('.$row_orders['id'].');"><i class="fa fa-close"></i></a>
                </td>';
              }
               if ($_GET['status'] == -1) {
                echo'<td class="text-center">
                  '.$row_orders['refuse_title'].'
                </td>';
              }
							echo'
              <td>
                <a href="#" class="btn '.($row_orders['info'] == "" ? "btn-default" : "btn-confirm").' btn-icon btn-circle btn-sm info" data-id="'.$row_orders['id'].'" data-value="'.$row_orders['info'].'" title="Info"><i class="fa fa-info"></i></a>';
								if ($_GET['status'] == 2) {
									echo'<a href="#" class="btn '.($row_orders['send_mail'] == 0 ? 'btn-primary' : 'btn-success').' btn-icon btn-circle btn-sm send-sms" data-id="'.$row_orders['id'].'" title="SMS photo ftp"><i class="fa fa-paper-plane-o"></i></a>&nbsp;';
								}
								if ($_GET['status'] == 2) {
									echo'<a href="#" class="btn '.($row_orders['send_mail'] == 0 ? 'btn-primary' : 'btn-success').' btn-icon btn-circle btn-sm send-mail" data-id="'.$row_orders['id'].'" title="Mail photo ftp"><i class="fa fa-envelope-o"></i></a>&nbsp;';
								}
								if ($row_orders['image'] != "") {
									echo'<a href="#" class="btn btn-success btn-icon btn-circle btn-sm eraser" data-id="'.$row_orders['id'].'" title="Supprimer le template"><i class="fa fa-eraser"></i></a>&nbsp;';
								}
								echo'
                <a href="mail.php?order_id='.$row_orders['id'].'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Mail HTML" target="_blank"><i class="fa fa-envelope-o"></i></a>&nbsp;
                <a href="add_order.php?order_id='.$row_orders['id'].'" class="btn btn-info btn-icon btn-circle btn-sm" title="Copie"><i class="fa fa-copy"></i></a>
                <a href="edit_order.php?order_id='.$row_orders['id'].'&status='.mysqli_real_escape_string($conn, $_GET['status']).(isset($_GET['arch']) ? '&arch=true' : '').'" class="btn btn-warning btn-icon btn-circle btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>
                &nbsp;<a class="btn btn-inverse btn-icon btn-circle btn-sm" title="Erreur" onClick="errorOrder('.$row_orders['id'].');"><i class="fa fa-flash"></i></a>
								&nbsp;<a class="btn btn-danger btn-icon btn-circle btn-sm" title="Supprimer" onClick="deleteOrder('.$row_orders['id'].');"><i class="fa fa-close"></i></a>
							</td>
						</tr>';
						$i++;
					}
				}
				?>
			</tbody>
			<tfoot>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th><?php echo number_format($total, 2, '.', ''); ?>€</th>
          <th></th>
          <th<?php if ($_GET['status'] == 0) { echo' class="hide"'; } ?>></th>
          <th></th>
          <th></th>
          <th></th>
        </tr>
      </tfoot>
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

    $('.show').on('click', function(event) {
      event.preventDefault();
      if ($('.start_date').val() != '' && $('.end_date').val() != '') {
        window.location.href = 'orders_list.php?status=<?php echo mysqli_real_escape_string($conn, $_GET['status']); if (isset($_GET['arch'])) { echo"&arch=true"; } ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&search_type=' + $('.search_type').val() + '&facteur=' + $('.facteur').val();
      }
    });

    $('.user_id').on('change', function(event) {
      event.preventDefault();
      if ($('.start_date').val() != '' && $('.end_date').val() != '') {
        window.location.href = 'orders_list.php?status=<?php echo mysqli_real_escape_string($conn, $_GET['status']); if (isset($_GET['arch'])) { echo"&arch=true"; } ?>&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&user_id=' + $('.user_id').val() + '&search_type=' + $('.search_type').val() + '&facteur=' + $('.facteur').val();
      } else {
        window.location.href = 'orders_list.php?status=<?php echo mysqli_real_escape_string($conn, $_GET['status']); if (isset($_GET['arch'])) { echo"&arch=true"; } ?>&user_id=' + $('.user_id').val() + '&search_type=' + $('.search_type').val() + '&facteur=' + $('.facteur').val();
      }
    });

		$('#data-table').DataTable({
			scrollY:        475,
      scrollX:        true,
      scrollCollapse: true,
      fixedHeader:           {
        header: true,
        footer: true
      },
			paging: false,
			responsive: false,
			order: [[ 0, 'desc' ]],
			columnDefs: [{
					targets: 'no-sort',
					orderable: false,
				}
			],
			dom: 'Bfrtip',
			buttons: [
				'excel',
      <?php if (isset($_GET['facteur']) && $_GET['facteur'] == 1 && $ids != "") { ?>
				{
					text: '<i class="fa fa-download"></i> Exporter',
					action: function (e, dt, node, config) {
						window.location.href = 'multi_pdf.php?orders_ids=<?php echo trim($ids, ","); ?>';
					}
				},
      <?php } ?>
        {
          text: '<i class="fa fa-plus"></i> Ajouter',
          action: function (e, dt, node, config) {
            window.location.href = 'add_order.php';
          }
        }
			]
		});

		$('.fancybox').fancybox({
			padding: 0,
			helpers: {
				overlay: {
					locked: false
				}
			}
		});

	});

	 $('.fa_send').on('click', function(event) {
      event.preventDefault();
      if ($(this).hasClass('text-success')) {
        $(this).addClass('text-danger');
        $(this).removeClass('text-success');
        $(this).html('NON');
        var status = 0;
      } else {
        $(this).addClass('text-success');
        $(this).removeClass('text-danger');
        $(this).html('OUI');
        var status = 1;
      }

      var order_id = $(this).attr('data-id');

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'fa', id: order_id, fa: status},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });

	$('.send-mail').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id'),
      ele = $(this);
		swal({
			title: 'Êtes-vous sûr?',
			text: '',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function(data) {
			if (data.value) {
				ele.addClass('btn-success');
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'send_mail', id: order_id},
					cache: false,
					success: function(responce) {
						if (responce == 'done'){
							swal({
								title: 'Fait!',
								text: 'Email a été envoyé avec succès.',
								type: 'success',
								confirmButtonColor: '#348fe2',
								confirmButtonText: 'Fermer',
								closeOnConfirm: true
							});
						} else {
							showError(responce);
						}
					}
				});
			} else {
				//
			}
		}, function(dismiss) {
			//
		});
	});

  $('.validation').on('click', function(event) {
    event.preventDefault();
    var order_id = $(this).attr('data-id'),
        ele = $(this);

    swal({
      title: 'Êtes-vous sûr?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Oui',
      cancelButtonColor: '#929ba1',
      cancelButtonText: 'Annuler'
    }).then(function(data) {
      if (data.value) {
        if ((ele).hasClass('btn-success')) {
          ele.removeClass('btn-success');
          ele.addClass('btn-default');
          var validation = 0;
        } else {
          ele.removeClass('btn-default');
          ele.addClass('btn-success');
          var validation = 1;
        }

        $.ajax({
          type: 'POST',
          url: 'd26386b04e.php',
          data: {event: 'validation', id: order_id, validation: validation},
          cache: false,
          success: function(response) {
            console.log(response);
          }
        });
      } else {
        //
      }

    });
  });

  $('.relaunch').on('click', function(event) {
    event.preventDefault();
    var order_id = $(this).attr('data-id'),
        ele = $(this);

    swal({
      title: 'Êtes-vous sûr?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Oui',
      cancelButtonColor: '#929ba1',
      cancelButtonText: 'Annuler'
    }).then(function(data) {
      if (data.value) {
        if ((ele).hasClass('btn-success')) {
          ele.removeClass('btn-success');
          ele.addClass('btn-default');
          var relaunch = 0;
        } else {
          ele.removeClass('btn-default');
          ele.addClass('btn-success');
          var relaunch = 1;
        }

        $.ajax({
          type: 'POST',
          url: 'd26386b04e.php',
          data: {event: 'relaunch', id: order_id, relaunch: relaunch},
          cache: false,
          success: function(response) {
            console.log(response);
          }
        });
      } else {
        //
      }

    });
  });

  $('.to-mail').on('click', function(event) {
    event.preventDefault();
    var order_id = $(this).attr('data-id'),
        ele = $(this);

    swal({
      title: 'Êtes-vous sûr?',
      text: '',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Oui',
      cancelButtonColor: '#929ba1',
      cancelButtonText: 'Annuler'
    }).then(function(data) {
      if (data.value) {
        if ((ele).hasClass('btn-success')) {
          ele.removeClass('btn-success');
          ele.addClass('btn-default');
          var to_mail = 0;
        } else {
          ele.removeClass('btn-default');
          ele.addClass('btn-success');
          var to_mail = 1;
        }

        $.ajax({
          type: 'POST',
          url: 'd26386b04e.php',
          data: {event: 'to_mail', id: order_id, to_mail: to_mail},
          cache: false,
          success: function(response) {
            console.log(response);
          }
        });
      } else {
        //
      }

    });
  });

	$('.send-sms').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id'),
        ele = $(this);
		swal({
			title: 'Êtes-vous sûr?',
			text: '',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function(data) {
			if (data.value) {
        ele.addClass('btn-success');
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'send_sms', id: order_id},
					cache: false,
					success: function(responce) {
						if (responce == 'done'){
							swal({
								title: 'Fait!',
								text: 'SMS a été envoyé avec succès.',
								type: 'success',
								confirmButtonColor: '#348fe2',
								confirmButtonText: 'Fermer',
								closeOnConfirm: true
							});
						} else {
							showError(responce);
						}
					}
				});

			} else {
				//
			}
		}, function(dismiss) {
			//
		});
	});

	$('.eraser').on('click', function(event) {
		event.preventDefault();
		var order_id = $(this).attr('data-id')

		$.ajax({
			type: 'POST',
			url: 'd26386b04e.php',
			data: {event: 'eraser', id: order_id},
			cache: false,
			success: function(responce) {
				if (responce == 'done'){
					window.location.reload();
				} else {
					showError(responce);
				}
			}
		});
	});

  $('.delete-image').on('click', function(event) {
    event.preventDefault();
    var order_image_id = $(this).attr('data-id')

    $.ajax({
      type: 'POST',
      url: 'd26386b04e.php',
      data: {event: 'delete_image', id: order_image_id},
      cache: false,
      success: function(responce) {
        if (responce == 'done'){
          window.location.reload();
        } else {
          showError(responce);
        }
      }
    });
  });


  $('.info').on('click', function(event) {
    event.preventDefault();
    var ele = $(this),
        order_id = ele.attr('data-id'),
        inputValue = ele.attr('data-value');
    swal({
      title: 'Entrez les informations',
      input: 'textarea',
      inputValue: inputValue,
      inputPlaceholder: 'Entrez les informations',
      showCancelButton: true,
      confirmButtonColor: '#7066e0',
      confirmButtonText: 'Sauvegarder',
      cancelButtonColor: '#929ba1',
      cancelButtonText: 'Annuler'
    }).then(function(data) {
      if (data.value) {
        $.ajax({
          type: 'POST',
          url: 'd26386b04e.php',
          data: {event: 'save_info', id: order_id, info: data.value},
          cache: false,
          success: function(responce) {
            if (responce == 'done'){
              ele.attr('data-value', data.value);
            } else {
              showError(responce);
            }

            if (data.value != '') {
              ele.removeClass('btn-default');
              ele.addClass('btn-confirm');
            } else {
              ele.removeClass('btn-confirm');
              ele.addClass('btn-default');
            }
          }
        });
      } else {
        //
      }
    }, function(dismiss) {
      //
    });



  });


	// Удаление пользователя
	function deleteOrder(id) {
		swal({
			title: 'Êtes-vous sûr,',
			text: 'de vouloir supprimer cet commande?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			confirmButtonText: 'Oui, effacez!',
			cancelButtonColor: '#929ba1',
			cancelButtonText: 'Annuler'
		}).then(function(data) {
			if (data.value) {
				$.ajax({
					type: 'POST',
					url: 'd26386b04e.php',
					data: {event: 'delete_order', id: id},
					cache: false,
					success: function(responce){
						$('#tr' + id).remove();
					}
				});
			} else {
				//
			}
		}, function(dismiss) {
			//
		});
	}

  // Удаление пользователя
  function errorOrder(id) {
    swal({
      title: 'Êtes-vous sûr,',
      text: 'de vouloir marquer cette commande comme une erreur ?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Oui',
      cancelButtonColor: '#929ba1',
      cancelButtonText: 'Annuler'
    }).then(function(data) {
      if (data.value) {
        $.ajax({
          type: 'POST',
          url: 'd26386b04e.php',
          data: {event: 'error_order', id: id},
          cache: false,
          success: function(responce){
            $('#tr' + id).remove();
          }
        });
      } else {
        //
      }
    }, function(dismiss) {
      //
    });
  }

  function refuseOrder(id) {
    swal({
      html: '<select class="refuse_title">' +
        '<option value="">Choisir une raison ...</option>' +
        '<option value="Trop cher">Trop cher</option>' +
        '<option value="Livraison cher">Livraison cher</option>' +
        '<option value="Impos de retrait">Impos de retrait</option>' +
        '<option value="Ne repond pas">Ne repond pas</option>' +
        '<option value="Presta annulé">Presta annulé</option>' +
        '<option value="KM sup cher">KM sup cher</option>' +
        '<option value="Orga. Event">Orga. Event</option>' +
        '<option value="Aix et marseille">Aix et marseille</option>' +
        '<option value="Brest">Brest</option>' +
        '<option value="Centre de france">Centre de france</option>' +
        '<option value="Lille">Lille</option>' +
        '<option value="Lyon">Lyon</option>' +
        '<option value="Strasbourg">Strasbourg </option>' +
        '<option value="Toulouse">Toulouse</option>' +
      '</select><br /><br /><h4><b>Êtes-vous sûr, de vouloir refusé cet commande ?</b></h4>',
      type: '',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Oui, refusé!',
      cancelButtonColor: '#929ba1',
      cancelButtonText: 'Annuler'
    }).then(function(data) {
      if (data.value) {
        if ($('.refuse_title').val() != '') {
          $.ajax({
            type: 'POST',
            url: 'd26386b04e.php',
            data: {event: 'refuse_order', id: id, refuse_title: $('.refuse_title').val()},
            cache: false,
            success: function(responce){
              $('#tr' + id).remove();
            }
          });
        } else {
          showError('Choisir une raison !');
        }
      } else {
        //
      }
    }, function(dismiss) {
      //
    });
  }

  function Facteur(order_id) {
      var ele = $('.facteur' + order_id) ;

      swal({
        title: 'Êtes-vous sûr?',
        text: 'de mettre cette facture en Factor ?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonColor: '#929ba1',
        cancelButtonText: 'Annuler'
      }).then(function(data) {
        if (data.value) {
          if ((ele).hasClass('btn-danger')) {
            ele.removeClass('btn-danger');
            ele.addClass('btn-default');
            var facteur = 0;
          } else {
            ele.removeClass('btn-default');
            ele.addClass('btn-danger');
            var facteur = 1;
          }

          $.ajax({
            type: 'POST',
            url: 'd26386b04e.php',
            data: {event: 'facteur', id: order_id, facteur: facteur},
            cache: false,
            success: function(response) {
              //console.log(response);
            }
          });
      } else {
          //
      }
    });
  }
</script>

<?php include("end.php"); ?>