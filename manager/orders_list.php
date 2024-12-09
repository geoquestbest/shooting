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
    $start_date = date("d.m.Y", strtotime("01.01.".(date("Y", time()) - 2)));
    $end_date = date("d.m.Y", strtotime("31.12.".(date("Y", time()) + 2)));
  }
?>
<style>
  .dataTables_scrollHeadInner {
    padding-left: 0 !important;
  }
</style>
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
          <th style="width: 75px;">ID</th>
          <?php if ($_GET['status'] == 2) { ?> <th class="no-sort">Factor</th><?php } ?>
          <th class="no-sort" style="width: 75px;">Prov</th>
          <th class="no-sort" style="width: auto;">Commercial</th>
          <th class="no-sort" style="width: auto;">Agence</th>
          <th class="no-sort" style="width: auto;">Date</th>
          <th class="no-sort">Utilisateur</th>
          <th class="no-sort">E-mail</th>
          <th class="no-sort">Téléphone</th>
          <th class="no-sort">Borne</th>
          <th class="no-sort">Type</th>
          <th class="no-sort">Date de l’évènement</th>
          <th class="no-sort">Date et l’heure du retour</th>
          <th class="no-sort" style="width: auto;">Relance</th>
          <th class="no-sort" style="width: auto;">J-X</th>
          <th class="no-sort">Livraison</th>
          <th class="no-sort">Mes options</th>
          <th class="no-sort">Tarif HT</th>
          <th class="no-sort">Tarif TTC</th>
          <th class="no-sort">Remise</th>
          <th class="no-sort">Code promo</th>
          <th style="width: 75px;">Devis</th>
          <th class="no-sort" style="width: 120px;">Templates</th>
          <?php if ($_GET['status'] == 2) {  ?><th class="no-sort" style="width: 50px;"><?php echo $title ?></th><?php } ?>
          <?php if ($_GET['status'] == 0) {  ?><th class="no-sort" style="width: 50px;">Refusé</th><?php } ?>
          <?php if ($_GET['status'] == -1) {  ?><th class="no-sort" style="width: auto">Refusé titre</th><?php } ?>
          <th class="no-sort" style="width: 160px!important;">Actions</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
        <tr>
          <th></th>
          <?php if ($_GET['status'] == 2) { ?> <th></th><?php } ?>
          <th style="width: 75px;"></th>
          <th style="width: auto;"></th>
          <th style="width: auto;"></th>
          <th style="width: auto;"></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th></th>
          <th style="width: auto;"></th>
          <th style="width: auto;"></th>
          <th></th>
          <th></th>
          <th></th>
          <th class="total"></th>
          <th></th>
          <th></th>
          <th style="width: 75px;"></th>
          <th class="no-sort" style="width: 120px;"></th>
          <?php if ($_GET['status'] == 2) {  ?><th class="no-sort" style="width: 50px;"></th><?php } ?>
          <?php if ($_GET['status'] == 0) {  ?><th style="width: 50px;"></th><?php } ?>
          <?php if ($_GET['status'] == -1) {  ?><th style="width: auto"></th><?php } ?>
          <th class="no-sort" style="width: 160px!important;"></th>
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
  var ids = '';

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
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      'ajax': {
        'url': 'orders_ajax.php?status=<?php echo mysqli_real_escape_string($conn, $_GET['status']); if (isset($_GET['arch'])) { echo"&arch=true"; }?>&user_id=' + $('.user_id').val() + '&start_date=' + $('.start_date').val() + '&end_date=' + $('.end_date').val() + '&search_type=' + $('.search_type').val() + '&facteur=' + $('.facteur').val(),
      },
      'drawCallback': function(settings) {
          $('.total').html('<span class="text-danger">' + settings.json.total + '</span>');
          ids = settings.json.ids;

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
      },
      'columns': [
        { data: 'id' },
        <?php if ($_GET['status'] == 2) { ?>
          { data: 'facteur' },
        <?php } ?>
        { data: 'invite' },
        { data: 'commercial' },
        { data: 'agency' },
        { data: 'date' },
        { data: 'customer' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'box_type' },
        { data: 'select_type' },
        { data: 'event_date' },
        { data: 'return_date' },
        { data: 'relaunch' },
        { data: 'jx' },
        { data: 'delivery' },
        { data: 'selected_options' },
        { data: 'tarif_ht' },
        { data: 'tarif_ttc' },
        { data: 'discount' },
        { data: 'code_promo' },
        { data: 'devis' },
        { data: 'templates' },
        <?php if ($_GET['status'] == 2) {  ?>
          { data: 'facture' },
        <?php } ?>
        <?php if ($_GET['status'] == 0 || $_GET['status'] == -1) { ?>
          { data: 'refuse' },
        <?php } ?>
        { data: 'actions' },
      ],
      pageLength: 50,
      scrollY:        475,
      scrollX:        true,
      scrollCollapse: false,
      fixedHeader:           {
        header: true,
        footer: true
      },
      responsive: false,
      ordering: true,
      order: [[0, 'desc']],
      columnDefs: [
        {
          'targets': 'no-sort',
          'orderable': false,
        },
        <?php if ($_GET['status'] == 2) { ?>
          { className: 'text-center', targets: [0, 1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24] },
        <?php } else { ?>
          { className: 'text-center', targets: [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23] },
        <?php } ?>
      ],
      dom: 'Bfrtip',
      buttons: [
      <?php if (isset($_GET['facteur']) && $_GET['facteur'] == 1) { ?>
        {
          text: '<i class="fa fa-download"></i> Excel',
          action: function (e, dt, node, config) {
            window.location.href = 'factor_xls.php?orders_ids=' + ids;
          }
        },
      <?php } ?>
      <?php if (isset($_GET['facteur']) && $_GET['facteur'] == 1) { ?>
        {
          text: '<i class="fa fa-download"></i> Exporter',
          action: function (e, dt, node, config) {
            window.location.href = 'multi_pdf.php?orders_ids=' + ids;
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

  function factureEnabled(id) {
    $.ajax({
      type: 'POST',
      url: 'd26386b04e.php',
      data: {event: 'facture_enabled', id: id, enabled: $('.facture_enabled' + id).is(':checked') ? 1 : 0},
      cache: false,
      success: function(responce) {

      }
    });
  }
</script>

<?php include("end.php"); ?>