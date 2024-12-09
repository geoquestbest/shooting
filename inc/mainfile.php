<?php
$db_host = 'localhost';
$db_user = 'shootpreprod';
$db_pass = 'tL0iK1hM6h';
$db_name = 'shootpreprod';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die("Error connecting to database!");
mysqli_select_db($conn, $db_name) or die ("Cannot connect to ".strtoupper($db_name)."!");

mysqli_query($conn, "set names utf8mb4");
mysqli_query($conn, "set character_set_client='utf8mb4'");
mysqli_query($conn, "set character_set_results='utf8mb4'");
mysqli_query($conn, "set collation_connection='utf8mb4_general_ci'");

mb_internal_encoding("UTF-8");

date_default_timezone_set('Europe/Paris');

define("ADMIN_UPLOAD_IMAGES_DIR", "../uploads/images/");
define("UPLOAD_IMAGES_DIR", "uploads/images/");
define("UPLOAD_AVATARS_DIR", "../uploads/images/avatars/");

$alphabet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

$result_settings = mysqli_query($conn, "SELECT * FROM `settings` WHERE `id` = '1'");
$row_settings = mysqli_fetch_assoc($result_settings);

function translitURL($str) {
	$tr = array(
		"А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
		"Д"=>"d","Е"=>"e","Ё"=>"yo","Ж"=>"zh","З"=>"z","И"=>"i",
		"Й"=>"j","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
		"О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
		"У"=>"u","Ф"=>"f","Х"=>"x","Ц"=>"c","Ч"=>"ch",
		"Ш"=>"sh","Щ"=>"shh","Ъ"=>"j","Ы"=>"y","Ь"=>"",
		"Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
		"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"yo","ж"=>"zh",
		"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
		"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"j",
		"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya","é"=>"e","É"=>"e","â"=>"a","Â"=>"a",
		" "=> "-", "."=> "");
	$urlstr = str_replace('–'," ",$str);
	$urlstr = str_replace('-'," ",$urlstr);
	$urlstr = str_replace('—'," ",$urlstr);

	$urlstr=preg_replace('/\s+/',' ',$urlstr);
	if (preg_match('/[^A-Za-z0-9_\-]/', $urlstr)) {
		$urlstr = strtr($urlstr,$tr);
		$urlstr = preg_replace('/[^A-Za-z0-9_\-]/', '', $urlstr);
		$urlstr = mb_strtolower($urlstr);
		return $urlstr;
	} else {
		return mb_strtolower($str);
	}
}

function getImageUrl($url, $res){
	$path_parts = pathinfo($url);
	return str_replace(".".$path_parts['extension'], "_".$res.".".$path_parts['extension'], $url);
}
?>