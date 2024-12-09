<?php
@session_start();

ini_set('memory_limit', '1G');
ini_set('xdebug.max_nesting_level', 500);
set_time_limit(0);
ini_set('max_execution_time', '900000');
ini_set('realpath_cache_size','16M');
ini_set('realpath_cache_ttl','1200');
ini_set('pcre.backtrack_limit','1000000');
ini_set('pcre.recursion_limit','200000');
ini_set('pcre.jit','1');

if ($_SESSION['user']['folder'] == "") {die('Access denied!');}

define("ADMIN_UPLOAD_USERS_DIR", "../uploads/users/");
define("ADMIN_ARCHIVES_DIR", "../uploads/archives/");

@require_once("pclzip.lib.php");

$dirs_arr = scandir(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']);
if (count($dirs_arr) > 2) {
	$file_name = "photo_".date('dmY_His', $row_orders['created_at']).".zip";
	$archive = new PclZip(ADMIN_ARCHIVES_DIR.$file_name, PCLZIP_OPT_NO_COMPRESSION);
	foreach($dirs_arr as $key => $dir) {
		if ($dir != "." && $dir != ".." && strpos($dir, "thumb_") === false) {
			if (is_file(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/".$dir)) {
				$archive->add(ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']."/".$dir, PCLZIP_OPT_REMOVE_PATH, ADMIN_UPLOAD_USERS_DIR.$_SESSION['user']['folder']);
			}
		}
	}
	file_force_download(ADMIN_ARCHIVES_DIR, $file_name);
	unlink(ADMIN_ARCHIVES_DIR.$file_name);
}

function file_force_download($path, $file) {
	if (file_exists($path.$file)) {
		if (ob_get_level()) {ob_end_clean();}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$file);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path.$file));
		readfile($path.$file);
	}
}
?>
