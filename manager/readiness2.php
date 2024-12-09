<?php
	$page_title = "Préparation";
	include("header.php");
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
		<table id="data-table" class="table table-striped table-bordered orders-table nowrap" width="100%">
			<thead>
				<tr>
					<th style="width: auto;">Date</th>
          <th style="width: auto;">Agence</th>
          <th style="width: auto;">Société</th>
					<th style="width: auto;">Nom Prenom</th>
          <th style="width: auto;">Acompte</th>
					<th style="width: auto;">Borne</th>
          <th style="width: 60px;">N</th>
          <th style="width: auto;">Maquette</th>
          <th style="width: auto;">Event</th>
          <th style="width: auto;">Borne prête</th>
          <th style="width: auto;">Data</th>
					<th style="width: auto;">Livraison</th>
          <th style="width: auto;">Infos Livraison</th>
          <th class="no-sort" style="width: 75px;">Configuration</th>
          <th class="no-sort" style="width: 50px;">Code</th>
          <th class="no-sort" style="width: 50px;">Connexion</th>
          <th style="width: auto;">HL</th>
					<th style="width: auto;">Mes options</th>
          <th style="width: 120px;">LivreurA</th>
          <th style="width: 120px;">LivreurR</th>
          <th style="width: auto;">Livraison Prix, €</th>
					<th style="width: 250px;">Remarque</th>
          <th class="no-sort" style="width: auto;"></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `status` = 2 AND `off` = ".(isset($_GET['arch']) ? 1 : 0));
				$i=1;
				while($row_orders = mysqli_fetch_assoc($result_orders)) {
					switch(strtolower(trim($row_orders['box_type']))) {
						case "ring": $bg_color = "#ea672a"; break;
						case "vegas": $bg_color = "#e41082"; break;
						case "miroir": $bg_color = "#08a6dd"; break;
						case "spinner_360": $bg_color = "#20a33e"; break;
						case "réalité_virtuelle": $bg_color = "#5d4e98"; break;
					}
          $result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$row_orders['id']);
					$delivery = (mb_strpos($row_orders['selected_options'], 'Retrait boutique') || $row_orders['delivery_options'] == "") ? 'Retrait boutique' : 'Livraison';
					echo'<tr id="tr'.$row_orders['id'].'" class="gradeX';if ($i%2 == 0) {echo' odd';} else {echo' even';} echo'">
						<td data-sort="'.strtotime($row_orders['event_date']).'">'.$row_orders['event_date'].'</td>
            <td class="text-center">';
                switch($row_orders['agency_id']) {
                  case 1: echo "Paris"; break;
                  case 2: echo "Bordeaux"; break;
                  default: echo "-"; break;
                }
              echo'</td>
            <td>'.$row_orders['societe'].'</td>
						<td>'.$row_orders['last_name'].' '.$row_orders['first_name'].'</td>
            <td class="text-center">'.((($row_orders['deposit'] > 0 && $row_orders['payment_status'] > 0)) ? "OK" : "").(($row_orders['select_type'] == "Une entreprise") ? "S" : "").'</td>
						<td class="text-center" style="background: '.$bg_color.'; color: #fff;">'.$row_orders['box_type'].'</td>
            <td class="text-center">
              <!--input type="text" value="'.$row_orders['box_id'].'" class="box_num form-control" data-id="'.$row_orders['id'].'" style="width: 60px;" /-->
              '.($row_orders['box_id'] != "null" ? $row_orders['box_id'] : "").'
            </td>
            <td class="text-center">';
                if ($row_orders['image'] != "") {
                  echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image'].'">OK</a>';
                }
                $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']);
                while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
                  echo'<a class="fancybox" href="'.ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image'].'">ОК</a>';
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
            echo'</td>
            <td class="text-center">'.(((strtolower(trim($row_orders['event_type'])) == "ring" && mysqli_num_rows($result_configure_orders) > 0 && $row_orders['image'] != '' && $row_orders['data'] != '') || $row_orders['event_ready'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="event_ready text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="event_ready text-danger">NON</a>').'</td>
            <td class="text-center">'.(($row_orders['box_ready'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="box_ready text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="box_ready text-danger">NON</a>').'</td>
            <td class="text-center">'.(($row_orders['data_ready'] == 1) ? '<a href="#" data-id="'.$row_orders['id'].'" class="data_ready text-success">OUI</a>' : '<a href="#" data-id="'.$row_orders['id'].'" class="data_ready text-danger">NON</a>').'</td>
					  <td class="text-center" style="background: '.(($delivery == "Livraison") ? "#c00" : "#20a33e").'; color: #fff;">
              '.$delivery;
              if ($row_orders['description'] != "") {
                echo'<br /><a class="btn btn-default btn-icon btn-circle btn-sm" title="Info" onClick="infoOrder(\''.str_replace("\n", "<br />", $row_orders['description']).'\');"><i class="fa fa-info"></i></a>';
              }
            echo'</td>
            <td class="text-center">
              '.($row_orders['delivery_valid'] == 1 ? 'OK' : '').'
            </td>
            <td class="text-center">
              <a href="configure.php?order_id='.$row_orders['id'].'&back_url='.$_SERVER['REQUEST_URI'].'" class="btn btn-'.((mysqli_num_rows($result_configure_orders) == 0) ? 'primary' : 'warning').' btn-icon btn-circle btn-lg" title="Configuration 1"><i class="fa fa-edit"></i></a>&nbsp;';
              if ($row_orders['image'] != "") {
                echo'<a href="be/?image='.$row_orders['image'].'&order_id='.$row_orders['id'].'" class="btn btn-'.(($row_orders['data'] == "") ? 'success' : 'danger').' btn-icon btn-circle btn-lg" title="Configuration 2"><i class="fa fa-edit"></i></a>';
              }
            echo'</td>
            <td class="text-center">'.$row_orders['password'].'</td>
            <td class="text-center"><a  class="btn btn-danger btn-icon btn-circle btn-lg" href="../album/?login='.$row_orders['num_id'].'&password='.$row_orders['password'].'" target="_blank" title="Connexion"><i class="fa fa-key"></i></a></td>
            <td class="text-center">';
              switch($row_orders['event_time']) {
                case 7: echo"7h à 19h"; break;
                case 8: echo"13h à 19h"; break;
                default: echo"-"; break;
              }
            echo'</td>
						<td>'.str_replace(", Livraison", "", str_replace(", Retrait boutique", "", $row_orders['selected_options'])).'</td>';

            if (mb_strpos(strtolower(trim($row_orders['selected_options'])), 'kilomètres supplémentaires') !== false) {
              foreach (explode(",", $row_orders['selected_options']) as $key => $value) {
                $options_arr = explode(":", $value);
                if (mb_strpos(strtolower(trim($options_arr[0])), 'kilomètres supplémentaires') !== false) {
                  $data_km = $options_arr[1] * $options_arr[2];
                }
              }
            } else {
              $data_km = 0;
            }

            echo'<td class="text-center">
              <select class="courier form-control" data-id="'.$row_orders['id'].'" data-km="'.($data_km/2).'" style="width: 120px;">
                <option value=""'.($row_orders['courier'] == "" ? ' selected' : '').'>Choisir...</option>
                <option value="Livreur"'.($row_orders['courier'] == "Livreur" ? ' selected' : '').'>Livreur</option>
                <option value="Shoontbox"'.($row_orders['courier'] == "Shoontbox" ? ' selected' : '').'>Shoontbox</option>
              </select>
            </td>

            <td class="text-center">
              <select class="courier_r form-control" data-id="'.$row_orders['id'].'" data-km="'.($data_km/2).'" style="width: 120px;">
                <option value=""'.($row_orders['courier_r'] == "" ? ' selected' : '').'>Choisir...</option>
                <option value="Livreur"'.($row_orders['courier_r'] == "Livreur" ? ' selected' : '').'>Livreur</option>
                <option value="Shoontbox"'.($row_orders['courier_r'] == "Shoontbox" ? ' selected' : '').'>Shoontbox</option>
              </select>
            </td>

            <td class="text-center"><input type="text" value="'.$row_orders['delivery_price'].'" class="delivery_price delivery_price'.$row_orders['id'].' form-control" data-id="'.$row_orders['id'].'" style="width: 75px;" /></td>

            <td class="text-center"><input type="number" value="'.$row_orders['comment'].'" class="comment form-control" data-id="'.$row_orders['id'].'" style="width: 240px;" /></td>';
            if (!isset($_GET['arch'])) {
              echo'<td class="text-center">
                <a class="btn btn-danger btn-icon btn-circle btn-sm" title="Fermer la commande" onClick="offOrder('.$row_orders['id'].');"><i class="fa fa-close"></i></a>
              </td>';
            }
            if (isset($_GET['arch'])) {
              echo'<td class="text-center">
                <a class="btn btn-primary btn-icon btn-circle btn-sm" title="Rétablir la commande" onClick="onOrder('.$row_orders['id'].');"><i class="fa fa-external-link"></i></a>
              </td>';
            }
					echo'</tr>';
					$i++;
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
<link href="assets/plugins/switchery/switchery.min.css" rel="stylesheet">
<style>
  a {
    text-decoration: none !important;
  }

  th {
    padding: 10px 22px 10px 2px !important;
  }
</style>
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
<script src="assets/plugins/switchery/switchery.min.js"></script>
<script src="assets\js\apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {

		App.init();


    $('.event_ready').on('click', function(event) {
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

      var order_id = $(this).attr('data-id')

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'event_ready', id: order_id, status: status},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });

    $('.box_ready').on('click', function(event) {
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

      var order_id = $(this).attr('data-id')

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'box_ready', id: order_id, status: status},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });

    $('.data_ready').on('click', function(event) {
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

      var order_id = $(this).attr('data-id')

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'data_ready', id: order_id, status: status},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });

    $('.comment').on('blur', function(event) {

      var order_id = $(this).attr('data-id'),
          text = $(this).val();

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'comment', id: order_id, text: text},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });

    $('.courier').on('change', function(event) {

      var order_id = $(this).attr('data-id'),
          data_km = $(this).attr('data-km'),
          text = $(this).val();

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'courier', id: order_id, text: text, },
        cache: false,
        success: function(responce) {
          if (responce == 'done') {
            if (text != '') {
              $('.delivery_price' + order_id).val('55/' + data_km);
            } else {
              $('.delivery_price' + order_id).val('');
            }
            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'delivery_price', id: order_id, text: $('.delivery_price' + order_id).val()},
              cache: false,
              success: function(responce) {
                if (responce == 'done'){
                } else {
                  showError(responce);
                }
              }
            });
          } else {
            showError(responce);
          }
        }
      });
    });

    $('.courier_r').on('change', function(event) {

      var order_id = $(this).attr('data-id'),
          data_km = $(this).attr('data-km'),
          text = $(this).val();

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'courier_r', id: order_id, text: text},
        cache: false,
        success: function(responce) {
          if (responce == 'done') {
            if (text != '') {
              $('.delivery_price' + order_id).val('55/' + data_km);
            } else {
              $('.delivery_price' + order_id).val('');
            }
            $.ajax({
              type: 'POST',
              url: 'd26386b04e.php',
              data: {event: 'delivery_price', id: order_id, text: $('.delivery_price' + order_id).val()},
              cache: false,
              success: function(responce) {
                if (responce == 'done'){
                } else {
                  showError(responce);
                }
              }
            });
          } else {
            showError(responce);
          }
        }
      });
    });

    $('.delivery_price').on('change', function(event) {

      var order_id = $(this).attr('data-id'),
          text = $(this).val();

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'delivery_price', id: order_id, text: text},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });


    $('.paid').on('click', function(event) {
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

      var order_id = $(this).attr('data-id')

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'paid', id: order_id, paid: status},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
    });



    $('.box_num').on('blur', function(event) {

      var order_id = $(this).attr('data-id'),
          text = $(this).val();

      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'box_num', id: order_id, text: text},
        cache: false,
        success: function(responce) {
          if (responce == 'done'){
          } else {
            showError(responce);
          }
        }
      });
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
			order: [[ 0, 'asc' ]],
			columnDefs: [{
					targets: 'no-sort',
					orderable: false,
				}
			],
			dom: 'Bfrtip',
			buttons: [
				'csv', 'excel', 'pdf', 'print'
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




	// Удаление пользователя
	function offOrder(id) {
		swal({
			title: 'Êtes-vous sûr,',
			text: 'de vouloir fermer la commande ?',
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
					data: {event: 'off_order', id: id},
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

  function infoOrder(text) {
    swal({
      html: text,
      type: 'info',
      showCancelButton: false,
      confirmButtonColor: '#d33',
      confirmButtonText: 'OK'
    }).then(function(data) {

    }, function(dismiss) {
      //
    });
  }

  function onOrder(id) {
    swal({
      title: 'Êtes-vous sûr,',
      text: 'de vouloir rétablir la commande ?',
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
          data: {event: 'on_order', id: id},
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
</script>

<?php include("end.php"); ?>