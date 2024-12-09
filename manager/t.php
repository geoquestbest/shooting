<?php
$cfg = array();
$cfg['xmlclient'] = FALSE;
$cfg['doctypeid'] = "<!DOCTYPE html>";
$contenttype = ($cfg['doctypeid']>2 && $cfg['xmlclient']) ? 'application/xhtml+xml' : 'text/html; charset=utf-8';
@header('Content-Type: '.$contenttype);


@require_once("../inc/mainfile.php");
$result_templates = mysqli_query($conn, "SELECT * FROM `templates`");
require_once("thumblib/ThumbLib.inc.php");
while($row_templates = mysqli_fetch_assoc($result_templates)) {
    if ($row_templates['preview'] != "" && !file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview'], '640'))) {
        $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['preview']);
        $thumb->resize(640, 0);
        $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview'], '640'));
    }

    if ($row_templates['preview2'] != "" && !file_exists(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview2'], '640'))) {
        $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['preview2']);
        $thumb->resize(640, 0);
        $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview2'], '640'));
    }

    echo $row_templates['preview']." ".$row_templates['preview2']."<br />";
}
@mysqli_close();
echo"done";
?>
