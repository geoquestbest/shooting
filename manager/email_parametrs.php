<?php
  $page_title = "Email";
  include("header.php");
?>

<!-- begin panel -->
<div class="panel panel-inverse" data-sortable-id="form-stuff-1">
  <div class="panel-heading">
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" data-original-title="" title=""><i class="fa fa-minus"></i></a>
    </div>
    <h4 class="panel-title">Email</h4>
  </div>
  <div class="panel-body">
    <form class="form-horizontal edit-email">
      <div class="form-group">
        <label class="col-md-3 control-label">Sujet du mail</label>
        <div class="col-md-9">
          <input type="text" class="form-control email_title" value="<?php echo htmlspecialchars_decode($row_settings['email_title'], ENT_QUOTES) ?>" placeholder="Sujet du mail" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Corps du mail</label>
        <div class="col-md-9">
          <textarea class="form-control" id="email_message" placeholder="Corps du mail"><?php echo $row_settings['email_message'] ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-3 control-label">Signature</label>
        <div class="col-md-9">
          <textarea type="text" class="form-control" id="email_signature" placeholder="Signature"><?php echo $row_settings['email_signature'] ?></textarea>
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



<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets\plugins\ckeditor\ckeditor.js"></script>
<script src="assets\plugins\ckfinder\ckfinder.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>

  $(document).ready(function() {

    App.init();

    var editor = CKEDITOR.replace('email_message');
    CKFinder.setupCKEditor(editor, 'assets/plugins/ckfinder/');

    var editor2 = CKEDITOR.replace('email_signature');
    CKFinder.setupCKEditor(editor2, 'assets/plugins/ckfinder/');

    $('.edit-email').on('submit', function(event){
      event.preventDefault();
      $.ajax({
        type: 'POST',
        url: 'd26386b04e.php',
        data: {event: 'edit_email', email_title: $('.email_title').val(), email_message: CKEDITOR.instances.email_message.getData(), email_signature: CKEDITOR.instances.email_signature.getData() },
        cache: false,
        success: function(responce){
          if (responce == 'done') {
            swal({
              title: 'Fait!',
              text: 'L\'information a été mise à jour avec succès!',
              type: 'success',
              confirmButtonColor: '#348fe2',
              confirmButtonText: 'ОК',
              closeOnConfirm: true
            }).then(function() {
              //
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