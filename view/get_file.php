<?php
if (!isset($_GET['file'])) {exit;}

ini_set('memory_limit', '1G');
ini_set('xdebug.max_nesting_level', 500);
set_time_limit(0);
ini_set('max_execution_time', '900000');
ini_set('realpath_cache_size','16M');
ini_set('realpath_cache_ttl','1200');
ini_set('pcre.backtrack_limit','1000000');
ini_set('pcre.recursion_limit','200000');
ini_set('pcre.jit','1');


$path_parts = pathinfo($_GET['file']);

//print_r($path_parts);

file_force_download("../uploads/".$path_parts['dirname']."/", $path_parts['basename']);

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
