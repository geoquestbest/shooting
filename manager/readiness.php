<?php
	$page_title = "Préparation";
	include("header.php");
?>
<!-- begin panel -->
<style>
  .dataTables_scrollHeadInner {
    padding-left: 0 !important;
  }
  th.no-sort {
    padding: 10px 15px !important;
  }
</style>
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
          <th class="no-sort" style="width: auto;">Société</th>
					<th class="no-sort" style="width: auto;">Nom Prenom</th>
          <th class="no-sort" style="width: auto;">Acompte</th>
					<th class="no-sort" style="width: auto;">Borne</th>
          <th class="no-sort" style="width: 60px;">N</th>
          <th style="width: auto;">Maquette</th>
          <th class="no-sort" style="width: auto;">Event</th>
          <th class="no-sort" style="width: auto;">Borne prête</th>
          <th class="no-sort" style="width: auto;">Data</th>
					<th class="no-sort" style="width: auto;">Livraison</th>
          <th class="no-sort" style="width: auto;">Infos Livraison</th>
          <th class="no-sort" style="width: 75px;">Configuration</th>
          <th class="no-sort" style="width: 50px;">Code</th>
          <th class="no-sort" style="width: 50px;">Connexion</th>
          <th class="no-sort" style="width: auto;">HL</th>
					<th class="no-sort" style="width: auto;">Mes options</th>
          <th class="no-sort" style="width: 120px;">LivreurA</th>
          <th class="no-sort" style="width: 120px;">LivreurR</th>
          <th class="no-sort" style="width: auto;">Livraison Prix, €</th>
					<th class="no-sort" style="width: 250px;">Remarque</th>
          <th class="no-sort" style="width: auto;"></th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>
</div>
<!-- end panel -->

<?php include("footer.php"); ?>

<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/RowReorder/css/rowReorder.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="assets/plugins/fancyBox/source/jquery.fancybox.css?v=2.1.6" rel="stylesheet" type="text/css" media="screen" />
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
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Select/js/dataTables.select.min.js"></script>
<script src="assets/plugins/DataTables/extensions/RowReorder/js/dataTables.rowReorder.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/fancyBox/source/jquery.fancybox.pack.js?v=2.1.6"></script>
<script src="assets/plugins/switchery/switchery.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {

		App.init();

    $('#data-table').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url': 'readiness_ajax.php<?php echo (isset($_GET['arch']) ? '?arch=true' : '') ?>',
      },
      'drawCallback': function(settings) {
        console.log(settings.json.order);
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
      },
      'columns': [
        { data: 'date' },
        { data: 'agency' },
        { data: 'societe' },
        { data: 'name' },
        { data: 'acompte' },
        { data: 'borne' },
        { data: 'box_id' },
        { data: 'images' },
        { data: 'event' },
        { data: 'box_ready' },
        { data: 'data_ready' },
        { data: 'delivery' },
        { data: 'delivery_valid' },
        { data: 'configure' },
        { data: 'password' },
        { data: 'login' },
        { data: 'event_time' },
        { data: 'selected_options' },
        { data: 'select1' },
        { data: 'select2' },
        { data: 'delivery_price' },
        { data: 'comment' },
        { data: 'on_off' },
      ],
      pageLength: 50,
      scrollY:        475,
      scrollX:        true,
      scrollCollapse: true,
      fixedHeader:           {
        header: true,
        footer: true
      },
      responsive: false,
      ordering: true,
      order: [[0, 'asc']],
      columnDefs: [
        {
          'targets': 'no-sort',
          'orderable': false,
        },
        { className: 'text-center', targets: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22] },
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
	function offOrder(id, ele) {
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
						$(ele).parents('tr').remove();
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

  function onOrder(id, ele) {
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
            $(ele).parents('tr').remove();
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