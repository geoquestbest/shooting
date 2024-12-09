<?php
session_start();
$cfg = array();
$cfg['xmlclient'] = FALSE;
$cfg['doctypeid'] = "<!DOCTYPE html>";
$contenttype = ($cfg['doctypeid']>2 && $cfg['xmlclient']) ? 'application/xhtml+xml' : 'text/html; charset=utf-8';
@header('Content-Type: '.$contenttype);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && json_decode(file_get_contents('php://input'), true) != "") $_POST = json_decode(file_get_contents('php://input'), true);
if (isset($_POST) && count($_POST) == 0) $_POST = json_decode(file_get_contents('php://input'), true);

@require_once("../inc/mainfile.php");
@require_once("passwordLib.php");

$event = "";


foreach ($_POST as $key => $value){if ($key != "submit") {${$key}= mysqli_real_escape_string($conn, $value);}}
foreach ($_GET as $key => $value){if ($key != "submit") {${$key} = mysqli_real_escape_string($conn, $value);}}

if ($event == "without_photo_frame") {
	mysqli_query($conn, "UPDATE `orders_new` SET `without_photo_frame` = $without_photo_frame WHERE `id` = ".$_SESSION['order']['id']);
	echo $without_photo_frame;
}


// Авторизация
if ($event == "login") {
	$result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` LIKE '".trim($email)."'");
	if (mysqli_num_rows($result_users) != 0) {
		$row_users = mysqli_fetch_assoc($result_users);
			if (password_verify(trim($password), $row_users['password_hash'])) {
				$_SESSION['user']['id'] = $row_users['id'];
				$_SESSION['user']['role'] = $row_users['role'];
				$_SESSION['user']['email'] = $row_users['email'];
				$_SESSION['user']['name'] = trim($row_users['first_name']." ".$row_users['last_name']);
				$_SESSION['user']['folder'] = $row_users['folder'];
				mysqli_query($conn, "UPDATE `users` SET `last_at` = ".time()." WHERE `id` = ".$row_users['id']);
				echo"done";
			} else {
				echo"error";
			}
	} else {
		$result_orders_new = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `num_id` LIKE '".trim($email)."' AND `password` LIKE '".trim($password)."' AND `long_duration` = 1") or die(mysqli_error($conn));
		if (mysqli_num_rows($result_orders_new) != 0) {
			$row_orders_new = mysqli_fetch_assoc($result_orders_new);
			$_SESSION['order']['id'] = $row_orders_new['id'];
			$_SESSION['order']['name'] = $row_orders_new['last_name']." ".$row_orders_new['first_name'];
			$_SESSION['order']['email'] = $row_orders_new['email'];
			$_SESSION['order']['pay'] = (mb_strpos($row_orders_new['selected_options'], "Marque blanche", 0) === false ? 0 : 1);
			$_SESSION['order']['box_type'] = $row_orders_new['box_type'];
			echo"done";
		} else {
			echo"error";
		}
	}
}

if ($event == "login_order") {
	$result_orders_new = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `num_id` LIKE '".trim($login)."' AND `password` LIKE '".trim($password)."'") or die(mysqli_error($conn));
	if (mysqli_num_rows($result_orders_new) != 0) {
		$row_orders_new = mysqli_fetch_assoc($result_orders_new);
		$_SESSION['order']['id'] = $row_orders_new['id'];
		$_SESSION['order']['pay'] = (mb_strpos($row_orders_new['selected_options'], "Marque blanche", 0) === false ? 0 : 1);
		$_SESSION['order']['box_type'] = $row_orders_new['box_type'];
		echo"done";
	} else {
		echo"error";
	}
}

if ($event == "login_album") {
	$result_orders_new = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `num_id` LIKE '".trim($login)."' AND `password` LIKE '".trim($password)."' AND `album_mail` = 1") or die(mysqli_error($conn));
	if (mysqli_num_rows($result_orders_new) != 0) {
		$row_orders_new = mysqli_fetch_assoc($result_orders_new);
		$_SESSION['order']['id'] = $row_orders_new['id'];
		$_SESSION['order']['event_date'] = $row_orders_new['event_date'];
		$_SESSION['order']['name'] = $row_orders_new['last_name']." ".$row_orders_new['first_name'];
		$_SESSION['order']['album'] = $row_orders_new['num_id'];
		echo"done";
	} else {
		echo"error";
	}
}

// Загрузка изображения
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
	$file = pathinfo($_FILES['image']['name']);
	$file_name = md5(time()).".".strtolower($file['extension']);
	move_uploaded_file($_FILES['image']['tmp_name'], ADMIN_UPLOAD_IMAGES_DIR.$file_name);
	echo $file_name;
} else {
	if(isset($_FILES['image']) && $_FILES['image']['error'] != 0) {echo"Error: ".$_FILES['image']['error'];}
}

if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0){
	$file = pathinfo($_FILES['pdf']['name']);
	move_uploaded_file($_FILES['pdf']['tmp_name'], ADMIN_UPLOAD_IMAGES_DIR.$file['basename']);
	echo $file['basename'];
} else {
	if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] != 0) {echo"Error: ".$_FILES['pdf']['error'];}
}

// Стирание загруженного изображения
if ($event == "remove_image") {
	@unlink(ADMIN_UPLOAD_IMAGES_DIR.$image);
	@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
}

if (isset($_SESSION['user'])) {

	// Формирование Url
	if ($event == "title2url") {
		echo translitURL($title);
	}

	// Редактирование общих настроек
	if ($event == "edit_sms") {
		mysqli_query($conn, "UPDATE `settings` SET `sms1` = '".htmlspecialchars($_POST['sms1'], ENT_QUOTES, 'UTF-8')."', `long1` = '$long1', `sms2` = '".htmlspecialchars($_POST['sms2'], ENT_QUOTES, 'UTF-8')."', `long2` = '$long2', `sms3` = '".htmlspecialchars($_POST['sms3'], ENT_QUOTES, 'UTF-8')."', `long3` = '$long3' WHERE `id` = 1") or die(mysqli_error($conn));
		echo"done";
	}

	if ($event == "edit_email") {
		mysqli_query($conn, "UPDATE `settings` SET `email_title` = '".htmlspecialchars($_POST['email_title'], ENT_QUOTES, 'UTF-8')."', `email_message` = '".htmlspecialchars($_POST['email_message'], ENT_QUOTES, 'UTF-8')."', `email_signature` = '".htmlspecialchars($_POST['email_signature'], ENT_QUOTES, 'UTF-8')."' WHERE `id` = 1") or die(mysqli_error($conn));
		echo"done";
	}

	if ($event == "edit_stocks") {
		mysqli_query($conn, "UPDATE `settings` SET `ring` = '$ring', `vegas` = '$vegas', `vegas_slim` = '$vegas_slim', `miroir` = '$miroir', `spinner` = '$spinner', `vr` = '$vr', `aircam` = '$aircam' WHERE `id` = 1") or die(mysqli_error($conn));
		echo"done";
	}

	if ($event == "edit_pdfs") {
		if ($ring_pdf != "") {
			mysqli_query($conn, "UPDATE `settings` SET `ring_pdf` = '$ring_pdf' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($vegas_pdf != "") {
			mysqli_query($conn, "UPDATE `settings` SET `vegas_pdf` = '$vegas_pdf' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($miroir_pdf != "") {
			mysqli_query($conn, "UPDATE `settings` SET `miroir_pdf` = '$miroir_pdf' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($spinner_pdf != "") {
			mysqli_query($conn, "UPDATE `settings` SET `spinner_pdf` = '$spinner_pdf' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($vr_pdf != "") {
			mysqli_query($conn, "UPDATE `settings` SET `vr_pdf` = '$vr_pdf' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($aircam_pdf != "") {
			mysqli_query($conn, "UPDATE `settings` SET `aircam_pdf` = '$aircam_pdf' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		echo"done";
	}



	if ($event == "delete_ring_pdf") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['ring_pdf']);
		mysqli_query($conn, "UPDATE `settings` SET `ring_pdf` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_vegas_pdf") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['vegas_pdf']);
		mysqli_query($conn, "UPDATE `settings` SET `vegas_pdf` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_miroir_pdf") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['miroir_pdf']);
		mysqli_query($conn, "UPDATE `settings` SET `miroir_pdf` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_spinner_pdf") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['spinner_pdf']);
		mysqli_query($conn, "UPDATE `settings` SET `spinner_pdf` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_vr_pdf") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['vr_pdf']);
		mysqli_query($conn, "UPDATE `settings` SET `vr_pdf` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_aircam_pdf") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['aircam_pdf']);
		mysqli_query($conn, "UPDATE `settings` SET `aircam_pdf` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "edit_pdfs2") {
		if ($ring_pdf2 != "") {
			mysqli_query($conn, "UPDATE `settings` SET `ring_pdf2` = '$ring_pdf2' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($vegas_pdf2 != "") {
			mysqli_query($conn, "UPDATE `settings` SET `vegas_pdf2` = '$vegas_pdf2' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($miroir_pdf2 != "") {
			mysqli_query($conn, "UPDATE `settings` SET `miroir_pdf2` = '$miroir_pdf2' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($spinner_pdf2 != "") {
			mysqli_query($conn, "UPDATE `settings` SET `spinner_pdf2` = '$spinner_pdf2' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($vr_pdf2 != "") {
			mysqli_query($conn, "UPDATE `settings` SET `vr_pdf2` = '$vr_pdf2' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		if ($vr_pdf2 != "") {
			mysqli_query($conn, "UPDATE `settings` SET `aircam_pdf2` = '$aircam_pdf2' WHERE `id` = 1") or die(mysqli_error($conn));
		}
		echo"done";
	}



	if ($event == "delete_ring_pdf2") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['ring_pdf2']);
		mysqli_query($conn, "UPDATE `settings` SET `ring_pdf2` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_vegas_pdf2") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['vegas_pdf2']);
		mysqli_query($conn, "UPDATE `settings` SET `vegas_pdf2` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_miroir_pdf2") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['miroir_pdf2']);
		mysqli_query($conn, "UPDATE `settings` SET `miroir_pdf2` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_spinner_pdf2") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['spinner_pdf2']);
		mysqli_query($conn, "UPDATE `settings` SET `spinner_pdf2` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_vr_pdf2") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['vr_pdf2']);
		mysqli_query($conn, "UPDATE `settings` SET `vr_pdf2` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	if ($event == "delete_aircam_pdf2") {
		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_settings['aircam_pdf2']);
		mysqli_query($conn, "UPDATE `settings` SET `vaircam_pdf2` = '' WHERE `id` = 1") or die(mysqli_error($conn));
	}

	// Добавление контента
	if ($event == "add_content") {
		$url = translitURL($url);
		$result_contents = mysqli_query($conn, "SELECT * FROM `contents` WHERE url='$url'");
		if (mysqli_num_rows($result_contents) == 0) {
			mysqli_query($conn, "INSERT INTO `contents` VALUES (NULL, '$title', '".str_replace("_textarea", "textarea", htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'))."', '$meta_title', '$meta_description', '$meta_keywords', '$url')");
			echo"done";
		} else {
			echo"Une page avec cette URL existe déjà!";
		}
	}

	// Редактировани контента
	if ($event == "edit_content") {
		$url = translitURL($url);
		$result_contents = mysqli_query($conn, "SELECT * FROM `contents` WHERE `url`='$url' AND `id` <>".$id);
		if (mysqli_num_rows($result_contents) == 0) {
			mysqli_query($conn, "UPDATE `contents` SET `title` = '$title', `content` = '".str_replace("_textarea", "textarea", htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'))."', `meta_title` = '$meta_title', `meta_description` = '$meta_description', `meta_keywords` = '$meta_keywords', `url` = '$url' WHERE `id` = ".$id);
			echo"done";
		} else {
			echo"Une page avec cette URL existe déjà!";
		}
	}

	// Удаление контента
	if ($event == "delete_content") {
		$result_contents = mysqli_query($conn, "SELECT * FROM `contents` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_contents) != 0) {
			mysqli_query($conn, "DELETE FROM `contents` WHERE id = ".$id);
			echo"done";
		} else {
			echo"Такой страницы не существует!";
		}
	}

	// Добавление нового пункта меню
	if ($event == "add_menu") {
		$result_menu = mysqli_query($conn, "SELECT * FROM `menu` WHERE `type_id` = ".$type_id." AND `parent_id` = ".$parent_id);
		mysqli_query($conn, "INSERT INTO `menu` VALUES (NULL, '$type_id', '$parent_id', '$title', '$description', '$content_id', '$url', '".(mysqli_num_rows($result_menu) + 1)."')");
		echo"done";
	}

	// Редактирование пункта меню
	if ($event == "edit_menu") {
		$result_menu = mysqli_query($conn, "SELECT `parent_id` FROM `menu` WHERE `id` = ".$id);
		$row_menu = mysqli_fetch_assoc($result_menu);
		$old_parent_id = $row_menu['parent_id'];
		mysqli_query($conn, "UPDATE menu SET `parent_id` = '$parent_id', `title` = '$title', `description` = '$description', `content_id` = '$content_id', `url` = '$url' WHERE `id` = ".$id);
		if ($old_parent_id != $parent_id) {
			$result_menu = mysqli_query($conn, "SELECT `id` FROM `menu` WHERE `type_id` = $type_id AND `parent_id` = $parent_id ORDER BY `position`");
			$i=1;
			while($row_menu = mysqli_fetch_assoc($result_menu)) {
				mysqli_query($conn, "UPDATE `menu` SET `position`='$i' WHERE `id` = ".$row_menu['id']);
				$i++;
			}
			$result_menu = mysqli_query($conn, "SELECT `id` FROM `menu` WHERE `type_id` = $type_id AND `parent_id` = $old_parent_id ORDER BY `position`");
			$i=1;
			while($row_menu = mysqli_fetch_assoc($result_menu)) {
				mysqli_query($conn, "UPDATE `menu` SET `position`='$i' WHERE `id` = ".$row_menu['id']);
				$i++;
			}
		}
		echo"done";
	}

	// Удаление пункта меню
	if ($event == "delete_menu") {
		$result_menu = mysqli_query($conn, "SELECT * FROM `menu` WHERE id = ".$id);
		if (mysqli_num_rows($result_menu) != 0) {
			$row_menu = mysqli_fetch_assoc($result_menu);
			delМenuTree($row_menu['id'], $row_menu['type_id'], 0) ;
			mysqli_query($conn, "DELETE FROM `menu` WHERE `id` = ".$id);
			$result_menu = mysqli_query($conn, "SELECT * FROM `menu` WHERE `type_id` = ".$row_menu['type_id']." AND `pid` = ".$row_menu['pid']." ORDER BY position");
			$i=1;
			while($row_menu = mysqli_fetch_assoc($result_menu)) {
				mysqli_query($conn, "UPDATE `menu` SET `position`='$i' WHERE `id` = ".$row_menu['id']);
				$i++;
			}
			echo"done";
		} else {
			echo"Il n'y a pas un tel élément de menu!";
		}
	}

	// Сортировка меню
	if ($event == "menu_reorder") {
		$new_positions_arr = explode(";", trim($new_positions, ";"));
		foreach ($new_positions_arr as $new_position) {
			$current = explode(":", $new_position);
			mysqli_query($conn, "UPDATE `menu` SET `position` = ".$current[1]." WHERE id = ".$current[0]);
		}
	}

	// Добавление нового виджета
	if ($event == "add_widget") {
		mysqli_query($conn, "INSERT INTO `widgets` VALUES (NULL, '$title', '".str_replace('_textarea', 'textarea', htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'))."')");
		echo"done";
	}

	// Редактирование виджета
	if ($event == "edit_widget") {
		mysqli_query($conn, "UPDATE `widgets` SET `title` = '$title', `content` = '".str_replace('_textarea', 'textarea', htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'))."' WHERE `id` = ".$id);
		echo"done";
	}

	// Удаление виджета
	if ($event == "delete_widget") {
		$result_menu = mysqli_query($conn, "SELECT * FROM `widgets` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_menu) != 0) {
			mysqli_query($conn, "DELETE FROM `widgets` WHERE id = ".$id);
			echo"done";
		} else {
			echo"Ce widget n'existe pas!";
		}
	}


	// Добавление нового виджета
	if ($event == "add_article") {
		mysqli_query($conn, "INSERT INTO `articles` VALUES (NULL, '$num', '$title', '".str_replace('_textarea', 'textarea', htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'))."', '$type_id', '$box_id', '$price')");
		echo"done";
	}

	// Редактирование виджета
	if ($event == "edit_article") {
		mysqli_query($conn, "UPDATE `articles` SET `num` = '$num', `title` = '$title', `content` = '".str_replace('_textarea', 'textarea', htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8'))."', `type_id` = '$type_id', `box_id` = '$box_id', `price` = '$price' WHERE `id` = ".$id);
		echo"done";
	}

	// Удаление виджета
	if ($event == "delete_article") {
		$result_menu = mysqli_query($conn, "SELECT * FROM `articles` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_menu) != 0) {
			mysqli_query($conn, "DELETE FROM `articles` WHERE id = ".$id);
			echo"done";
		} else {
			echo"Ce article n'existe pas!";
		}
	}

	if ($event == "add_facture") {
		mysqli_query($conn, "INSERT INTO `factures` VALUES (NULL, '$order_id', '$articles_ids')");
		echo"done";
	}

	if ($event == "edit_facture") {
		mysqli_query($conn, "UPDATE `factures` SET `order_id` = '$order_id', `articles_ids` = '$articles_ids' WHERE `id` = ".$id);
		echo"done";
	}

	// Удаление виджета
	if ($event == "delete_facture") {
		$result_menu = mysqli_query($conn, "SELECT * FROM `factures` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_menu) != 0) {
			mysqli_query($conn, "DELETE FROM `factures` WHERE id = ".$id);
			echo"done";
		} else {
			echo"Ce facture n'existe pas!";
		}
	}

	if ($event == "add_repair") {
    $exist = 0; $event_date = "";
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `box_id` LIKE '%$box_id%'");
    while($row_orders = mysqli_fetch_assoc($result_orders)) {
      if (strtotime($row_orders['event_date'])  >= time() && strtotime($row_orders['event_date']) <= time() + 14*24*3600) {
        $exist = $row_orders['id'];
        $event_date = $row_orders['event_date'];
      }
    }
    if ($exist == 0) {
  		mysqli_query($conn, "INSERT INTO `repair` VALUES (NULL, '$agency_id', '$box_id', '".time()."')");
  		echo"done";
    } else {
      echo"La commande N°".$exist." se voit attribuer ce numéro de borne. Date de l'événement ".$event_date;
    }
	}


	if ($event == "edit_repair") {
    $exist = 0; $event_date = "";
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `box_id` LIKE '%$box_id%'");
    while($row_orders = mysqli_fetch_assoc($result_orders)) {
      if (strtotime($row_orders['event_date'])  >= time() && strtotime($row_orders['event_date']) <= time() + 14*24*3600) {
        $exist = $row_orders['id'];
        $event_date = $row_orders['event_date'];
      }
    }
    if ($exist == 0) {
  		mysqli_query($conn, "UPDATE `repair` SET `agency_id` = '$agency_id', `box_id` = '$box_id' WHERE `id` = ".$id);
  		echo"done";
    } else {
      echo"La commande N°".$exist." se voit attribuer ce numéro de borne. Date de l'événement ".$event_date;
    }
	}

	// Удаление виджета
	if ($event == "delete_repair") {
		$result_menu = mysqli_query($conn, "SELECT * FROM `repair` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_menu) != 0) {
			mysqli_query($conn, "DELETE FROM `repair` WHERE id = ".$id);
			echo"done";
		} else {
			echo"Ce repair n'existe pas!";
		}
	}

	// Добавление пользователя
	if ($event == "add_user") {
		$result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` LIKE '".trim($email)."'");
		if (mysqli_num_rows($result_users) == 0) {
			$time = time();
			$password_hash = password_hash(trim($password), PASSWORD_DEFAULT, array('cost' => 13));
			mysqli_query($conn, "INSERT INTO `users`(`id`, `role`, `email`, `password_hash`, `accesses`, `first_name`, `last_name`, `created_at`, `last_at`, `signature`, `is_commercial`) VALUES (NULL, '$role', '$email', '$password_hash', '$accesses', '$first_name', '$last_name', '$time', '$time', '".htmlspecialchars($_POST['signature'], ENT_QUOTES, 'UTF-8')."', '$is_commercial')");
			echo"done";
		} else {
			echo"Un utilisateur avec cet email existe déjà !";
		}
	}

	// Редактирование пользователя
	if ($event == "edit_user") {
		$result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` LIKE '".trim($email)."'");
		if (mysqli_num_rows($result_users) <= 1) {
			mysqli_query($conn, "UPDATE `users` SET `role` = '$role', `email` = '$email', `accesses` = '$accesses', `first_name` = '$first_name', `last_name` = '$last_name', `signature` = '".htmlspecialchars($_POST['signature'], ENT_QUOTES, 'UTF-8')."', `is_commercial` = '$is_commercial' WHERE `id` = ".$id) or die(mysqli_error($conn));
			if (trim($password) != "") {
				$password_hash = password_hash(trim($password), PASSWORD_DEFAULT, array('cost' => 13));
				mysqli_query($conn, "UPDATE`users`SET `password_hash` = '$password_hash' WHERE `id` = ".$id);
			}
			echo"done";
		} else {
			echo"Un utilisateur avec cet email existe déjà !";
		}
	}

	if ($event == "delete_user") {
		mysqli_query($conn, "DELETE FROM `users` WHERE `id` = ".$id);
		echo"done";
	}

	// Загрузка изображения галереи
	if (isset($_FILES['file_gallery'])) {
		$total = count($_FILES['file_gallery']['name']);
		$files_names = "";
		for($i = 0; $i < $total; $i++) {
			$file = pathinfo($_FILES['file_gallery']['name'][$i]);
			$file_name = md5(time().'-'.$i.'-'.rand(0, 1000)).".".strtolower($file['extension']);
			move_uploaded_file($_FILES['file_gallery']['tmp_name'][$i], ADMIN_UPLOAD_IMAGES_DIR.$file_name);
			$files_names .= $file_name.";";
		}
		echo trim($files_names, ";");
	}

	// Стирание загруженного файла галереи
	if ($event == "remove_gallery_image") {
		$images_arr = explode(";", $images);
		$files_names = "";
		foreach($images_arr as $key => $image) {
			if ($key == $index) {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$image);
			} else {
				$files_names .= $image.";";
			}
		}
		echo trim($files_names, ";");
	}

	if ($event == "remove_gallery_video") {
		$videos_arr = explode(";", $videos);
		$files_names = "";
		foreach($videos_arr as $key => $video) {
			if ($key == $index) {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$video);
			} else {
				$files_names .= $video.";";
			}
		}
		echo trim($files_names, ";");
	}

	// Добавление галереи
	if ($event == "add_gallery") {
		$result_gallery = mysqli_query($conn, "SELECT * FROM `gallery`");
		mysqli_query($conn, "INSERT INTO `gallery` (`id`, `title`, `url`, `position`) VALUES (NULL, '$title', '$url', '".(mysqli_num_rows($result_gallery) + 1)."')");
		$gallery_id = mysqli_insert_id($conn);
		if ($images != "") {
			$images_arr = explode(";", $images);
			require_once("thumblib/ThumbLib.inc.php");
			foreach($images_arr as $key => $image) {
				$size = getimagesize(ADMIN_UPLOAD_IMAGES_DIR.$image);
				if ($size[0] > 620) {
					$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
					$thumb->resize(620, 0);
					$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.$image);
				}
				$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
				$thumb->resize(120, 0);
				$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
				mysqli_query($conn, "INSERT INTO `gallery_images` (`id`, `gallery_id`, `image`) VALUES (NULL, '$gallery_id', '$image')");
			}
		}
		echo"done";
	}

	// Редактирование галереи
	if ($event == "edit_gallery") {
		mysqli_query($conn, "UPDATE `gallery` SET `title` = '$title', `url` = '$url' WHERE `id` = ".$id);
		if ($images != "") {
			$images_arr = explode(";", $images);
			require_once("thumblib/ThumbLib.inc.php");
			foreach($images_arr as $key => $image) {
				$size = getimagesize(ADMIN_UPLOAD_IMAGES_DIR.$image);
				if ($size[0] > 620) {
					$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
					$thumb->resize(620, 0);
					$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.$image);
				}
				$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
				$thumb->resize(120, 0);
				$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
				mysqli_query($conn, "INSERT INTO `gallery_images` (`id`, `gallery_id`, `image`) VALUES (NULL, '$id', '$image')");
			}
		}
		echo"done";
	}

	// Удаление загруженного файла галереи
	if ($event == "delete_gallery_image") {
		$result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `image` LIKE '$image'");
		if (mysqli_num_rows($result_gallery_images) > 0) {
			$row_gallery_images = mysqli_fetch_assoc($result_gallery_images);
			//$result_gallery = mysqli_query($conn, "SELECT * FROM `gallery` WHERE `id` = ".$row_gallery_images['gallery_id']);
			//if (mysqli_num_rows($result_gallery) > 0) {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_images['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_gallery_images['image'], '120'));
				mysqli_query($conn, "DELETE FROM `gallery_images` WHERE `image` LIKE '$image'");
			//}
		}
		echo"done";
	}

	if ($event == "delete_gallery_video") {
		$result_gallery_videos = mysqli_query($conn, "SELECT * FROM `gallery_videos` WHERE `video` LIKE '$video'");
		if (mysqli_num_rows($result_gallery_videos) > 0) {
			$row_gallery_videos = mysqli_fetch_assoc($result_gallery_videos);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_videos['video']);
			mysqli_query($conn, "DELETE FROM `gallery_videos` WHERE `video` LIKE '$video'");
		}
		echo"done";
	}

	// Сортировка галереи
	if ($event == "gallery_reorder") {
		$new_positions_arr = explode(";", trim($new_positions, ";"));
		foreach ($new_positions_arr as $new_position) {
			$current = explode(":", $new_position);
			mysqli_query($conn, "UPDATE `gallery` SET `position` = ".$current[1]." WHERE `id` = ".$current[0]);
		}
	}

	// Удаление галереи
	if ($event == "gallery_delete") {
		$result_gallery = mysqli_query($conn, "SELECT * FROM `gallery` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_gallery) != 0) {
			$row_gallery = mysqli_fetch_assoc($result_gallery);
			$result_gallery_images = mysqli_query($conn, "SELECT * FROM `gallery_images` WHERE `gallery_id` = ".$row_gallery['id']);
			if (mysqli_num_rows($result_gallery_images) != 0) {
				while($row_gallery_images = mysqli_fetch_assoc($result_gallery_images)) {
					@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_gallery_images['image']);
					@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_gallery_images['image'], '120'));
					mysqli_query($conn, "DELETE FROM `gallery_images` WHERE `id` = ".$row_gallery_images['id']);
				}
			}
			mysqli_query($conn, "DELETE FROM `gallery` WHERE `id` = ".$row_gallery['id']);
			$result_gallery = mysqli_query($conn, "SELECT * FROM `gallery` ORDER BY `position`");
			$i=1;
			while($row_gallery = mysqli_fetch_assoc($result_gallery)) {
				mysqli_query($conn, "UPDATE `gallery` SET `position` = '$i' WHERE `id` = ".$row_gallery['id']);
				$i++;
			}
			echo"done";
		} else {
			echo"Il n'y a pas une telle galerie dans la base de données!";
		}
	}

	if ($event == "add_order") {
		if (strtotime($take_date) > strtotime($return_date)) {
			echo "Vérifiez les dates de retrait et de retour du box!";
		} else {
			//$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `num_id` LIKE '$num_id'");
			//if (mysqli_num_rows($result_orders) == 0) {
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `password` LIKE '$password'");
				if (mysqli_num_rows($result_orders)  == 0) {
					if ($status == 2) {
						$num_id = "FA".$row_settings['facture'];
						mysqli_query($conn, "UPDATE `settings` SET `facture` = '".($row_settings['facture'] + 1)."' WHERE `id` = 1");
					} else {
						$num_id = "DE".$row_settings['devi'];
						mysqli_query($conn, "UPDATE `settings` SET `devi` = '".($row_settings['devi'] + 1)."' WHERE `id` = 1");
					}
					mysqli_query($conn, "INSERT INTO `orders_new`(`id`, `user_id`, `agency_id`, `invite`, `select_type`, `box_type`, `price`, `amount`, `box_num`, `event_date`, `event_time`, `event_type`, `event_place`, `return_place`, `selected_options`, `delivery_options`, `last_name`, `first_name`, `city`, `address`, `cp`, `email`, `phone`, `num_id`, `sage`, `password`, `ord`, `societe`, `description`, `about`, `total`, `discount`, `template_id`, `image`, `template_status`, `data`, `deposit`, `payment_status`, `payment_type1`, `payment_type2`, `advance_payment`, `take_date`, `return_date`, `take_time`, `return_time`, `horaire`, `transportation_time`, `box_id`, `event_ready`, `box_ready`, `data_ready`, `courier`, `paid`, `comment`,  `comment_payment`, `status`, `send_mail`, `off`, `tripadvisor_mail`, `album_mail`, `created_at`, `updated_at`, `fa`, `info`, `validation`, `email_title`, `email_message`, `to_mail`, `entreprise_pdf`, `address_pdf`, `city_pdf`, `cp_pdf`, `number_pdf`, `other_pdf`, `qr`, `refuse_title`, `long_duration`, `agenda`, `promocode`, `personal_data`, `relaunch`, `marriage`, `devis`, `gallery_access`) VALUES (NULL, '$user_id', '$agency_id', '0', '$select_type', '$box_type', '$price', '$amount', '', '$event_date', '$event_time', '$event_type', '$event_place', '$return_place', '$selected_options', '$delivery_options', '$last_name', '$first_name', '$city', '".htmlspecialchars($address, ENT_QUOTES, 'UTF-8')."', '$cp', '$email', '$phone', '".trim($num_id)."', '".trim($sage)."', '$password', '$ord', '$societe', '$description', '$about', '$total', '$discount', '0', '', '0', '', '0', '0', '0', '0', '$advance_payment', '$take_date', '$return_date', '$take_time', '$return_time', '$horaire', '$transportation_time', '$box_id', '0', '0', '0', '', '0', '', '', '$status', '0', '0', '0', '0', '".time()."', '".time()."', '0', '', '0', '".htmlspecialchars($_POST['email_title'], ENT_QUOTES, 'UTF-8')."', '".htmlspecialchars($_POST['email_message'], ENT_QUOTES, 'UTF-8')."', '0', '".htmlspecialchars($_POST['entreprise_pdf'], ENT_QUOTES, 'UTF-8')."', '".htmlspecialchars($_POST['address_pdf'], ENT_QUOTES, 'UTF-8')."', '$city_pdf', '$cp_pdf', '$number_pdf', '$other_pdf', '', '', '$long_duration', '0', '$promocode', '1', '0', '0', '".$row_settings['devi']."', '0')") or die('Error 1 '.mysqli_error($conn));
					$id = mysqli_insert_id($conn);
					if ($bornes != '') {
						$bornes_arr = explode(";", $bornes);
						foreach ($bornes_arr as $key => $value) {
							$value_arr = explode("::", $value);
							mysqli_query($conn, "INSERT INTO `bornes`(`id`, `order_id`, `box_type`, `price`, `amount`, `box_id`, `selected_options`) VALUES (NULL, '$id', '".$value_arr[0]."', '".$value_arr[1]."', '".$value_arr[2]."', '".$value_arr[3]."', '".$value_arr[4]."')") or die('Error 2 '.mysqli_error($conn));
						}
					}
	        if ($status == 2) {
	          file_get_contents("https://ftp.shootnbox.fr/calendar2.php");
	          file_get_contents("https://ftp.shootnbox.fr/calendar3.php?order_id=".$id);
	        }
					echo "done";
				} else {
					echo"Une autre commande avec le même mot de passe existe déjà !";
				}
			//} else {
				//echo"Une autre commande avec le même Devis N° existe déjà !";
			//}
		}
	}

	if ($event == "edit_payment") {
		mysqli_query($conn, "UPDATE `orders_new` SET `deposit` = '$deposit', `payment_type1` = '$payment_type1', `payment_type2` = '$payment_type2', `take_date` = '$take_date', `return_date` = '$return_date' WHERE `id` = ".$id) or die(mysqli_error($conn));
		echo "done";
	}

	if ($event == "pay_status") {
		mysqli_query($conn, "UPDATE `orders_new` SET  `payment_status` = '$status' WHERE `id` = ".$id) or die(mysqli_error($conn));
		if  ($status == 2) {
				$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
        $row_orders = mysqli_fetch_assoc($result_orders);
        $subject = "Votre espace client Shootnbox ".date("d-m-Y H:i", time());
				$mailheaders = "MIME-Version: 1.0\r\n";
				$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
				$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
				$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
				$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
				  <head>
				    <meta charset="UTF-8">
				    <meta content="width=device-width, initial-scale=1" name="viewport">
				    <meta name="x-apple-disable-message-reformatting">
				    <meta http-equiv="X-UA-Compatible" content="IE=edge">
				    <meta content="telephone=no" name="format-detection">
				    <title></title>
				    <!--[if gte mso 9]><style>
				        sup { font-size: 100% !important; }
				      </style><![endif]-->
				    <style type="text/css">
				      /* CONFIG STYLES Please do not delete and edit CSS styles below */
				      /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
				      a[x-apple-data-detectors] {
				          color: inherit !important;
				          text-decoration: none !important;
				          font-size: inherit !important;
				          font-family: inherit !important;
				          font-weight: inherit !important;
				          line-height: inherit !important;
				      }
				      /*
				      END OF IMPORTANT
				      */
				      /*
				      END CONFIG STYLES
				      */
				      /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
				      @media only screen and (max-width: 600px) {
				          p,
				          ul li,
				          ol li,
				          a {
				        line-height: 150% !important;
				          }
				          h1,
				          h2,
				          h3,
				          h1 a,
				          h2 a,
				          h3 a {
				        line-height: 120%;
				          }
				          h1 {
				        font-size: 30px !important;
				        text-align: center;
				          }
				          h2 {
				        font-size: 26px !important;
				        text-align: center;
				          }
				          h3 {
				        font-size: 20px !important;
				        text-align: center;
				          }
				          .es-header-body h1 a,
				          .es-content-body h1 a,
				          .es-footer-body h1 a {
				        font-size: 30px !important;
				          }
				          .es-header-body h2 a,
				          .es-content-body h2 a,
				          .es-footer-body h2 a {
				        font-size: 26px !important;
				          }
				          .es-header-body h3 a,
				          .es-content-body h3 a,
				          .es-footer-body h3 a {
				        font-size: 20px !important;
				          }
				          .es-menu td a {
				        font-size: 16px !important;
				          }
				          .es-header-body p,
				          .es-header-body ul li,
				          .es-header-body ol li,
				          .es-header-body a {
				        font-size: 16px !important;
				          }
				          .es-content-body p,
				          .es-content-body ul li,
				          .es-content-body ol li,
				          .es-content-body a {
				        font-size: 16px !important;
				          }
				          .es-footer-body p,
				          .es-footer-body ul li,
				          .es-footer-body ol li,
				          .es-footer-body a {
				        font-size: 16px !important;
				          }
				          .es-infoblock p,
				          .es-infoblock ul li,
				          .es-infoblock ol li,
				          .es-infoblock a {
				        font-size: 12px !important;
				          }
				          *[class="gmail-fix"] {
				        display: none !important;
				          }
				          .es-m-txt-c,
				          .es-m-txt-c h1,
				          .es-m-txt-c h2,
				          .es-m-txt-c h3 {
				        text-align: center !important;
				          }
				          .es-m-txt-r,
				          .es-m-txt-r h1,
				          .es-m-txt-r h2,
				          .es-m-txt-r h3 {
				        text-align: right !important;
				          }
				          .es-m-txt-l,
				          .es-m-txt-l h1,
				          .es-m-txt-l h2,
				          .es-m-txt-l h3 {
				        text-align: left !important;
				          }
				          .es-m-txt-r img,
				          .es-m-txt-c img,
				          .es-m-txt-l img {
				        display: inline !important;
				          }
				          .es-adaptive table,
				          .es-left,
				          .es-right {
				        width: 100% !important;
				          }
				          .es-content table,
				          .es-header table,
				          .es-footer table,
				          .es-content,
				          .es-footer,
				          .es-header {
				        width: 100% !important;
				        max-width: 600px !important;
				          }
				          .es-adapt-td {
				        display: block !important;
				        width: 100% !important;
				          }
				          .adapt-img {
				        width: 100% !important;
				        height: auto !important;
				          }
				          .es-m-p0 {
				        padding: 0 !important;
				          }
				          .es-m-p0r {
				        padding-right: 0 !important;
				          }
				          .es-m-p0l {
				        padding-left: 0 !important;
				          }
				          .es-m-p0t {
				        padding-top: 0 !important;
				          }
				          .es-m-p0b {
				        padding-bottom: 0 !important;
				          }
				          .es-m-p20b {
				        padding-bottom: 20px !important;
				          }
				          .es-mobile-hidden,
				          .es-hidden {
				        display: none !important;
				          }
				          tr.es-desk-hidden,
				          td.es-desk-hidden,
				          table.es-desk-hidden {
				        width: auto !important;
				        overflow: visible !important;
				        float: none !important;
				        max-height: inherit !important;
				        line-height: inherit !important;
				          }
				          tr.es-desk-hidden {
				        display: table-row !important;
				          }
				          table.es-desk-hidden {
				        display: table !important;
				          }
				          td.es-desk-menu-hidden {
				        display: table-cell !important;
				          }
				          .es-menu td {
				        width: 1% !important;
				          }
				          table.es-table-not-adapt,
				          .esd-block-html table {
				        width: auto !important;
				          }
				          table.es-social {
				        display: inline-block !important;
				          }
				          table.es-social td {
				        display: inline-block !important;
				          }
				          .es-desk-hidden {
				        display: table-row !important;
				        width: auto !important;
				        overflow: visible !important;
				        max-height: inherit !important;
				          }
				          .es-m-p5 {
				        padding: 5px !important;
				          }
				          .es-m-p5t {
				        padding-top: 5px !important;
				          }
				          .es-m-p5b {
				        padding-bottom: 5px !important;
				          }
				          .es-m-p5r {
				        padding-right: 5px !important;
				          }
				          .es-m-p5l {
				        padding-left: 5px !important;
				          }
				          .es-m-p10 {
				        padding: 10px !important;
				          }
				          .es-m-p10t {
				        padding-top: 10px !important;
				          }
				          .es-m-p10b {
				        padding-bottom: 10px !important;
				          }
				          .es-m-p10r {
				        padding-right: 10px !important;
				          }
				          .es-m-p10l {
				        padding-left: 10px !important;
				          }
				          .es-m-p15 {
				        padding: 15px !important;
				          }
				          .es-m-p15t {
				        padding-top: 15px !important;
				          }
				          .es-m-p15b {
				        padding-bottom: 15px !important;
				          }
				          .es-m-p15r {
				        padding-right: 15px !important;
				          }
				          .es-m-p15l {
				        padding-left: 15px !important;
				          }
				          .es-m-p20 {
				        padding: 20px !important;
				          }
				          .es-m-p20t {
				        padding-top: 20px !important;
				          }
				          .es-m-p20r {
				        padding-right: 20px !important;
				          }
				          .es-m-p20l {
				        padding-left: 20px !important;
				          }
				          .es-m-p25 {
				        padding: 25px !important;
				          }
				          .es-m-p25t {
				        padding-top: 25px !important;
				          }
				          .es-m-p25b {
				        padding-bottom: 25px !important;
				          }
				          .es-m-p25r {
				        padding-right: 25px !important;
				          }
				          .es-m-p25l {
				        padding-left: 25px !important;
				          }
				          .es-m-p30 {
				        padding: 30px !important;
				          }
				          .es-m-p30t {
				        padding-top: 30px !important;
				          }
				          .es-m-p30b {
				        padding-bottom: 30px !important;
				          }
				          .es-m-p30r {
				        padding-right: 30px !important;
				          }
				          .es-m-p30l {
				        padding-left: 30px !important;
				          }
				          .es-m-p35 {
				        padding: 35px !important;
				          }
				          .es-m-p35t {
				        padding-top: 35px !important;
				          }
				          .es-m-p35b {
				        padding-bottom: 35px !important;
				          }
				          .es-m-p35r {
				        padding-right: 35px !important;
				          }
				          .es-m-p35l {
				        padding-left: 35px !important;
				          }
				          .es-m-p40 {
				        padding: 40px !important;
				          }
				          .es-m-p40t {
				        padding-top: 40px !important;
				          }
				          .es-m-p40b {
				        padding-bottom: 40px !important;
				          }
				          .es-m-p40r {
				        padding-right: 40px !important;
				          }
				          .es-m-p40l {
				        padding-left: 40px !important;
				          }
				          .es-content-body .fz46 {
				        font-size: 46px !important;
				          }
				      }
				      /* END RESPONSIVE STYLES */
				      .bg-item {
				        position: relative;
				      }
				      .bg-item {
				        content: "";
				        display: block;
				        position: absolute;
				        background-color: #F9F6F3;
				        width: 420px;
				        height: 170px;
				        border-radius: 35px;
				        z-index: -1;
				        top: -20px;
				        left: 70px;
				      }
				    </style>
				  </head>
				  <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
				    <div class="es-wrapper-color" style="background-color:#f6f6f6;">
				      <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;background-color:#f6f6f6;">
				        <tbody>
				          <tr>
				            <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
				              <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
				                <tbody>
				                  <tr>
				                    <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
				                      <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
				                        <tbody>
				                          <tr>
				                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
				                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                <tbody>
				                                  <tr>
				                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
				                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                        <tbody>
				                                          <tr>
				                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_ec46a73fb0402205679875e14c04bfd64dbab0b9eef7b02cc17b33d36e10e89f/images/logo_rOc.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="192"></a></td>
				                                          </tr>
				                                        </tbody>
				                                      </table>
				                                    </td>
				                                  </tr>
				                                </tbody>
				                              </table>
				                            </td>
				                          </tr>
				                          <tr>
				                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
				                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                <tbody>
				                                  <tr>
				                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
				                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                        <tbody>
				                                          <tr>
				                                            <td align="center" class="esd-block-text es-p25r es-p25l es-m-p0r es-m-p0l" style="padding:0;Margin:0;padding-left:25px;padding-right:25px;">
				                                              <p class="fz46" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-size: 57px; color: #da3a8d; line-height: 100%;"><strong>Votre espace client</strong></p>
				                                            </td>
				                                          </tr>
				                                        </tbody>
				                                      </table>
				                                    </td>
				                                  </tr>
				                                </tbody>
				                              </table>
				                            </td>
				                          </tr>
				                          <tr>
				                            <td class="esd-structure es-p20" align="left" style="padding:0;Margin:0;padding:20px;">
				                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                <tbody>
				                                  <tr>
				                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
				                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                        <tbody>
				                                          <tr>
				                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_ec46a73fb0402205679875e14c04bfd64dbab0b9eef7b02cc17b33d36e10e89f/images/zigzag.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="191"></a></td>
				                                          </tr>
				                                        </tbody>
				                                      </table>
				                                    </td>
				                                  </tr>
				                                </tbody>
				                              </table>
				                            </td>
				                          </tr>
				                          <tr>
				                            <td class="esd-structure es-p40t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
				                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                <tbody>
				                                  <tr>
				                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
				                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                        <tbody>
				                                          <tr style="position: relative; z-index: 1;">
				                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
				                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color:#333333;font-size: 17px;"><strong>Un nouvel élément a été chargé sur votre</strong><br>
				                                              </p>
				                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color:#333333;font-size: 17px;"><strong>espace client</strong></p>
				                                              <div class="bg-item"></div>
				                                            </td>
				                                          </tr>
				                                        </tbody>
				                                      </table>
				                                    </td>
				                                  </tr>
				                                </tbody>
				                              </table>
				                            </td>
				                          </tr>
				                          <tr>
				                            <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
				                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                <tbody>
				                                  <tr>
				                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
				                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                        <tbody>
				                                          <tr>
				                                            <td align="center" class="esd-block-button" style="padding:0;Margin:0;"><span class="es-button-border" style="border:none;display:inline-block;border-radius:30px;width:auto;border-width: 0px; background: #da3a8d;"><a href="https://ftp.shootnbox.fr/cabinet/" class="es-button" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;display:inline-block;border-radius:30px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:120%;color:#ffffff;text-decoration:none;width:auto;text-align:center;padding:10px 20px 10px 20px;mso-padding-alt:0;mso-style-priority:100 !important;text-decoration:none !important;background: #da3a8d; font-size: 17px;border:none;">Cliquez ici pour y accéder</a></span></td>
				                                          </tr>
				                                          <tr>
				                                            <td align="center" class="esd-block-spacer" height="90" style="padding:0;Margin:0;"></td>
				                                          </tr>
				                                        </tbody>
				                                      </table>
				                                    </td>
				                                  </tr>
				                                </tbody>
				                              </table>
				                            </td>
				                          </tr>
				                          <tr>
				                            <td class="esd-structure es-p20" align="left" bgcolor="#707070" style="padding:0;Margin:0;padding:20px;background-color: #707070;">
				                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                <tbody>
				                                  <tr>
				                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
				                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
				                                        <tbody>
				                                          <tr>
				                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
				                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">Shootnbox</p>
				                                            </td>
				                                          </tr>
				                                          <tr>
				                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
				                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">contact@shootnbox.fr 01 45</p>
				                                            </td>
				                                          </tr>
				                                          <tr>
				                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
				                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">01 45 01 66 66</p>
				                                            </td>
				                                          </tr>
				                                        </tbody>
				                                      </table>
				                                    </td>
				                                  </tr>
				                                </tbody>
				                              </table>
				                            </td>
				                          </tr>
				                        </tbody>
				                      </table>
				                    </td>
				                  </tr>
				                </tbody>
				              </table>
				            </td>
				          </tr>
				        </tbody>
				      </table>
				    </div>
				  </body>
				</html>';
          mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
          mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
          mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
          mail("info@evput.com", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        }
		echo "done";
	}

	if ($event == "event_ready") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `event_ready` = '$status' WHERE `id` = ".$id);
  	 echo "done";
  }

	if ($event == "box_ready") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `box_ready` = '$status' WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "data_ready") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `data_ready` = '$status' WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "comment") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `comment` = '$text' WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "comment_payment") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `comment_payment` = '$text' WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "courier") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `courier` = '$text' WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "courier_r") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `courier_r` = '$text' WHERE `id` = ".$id);
  	 echo "done";
  }

   if ($event == "delivery_price") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `delivery_price` = '$text' WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "paid") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `paid` = $paid WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "fa") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `fa` = $fa WHERE `id` = ".$id);
  	 echo "done";
  }

  if ($event == "box_num") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `box_num` = '$text' WHERE `id` = ".$id);
  	 mysqli_query($conn, "UPDATE `orders_new` SET `box_id` = '$text' WHERE `id` = ".$id);
  	 echo "done";
  }


  if ($event == "delete_template_image") {
		$result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `image` LIKE '$image'");
		if (mysqli_num_rows($result_template_images) > 0) {
			$row_template_images = mysqli_fetch_assoc($result_template_images);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_template_images['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_template_images['image'], '120'));
			mysqli_query($conn, "DELETE FROM `template_images` WHERE `image` LIKE '$image'");
		}
		echo"done";
	}


	// Редактирование заказа
	 if ($event == "edit_order") {
	 	$mail = false;
    if (strtotime($take_date) > strtotime($return_date)) {
      echo "Vérifiez les dates de ramassage et de retour du box!";
    } else {
    //$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `num_id` LIKE '$num_id' AND `id` != ".$id);
    //if (mysqli_num_rows($result_orders) == 0 || $num_id == "") {
      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `password` LIKE '$password' AND `id` != ".$id);
      if (mysqli_num_rows($result_orders) == 0) {


        $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
        $row_orders = mysqli_fetch_assoc($result_orders);

        if (!file_exists("../uploads/Factures/".$num_id)) {
          mkdir("../uploads/Factures/".$num_id, 0777, true);
        }

        if ($row_orders['status'] == 0 && $status == 2) {
          file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/".$num_id.".pdf");
          mysqli_query($conn, "INSERT INTO `devis` (`id`, `order_id`, `pdf`, `created_at`) VALUES (NULL, '$id', '".$num_id.".pdf', '".time()."')");

          $num_id = "FA".$row_settings['facture'];
          if (!file_exists("../uploads/Factures/".$num_id)) {
            mkdir("../uploads/Factures/".$num_id, 0777, true);
          }
          mysqli_query($conn, "UPDATE `settings` SET `facture` = '".($row_settings['facture'] + 1)."' WHERE `id` = 1");
          file_put_contents($id."-".$num_id.date("dmYHi", time()).".txt", $num_id);

          mysqli_query($conn, "UPDATE `orders_new` SET `num_id` = '".trim($num_id)."', `status` = '$status'  WHERE `id` = ".$id) or die(mysqli_error($conn));

          file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/".$num_id.".pdf");
          mysqli_query($conn, "INSERT INTO `facture` (`id`, `order_id`, `pdf`, `created_at`) VALUES (NULL, '$id', '".$num_id.".pdf', '".time()."')");

          if ($row_orders['devis'] == 0) {
            mysqli_query($conn, "UPDATE `orders_new` SET `devis` = '".str_replace("FA", "", str_replace("DE", "", $row_orders['num_id']))."' WHERE `id` = ".$id) or die(mysqli_error($conn));
          }

          mysqli_query($conn, "UPDATE `orders_new` SET `updated_at` = '".time()."' WHERE `id` = ".$id) or die(mysqli_error($conn));
        }

        if ($status == 2) {
          $num_id = str_replace("DE", "FA", $num_id);
        }

        if ($row_orders['status'] == 2 && $status == 2) {
          if ($total < $row_orders['total']) {
            $result_avoir = mysqli_query($conn, "SELECT * FROM `avoir` WHERE `order_id` = ".$id);
            if (mysqli_num_rows($result_avoir) > 0) {
              $idx = mysqli_num_rows($result_avoir) - 1;
              $sufix = $alphabet[$idx];
            } else {
              $sufix = '';
            }
            if (strpos($row_orders['select_type'], 'entreprise') !== false) {
              file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT).".pdf&avoir=AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT)."&credit=".(($row_orders['total'] - $total)*1.2));
            } else {
              file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT).".pdf&avoir=AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT)."&credit=".($row_orders['total'] - $total));
            }
            mysqli_query($conn, "INSERT INTO `avoir` (`id`, `order_id`, `pdf`, `created_at`) VALUES (NULL, '$id', 'AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT).".pdf', '".time()."')");
            mysqli_query($conn, "UPDATE `settings` SET `avoir` = '".($row_settings['avoir'] + 1)."' WHERE `id` = 1");
            mysqli_query($conn, "UPDATE `orders_new` SET `updated_at` = '".time()."' WHERE `id` = ".$id) or die(mysqli_error($conn));
          }
        }


        mysqli_query($conn, "UPDATE `orders_new` SET `user_id` = '$user_id', `agency_id` = '$agency_id', `invite` = '$invite', `num_id` = '".trim($num_id)."',  `sage` = '".trim($sage)."', `password` = '$password', `ord` = '$ord', `select_type` = '$select_type', `box_type` = '$box_type', `price` = '$price', `amount` = '$amount', `event_date` = '$event_date', `event_time` = '$event_time', `event_place` = '$event_place', `return_place` = '$return_place', `event_type` = '$event_type', `societe` = '$societe', `description` = '$description', `about` = '$about', `selected_options` = '$selected_options', `delivery_options` = '$delivery_options', `last_name` = '$last_name', `first_name` = '$first_name', `city` = '$city', `address` = '".htmlspecialchars($address, ENT_QUOTES, 'UTF-8')."', `cp` = '$cp', `email` = '$email', `phone` = '$phone', `total` = '$total', `discount` = '$discount', `status`='$status', `advance_payment` = '$advance_payment', `take_date` = '$take_date', `return_date` = '$return_date', `take_time` = '$take_time', `return_time` = '$return_time', `horaire` = '$horaire', `transportation_time` = '$transportation_time', `box_id` = '$box_id', `email_title` = '".htmlspecialchars($_POST['email_title'], ENT_QUOTES, 'UTF-8')."',  `email_message` = '".htmlspecialchars($_POST['email_message'], ENT_QUOTES, 'UTF-8')."', `entreprise_pdf`='".htmlspecialchars($_POST['entreprise_pdf'], ENT_QUOTES, 'UTF-8')."', `address_pdf`='".htmlspecialchars($_POST['address_pdf'], ENT_QUOTES, 'UTF-8')."', `city_pdf` = '$city_pdf', `cp_pdf` = '$cp_pdf', `number_pdf`='$number_pdf', `other_pdf`='$other_pdf', `long_duration` = '$long_duration', `promocode` = '$promocode', `marriage` = '$marriage', `format_id` = '$format_id', `deposit_link` = '$deposit_link', `sale_link` = '$sale_link', `gallery_access` = '0'  WHERE `id` = ".$id) or die(mysqli_error($conn));

        if ($advance_payment > 0) {
          //mysqli_query($conn, "UPDATE `orders_new` SET `advance_payment = '0', `deposit` = '$advance_payment' WHERE `id` = ".$id) or die(mysqli_error($conn));
        }

        if ($row_orders['agenda'] == "0") {
          file_get_contents("https://ftp.shootnbox.fr/calendar2.php");
          file_get_contents("https://ftp.shootnbox.fr/calendar3.php?order_id=".$id);
        } else {
          file_get_contents("https://ftp.shootnbox.fr/calendar3.php?order_id=".$id);
        }


        if ($bornes != '') {
          $bornes_arr = explode(";", $bornes);
          mysqli_query($conn, "DELETE FROM `bornes` WHERE `order_id` = ".$id) or die(mysqli_error($conn));
          foreach ($bornes_arr as $key => $value) {
            $value_arr = explode("::", $value);
            mysqli_query($conn, "INSERT INTO `bornes`(`id`, `order_id`, `box_type`, `price`, `amount`, `box_id`, `selected_options`) VALUES (NULL, '$id', '".$value_arr[0]."', '".$value_arr[1]."', '".$value_arr[2]."', '".$value_arr[3]."', '".$value_arr[4]."')") or die(mysqli_error($conn));
          }
        } else {
          mysqli_query($conn, "DELETE FROM `bornes` WHERE `order_id` = ".$id) or die(mysqli_error($conn));
        }

        if ($image != "") {
          require_once("thumblib/ThumbLib.inc.php");
          $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
          $thumb->resize(120, 0);
          $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
          mysqli_query($conn, "UPDATE `orders_new` SET `image` = '$image', `template_status` = '0' WHERE `id` = ".$id);
          @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_ordersorders['image']);
          @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120'));
        }
        if ($image_vegas != "") {
          require_once("thumblib/ThumbLib.inc.php");
          $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image_vegas);
          $thumb->resize(120, 0);
          $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image_vegas, '120'));
          mysqli_query($conn, "UPDATE `orders_new` SET `image_vegas` = '$image_vegas', `template_status` = '0' WHERE `id` = ".$id);
          @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_ordersorders['image_vegas']);
          @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image_vegas'], '120'));
        }
        if ($images != "") {
          $images_arr = explode(";", $images);
          require_once("thumblib/ThumbLib.inc.php");
          foreach($images_arr as $key => $image) {
            $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
            $thumb->resize(120, 0);
            $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
            mysqli_query($conn, "INSERT INTO `template_images` (`id`, `order_id`, `image`) VALUES (NULL, '$id', '$image')");
          }
        }

        if ($row_orders['status'] == 2 && $status == 2) {
          if ($total > $row_orders['total']) {
            $result_facture = mysqli_query($conn, "SELECT * FROM `facture` WHERE `order_id` = ".$id);
            if (mysqli_num_rows($result_facture) > 0) {
              $idx = mysqli_num_rows($result_facture) - 1;
              $sufix = $alphabet[$idx];
            } else {
              $sufix = '';
            }
            file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/".$num_id.$sufix.".pdf");
            mysqli_query($conn, "INSERT INTO `facture` (`id`, `order_id`, `pdf`, `created_at`) VALUES (NULL, '$id', '".$num_id.$sufix.".pdf', '".time()."')") or die(mysqli_error($conn));
            mysqli_query($conn, "UPDATE `orders_new` SET `updated_at` = '".time()."' WHERE `id` = ".$id) or die(mysqli_error($conn));
          }
          /*if ($total < $row_orders['total']) {
            $result_avoir = mysqli_query($conn, "SELECT * FROM `avoir` WHERE `order_id` = ".$id);
            if (mysqli_num_rows($result_avoir) > 0) {
              $idx = mysqli_num_rows($result_avoir) - 1;
              $sufix = $alphabet[$idx];
            } else {
              $sufix = '';
            }
            if (strpos($row_orders['select_type'], 'entreprise') !== false) {
              file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT).".pdf&avoir=AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT)."&credit=".(($row_orders['total'] - $total)*1.2));
            } else {
              file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT).".pdf&avoir=AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT)."&credit=".($row_orders['total'] - $total));
            }
            mysqli_query($conn, "INSERT INTO `avoir` (`id`, `order_id`, `pdf`, `created_at`) VALUES (NULL, '$id', 'AV".str_pad($row_settings['avoir'], 5, '0', STR_PAD_LEFT).".pdf', '".time()."')");
            mysqli_query($conn, "UPDATE `settings` SET `avoir` = '".($row_settings['avoir'] + 1)."' WHERE `id` = 1");
            mysqli_query($conn, "UPDATE `orders_new` SET `updated_at` = '".time()."' WHERE `id` = ".$id) or die(mysqli_error($conn));
          }*/
        }

        if ($row_orders['status'] == 0 && $status == 0) {
          if ($total != $row_orders['total']) {
            $result_devis = mysqli_query($conn, "SELECT * FROM `devis` WHERE `order_id` = ".$id);
            if (mysqli_num_rows($result_devis) > 0) {
              $idx = mysqli_num_rows($result_devis) - 1;
              $sufix = $alphabet[$idx];
            } else {
              $sufix = '';
            }
            file_get_contents("https://ftp.shootnbox.fr/manager/to_pdf.php?order_id=".$id."&file_name=../uploads/Factures/".$num_id."/".$num_id.$sufix.".pdf");
            mysqli_query($conn, "INSERT INTO `devis` (`id`, `order_id`, `pdf`, `created_at`) VALUES (NULL, '$id', '".$num_id.$sufix.".pdf', '".time()."')") or die(mysqli_error($conn));
            mysqli_query($conn, "UPDATE `orders_new` SET `updated_at` = '".time()."' WHERE `id` = ".$id) or die(mysqli_error($conn));
          }
        }

        if ($row_orders['status'] == 0 && $status == 2) {
        	$subject = "Votre espace client Shootnbox ".date("d-m-Y H:i", time());
          $mailheaders = "MIME-Version: 1.0\r\n";
          $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
          $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
          $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
          $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
						  <head>
						    <meta charset="UTF-8">
						    <meta content="width=device-width, initial-scale=1" name="viewport">
						    <meta name="x-apple-disable-message-reformatting">
						    <meta http-equiv="X-UA-Compatible" content="IE=edge">
						    <meta content="telephone=no" name="format-detection">
						    <title></title>
						    <!--[if gte mso 9]><style>
						        sup { font-size: 100% !important; }
						      </style><![endif]-->
						    <style type="text/css">
						      /* CONFIG STYLES Please do not delete and edit CSS styles below */
						      /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
						      a[x-apple-data-detectors] {
						          color: inherit !important;
						          text-decoration: none !important;
						          font-size: inherit !important;
						          font-family: inherit !important;
						          font-weight: inherit !important;
						          line-height: inherit !important;
						      }
						      /*
						      END OF IMPORTANT
						      */
						      /*
						      END CONFIG STYLES
						      */
						      /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
						      @media only screen and (max-width: 600px) {
						          p,
						          ul li,
						          ol li,
						          a {
						        line-height: 150% !important;
						          }
						          h1,
						          h2,
						          h3,
						          h1 a,
						          h2 a,
						          h3 a {
						        line-height: 120%;
						          }
						          h1 {
						        font-size: 30px !important;
						        text-align: center;
						          }
						          h2 {
						        font-size: 26px !important;
						        text-align: center;
						          }
						          h3 {
						        font-size: 20px !important;
						        text-align: center;
						          }
						          .es-header-body h1 a,
						          .es-content-body h1 a,
						          .es-footer-body h1 a {
						        font-size: 30px !important;
						          }
						          .es-header-body h2 a,
						          .es-content-body h2 a,
						          .es-footer-body h2 a {
						        font-size: 26px !important;
						          }
						          .es-header-body h3 a,
						          .es-content-body h3 a,
						          .es-footer-body h3 a {
						        font-size: 20px !important;
						          }
						          .es-menu td a {
						        font-size: 16px !important;
						          }
						          .es-header-body p,
						          .es-header-body ul li,
						          .es-header-body ol li,
						          .es-header-body a {
						        font-size: 16px !important;
						          }
						          .es-content-body p,
						          .es-content-body ul li,
						          .es-content-body ol li,
						          .es-content-body a {
						        font-size: 16px !important;
						          }
						          .es-footer-body p,
						          .es-footer-body ul li,
						          .es-footer-body ol li,
						          .es-footer-body a {
						        font-size: 16px !important;
						          }
						          .es-infoblock p,
						          .es-infoblock ul li,
						          .es-infoblock ol li,
						          .es-infoblock a {
						        font-size: 12px !important;
						          }
						          *[class="gmail-fix"] {
						        display: none !important;
						          }
						          .es-m-txt-c,
						          .es-m-txt-c h1,
						          .es-m-txt-c h2,
						          .es-m-txt-c h3 {
						        text-align: center !important;
						          }
						          .es-m-txt-r,
						          .es-m-txt-r h1,
						          .es-m-txt-r h2,
						          .es-m-txt-r h3 {
						        text-align: right !important;
						          }
						          .es-m-txt-l,
						          .es-m-txt-l h1,
						          .es-m-txt-l h2,
						          .es-m-txt-l h3 {
						        text-align: left !important;
						          }
						          .es-m-txt-r img,
						          .es-m-txt-c img,
						          .es-m-txt-l img {
						        display: inline !important;
						          }
						          .es-adaptive table,
						          .es-left,
						          .es-right {
						        width: 100% !important;
						          }
						          .es-content table,
						          .es-header table,
						          .es-footer table,
						          .es-content,
						          .es-footer,
						          .es-header {
						        width: 100% !important;
						        max-width: 600px !important;
						          }
						          .es-adapt-td {
						        display: block !important;
						        width: 100% !important;
						          }
						          .adapt-img {
						        width: 100% !important;
						        height: auto !important;
						          }
						          .es-m-p0 {
						        padding: 0 !important;
						          }
						          .es-m-p0r {
						        padding-right: 0 !important;
						          }
						          .es-m-p0l {
						        padding-left: 0 !important;
						          }
						          .es-m-p0t {
						        padding-top: 0 !important;
						          }
						          .es-m-p0b {
						        padding-bottom: 0 !important;
						          }
						          .es-m-p20b {
						        padding-bottom: 20px !important;
						          }
						          .es-mobile-hidden,
						          .es-hidden {
						        display: none !important;
						          }
						          tr.es-desk-hidden,
						          td.es-desk-hidden,
						          table.es-desk-hidden {
						        width: auto !important;
						        overflow: visible !important;
						        float: none !important;
						        max-height: inherit !important;
						        line-height: inherit !important;
						          }
						          tr.es-desk-hidden {
						        display: table-row !important;
						          }
						          table.es-desk-hidden {
						        display: table !important;
						          }
						          td.es-desk-menu-hidden {
						        display: table-cell !important;
						          }
						          .es-menu td {
						        width: 1% !important;
						          }
						          table.es-table-not-adapt,
						          .esd-block-html table {
						        width: auto !important;
						          }
						          table.es-social {
						        display: inline-block !important;
						          }
						          table.es-social td {
						        display: inline-block !important;
						          }
						          .es-desk-hidden {
						        display: table-row !important;
						        width: auto !important;
						        overflow: visible !important;
						        max-height: inherit !important;
						          }
						          .es-m-p5 {
						        padding: 5px !important;
						          }
						          .es-m-p5t {
						        padding-top: 5px !important;
						          }
						          .es-m-p5b {
						        padding-bottom: 5px !important;
						          }
						          .es-m-p5r {
						        padding-right: 5px !important;
						          }
						          .es-m-p5l {
						        padding-left: 5px !important;
						          }
						          .es-m-p10 {
						        padding: 10px !important;
						          }
						          .es-m-p10t {
						        padding-top: 10px !important;
						          }
						          .es-m-p10b {
						        padding-bottom: 10px !important;
						          }
						          .es-m-p10r {
						        padding-right: 10px !important;
						          }
						          .es-m-p10l {
						        padding-left: 10px !important;
						          }
						          .es-m-p15 {
						        padding: 15px !important;
						          }
						          .es-m-p15t {
						        padding-top: 15px !important;
						          }
						          .es-m-p15b {
						        padding-bottom: 15px !important;
						          }
						          .es-m-p15r {
						        padding-right: 15px !important;
						          }
						          .es-m-p15l {
						        padding-left: 15px !important;
						          }
						          .es-m-p20 {
						        padding: 20px !important;
						          }
						          .es-m-p20t {
						        padding-top: 20px !important;
						          }
						          .es-m-p20r {
						        padding-right: 20px !important;
						          }
						          .es-m-p20l {
						        padding-left: 20px !important;
						          }
						          .es-m-p25 {
						        padding: 25px !important;
						          }
						          .es-m-p25t {
						        padding-top: 25px !important;
						          }
						          .es-m-p25b {
						        padding-bottom: 25px !important;
						          }
						          .es-m-p25r {
						        padding-right: 25px !important;
						          }
						          .es-m-p25l {
						        padding-left: 25px !important;
						          }
						          .es-m-p30 {
						        padding: 30px !important;
						          }
						          .es-m-p30t {
						        padding-top: 30px !important;
						          }
						          .es-m-p30b {
						        padding-bottom: 30px !important;
						          }
						          .es-m-p30r {
						        padding-right: 30px !important;
						          }
						          .es-m-p30l {
						        padding-left: 30px !important;
						          }
						          .es-m-p35 {
						        padding: 35px !important;
						          }
						          .es-m-p35t {
						        padding-top: 35px !important;
						          }
						          .es-m-p35b {
						        padding-bottom: 35px !important;
						          }
						          .es-m-p35r {
						        padding-right: 35px !important;
						          }
						          .es-m-p35l {
						        padding-left: 35px !important;
						          }
						          .es-m-p40 {
						        padding: 40px !important;
						          }
						          .es-m-p40t {
						        padding-top: 40px !important;
						          }
						          .es-m-p40b {
						        padding-bottom: 40px !important;
						          }
						          .es-m-p40r {
						        padding-right: 40px !important;
						          }
						          .es-m-p40l {
						        padding-left: 40px !important;
						          }
						          .es-content-body .fz46 {
						        font-size: 46px !important;
						          }
						          .custom_padding {
						        padding-right: 0 !important;
						        padding-left: 0 !important;
						          }
						      }
						      /* END RESPONSIVE STYLES */
						    </style>
						  </head>
						  <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
						    <div class="es-wrapper-color" style="background-color:#f6f6f6;">
						      <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;background-color:#f6f6f6;">
						        <tbody>
						          <tr>
						            <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
						              <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
						                <tbody>
						                  <tr>
						                    <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
						                      <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
						                        <tbody>
						                          <tr>
						                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_36d8f38bcb5973575b8b7d024ca409735a92d35124f3ceec002556594bdaf13a/images/logo.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="192"></a></td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p20t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-text es-p20b" style="padding:0;Margin:0;padding-bottom:20px;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #da3a8d; font-size: 15px;"><strong>Bonjour,</strong></p>
						                                            </td>
						                                          </tr>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p class="custom_padding" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;padding-right:100px;padding-left:100px;color: #da3a8d; font-size: 15px;"><strong>Merci pour votre confiance et bienvenue dans la Shootnbox Family&nbsp;!</strong></p>
						                                            </td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Vous pouvez dès à présent vous connecter à votre <strong>Espace Client !</strong></p>
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Celui-ci est votre interface dédiée, regroupant toutes vos informations.</p>
						                                            </td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-text es-p40r es-p40l es-m-p0r es-m-p0l" style="padding:0;Margin:0;padding-left:40px;padding-right:40px;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Veuillez complétez les champs requis afin de finaliser les détails indispensables de votre événement.</p>
						                                            </td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p30t es-p40r es-p40l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-top:30px;padding-left:40px;padding-right:40px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="520" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-left:3px solid #ff0000;border-right:3px solid #ff0000;border-top:3px solid #ff0000;border-bottom:3px solid #ff0000;border-radius: 12px; border-collapse: separate;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-text es-p15t es-p10r es-p10l" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:15px;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #ff0000; font-size: 15px;"><strong>ATTENTION !</strong></p>
						                                            </td>
						                                          </tr>
						                                          <tr>
						                                            <td align="center" class="esd-block-text es-p15b es-p10r es-p10l" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-bottom:15px;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #ff0000; font-size: 15px;"><strong>Merci de remplir ces informations avant le : '.date("d.m.Y", strtotime($event_date) - 7*24*3600).'</strong></p>
						                                            </td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-button" style="padding:0;Margin:0;"><span class="es-button-border" style="border:none;display:inline-block;border-radius:30px;width:auto;border-width: 0px; background: #da3a8d;"><a href="https://ftp.shootnbox.fr/cabinet/" class="es-button" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;display:inline-block;border-radius:30px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:120%;color:#ffffff;text-decoration:none;width:auto;text-align:center;padding:10px 20px 10px 20px;mso-padding-alt:0;mso-style-priority:100 !important;text-decoration:none !important;background: #da3a8d; font-size: 17px;border:none;">Cliquez ici pour y accéder</a></span></td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p30t es-p40b es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:40px;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong>Vos identifiants pour vous connecter sont les suivants:</strong></p>
						                                            </td>
						                                          </tr>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong></strong><strong><span style="color:#D23680;">Identifiant:</span> '.$num_id.'</strong></p>
						                                            </td>
						                                          </tr>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#D23680;">Mot de passe:</span> '.$password.'</strong></p>
						                                            </td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                          <tr>
						                            <td class="esd-structure es-p20" align="left" bgcolor="#707070" style="padding:0;Margin:0;padding:20px;background-color: #707070;">
						                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                <tbody>
						                                  <tr>
						                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
						                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
						                                        <tbody>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">Shootnbox</p>
						                                            </td>
						                                          </tr>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">contact@shootnbox.fr 01 45</p>
						                                            </td>
						                                          </tr>
						                                          <tr>
						                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
						                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">01 45 01 66 66</p>
						                                            </td>
						                                          </tr>
						                                        </tbody>
						                                      </table>
						                                    </td>
						                                  </tr>
						                                </tbody>
						                              </table>
						                            </td>
						                          </tr>
						                        </tbody>
						                      </table>
						                    </td>
						                  </tr>
						                </tbody>
						              </table>
						            </td>
						          </tr>
						        </tbody>
						      </table>
						    </div>
						  </body>
						</html>';
          mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
          mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
          mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        }
        echo"done";
      } else {
        echo"Une autre commande avec le même mot de passe existe déjà !";
      }
    //} else {
      //echo"Une autre commande avec le même Devis N° existe déjà !";
    //}
    }
  }

	// Удаление заказа
	if ($event == "delete_order") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_orders) != 0) {
			file_get_contents("https://ftp.shootnbox.fr/calendar4.php?order_id=".$id);
			mysqli_query($conn, "DELETE FROM `orders_new` WHERE id = ".$id) or die(mysqli_error($conn));
			echo"done";
		} else {
			echo"Ce commande n'existe pas!";
		}
	}

	if ($event == "facteur") {
    mysqli_query($conn, "UPDATE `orders_new` SET `facteur` = $facteur WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "facture_enabled") {
    mysqli_query($conn, "UPDATE `orders_new` SET `facture_enabled` = $enabled WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

	if ($event == "refuse_order") {
    mysqli_query($conn, "UPDATE `orders_new` SET `status` = -1, `refuse_title` = '$refuse_title' WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "error_order") {
    mysqli_query($conn, "UPDATE `orders_new` SET `status` = -2 WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "off_order") {
    mysqli_query($conn, "UPDATE `orders_new` SET `off` = 1 WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "on_order") {
    mysqli_query($conn, "UPDATE `orders_new` SET `off` = 0 WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "validation") {
    mysqli_query($conn, "UPDATE `orders_new` SET `validation` = $validation WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "relaunch") {
    mysqli_query($conn, "UPDATE `orders_new` SET `relaunch` = $relaunch WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "pay_order") {
    mysqli_query($conn, "UPDATE `orders_new` SET `payment_status` = 2 WHERE id = ".$id) or die(mysqli_error($conn));
    echo"done";
  }

  if ($event == "add_promo") {
    mysqli_query($conn, "INSERT INTO `promocode`(`id`, `promocode`, `start_date`, `end_date`, `weekday`, `sum`, `bornes_ids`) VALUES (NULL, '$promocode', '".strtotime($start_date)."', '".strtotime($end_date)."', '$weekday', '$sum', '$bornes_ids')");
    echo"done";
  }

  // Удаление варианта доставки
  if ($event == "promo_delete") {
    mysqli_query($conn, "DELETE FROM `promocode` WHERE `id` = ".$id);
    echo"done";
  }

  if ($event == "edit_promo") {
    mysqli_query($conn, "UPDATE `promocode` SET `promocode` = '$promocode', `start_date` = '".strtotime($start_date)."', `end_date` = '".strtotime($end_date)."', `weekday` = '$weekday', `sum` = '$sum', `bornes_ids` = '$bornes_ids' WHERE `id` = ".$id);
    echo"done";
  }

  if ($event == "check_promo") {
    $result_promocode = mysqli_query($conn, "SELECT `sum` FROM `promocode` WHERE `promocode` LIKE '$promocode'");
    if (mysqli_num_rows($result_promocode) == 0) {
      echo"error";
    } else {
      $result_promocode = mysqli_query($conn, "SELECT `sum` FROM `promocode` WHERE ".strtotime($event_date)." > `start_date` AND ".strtotime($event_date)." < `end_date` AND `weekday` LIKE '%".date('w')."%' AND `promocode` LIKE '$promocode'");
      if (mysqli_num_rows($result_promocode) == 0) {
        echo"outdate";
      } else {
        $row_promocode = mysqli_fetch_assoc($result_promocode);
        echo $row_promocode['sum'];
      }
    }
  }

  if ($event == "to_mail") {
    mysqli_query($conn, "UPDATE `orders_new` SET `to_mail` = $to_mail WHERE id = ".$id) or die(mysqli_error($conn));
    if ($to_mail == 1) {
    	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
			$row_orders = mysqli_fetch_assoc($result_orders);
			$result_users = mysqli_query($conn, "SELECT * FROM `users` WHERE `id` = ".$_SESSION['user']['id']);
			$row_users = mysqli_fetch_assoc($result_users);
			$subject = htmlspecialchars_decode($row_orders['email_title'], ENT_QUOTES)." ".date("d-m-Y H:i", time());
			$message = htmlspecialchars_decode($row_orders['email_message'].$row_users['signature'].$row_settings['email_signature'], ENT_QUOTES);

			$message = str_replace('notre devis', '<a href="https://ftp.shootnbox.fr/manager/to_pdf.php?order_id='.$row_orders['id'].'">notre devis</a>', $message);

			if (strpos($select_type, 'entreprise') === false) {
				$prefix = "2";
			} else {
				$prefix = "";
			}

			switch($row_orders['box_type']) {
	  		case "Ring":
	  		case "Ring_promotionnel": $message = str_replace('notre brochure', '<a href="https://ftp.shootnbox.fr/uploads/images/'.$settings['ring_pdf'.$prefix].'">notre brochure</a>', $message); break;
	  		case "Vegas":
        case "Vegas_800":
        case "Vegas_1200":
        case "Vegas_1600":
        case "Vegas_2000": $message = str_replace('notre brochure', '<a href="https://ftp.shootnbox.fr/uploads/images/'.$settings['vegas_pdf'.$prefix].'">notre brochure</a>', $message); break;
	  		case "Miroir":
        case "Miroir_800":
        case "Miroir_1200":
        case "Miroir_1600":
        case "Miroir_2000": $message = str_replace('notre brochure', '<a href="https://ftp.shootnbox.fr/uploads/images/'.$settings['miroir_pdf'.$prefix].'">notre brochure</a>', $message); break;
	  		case "Spinner_360": $message = str_replace('notre brochure', '<a href="https://ftp.shootnbox.fr/uploads/images/'.$settings['spinner_pdf'.$prefix].'">notre brochure</a>', $message); break;
	  		case "Réalité_Virtuelle": $message = str_replace('notre brochure', '<a href="https://ftp.shootnbox.fr/uploads/images/'.$settings['vr_pdf'.$prefix].'">notre brochure</a>', $message); break;
	  		case "Aircam_360": $message = str_replace('notre brochure', '<a href="https://ftp.shootnbox.fr/uploads/images/'.$settings['aircam_pdf'.$prefix].'">notre brochure</a>', $message); break;
	  	}

			$mailheaders = "MIME-Version: 1.0\r\n";
			$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
			$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
			$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
			$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	            <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
	              <head>
	                <meta charset="UTF-8">
	                <meta content="width=device-width, initial-scale=1" name="viewport">
	                <meta name="x-apple-disable-message-reformatting">
	                <meta http-equiv="X-UA-Compatible" content="IE=edge">
	                <meta content="telephone=no" name="format-detection">
	                <title>'.$subject.'</title>
	              </head>
                <body>
                	'.$message.'
                </body>
              </html>';
      mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
      mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
      mail("info@evput.com", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
    }
    echo"done";
  }

  if ($event == "send_sms") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
		$row_orders = mysqli_fetch_assoc($result_orders);
		$message = htmlspecialchars_decode($row_settings['sms1'], ENT_QUOTES);
		$long = $row_settings['long1'];

    ob_start();
    // CapitoleMobile POST URL
    $postUrl = "https://sms.capitolemobile.com/api/sendsms/xml";

    //Structure de Données XML
    $xmlString = '<SMS>
      <authentification>
        <username>Shootnbox</username>
        <password>5ec51a28d3bcf5c3cc6dde3e4a452ad0b3b09e70</password>
      </authentification>
      <message>
        <text>'.$message.'</text>
        <long>'.$long.'</long>
        <sender>ShootnBox</sender>
      </message>
      <recipients>
        <gsm>'.preg_replace("/[^0-9\s]/", '', $row_orders['phone']).'</gsm>
      </recipients>
    </SMS>';

    // insertion du nom de la variable POST "XML" avant les données au format XML
    $fields = "XML=" . urlencode($xmlString);

    // dans cet exemple, la requête POST est realisée grâce à la librairie Curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $postUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    // Réponse de la requête POST
    $response = curl_exec($ch);
    curl_close($ch);
		ob_get_clean();
		echo"done";
	}

	// Редактирование заказа
	if ($event == "send_mail") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
		$row_orders = mysqli_fetch_assoc($result_orders);
    mysqli_query($conn, "UPDATE `orders_new` SET `send_mail` = 1 WHERE `id` = ".$id);

		$subject = "Votre espace client Shootnbox ".date("d-m-Y H:i", time());
		$mailheaders = "MIME-Version: 1.0\r\n";
		$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
		  <head>
		    <meta charset="UTF-8">
		    <meta content="width=device-width, initial-scale=1" name="viewport">
		    <meta name="x-apple-disable-message-reformatting">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta content="telephone=no" name="format-detection">
		    <title></title>
		    <!--[if gte mso 9]><style>
		        sup { font-size: 100% !important; }
		      </style><![endif]-->
		    <style type="text/css">
		      /* CONFIG STYLES Please do not delete and edit CSS styles below */
		      /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
		      a[x-apple-data-detectors] {
		          color: inherit !important;
		          text-decoration: none !important;
		          font-size: inherit !important;
		          font-family: inherit !important;
		          font-weight: inherit !important;
		          line-height: inherit !important;
		      }
		      /*
		      END OF IMPORTANT
		      */
		      /*
		      END CONFIG STYLES
		      */
		      /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
		      @media only screen and (max-width: 600px) {
		          p,
		          ul li,
		          ol li,
		          a {
		        line-height: 150% !important;
		          }
		          h1,
		          h2,
		          h3,
		          h1 a,
		          h2 a,
		          h3 a {
		        line-height: 120%;
		          }
		          h1 {
		        font-size: 30px !important;
		        text-align: center;
		          }
		          h2 {
		        font-size: 26px !important;
		        text-align: center;
		          }
		          h3 {
		        font-size: 20px !important;
		        text-align: center;
		          }
		          .es-header-body h1 a,
		          .es-content-body h1 a,
		          .es-footer-body h1 a {
		        font-size: 30px !important;
		          }
		          .es-header-body h2 a,
		          .es-content-body h2 a,
		          .es-footer-body h2 a {
		        font-size: 26px !important;
		          }
		          .es-header-body h3 a,
		          .es-content-body h3 a,
		          .es-footer-body h3 a {
		        font-size: 20px !important;
		          }
		          .es-menu td a {
		        font-size: 16px !important;
		          }
		          .es-header-body p,
		          .es-header-body ul li,
		          .es-header-body ol li,
		          .es-header-body a {
		        font-size: 16px !important;
		          }
		          .es-content-body p,
		          .es-content-body ul li,
		          .es-content-body ol li,
		          .es-content-body a {
		        font-size: 16px !important;
		          }
		          .es-footer-body p,
		          .es-footer-body ul li,
		          .es-footer-body ol li,
		          .es-footer-body a {
		        font-size: 16px !important;
		          }
		          .es-infoblock p,
		          .es-infoblock ul li,
		          .es-infoblock ol li,
		          .es-infoblock a {
		        font-size: 12px !important;
		          }
		          *[class="gmail-fix"] {
		        display: none !important;
		          }
		          .es-m-txt-c,
		          .es-m-txt-c h1,
		          .es-m-txt-c h2,
		          .es-m-txt-c h3 {
		        text-align: center !important;
		          }
		          .es-m-txt-r,
		          .es-m-txt-r h1,
		          .es-m-txt-r h2,
		          .es-m-txt-r h3 {
		        text-align: right !important;
		          }
		          .es-m-txt-l,
		          .es-m-txt-l h1,
		          .es-m-txt-l h2,
		          .es-m-txt-l h3 {
		        text-align: left !important;
		          }
		          .es-m-txt-r img,
		          .es-m-txt-c img,
		          .es-m-txt-l img {
		        display: inline !important;
		          }
		          .es-adaptive table,
		          .es-left,
		          .es-right {
		        width: 100% !important;
		          }
		          .es-content table,
		          .es-header table,
		          .es-footer table,
		          .es-content,
		          .es-footer,
		          .es-header {
		        width: 100% !important;
		        max-width: 600px !important;
		          }
		          .es-adapt-td {
		        display: block !important;
		        width: 100% !important;
		          }
		          .adapt-img {
		        width: 100% !important;
		        height: auto !important;
		          }
		          .es-m-p0 {
		        padding: 0 !important;
		          }
		          .es-m-p0r {
		        padding-right: 0 !important;
		          }
		          .es-m-p0l {
		        padding-left: 0 !important;
		          }
		          .es-m-p0t {
		        padding-top: 0 !important;
		          }
		          .es-m-p0b {
		        padding-bottom: 0 !important;
		          }
		          .es-m-p20b {
		        padding-bottom: 20px !important;
		          }
		          .es-mobile-hidden,
		          .es-hidden {
		        display: none !important;
		          }
		          tr.es-desk-hidden,
		          td.es-desk-hidden,
		          table.es-desk-hidden {
		        width: auto !important;
		        overflow: visible !important;
		        float: none !important;
		        max-height: inherit !important;
		        line-height: inherit !important;
		          }
		          tr.es-desk-hidden {
		        display: table-row !important;
		          }
		          table.es-desk-hidden {
		        display: table !important;
		          }
		          td.es-desk-menu-hidden {
		        display: table-cell !important;
		          }
		          .es-menu td {
		        width: 1% !important;
		          }
		          table.es-table-not-adapt,
		          .esd-block-html table {
		        width: auto !important;
		          }
		          table.es-social {
		        display: inline-block !important;
		          }
		          table.es-social td {
		        display: inline-block !important;
		          }
		          .es-desk-hidden {
		        display: table-row !important;
		        width: auto !important;
		        overflow: visible !important;
		        max-height: inherit !important;
		          }
		          .es-m-p5 {
		        padding: 5px !important;
		          }
		          .es-m-p5t {
		        padding-top: 5px !important;
		          }
		          .es-m-p5b {
		        padding-bottom: 5px !important;
		          }
		          .es-m-p5r {
		        padding-right: 5px !important;
		          }
		          .es-m-p5l {
		        padding-left: 5px !important;
		          }
		          .es-m-p10 {
		        padding: 10px !important;
		          }
		          .es-m-p10t {
		        padding-top: 10px !important;
		          }
		          .es-m-p10b {
		        padding-bottom: 10px !important;
		          }
		          .es-m-p10r {
		        padding-right: 10px !important;
		          }
		          .es-m-p10l {
		        padding-left: 10px !important;
		          }
		          .es-m-p15 {
		        padding: 15px !important;
		          }
		          .es-m-p15t {
		        padding-top: 15px !important;
		          }
		          .es-m-p15b {
		        padding-bottom: 15px !important;
		          }
		          .es-m-p15r {
		        padding-right: 15px !important;
		          }
		          .es-m-p15l {
		        padding-left: 15px !important;
		          }
		          .es-m-p20 {
		        padding: 20px !important;
		          }
		          .es-m-p20t {
		        padding-top: 20px !important;
		          }
		          .es-m-p20r {
		        padding-right: 20px !important;
		          }
		          .es-m-p20l {
		        padding-left: 20px !important;
		          }
		          .es-m-p25 {
		        padding: 25px !important;
		          }
		          .es-m-p25t {
		        padding-top: 25px !important;
		          }
		          .es-m-p25b {
		        padding-bottom: 25px !important;
		          }
		          .es-m-p25r {
		        padding-right: 25px !important;
		          }
		          .es-m-p25l {
		        padding-left: 25px !important;
		          }
		          .es-m-p30 {
		        padding: 30px !important;
		          }
		          .es-m-p30t {
		        padding-top: 30px !important;
		          }
		          .es-m-p30b {
		        padding-bottom: 30px !important;
		          }
		          .es-m-p30r {
		        padding-right: 30px !important;
		          }
		          .es-m-p30l {
		        padding-left: 30px !important;
		          }
		          .es-m-p35 {
		        padding: 35px !important;
		          }
		          .es-m-p35t {
		        padding-top: 35px !important;
		          }
		          .es-m-p35b {
		        padding-bottom: 35px !important;
		          }
		          .es-m-p35r {
		        padding-right: 35px !important;
		          }
		          .es-m-p35l {
		        padding-left: 35px !important;
		          }
		          .es-m-p40 {
		        padding: 40px !important;
		          }
		          .es-m-p40t {
		        padding-top: 40px !important;
		          }
		          .es-m-p40b {
		        padding-bottom: 40px !important;
		          }
		          .es-m-p40r {
		        padding-right: 40px !important;
		          }
		          .es-m-p40l {
		        padding-left: 40px !important;
		          }
		          .es-content-body .fz46 {
		        font-size: 46px !important;
		          }
		          .custom_padding {
		        padding-right: 0 !important;
		        padding-left: 0 !important;
		          }
		      }
		      /* END RESPONSIVE STYLES */
		    </style>
		  </head>
		  <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
		    <div class="es-wrapper-color" style="background-color:#f6f6f6;">
		      <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;background-color:#f6f6f6;">
		        <tbody>
		          <tr>
		            <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
		              <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
		                <tbody>
		                  <tr>
		                    <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
		                      <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
		                        <tbody>
		                          <tr>
		                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_36d8f38bcb5973575b8b7d024ca409735a92d35124f3ceec002556594bdaf13a/images/logo.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="192"></a></td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p20t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text es-p20b" style="padding:0;Margin:0;padding-bottom:20px;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #da3a8d; font-size: 15px;"><strong>Bonjour,</strong></p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p class="custom_padding" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;padding-right:100px;padding-left:100px;color: #da3a8d; font-size: 15px;"><strong>Merci pour votre confiance et bienvenue dans la Shootnbox Family&nbsp;!</strong></p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Vous pouvez dès à présent vous connecter à votre <strong>Espace Client !</strong></p>
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Celui-ci est votre interface dédiée, regroupant toutes vos informations.</p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text es-p40r es-p40l es-m-p0r es-m-p0l" style="padding:0;Margin:0;padding-left:40px;padding-right:40px;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;">Veuillez complétez les champs requis afin de finaliser les détails indispensables de votre événement.</p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p30t es-p40r es-p40l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-top:30px;padding-left:40px;padding-right:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="520" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-left:3px solid #ff0000;border-right:3px solid #ff0000;border-top:3px solid #ff0000;border-bottom:3px solid #ff0000;border-radius: 12px; border-collapse: separate;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text es-p15t es-p10r es-p10l" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:15px;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #ff0000; font-size: 15px;"><strong>ATTENTION !</strong></p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text es-p15b es-p10r es-p10l" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-bottom:15px;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #ff0000; font-size: 15px;"><strong>Merci de remplir ces informations avant le : 05/06/2023</strong></p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-button" style="padding:0;Margin:0;"><span class="es-button-border" style="border:none;display:inline-block;border-radius:30px;width:auto;border-width: 0px; background: #da3a8d;"><a href="https://ftp.shootnbox.fr/cabinet/" class="es-button" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;display:inline-block;border-radius:30px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:120%;color:#ffffff;text-decoration:none;width:auto;text-align:center;padding:10px 20px 10px 20px;mso-padding-alt:0;mso-style-priority:100 !important;text-decoration:none !important;background: #da3a8d; font-size: 17px;border:none;">Cliquez ici pour y accéder</a></span></td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p30t es-p40b es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong>Vos identifiants pour vous connecter sont les suivants:</strong></p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong></strong><strong><span style="color:#D23680;">Identifiant:</span> '.$row_orders['num_id'].'</strong></p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#D23680;">Mot de passe:</span> '.$row_orders['password'].'</strong></p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p20" align="left" bgcolor="#707070" style="padding:0;Margin:0;padding:20px;background-color: #707070;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">Shootnbox</p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">contact@shootnbox.fr 01 45</p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">01 45 01 66 66</p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                        </tbody>
		                      </table>
		                    </td>
		                  </tr>
		                </tbody>
		              </table>
		            </td>
		          </tr>
		        </tbody>
		      </table>
		    </div>
		  </body>
		</html>';
		mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
    mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		echo"done";
	}

		// Редактирование заказа
	if ($event == "change_mail") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
		$row_orders = mysqli_fetch_assoc($result_orders);

		$subject = "Votre espace client Shootnbox ".date("d-m-Y H:i", time());
		$mailheaders = "MIME-Version: 1.0\r\n";
		$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
		  <head>
		    <meta charset="UTF-8">
		    <meta content="width=device-width, initial-scale=1" name="viewport">
		    <meta name="x-apple-disable-message-reformatting">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta content="telephone=no" name="format-detection">
		    <title></title>
		    <!--[if gte mso 9]><style>
		        sup { font-size: 100% !important; }
		      </style><![endif]-->
		    <style type="text/css">
		      /* CONFIG STYLES Please do not delete and edit CSS styles below */
		      /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
		      a[x-apple-data-detectors] {
		          color: inherit !important;
		          text-decoration: none !important;
		          font-size: inherit !important;
		          font-family: inherit !important;
		          font-weight: inherit !important;
		          line-height: inherit !important;
		      }
		      /*
		      END OF IMPORTANT
		      */
		      /*
		      END CONFIG STYLES
		      */
		      /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
		      @media only screen and (max-width: 600px) {
		          p,
		          ul li,
		          ol li,
		          a {
		        line-height: 150% !important;
		          }
		          h1,
		          h2,
		          h3,
		          h1 a,
		          h2 a,
		          h3 a {
		        line-height: 120%;
		          }
		          h1 {
		        font-size: 30px !important;
		        text-align: center;
		          }
		          h2 {
		        font-size: 26px !important;
		        text-align: center;
		          }
		          h3 {
		        font-size: 20px !important;
		        text-align: center;
		          }
		          .es-header-body h1 a,
		          .es-content-body h1 a,
		          .es-footer-body h1 a {
		        font-size: 30px !important;
		          }
		          .es-header-body h2 a,
		          .es-content-body h2 a,
		          .es-footer-body h2 a {
		        font-size: 26px !important;
		          }
		          .es-header-body h3 a,
		          .es-content-body h3 a,
		          .es-footer-body h3 a {
		        font-size: 20px !important;
		          }
		          .es-menu td a {
		        font-size: 16px !important;
		          }
		          .es-header-body p,
		          .es-header-body ul li,
		          .es-header-body ol li,
		          .es-header-body a {
		        font-size: 16px !important;
		          }
		          .es-content-body p,
		          .es-content-body ul li,
		          .es-content-body ol li,
		          .es-content-body a {
		        font-size: 16px !important;
		          }
		          .es-footer-body p,
		          .es-footer-body ul li,
		          .es-footer-body ol li,
		          .es-footer-body a {
		        font-size: 16px !important;
		          }
		          .es-infoblock p,
		          .es-infoblock ul li,
		          .es-infoblock ol li,
		          .es-infoblock a {
		        font-size: 12px !important;
		          }
		          *[class="gmail-fix"] {
		        display: none !important;
		          }
		          .es-m-txt-c,
		          .es-m-txt-c h1,
		          .es-m-txt-c h2,
		          .es-m-txt-c h3 {
		        text-align: center !important;
		          }
		          .es-m-txt-r,
		          .es-m-txt-r h1,
		          .es-m-txt-r h2,
		          .es-m-txt-r h3 {
		        text-align: right !important;
		          }
		          .es-m-txt-l,
		          .es-m-txt-l h1,
		          .es-m-txt-l h2,
		          .es-m-txt-l h3 {
		        text-align: left !important;
		          }
		          .es-m-txt-r img,
		          .es-m-txt-c img,
		          .es-m-txt-l img {
		        display: inline !important;
		          }
		          .es-adaptive table,
		          .es-left,
		          .es-right {
		        width: 100% !important;
		          }
		          .es-content table,
		          .es-header table,
		          .es-footer table,
		          .es-content,
		          .es-footer,
		          .es-header {
		        width: 100% !important;
		        max-width: 600px !important;
		          }
		          .es-adapt-td {
		        display: block !important;
		        width: 100% !important;
		          }
		          .adapt-img {
		        width: 100% !important;
		        height: auto !important;
		          }
		          .es-m-p0 {
		        padding: 0 !important;
		          }
		          .es-m-p0r {
		        padding-right: 0 !important;
		          }
		          .es-m-p0l {
		        padding-left: 0 !important;
		          }
		          .es-m-p0t {
		        padding-top: 0 !important;
		          }
		          .es-m-p0b {
		        padding-bottom: 0 !important;
		          }
		          .es-m-p20b {
		        padding-bottom: 20px !important;
		          }
		          .es-mobile-hidden,
		          .es-hidden {
		        display: none !important;
		          }
		          tr.es-desk-hidden,
		          td.es-desk-hidden,
		          table.es-desk-hidden {
		        width: auto !important;
		        overflow: visible !important;
		        float: none !important;
		        max-height: inherit !important;
		        line-height: inherit !important;
		          }
		          tr.es-desk-hidden {
		        display: table-row !important;
		          }
		          table.es-desk-hidden {
		        display: table !important;
		          }
		          td.es-desk-menu-hidden {
		        display: table-cell !important;
		          }
		          .es-menu td {
		        width: 1% !important;
		          }
		          table.es-table-not-adapt,
		          .esd-block-html table {
		        width: auto !important;
		          }
		          table.es-social {
		        display: inline-block !important;
		          }
		          table.es-social td {
		        display: inline-block !important;
		          }
		          .es-desk-hidden {
		        display: table-row !important;
		        width: auto !important;
		        overflow: visible !important;
		        max-height: inherit !important;
		          }
		          .es-m-p5 {
		        padding: 5px !important;
		          }
		          .es-m-p5t {
		        padding-top: 5px !important;
		          }
		          .es-m-p5b {
		        padding-bottom: 5px !important;
		          }
		          .es-m-p5r {
		        padding-right: 5px !important;
		          }
		          .es-m-p5l {
		        padding-left: 5px !important;
		          }
		          .es-m-p10 {
		        padding: 10px !important;
		          }
		          .es-m-p10t {
		        padding-top: 10px !important;
		          }
		          .es-m-p10b {
		        padding-bottom: 10px !important;
		          }
		          .es-m-p10r {
		        padding-right: 10px !important;
		          }
		          .es-m-p10l {
		        padding-left: 10px !important;
		          }
		          .es-m-p15 {
		        padding: 15px !important;
		          }
		          .es-m-p15t {
		        padding-top: 15px !important;
		          }
		          .es-m-p15b {
		        padding-bottom: 15px !important;
		          }
		          .es-m-p15r {
		        padding-right: 15px !important;
		          }
		          .es-m-p15l {
		        padding-left: 15px !important;
		          }
		          .es-m-p20 {
		        padding: 20px !important;
		          }
		          .es-m-p20t {
		        padding-top: 20px !important;
		          }
		          .es-m-p20r {
		        padding-right: 20px !important;
		          }
		          .es-m-p20l {
		        padding-left: 20px !important;
		          }
		          .es-m-p25 {
		        padding: 25px !important;
		          }
		          .es-m-p25t {
		        padding-top: 25px !important;
		          }
		          .es-m-p25b {
		        padding-bottom: 25px !important;
		          }
		          .es-m-p25r {
		        padding-right: 25px !important;
		          }
		          .es-m-p25l {
		        padding-left: 25px !important;
		          }
		          .es-m-p30 {
		        padding: 30px !important;
		          }
		          .es-m-p30t {
		        padding-top: 30px !important;
		          }
		          .es-m-p30b {
		        padding-bottom: 30px !important;
		          }
		          .es-m-p30r {
		        padding-right: 30px !important;
		          }
		          .es-m-p30l {
		        padding-left: 30px !important;
		          }
		          .es-m-p35 {
		        padding: 35px !important;
		          }
		          .es-m-p35t {
		        padding-top: 35px !important;
		          }
		          .es-m-p35b {
		        padding-bottom: 35px !important;
		          }
		          .es-m-p35r {
		        padding-right: 35px !important;
		          }
		          .es-m-p35l {
		        padding-left: 35px !important;
		          }
		          .es-m-p40 {
		        padding: 40px !important;
		          }
		          .es-m-p40t {
		        padding-top: 40px !important;
		          }
		          .es-m-p40b {
		        padding-bottom: 40px !important;
		          }
		          .es-m-p40r {
		        padding-right: 40px !important;
		          }
		          .es-m-p40l {
		        padding-left: 40px !important;
		          }
		          .es-content-body .fz46 {
		        font-size: 46px !important;
		          }
		      }
		      /* END RESPONSIVE STYLES */
		      .bg-item {
		        position: relative;
		      }
		      .bg-item {
		        content: "";
		        display: block;
		        position: absolute;
		        background-color: #F9F6F3;
		        width: 420px;
		        height: 170px;
		        border-radius: 35px;
		        z-index: -1;
		        top: -20px;
		        left: 70px;
		      }
		    </style>
		  </head>
		  <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
		    <div class="es-wrapper-color" style="background-color:#f6f6f6;">
		      <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;background-color:#f6f6f6;">
		        <tbody>
		          <tr>
		            <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
		              <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
		                <tbody>
		                  <tr>
		                    <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
		                      <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
		                        <tbody>
		                          <tr>
		                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_ec46a73fb0402205679875e14c04bfd64dbab0b9eef7b02cc17b33d36e10e89f/images/logo_rOc.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="192"></a></td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text es-p25r es-p25l es-m-p0r es-m-p0l" style="padding:0;Margin:0;padding-left:25px;padding-right:25px;">
		                                              <p class="fz46" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-size: 57px; color: #da3a8d; line-height: 100%;"><strong>Votre espace client</strong></p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p20" align="left" style="padding:0;Margin:0;padding:20px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;font-size:14px;"><img src="https://opgygf.stripocdn.email/content/guids/CABINET_ec46a73fb0402205679875e14c04bfd64dbab0b9eef7b02cc17b33d36e10e89f/images/zigzag.png" alt style="border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="191"></a></td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p40t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr style="position: relative; z-index: 1;">
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color:#333333;font-size: 17px;"><strong>Un nouvel élément a été chargé sur votre</strong><br>
		                                              </p>
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color:#333333;font-size: 17px;"><strong>espace client</strong></p>
		                                              <div class="bg-item"></div>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-button" style="padding:0;Margin:0;"><span class="es-button-border" style="border:none;display:inline-block;border-radius:30px;width:auto;border-width: 0px; background: #da3a8d;"><a href="https://ftp.shootnbox.fr/cabinet/" class="es-button" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;display:inline-block;border-radius:30px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:120%;color:#ffffff;text-decoration:none;width:auto;text-align:center;padding:10px 20px 10px 20px;mso-padding-alt:0;mso-style-priority:100 !important;text-decoration:none !important;background: #da3a8d; font-size: 17px;border:none;">Cliquez ici pour y accéder</a></span></td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-spacer" height="90" style="padding:0;Margin:0;"></td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p30t es-p40b es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;padding-bottom:40px;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong>Vos identifiants pour vous connecter sont les suivants:</strong></p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong></strong><strong><span style="color:#D23680;">Identifiant:</span> '.$row_orders['num_id'].'</strong></p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#D23680;">Mot de passe:</span> '.$row_orders['password'].'</strong></p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                          <tr>
		                            <td class="esd-structure es-p20" align="left" bgcolor="#707070" style="padding:0;Margin:0;padding:20px;background-color: #707070;">
		                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                <tbody>
		                                  <tr>
		                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
		                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                        <tbody>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">Shootnbox</p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">contact@shootnbox.fr 01 45</p>
		                                            </td>
		                                          </tr>
		                                          <tr>
		                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
		                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size:14px;color: #ffffff;">01 45 01 66 66</p>
		                                            </td>
		                                          </tr>
		                                        </tbody>
		                                      </table>
		                                    </td>
		                                  </tr>
		                                </tbody>
		                              </table>
		                            </td>
		                          </tr>
		                        </tbody>
		                      </table>
		                    </td>
		                  </tr>
		                </tbody>
		              </table>
		            </td>
		          </tr>
		        </tbody>
		      </table>
		    </div>
		  </body>
		</html>';
		mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
    mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		echo"done";
	}

	if ($event == "tripadvisor_mail") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
		$row_orders = mysqli_fetch_assoc($result_orders);
    mysqli_query($conn, "UPDATE `orders_new` SET `tripadvisor_mail` = 1 WHERE `id` = ".$id);

		$subject = "Vos photos sont en ligne ! ".date("d-m-Y H:i", time());
		$mailheaders = "MIME-Version: 1.0\r\n";
		$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="x-apple-disable-message-reformatting" />
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<style type="text/css">
			    body, .maintable { height:100% !important; width:100% !important; margin:0; padding:0;}
			    img, a img { border:0; outline:none; text-decoration:none;}
			    p {margin-top:0; margin-right:0; margin-left:0; padding:0;}
			    .ReadMsgBody {width:100%;}
			    .ExternalClass {width:100%;}
			    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
			    img {-ms-interpolation-mode: bicubic;}
			    body, table, td, p, a, li, blockquote {-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%;}
			   /*p {display: table; table-layout: fixed; width: 100%; word-wrap: break-word;} */
			</style>
			<style type="text/css">
			@media only screen and (max-width: 480px) {
			 .rtable {width: 100% !important;}
			 .rtable tr {height:auto !important; display: block;}
			 .contenttd {max-width: 100% !important; display: block; width: auto !important;}
			 .contenttd:after {content: ""; display: table; clear: both;}
			 .hiddentds {display: none;}
			 .imgtable, .imgtable table {max-width: 100% !important; height: auto; float: none; margin: 0 auto;}
			 .imgtable.btnset td {display: inline-block;}
			 .imgtable img {width: 100%; height: auto !important;display: block;}
			 table {float: none;}
			 .mobileHide {display: none !important; width: 0 !important; max-height: 0 !important; overflow: hidden !important;}
			 .desktopHide {display: block !important; width: 100% !important; max-height: unset !important; overflow: unset !important;}
			 .noresponsive p {display: table; table-layout: fixed; width: 100%; word-wrap: break-word;}
			}
			@media only screen and (min-width: 481px) {
			 .desktopHide {display: none !important; width: 0 !important; max-height: 0 !important; overflow: hidden !important;}
			}
			</style>
			<!--[if gte mso 9]>
			<xml>
			  <o:OfficeDocumentSettings>
			    <o:AllowPNG/>
			    <o:PixelsPerInch>96</o:PixelsPerInch>
			  </o:OfficeDocumentSettings>
			</xml>
			<![endif]-->
			<title></title>
			</head>
			<body style="overflow: auto; padding:0; margin:0; font-size: 14px; font-family: arial, helvetica, sans-serif; cursor:auto; background-color:#f2f2f2">
			<table style="BACKGROUND-COLOR: #f2f2f2" cellspacing="0" cellpadding="0" width="100%">
			<tr>
			<td style="FONT-SIZE: 0px; HEIGHT: 0px; LINE-HEIGHT: 0"></td>
			</tr>
			<tr>
			<td valign="top">
			<table class="rtable" style="WIDTH: 672px; MARGIN: 0px auto" cellspacing="0" cellpadding="0" width="672" align="center" border="0">
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 138px" height="138">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; VERTICAL-ALIGN: middle; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: transparent" colspan="2">
			<div><!--[if gte mso 12]>
			    <table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td align="center">
			<![endif]-->
			<table class="imgtable" style="MARGIN: 0px auto" cellspacing="0" cellpadding="0" align="center" border="0">
			<tr>
			<td style="PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px" align="center">
			<table cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BACKGROUND-COLOR: transparent"><img style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="" src="https://ftp.shootnbox.fr/manager/mail/Image_1_b9e1f718492a4948ab1d71ab276572c8.png" width="666" hspace="0" vspace="0" /></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<!--[if gte mso 12]>
			    </td></tr></table>
			<![endif]--></div>
			</th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 94px" height="94">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 642px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 10px; TEXT-ALIGN: left; PADDING-TOP: 10px; PADDING-LEFT: 15px; BORDER-LEFT: medium none; PADDING-RIGHT: 15px; BACKGROUND-COLOR: transparent">
			<p style="FONT-SIZE: 18px; MARGIN-BOTTOM: 1em; FONT-FAMILY: tahoma, geneva, sans-serif; MARGIN-TOP: 0px; COLOR: #e7388c; TEXT-ALIGN: center; LINE-HEIGHT: 28px; BACKGROUND-COLOR: transparent; mso-line-height-rule: exactly" align="center"><strong><span style="LINE-HEIGHT: 28px">Bon</span><span style="LINE-HEIGHT: 28px">ne nouvelle !&nbsp;<br />
			 vos photos sont disponibles dans votre galerie personnelle &#128525; !</span></strong></p>
			</th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 195px" height="195">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; VERTICAL-ALIGN: middle; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: transparent"><!--[if gte mso 12]>
			    <table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td align="center">
			<![endif]-->
			<table class="imgtable" style="MARGIN: 0px auto" cellspacing="0" cellpadding="0" align="center" border="0">
			<tr>
			<td style="PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px" align="center">
			<table cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BACKGROUND-COLOR: transparent"><img style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="" src="https://ftp.shootnbox.fr/manager/mail/Image_2_e7f90392d8b74509bf0b7a86a970ff70.jpg" width="668" hspace="0" vspace="0" /></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<!--[if gte mso 12]>
			    </td></tr></table>
			<![endif]--></th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: #feffff 5px solid; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 56px" height="56">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 642px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 10px; PADDING-LEFT: 15px; BORDER-LEFT: medium none; PADDING-RIGHT: 15px; BACKGROUND-COLOR: transparent">
			<p style="FONT-SIZE: 18px; MARGIN-BOTTOM: 1em; FONT-FAMILY: tahoma, geneva, sans-serif; MARGIN-TOP: 0px; COLOR: #e7388c; TEXT-ALIGN: center; LINE-HEIGHT: 28px; BACKGROUND-COLOR: transparent; mso-line-height-rule: exactly" align="center"><strong>Pour recevoir votre lien :</strong></p>
			</th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 212px" height="212">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 642px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 15px; BORDER-LEFT: medium none; PADDING-RIGHT: 15px; BACKGROUND-COLOR: transparent">
			<p style="FONT-SIZE: 16px; MARGIN-BOTTOM: 1em; FONT-FAMILY: tahoma, geneva, sans-serif; MARGIN-TOP: 0px; COLOR: #e7388c; TEXT-ALIGN: justify; LINE-HEIGHT: 25px; BACKGROUND-COLOR: transparent; mso-line-height-rule: exactly" align="justify"><strong>Option 1 &ndash; Donnez votre avis sur Google ! &#11088; &#11088; &#11088; &#11088; &#11088;</strong></p>
			<table class="imgtable" cellspacing="0" cellpadding="0" align="right" border="0">
			<tr>
			<td style="PADDING-BOTTOM: 2px; PADDING-TOP: 0px; PADDING-LEFT: 10px; PADDING-RIGHT: 2px" align="center">
			<table cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BACKGROUND-COLOR: transparent"><img style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="" src="https://ftp.shootnbox.fr/manager/mail/Image_3_1d0aec7953744d57b4c8938a93ad2203.png" width="213" hspace="0" vspace="0" /></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<p style="FONT-SIZE: 14px; MARGIN-BOTTOM: 1em; FONT-FAMILY: arial, helvetica, sans-serif; MARGIN-TOP: 0px; COLOR: #575757; TEXT-ALIGN: justify; LINE-HEIGHT: 22px; BACKGROUND-COLOR: transparent; mso-line-height-rule: exactly" align="justify">Rapide et facile &agrave; donner, votre avis compte beaucoup pour nous ! Vous nous avez fait confiance gr&acirc;ce aux nombreuses recommandations clients ?&nbsp; Partagez &agrave; votre tour votre exp&eacute;rience ! Vous recevrez ensuite automatiquement votre lien par mail &#128640;.&nbsp; Merci pour votre contribution ! &#10084;</p>
			<!--[if gte mso 12]>
			    <table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td align="center">
			<![endif]-->
			<table class="imgtable btnset" style="TEXT-ALIGN: center; MARGIN: 0px auto" cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td class="contenttd" style="VERTICAL-ALIGN: middle; PADDING-BOTTOM: 2px; PADDING-TOP: 2px; PADDING-LEFT: 2px; PADDING-RIGHT: 2px"><a href="https://g.page/r/CV9hJZofgnkSEAg/review" target="_blank"><img title="" border="none" alt="Cliquez ici" src="https://ftp.shootnbox.fr/manager/mail/Image_4_bd4dca3b77594909b024dec0b641a051.png" /></a> </td>
			</tr>
			</table>
			<!--[if gte mso 12]>
			    </td></tr></table>
			<![endif]--></th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 233px" height="233">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 5px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: transparent"><!--[if gte mso 12]>
			    <table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td align="center">
			<![endif]-->
			<table class="imgtable" style="MARGIN: 0px auto" cellspacing="0" cellpadding="0" align="center" border="0">
			<tr>
			<td style="PADDING-BOTTOM: 0px; PADDING-TOP: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px" align="center">
			<table cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BACKGROUND-COLOR: transparent"><a href="https://g.page/r/CV9hJZofgnkSEAg/review" target="_blank"><img style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="" src="https://ftp.shootnbox.fr/manager/mail/Image_5_88065cc077de4cab94315986fb6f23db.png" width="672" hspace="0" vspace="0" /></a></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<!--[if gte mso 12]>
			    </td></tr></table>
			<![endif]--></th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: #e7388c 5px solid; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 134px" height="134">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 657px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 20px; TEXT-ALIGN: left; PADDING-TOP: 16px; PADDING-LEFT: 15px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: transparent">
			<table class="imgtable" cellspacing="0" cellpadding="0" align="right" border="0">
			<tr>
			<td style="PADDING-BOTTOM: 2px; PADDING-TOP: 2px; PADDING-LEFT: 10px; PADDING-RIGHT: 0px" align="center">
			<table cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BACKGROUND-COLOR: transparent"><img style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="" src="https://ftp.shootnbox.fr/manager/mail/Image_6_9d4dccf91911446fa912acc2067bcc78.png" width="240" hspace="0" vspace="0" /></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
			<p style="FONT-SIZE: 14px; MARGIN-BOTTOM: 1em; FONT-FAMILY: arial, helvetica, sans-serif; MARGIN-TOP: 0px; COLOR: #575757; TEXT-ALIGN: left; LINE-HEIGHT: 22px; BACKGROUND-COLOR: transparent; mso-line-height-rule: exactly" align="left"><strong><span style="FONT-SIZE: 13px; FONT-FAMILY: tahoma, geneva, sans-serif; LINE-HEIGHT: 20px">Option 2 &ndash; Envoyez-nous un mail. &#128231;&nbsp;</span></strong><br />
			Vous ne souhaitez pas donner votre avis &#128549; ?&nbsp; Faites-le nous savoir en r&eacute;pondant &agrave; ce mail. Notre Team vous enverra alors manuellement votre lien dans un d&eacute;lai de 48h. &#128076;</p>
			</th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 10px; TEXT-ALIGN: left; PADDING-TOP: 10px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 52px" height="52">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 0px; TEXT-ALIGN: left; PADDING-TOP: 0px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: #feffff">
			<div style="PADDING-BOTTOM: 10px; TEXT-ALIGN: center; PADDING-TOP: 10px; PADDING-LEFT: 10px; PADDING-RIGHT: 10px">
			<table class="imgtable" style="DISPLAY: inline-block" cellspacing="0" cellpadding="0" border="0">
			<tr>
			<td style="PADDING-RIGHT: 5px"><a href="https://www.facebook.com/shootnBox.fr/" target="_blank"><img title="Facebook" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="Facebook" src="https://ftp.shootnbox.fr/manager/mail/Image_7_dcf7a2d1ede4408fb1b238d5b3b40518.png" width="32" /></a> </td>
			<td style="PADDING-RIGHT: 5px"><a href="https://www.instagram.com/shootnbox/" target="_blank"><img title="Instagram" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="Instagram" src="https://ftp.shootnbox.fr/manager/mail/Image_8_8e9762fba2af4a368939e054bcec00ff.png" width="32" /></a> </td>
			<td><a href="https://www.youtube.com/channel/UCnbHhqe1yV_82-dnfaSFoOQ" target="_blank"><img title="Youtube" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; DISPLAY: block" alt="Youtube" src="https://ftp.shootnbox.fr/manager/mail/Image_9_c1681836cdff48aeb75d99b455c42b3b.png" width="32" /></a> </td>
			</tr>
			</table>
			</div>
			</th>
			</tr>
			</table>
			</th>
			</tr>
			<tr>
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 672px; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 1px; TEXT-ALIGN: left; PADDING-TOP: 1px; PADDING-LEFT: 0px; BORDER-LEFT: medium none; PADDING-RIGHT: 0px; BACKGROUND-COLOR: transparent">
			<table style="WIDTH: 100%" cellspacing="0" cellpadding="0" align="left">
			<tr style="HEIGHT: 60px" height="60">
			<th class="contenttd" style="BORDER-TOP: medium none; BORDER-RIGHT: medium none; WIDTH: 642px; VERTICAL-ALIGN: top; BORDER-BOTTOM: medium none; FONT-WEIGHT: normal; PADDING-BOTTOM: 1px; TEXT-ALIGN: left; PADDING-TOP: 1px; PADDING-LEFT: 15px; BORDER-LEFT: medium none; PADDING-RIGHT: 15px; BACKGROUND-COLOR: #feffff">
			<p style="FONT-SIZE: 10px; MARGIN-BOTTOM: 1em; FONT-FAMILY: arial, helvetica, sans-serif; MARGIN-TOP: 0px; COLOR: #7c7c7c; TEXT-ALIGN: center; LINE-HEIGHT: 12px; BACKGROUND-COLOR: transparent; mso-line-height-rule: exactly" align="center"><strong>Team Shootnbox<br />
			 01.45.01.66.66<br />
			www.shootnbox.fr<br />
			 5, Rue Malmaison - 93170 Bagnolet</strong><a style="COLOR: #dfe0e0" href="http://www.mysite.com/"></a></p>
			</th>
			</tr>
			</table>
			</th>
			</tr>
			</table>
			</td>
			</tr>
			<tr>
			<td style="FONT-SIZE: 0px; HEIGHT: 8px; LINE-HEIGHT: 0">&nbsp;</td>
			</tr>
			</table>
			<!-- Created with MailStyler 2.22.02.21 -->
			</body>
			</html>';
		mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
    mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		echo"done";
	}

	if ($event == "album_mail") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
		$row_orders = mysqli_fetch_assoc($result_orders);
    mysqli_query($conn, "UPDATE `orders_new` SET `album_mail` = 1 WHERE `id` = ".$id);

		$subject = "Vos photos Shootnbox ".date("d-m-Y H:i", time());
		$mailheaders = "MIME-Version: 1.0\r\n";
		$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
		  <head>
		    <meta charset="UTF-8">
		    <meta content="width=device-width, initial-scale=1" name="viewport">
		    <meta name="x-apple-disable-message-reformatting">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <meta content="telephone=no" name="format-detection">
		    <title></title>
		    <!--[if (mso 16)]>
		      <style type="text/css">
		        a {text-decoration: none;}
		      </style>
		    <![endif]-->
		    <!--[if gte mso 9]><style>
		        sup { font-size: 100% !important; }
		      </style><![endif]-->
		    <!--[if gte mso 9]>
		      <xml>
		        <o:OfficeDocumentSettings>
		          <o:AllowPNG></o:AllowPNG>
		          <o:PixelsPerInch>96</o:PixelsPerInch>
		        </o:OfficeDocumentSettings>
		      </xml>
		    <![endif]-->
		    <!--[if !mso]><!-- -->
		      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
		      <!--<![endif]-->
		      <style type="text/css">
		        /* CONFIG STYLES Please do not delete and edit CSS styles below */
		        /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
		        a[x-apple-data-detectors] {
		            color: inherit !important;
		            text-decoration: none !important;
		            font-size: inherit !important;
		            font-family: inherit !important;
		            font-weight: inherit !important;
		            line-height: inherit !important;
		        }
		        /*
		        END OF IMPORTANT
		        */
		        /*
		        END CONFIG STYLES
		        */
		        /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
		        @media only screen and (max-width: 600px) {
		            p,
		            ul li,
		            ol li,
		            a {
		                line-height: 150% !important;
		            }
		            h1,
		            h2,
		            h3,
		            h1 a,
		            h2 a,
		            h3 a {
		                line-height: 120%;
		            }
		            h1 {
		                font-size: 30px !important;
		                text-align: center;
		            }
		            h2 {
		                font-size: 26px !important;
		                text-align: center;
		            }
		            h3 {
		                font-size: 20px !important;
		                text-align: center;
		            }
		            .es-header-body h1 a,
		            .es-content-body h1 a,
		            .es-footer-body h1 a {
		                font-size: 30px !important;
		            }
		            .es-header-body h2 a,
		            .es-content-body h2 a,
		            .es-footer-body h2 a {
		                font-size: 26px !important;
		            }
		            .es-header-body h3 a,
		            .es-content-body h3 a,
		            .es-footer-body h3 a {
		                font-size: 20px !important;
		            }
		            .es-menu td a {
		                font-size: 16px !important;
		            }
		            .es-header-body p,
		            .es-header-body ul li,
		            .es-header-body ol li,
		            .es-header-body a {
		                font-size: 16px !important;
		            }
		            .es-content-body p,
		            .es-content-body ul li,
		            .es-content-body ol li,
		            .es-content-body a {
		                font-size: 16px !important;
		            }
		            .es-footer-body p,
		            .es-footer-body ul li,
		            .es-footer-body ol li,
		            .es-footer-body a {
		                font-size: 16px !important;
		            }
		            .es-infoblock p,
		            .es-infoblock ul li,
		            .es-infoblock ol li,
		            .es-infoblock a {
		                font-size: 12px !important;
		            }
		            *[class="gmail-fix"] {
		                display: none !important;
		            }
		            .es-m-txt-c,
		            .es-m-txt-c h1,
		            .es-m-txt-c h2,
		            .es-m-txt-c h3 {
		                text-align: center !important;
		            }
		            .es-m-txt-r,
		            .es-m-txt-r h1,
		            .es-m-txt-r h2,
		            .es-m-txt-r h3 {
		                text-align: right !important;
		            }
		            .es-m-txt-l,
		            .es-m-txt-l h1,
		            .es-m-txt-l h2,
		            .es-m-txt-l h3 {
		                text-align: left !important;
		            }
		            .es-m-txt-r img,
		            .es-m-txt-c img,
		            .es-m-txt-l img {
		                display: inline !important;
		            }
		            .es-button-border {
		                display: block !important;
		            }
		            a.es-button,
		            button.es-button {
		                font-size: 20px !important;
		                display: block !important;
		                border-width: 10px 0px 10px 0px !important;
		            }
		            .es-adaptive table,
		            .es-left,
		            .es-right {
		                width: 100% !important;
		            }
		            .es-content table,
		            .es-header table,
		            .es-footer table,
		            .es-content,
		            .es-footer,
		            .es-header {
		                width: 100% !important;
		                max-width: 600px !important;
		            }
		            .es-adapt-td {
		                display: block !important;
		                width: 100% !important;
		            }
		            .adapt-img {
		                width: 100% !important;
		                height: auto !important;
		            }
		            .es-m-p0 {
		                padding: 0 !important;
		            }
		            .es-m-p0r {
		                padding-right: 0 !important;
		            }
		            .es-m-p0l {
		                padding-left: 0 !important;
		            }
		            .es-m-p0t {
		                padding-top: 0 !important;
		            }
		            .es-m-p0b {
		                padding-bottom: 0 !important;
		            }
		            .es-m-p20b {
		                padding-bottom: 20px !important;
		            }
		            .es-mobile-hidden,
		            .es-hidden {
		                display: none !important;
		            }
		            tr.es-desk-hidden,
		            td.es-desk-hidden,
		            table.es-desk-hidden {
		                width: auto !important;
		                overflow: visible !important;
		                float: none !important;
		                max-height: inherit !important;
		                line-height: inherit !important;
		            }
		            tr.es-desk-hidden {
		                display: table-row !important;
		            }
		            table.es-desk-hidden {
		                display: table !important;
		            }
		            td.es-desk-menu-hidden {
		                display: table-cell !important;
		            }
		            .es-menu td {
		                width: 1% !important;
		            }
		            table.es-table-not-adapt,
		            .esd-block-html table {
		                width: auto !important;
		            }
		            table.es-social {
		                display: inline-block !important;
		            }
		            table.es-social td {
		                display: inline-block !important;
		            }
		            .m_pic {
		                width: 60%;
		            }
		            .es-m-p5 {
		                padding: 5px !important;
		            }
		            .es-m-p5t {
		                padding-top: 5px !important;
		            }
		            .es-m-p5b {
		                padding-bottom: 5px !important;
		            }
		            .es-m-p5r {
		                padding-right: 5px !important;
		            }
		            .es-m-p5l {
		                padding-left: 5px !important;
		            }
		            .es-m-p10 {
		                padding: 10px !important;
		            }
		            .es-m-p10t {
		                padding-top: 10px !important;
		            }
		            .es-m-p10b {
		                padding-bottom: 10px !important;
		            }
		            .es-m-p10r {
		                padding-right: 10px !important;
		            }
		            .es-m-p10l {
		                padding-left: 10px !important;
		            }
		            .es-m-p15 {
		                padding: 15px !important;
		            }
		            .es-m-p15t {
		                padding-top: 15px !important;
		            }
		            .es-m-p15b {
		                padding-bottom: 15px !important;
		            }
		            .es-m-p15r {
		                padding-right: 15px !important;
		            }
		            .es-m-p15l {
		                padding-left: 15px !important;
		            }
		            .es-m-p20 {
		                padding: 20px !important;
		            }
		            .es-m-p20t {
		                padding-top: 20px !important;
		            }
		            .es-m-p20r {
		                padding-right: 20px !important;
		            }
		            .es-m-p20l {
		                padding-left: 20px !important;
		            }
		            .es-m-p25 {
		                padding: 25px !important;
		            }
		            .es-m-p25t {
		                padding-top: 25px !important;
		            }
		            .es-m-p25b {
		                padding-bottom: 25px !important;
		            }
		            .es-m-p25r {
		                padding-right: 25px !important;
		            }
		            .es-m-p25l {
		                padding-left: 25px !important;
		            }
		            .es-m-p30 {
		                padding: 30px !important;
		            }
		            .es-m-p30t {
		                padding-top: 30px !important;
		            }
		            .es-m-p30b {
		                padding-bottom: 30px !important;
		            }
		            .es-m-p30r {
		                padding-right: 30px !important;
		            }
		            .es-m-p30l {
		                padding-left: 30px !important;
		            }
		            .es-m-p35 {
		                padding: 35px !important;
		            }
		            .es-m-p35t {
		                padding-top: 35px !important;
		            }
		            .es-m-p35b {
		                padding-bottom: 35px !important;
		            }
		            .es-m-p35r {
		                padding-right: 35px !important;
		            }
		            .es-m-p35l {
		                padding-left: 35px !important;
		            }
		            .es-m-p40 {
		                padding: 40px !important;
		            }
		            .es-m-p40t {
		                padding-top: 40px !important;
		            }
		            .es-m-p40b {
		                padding-bottom: 40px !important;
		            }
		            .es-m-p40r {
		                padding-right: 40px !important;
		            }
		            .es-m-p40l {
		                padding-left: 40px !important;
		            }
		            .es-content-body .fz14 {
		                font-size: 14px !important;
		            }
		        }
		        /* END RESPONSIVE STYLES */
		      </style>
		    </head>
		    <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
		      <div class="es-wrapper-color" style="background-color:#f6f6f6;">
		        <!--[if gte mso 9]>
		          <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
		            <v:fill type="tile" color="#f6f6f6"></v:fill>
		          </v:background>
		        <![endif]-->
		        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;">
		          <tbody>
		            <tr>
		              <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
		                <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
		                  <tbody>
		                    <tr>
		                      <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
		                        <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
		                          <tbody>
		                            <tr>
		                              <td class="esd-structure es-p20t" align="left" style="padding:0;Margin:0;padding-top:20px;">
		                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                  <tbody>
		                                    <tr>
		                                      <td class="esd-container-frame" width="600" valign="top" align="center" style="padding:0;Margin:0;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;" align="center">
		                                                <a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img class="adapt-img" src="https://ftp.shootnbox.fr/manager/mail/header.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="600"></a>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                  </tbody>
		                                </table>
		                              </td>
		                            </tr>
		                            <tr>
		                              <td class="esd-structure es-p25t es-p25r es-p25l es-m-p10r es-m-p10l" align="left" style="padding:0;Margin:0;padding-top:25px;padding-left:25px;padding-right:25px;">
		                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                  <tbody>
		                                    <tr>
		                                      <td class="esd-container-frame" width="550" valign="top" align="center" style="padding:0;Margin:0;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-block-text es-p10b" align="left" style="padding:0;Margin:0;padding-bottom:10px;">
		                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong>Nous avons été ravis de prendre part à votre événement !</strong><strong></strong><br>
		                                                </p>
		                                              </td>
		                                            </tr>
		                                            <tr>
		                                              <td class="esd-block-text" esd-links-color="#333333" align="left" style="padding:0;Margin:0;">
		                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;">Vous trouverez ci-dessous le lien vers votre galerie personnelle dédiée, regroupant l’ensemble des médias réalisés par notre animation. Visionnez et téléchargez gratuitement votre reportage en Haute Définition !Nous vous rappelons que ce lien est actif 3 mois à partir d’aujourd’hui. Passé ce délai, les fichiers seront supprimés définitivement de nos serveurs, sans possibilité de consultation ultérieure.</p>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                  </tbody>
		                                </table>
		                              </td>
		                            </tr>
		                            <tr>
		                              <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
		                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                  <tbody>
		                                    <tr>
		                                      <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-block-text" align="center" style="padding:0;Margin:0;">
		                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong>Vos identifiants pour vous connecter sont les suivants :</strong></p>
		                                              </td>
		                                            </tr>
		                                            <tr>
		                                              <td class="esd-block-text" esd-links-underline="none" esd-links-color="#333333" align="center" style="padding:0;Margin:0;">
		                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">Identifiant </span>: '.$row_orders['num_id'].'</strong><br>
		                                                </p>
		                                              </td>
		                                            </tr>
		                                            <tr>
		                                              <td class="esd-block-text" align="center" style="padding:0;Margin:0;">
		                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">Mot de passe </span>: '.$row_orders['password'].'<br>
		                                                  </strong></p>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                  </tbody>
		                                </table>
		                              </td>
		                            </tr>
		                            <tr>
		                              <td class="esd-structure es-p20t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px;">
		                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                  <tbody>
		                                    <tr>
		                                      <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;" align="center">
		                                                <a target="_blank" href="https://ftp.shootnbox.fr/album/" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://ftp.shootnbox.fr/manager/mail/btn2.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="276"></a>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                  </tbody>
		                                </table>
		                              </td>
		                            </tr>
		                            <tr>
		                              <td class="esd-structure es-p20t es-p25b es-p25r es-p25l es-m-p10r es-m-p10l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-bottom:25px;padding-left:25px;padding-right:25px;">
		                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                  <tbody>
		                                    <tr>
		                                      <td class="esd-container-frame" width="550" valign="top" align="center" style="padding:0;Margin:0;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-block-text" align="left" style="padding:0;Margin:0;">
		                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;">La Team Shootnbox se tient à votre disposition pour toute aide dont vous auriez besoin <strong>🙂</strong><br>
		                                                </p>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                  </tbody>
		                                </table>
		                              </td>
		                            </tr>
		                            <tr>
		                              <td class="esd-structure es-p15t es-p15b es-p20r es-p20l" style="padding:0;Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;background-color: #171b1d;" bgcolor="#171b1d" align="left">
		                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                  <tbody>
		                                    <tr>
		                                      <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-block-image es-p10b" style="padding:0;Margin:0;padding-bottom:10px;font-size: 0px;" align="center">
		                                                <a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://ftp.shootnbox.fr/manager/mail/logo_footer_2x.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="139"></a>
		                                              </td>
		                                            </tr>
		                                            <tr>
		                                              <td class="esd-block-text" esd-links-underline="none" esd-links-color="#ffffff" align="center" style="padding:0;Margin:0;">
		                                                <p class="fz14" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;color: #ffffff;"><a target="_blank" href="mailto:contact@shootnbox.fr" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;text-decoration: none; color: #ffffff;">contact@shootnbox.fr</a> | 01 45 01 66 66</p>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                  </tbody>
		                                </table>
		                              </td>
		                            </tr>
		                          </tbody>
		                        </table>
		                      </td>
		                    </tr>
		                  </tbody>
		                </table>
		              </td>
		            </tr>
		          </tbody>
		        </table>
		      </div>
		    </body>
		  </html>';
		mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
    mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		echo"done";
	}

	// Добавление типа
  if ($event == "add_type") {
    mysqli_query($conn, "INSERT INTO `types` (`id`, `title`) VALUES (NULL, '$title')");
    echo"done";
  }

  // Редактирование типа
  if ($event == "edit_type") {
    mysqli_query($conn, "UPDATE `types` SET `title` = '$title' WHERE `id` = ".$id);
    echo"done";
  }

  // Удаление типа
  if ($event == "delete_type") {
    mysqli_query($conn, "DELETE FROM `types` WHERE `id` = ".$id);
    echo"done";
  }

  // Добавление типа
  if ($event == "add_time") {
    mysqli_query($conn, "INSERT INTO `times` (`id`, `title`) VALUES (NULL, '$title')");
    echo"done";
  }

  // Редактирование типа
  if ($event == "edit_time") {
    mysqli_query($conn, "UPDATE `times` SET `title` = '$title' WHERE `id` = ".$id);
    echo"done";
  }

  // Удаление типа
  if ($event == "delete_time") {
    mysqli_query($conn, "DELETE FROM `times` WHERE `id` = ".$id);
    echo"done";
  }


  // Добавление типа
  if ($event == "add_bornes_type") {
    mysqli_query($conn, "INSERT INTO `bornes_types` (`id`, `title`, `description`, `description_pdf`, `image`, `image2`, `department`, `price`, `eprice`, `options_ids`, `eoptions_ids`, `delivery_ids`, `color`, `amount`, `width`, `height`, `depth`, `weight`) VALUES (NULL, '".urldecode($title)."', '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', '$image', '$image2', '$department', '$price', '$eprice', '".urldecode($options_ids)."', '".urldecode($eoptions_ids)."', '".urldecode($delivery_ids)."', '$color', '$amount', '$width', '$height', '$depth', '$weight')") or die(mysqli_error($conn));
   /*if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
		if ($image2 != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image2);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image2, '120'));
		}*/
    echo"done";
  }

  // Редактирование типа
  if ($event == "edit_bornes_type") {
    mysqli_query($conn, "UPDATE `bornes_types` SET `title` = '".urldecode($title)."', `description` = '$description', `description_pdf` = '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', `department` = '$department', `price` = '$price', `eprice` = '$eprice', `options_ids` = '".urldecode($options_ids)."', `eoptions_ids` = '".urldecode($eoptions_ids)."', `delivery_ids` = '".urldecode($delivery_ids)."', `color` = '$color', `amount` = '$amount', `width` = '$width', `height` = '$height', `depth` = '$depth', `weight` = '$weight' WHERE `id` = ".$id) or die(mysqli_error($conn));;
    if ($image != "") {
			$result_bornes_types = mysqli_query($conn, "SELECT `image` FROM `bornes_types` WHERE `id` = ".$id);
			$row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
			/*require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));*/
			mysqli_query($conn, "UPDATE `bornes_types` SET `image` = '$image' WHERE `id` = ".$id);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_bornes_types['image'], '120'));
		}
		if ($image2 != "") {
			$result_bornes_types = mysqli_query($conn, "SELECT `image2` FROM `bornes_types` WHERE `id` = ".$id);
			$row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
			/*require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image2);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image2, '120'));*/
			mysqli_query($conn, "UPDATE `bornes_types` SET `image2` = '$image2' WHERE `id` = ".$id);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image2']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_bornes_types['image2'], '120'));
		}
    echo"done";
  }

  if ($event == "delete_image_bornes_type") {
		$result_bornes_types = mysqli_query($conn, "SELECT `image` FROM `bornes_types` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_bornes_types) != 0) {
			$row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_bornes_types['image'], '120'));
			mysqli_query($conn, "UPDATE `bornes_types` SET `image` = '' WHERE `id` = ".$id);
		}
	}

	if ($event == "delete_image2_bornes_type") {
		$result_bornes_types = mysqli_query($conn, "SELECT `image2` FROM `bornes_types` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_bornes_types) != 0) {
			$row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image2']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_bornes_types['image2'], '120'));
			mysqli_query($conn, "UPDATE `bornes_types` SET `image2` = '' WHERE `id` = ".$id);
		}
	}

  // Удаление типа
  if ($event == "delete_bornes_type") {
    mysqli_query($conn, "DELETE FROM `bornes_types` WHERE `id` = ".$id);
    $result_bornes_types =mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_bornes_types) != 0) {
			$row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
			if ($row_bornes_types['image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_bornes_types['image'], '120'));
			}
			if ($row_bornes_types['image2'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_bornes_types['image2']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_bornes_types['image2'], '120'));
			}
		}
    echo"done";
  }

  // Добавление опции htmlspecialchars($_POST['sms1'], ENT_QUOTES, 'UTF-8')
  if ($event == "add_option") {
    mysqli_query($conn, "INSERT INTO `options` (`id`, `title`, `description`, `description_pdf`, `image`, `price`, `eprice`, `icon`, `conflicting_options_ids`, `is_personal`, `template`, `designer`, `status`) VALUES (NULL, '".urldecode($title)."', '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', '$image', '$price', '$eprice', '".urldecode($icon)."', '$conflicting_options_ids', '$is_personal', '$template', '$designer', '1')");
    if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
    echo"done";
  }

  // Редактирование опции
  if ($event == "edit_option") {
    mysqli_query($conn, "UPDATE `options` SET `title` = '".urldecode($title)."', `description` = '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', `description_pdf` = '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', `price` = '$price', `eprice` = '$eprice', `icon` = '".urldecode($icon)."', `conflicting_options_ids` = '$conflicting_options_ids', `is_personal` = '$is_personal', `template` = '$template', `designer` = '$designer' WHERE `id` = ".$id);
    if ($image != "") {
			$result_options = mysqli_query($conn, "SELECT `image` FROM `options` WHERE `id` = ".$id);
			$row_options = mysqli_fetch_assoc($result_options);
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
			mysqli_query($conn, "UPDATE `options` SET `image` = '$image' WHERE `id` = ".$id);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_options['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_options['image'], '120'));
		}
    echo"done";
  }

  if ($event == "delete_image_option") {
		$result_options = mysqli_query($conn, "SELECT `image` FROM `options` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_options) != 0) {
			$row_options = mysqli_fetch_assoc($result_options);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_options['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_options['image'], '120'));
			mysqli_query($conn, "UPDATE `options` SET `image` = '' WHERE `id` = ".$id);
		}
	}

  // Удаление опции
  if ($event == "delete_option") {
    mysqli_query($conn, "DELETE FROM `options` WHERE `id` = ".$id);
    $result_options =mysqli_query($conn, "SELECT * FROM `options` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_options) != 0) {
			$row_options = mysqli_fetch_assoc($result_options);
			if ($row_options['image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_options['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_options['image'], '120'));
			}
		}
    echo"done";
  }

  if ($event == "option_status") {
    mysqli_query($conn, "UPDATE `options` SET `status` = ".$status." WHERE `id` = ".$id);
  }

  // Добавление доставки
  if ($event == "add_delivery") {
    mysqli_query($conn, "INSERT INTO `delivery` (`id`, `title`, `description`, `description_pdf`, `image`, `price`, `eprice`, `status`) VALUES (NULL, '".urldecode($title)."', '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', '$image', '$price', '$eprice', '1')");
    if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
    echo"done";
  }

  // Редактирование доставки
  if ($event == "edit_delivery") {
    mysqli_query($conn, "UPDATE `delivery` SET `title` = '".urldecode($title)."', `description` = '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', `description_pdf` = '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', `price` = '$price', `eprice` = '$eprice' WHERE `id` = ".$id);
    if ($image != "") {
			$result_delivery = mysqli_query($conn, "SELECT `image` FROM `delivery` WHERE `id` = ".$id);
			$row_delivery = mysqli_fetch_assoc($result_delivery);
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
			mysqli_query($conn, "UPDATE `delivery` SET `image` = '$image' WHERE `id` = ".$id);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_delivery['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_delivery['image'], '120'));
		}
    echo"done";
  }

  if ($event == "delete_image_delivery") {
		$result_delivery = mysqli_query($conn, "SELECT `image` FROM `delivery` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_delivery) != 0) {
			$row_delivery = mysqli_fetch_assoc($result_delivery);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_delivery['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_delivery['image'], '120'));
			mysqli_query($conn, "UPDATE `delivery` SET `image` = '' WHERE `id` = ".$id);
		}
	}

  // Удаление доставки
  if ($event == "delete_delivery") {
  	$result_delivery =mysqli_query($conn, "SELECT * FROM `delivery` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_delivery) != 0) {
			$row_delivery = mysqli_fetch_assoc($result_delivery);
			if ($row_delivery['image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_delivery['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_delivery['image'], '120'));
			}
		}
    mysqli_query($conn, "DELETE FROM `delivery` WHERE `id` = ".$id);
    echo"done";
  }

  if ($event == "delivery_status") {
    mysqli_query($conn, "UPDATE `delivery` SET `status` = ".$status." WHERE `id` = ".$id);
  }

  // Добавление доставки
  if ($event == "add_recovery") {
    mysqli_query($conn, "INSERT INTO `recovery` (`id`, `title`, `description`, `description_pdf`, `image`, `department`, `price`, `eprice`, `status`) VALUES (NULL, '".urldecode($title)."', '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', '$image', '$department', '$price', '$eprice', '1')");
    if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
    echo"done";
  }

  // Редактирование доставки
  if ($event == "edit_recovery") {
    mysqli_query($conn, "UPDATE `recovery` SET `title` = '".urldecode($title)."', `description` = '".htmlspecialchars(urldecode($description), ENT_QUOTES, 'UTF-8')."', `description_pdf` = '".htmlspecialchars(urldecode($description_pdf), ENT_QUOTES, 'UTF-8')."', `department` = '$department', `price` = '$price', `eprice` = '$eprice' WHERE `id` = ".$id);
    if ($image != "") {
			$result_recovery = mysqli_query($conn, "SELECT `image` FROM `recovery` WHERE `id` = ".$id);
			$row_recovery = mysqli_fetch_assoc($result_recovery);
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
			mysqli_query($conn, "UPDATE `recovery` SET `image` = '$image' WHERE `id` = ".$id);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_recovery['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_recovery['image'], '120'));
		}
    echo"done";
  }

  if ($event == "delete_image_recovery") {
		$result_recovery = mysqli_query($conn, "SELECT `image` FROM `recovery` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_recovery) != 0) {
			$row_recovery = mysqli_fetch_assoc($result_recovery);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_recovery['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_recovery['image'], '120'));
			mysqli_query($conn, "UPDATE `recovery` SET `image` = '' WHERE `id` = ".$id);
		}
	}

  // Удаление доставки
  if ($event == "delete_recovery") {
  	$result_recovery =mysqli_query($conn, "SELECT * FROM `recovery` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_recovery) != 0) {
			$row_recovery = mysqli_fetch_assoc($result_recovery);
			if ($row_recovery['image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_recovery['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_recovery['image'], '120'));
			}
		}
    mysqli_query($conn, "DELETE FROM `recovery` WHERE `id` = ".$id);
    echo"done";
  }

  if ($event == "recovery_status") {
    mysqli_query($conn, "UPDATE `recovery` SET `status` = ".$status." WHERE `id` = ".$id);
  }

    // Добавление типа
  if ($event == "add_deliveris") {
    mysqli_query($conn, "INSERT INTO `deliveris` (`id`, `title`) VALUES (NULL, '$title')");
    echo"done";
  }

  // Редактирование типа
  if ($event == "edit_deliveris") {
    mysqli_query($conn, "UPDATE `deliveris` SET `title` = '$title' WHERE `id` = ".$id);
    echo"done";
  }

  // Удаление типа
  if ($event == "delete_deliveris") {
    mysqli_query($conn, "DELETE FROM `deliveris` WHERE `id` = ".$id);
    echo"done";
  }

  if ($event == "add_category") {
    mysqli_query($conn, "INSERT INTO `categories` (`id`, `title`, `image`) VALUES (NULL, '$title', '$image')");
    if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
    echo"done";
  }

  // Редактирование типа
  if ($event == "edit_category") {
    mysqli_query($conn, "UPDATE `categories` SET `title` = '$title' WHERE `id` = ".$id);
    if ($image != "") {
			$result_categories = mysqli_query($conn, "SELECT `image` FROM `categories` WHERE `id` = ".$id);
			$row_categories = mysqli_fetch_assoc($result_templates);
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
			mysqli_query($conn, "UPDATE `categories` SET `image` = '$image' WHERE `id` = ".$id);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_categories['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_categories['image'], '120'));
		}
    echo"done";
  }

  if ($event == "delete_image_category") {
		$result_categories = mysqli_query($conn, "SELECT `image` FROM `categories` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_categories) != 0) {
			$row_categories= mysqli_fetch_assoc($result_categories);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_categories['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_categories['image'], '120'));
			mysqli_query($conn, "UPDATE `categories` SET `image` = '' WHERE `id` = ".$id);
		}
	}

  // Удаление типа
  if ($event == "delete_category") {
    $result_categories =mysqli_query($conn, "SELECT * FROM `categories` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_categories) != 0) {
			$row_categories = mysqli_fetch_assoc($result_categories);
			if ($row_categories['image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_categories['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_categories['image'], '120'));
			}
			mysqli_query($conn, "DELETE FROM `categories` WHERE `id` = ".$id);
			echo"done";
		} else {
			echo"error";
		}
  }

  if ($event == "add_template") {
		mysqli_query($conn, "INSERT INTO `templates`(`id`, `title`, `image`, `second_image`, `third_image`, `preview`, `preview2`, `data`, `types_ids`, `photos_amount`, `boxes`, `format_id`, `status`) VALUES (NULL, '$title', '$image', '$second_image', '', '', '', '', '$types_ids', '$photos_amount', '$boxes', '$format_id', '1')") or die(mysqli_error($conn));
		if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
		if ($second_image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$second_image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($second_image, '120'));
		}
		echo"done";
	}

	if ($event == "edit_template") {
		$result_templates = mysqli_query($conn, "SELECT `id` FROM `templates` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_templates) != 0) {
			mysqli_query($conn, "UPDATE `templates` SET `title` = '$title', `types_ids` = '$types_ids', `photos_amount` = '$photos_amount', `boxes` = '$boxes', `format_id` = '$format_id' WHERE `id` = ".$id) or die(mysqli_error($conn));
			if ($image != "") {
				$result_templates = mysqli_query($conn, "SELECT `image` FROM `templates` WHERE `id` = ".$id);
				$row_templates = mysqli_fetch_assoc($result_templates);
				require_once("thumblib/ThumbLib.inc.php");
				$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
				$thumb->resize(120, 0);
				$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
				mysqli_query($conn, "UPDATE `templates` SET `image` = '$image' WHERE `id` = ".$id);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120'));
			}

			if ($second_image != "") {
				$result_templates = mysqli_query($conn, "SELECT `second_image` FROM `templates` WHERE `id` = ".$id);
				$row_templates = mysqli_fetch_assoc($result_templates);
        require_once("thumblib/ThumbLib.inc.php");
        $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$second_image);
        $thumb->resize(120, 0);
        $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($second_image, '120'));
				mysqli_query($conn, "UPDATE `templates` SET `second_image` = '$second_image' WHERE `id` = ".$id);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['second_image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['second_image'], '120'));
			}
			echo"done";
		} else {
			echo"error";
		}
	}

	if ($event == "delete_image_template") {
		$result_templates = mysqli_query($conn, "SELECT `image` FROM `templates` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_templates) != 0) {
			$row_templates = mysqli_fetch_assoc($result_templates);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120'));
			mysqli_query($conn, "UPDATE `templates` SET `image` = '' WHERE `id` = ".$id);
		}
	}

	if ($event == "delete_second_image_template") {
		$result_templates = mysqli_query($conn, "SELECT `second_image` FROM `templates` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_templates) != 0) {
			$row_templates = mysqli_fetch_assoc($result_templates);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['second_image']);
			mysqli_query($conn, "UPDATE `templates` SET `second_image` = '' WHERE `id` = ".$id);
		}
	}

	if ($event == "delete_template") {
		$result_templates =mysqli_query($conn, "SELECT * FROM `templates` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_templates) != 0) {
			$row_templates = mysqli_fetch_assoc($result_templates);
			if ($row_templates['image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['image'], '120'));
			}
			if ($row_templates['second_image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['second_image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['second_image'], '120'));
			}
			if ($row_templates['third_image'] != "") {
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['third_image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['third_image'], '120'));
			}
			mysqli_query($conn, "DELETE FROM `templates` WHERE `id` = ".$id);
			echo"done";
		} else {
			echo"error";
		}
	}

	if ($event == "to_edit") {
		$result_templates = mysqli_query($conn, "SELECT `image` FROM `templates` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_templates) != 0) {
			$row_templates = mysqli_fetch_assoc($result_templates);
			if ($row_templates['image'] != "") {
				copy(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['image'], "./editor/frame.png");
				setcookie("template_id", $id);
				echo"done";
			} else {
				echo"error";
			}
		} else {
			echo"error";
		}
	}

	// Деактивация заведения
	if ($event == "template_deactivate") {
		mysqli_query($conn, "UPDATE `templates` SET `status` = 0 WHERE `id` = ".$id);
	}

	// Активация заведения
	if ($event == "template_activate") {
		mysqli_query($conn, "UPDATE `templates` SET `status` = 1 WHERE `id` = ".$id);
	}

}

if ($event == "eraser") {
	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
  $row_orders = mysqli_fetch_assoc($result_orders);
  @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image']);
	@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image'], '120'));
	mysqli_query($conn, "UPDATE `orders_new` SET `image` = '' WHERE `id` = ".$id);
	echo"done";
}

if ($event == "eraser_vegas") {
	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$id);
  $row_orders = mysqli_fetch_assoc($result_orders);
  @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_orders['image_vegas']);
	@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders['image_vegas'], '120'));
	mysqli_query($conn, "UPDATE `orders_new` SET `image_vegas` = '' WHERE `id` = ".$id);
	echo"done";
}

if ($event == "save_info") {
	mysqli_query($conn, "UPDATE `orders_new` SET `info` = '$info' WHERE `id` = ".$id);
	echo"done";
}

if ($event == "delete_image") {
		$result_orders_images = mysqli_query($conn, "SELECT `image` FROM `orders_images` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_orders_images) != 0) {
			$row_orders_images = mysqli_fetch_assoc($result_orders_images);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120'));
			mysqli_query($conn, "DELETE FROM `orders_images` WHERE `id` = ".$id);
			echo"done";
		}
	}

	if ($event == "configure_order") {


    if (!isset($event_id)) {
    	$event_id = 0;
    }

		$result_configure_orders = mysqli_query($conn, "SELECT * FROM `configure_orders` WHERE `order_id` = ".$id." AND `event_id` = ".$event_id);
		if (mysqli_num_rows($result_configure_orders) != 0) {

			mysqli_query($conn, "UPDATE `configure_orders` SET `background_color` = '$background_color', `text_color` = '$text_color', `photo_switch` = '$photo_switch', `photo_delay_1` = '$photo_delay_1', `photo_delay_2` = '$photo_delay_2', `gif_switch` = '$gif_switch', `gif_delay_1` = '$gif_delay_1', `gif_delay_2` = '$gif_delay_2', `gif_speed` = '$gif_speed', `boomerang_switch` = '$boomerang_switch', `boomerang_delay` = '$boomerang_delay', `boomerang_duration` = '$boomerang_duration', `boomerang_speed` = '$boomerang_speed', `prop_switch` = '$prop_switch', `gallery_id` = '$gallery_id', `sms_switch` = '$sms_switch', `sms_text` = '$sms_text', `sms_popup` = '$sms_popup', `sms_button` = '$sms_button', `email_switch` = '$email_switch', `email_from` = '$email_from', `email_subject` = '$email_subject', `email_text` = '$email_text', `email_popup` = '$email_popup', `email_button` = '$email_button', `qr_code_switch` = '$qr_code_switch', `rgpd_switch` = '$rgpd_switch', `rgpd_text` = '$rgpd_text', `rgpd_yes` = '$rgpd_yes', `rgpd_no` = '$rgpd_no', `photo_amount` = '$photo_amount', `photo_max` = '$photo_max' WHERE `order_id` = ".$id) or die(mysqli_error($conn));


			if ($video != "") {
				$row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
				if ($row_configure_orders['video'] != "") {
					mysqli_query($conn, "UPDATE `configure_orders` SET `video` = '".$row_configure_orders['video']."' WHERE `order_id` = ".$id);
				} else {
					mysqli_query($conn, "UPDATE `configure_orders` SET `video` = '$video' WHERE `order_id` = ".$id);
				}
				//@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_configure_orders['video']);
			}

			if ($video1 != "") {
				$videos_arr = explode(";", $video1);
				foreach($videos_arr as $key => $video) {
					mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '0', '$id', '1', '$video')");
				}
			}

			if ($video2 != "") {
				$videos_arr = explode(";", $video2);
				foreach($videos_arr as $key => $video) {
					mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '0', '$id', '2', '$video')");
				}
			}

			if ($video3 != "") {
				$videos_arr = explode(";", $video3);
				foreach($videos_arr as $key => $video) {
					mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '0', '$id', '3', '$video')");
				}
			}


			if ($image != "") {
				$row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
				require_once("thumblib/ThumbLib.inc.php");
				$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
				$thumb->resize(120, 0);
				$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
				mysqli_query($conn, "UPDATE `configure_orders` SET `image` = '$image' WHERE `order_id` = ".$id);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_configure_orders['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_configure_orders['image'], '120'));
			}

			echo"done";
		} else {

      require_once("thumblib/ThumbLib.inc.php");

      /*if ($image != "") {
        $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
        $thumb->resize(120, 0);
        $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
      } else {
        $image = md5(time()).".png";
        copy(ADMIN_UPLOAD_IMAGES_DIR."image.png", ADMIN_UPLOAD_IMAGES_DIR.$image);
        $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
        $thumb->resize(120, 0);
        $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
      }

      if ($video == "") {
        $video = md5(time()).".mp4";
        copy(ADMIN_UPLOAD_IMAGES_DIR."video.mp4", ADMIN_UPLOAD_IMAGES_DIR.$video);
      }*/

      if ($image != "") {
        $file = pathinfo($image);
	      $image2 = md5(time()).".".strtolower($file['extension']);
	      copy(ADMIN_UPLOAD_IMAGES_DIR.$image, ADMIN_UPLOAD_IMAGES_DIR.$image2);
	      $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image2);
	      $thumb->resize(120, 0);
	      $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image2, '120'));
	    } else {
	    	$image2 = "";
	    }

	    if ($video != "") {
        $file = pathinfo($video);
	      $video2 = md5(time()).".".strtolower($file['extension']);
	      copy(ADMIN_UPLOAD_IMAGES_DIR.$video, ADMIN_UPLOAD_IMAGES_DIR.$video2);
	    } else {
	    	$video2 = "";
	    }

			mysqli_query($conn, "INSERT INTO `configure_orders`(`id`, `order_id`, `event_id`, `video`, `image`, `background_color`, `text_color`, `photo_switch`, `photo_delay_1`, `photo_delay_2`, `gif_switch`, `gif_delay_1`, `gif_delay_2`, `gif_speed`, `boomerang_switch`, `boomerang_delay`, `boomerang_duration`, `boomerang_speed`, `prop_switch`, `gallery_id`, `sms_switch`, `sms_text`, `sms_popup`, `sms_button`, `email_switch`, `email_from`, `email_subject`, `email_text`, `email_popup`, `email_button`, `qr_code_switch`, `rgpd_switch`, `rgpd_text`, `rgpd_yes`, `rgpd_no`, `photo_amount`, `photo_max`) VALUES (NULL, '$id', '$event_id', '$video2', '$image2', '$background_color', '$text_color', '$photo_switch', '$photo_delay_1', '$photo_delay_2', '$gif_switch', '$gif_delay_1', '$gif_delay_2', '$gif_speed', '$boomerang_switch', '$boomerang_delay', '$boomerang_duration', '$boomerang_speed', '$prop_switch', '$gallery_id', '$sms_switch', '$sms_text', '$sms_popup', '$sms_button', '$email_switch', '$email_from', '$email_subject', '$email_text', '$email_popup', '$email_button', '$qr_code_switch', '$rgpd_switch', '$rgpd_text', '$rgpd_yes', '$rgpd_no', '$photo_amount', '$photo_max')") or die(mysqli_error($conn));

			echo"done";
		}
	}

	if ($event == "delete_configure_orders_video") {
		$result_configure_orders = mysqli_query($conn, "SELECT `video` FROM `configure_orders` WHERE `order_id` = ".$id);
		if (mysqli_num_rows($result_configure_orders) != 0) {
			$row_configure_orders= mysqli_fetch_assoc($result_configure_orders);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_configure_orders['video']);
			mysqli_query($conn, "UPDATE `configure_orders` SET `video` = '' WHERE `order_id` = ".$id);
		}
	}

	if ($event == "delete_configure_orders_image") {
		$result_configure_orders = mysqli_query($conn, "SELECT `image` FROM `configure_orders` WHERE `order_id` = ".$id);
		if (mysqli_num_rows($result_configure_orders) != 0) {
			$row_configure_orders= mysqli_fetch_assoc($result_configure_orders);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_configure_orders['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_configure_orders['image'], '120'));
			mysqli_query($conn, "UPDATE `configure_orders` SET `image` = '' WHERE `order_id` = ".$id);
		}
	}

	if ($event == "configure_box") {

			if ($video != "") {
				$row_configure_orders = mysqli_fetch_assoc($result_configure_orders);
				mysqli_query($conn, "UPDATE `configure_orders` SET `video` = '$video' WHERE `order_id` = ".$id);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_configure_orders['video']);
			}

			if ($video1 != "") {
				$videos_arr = explode(";", $video1);
				foreach($videos_arr as $key => $video) {
					mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '$box_type', '0', '1', '$video')");
				}
			}

			if ($video2 != "") {
				$videos_arr = explode(";", $video2);
				foreach($videos_arr as $key => $video) {
					mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '$box_type', '0', '2', '$video')");
				}
			}

			if ($video3 != "") {
				$videos_arr = explode(";", $video3);
				foreach($videos_arr as $key => $video) {
					mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '$box_type', '0', '3', '$video')");
				}
			}


			if ($image != "") {
				mysqli_query($conn, "INSERT INTO `gallery_videos` (`id`, `box_type`, `order_id`, `type_id`, `video`) VALUES (NULL, '$box_type', '0', '4', '$image')");
			}

			echo"done";
	}


  if (file_get_contents('php://input') != "") {
    $parametrs_arr = explode("&", str_replace("==", "--", file_get_contents('php://input')));
    foreach($parametrs_arr as $parametr) {
      $parametr_arr = explode("=", $parametr);
      ${$parametr_arr['0']} = $parametr_arr[1];
    }
  }

  if ($event == "save_order_data") {
  	 mysqli_query($conn, "UPDATE `orders_new` SET `data` = '".str_replace('--', '==', $data)."', `template_status` = 1, `gallery_valid` = 0 WHERE `id` = ".$order_id);
  }



  if ($event == "save_image") {

    $img = str_replace('data:image/png;base64,', '', $data);
    $img = str_replace(' ', '+', $img);
    $img = str_replace('--', '==', $img);
    $data = base64_decode($img);
    $file_name = md5(time()).".png";
    file_put_contents(ADMIN_UPLOAD_IMAGES_DIR.$file_name, $data);
    require_once("thumblib/ThumbLib.inc.php");
    $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$file_name);
    $thumb->resize(120, 0);
    $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($file_name, '120'));

    if (isset($data2)) {
      $img2 = str_replace('data:image/png;base64,', '', $data2);
      $img2 = str_replace(' ', '+', $img2);
      $img2 = str_replace('--', '==', $img2);
      $data2 = base64_decode($img2);
      $file_name2 = md5(time().rand(1000, 10000)).".png";
      file_put_contents(ADMIN_UPLOAD_IMAGES_DIR.$file_name2, $data2);
      require_once("thumblib/ThumbLib.inc.php");
      $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$file_name2);
      $thumb->resize(640, 0);
      $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($file_name2, '640'));
      $thumb->resize(120, 0);
      $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($file_name2, '120'));
    }

    if (isset($data3)) {
      $img3 = str_replace('data:image/png;base64,', '', $data3);
      $img3 = str_replace(' ', '+', $img3);
      $img3 = str_replace('--', '==', $img3);
      $data3 = base64_decode($img3);
      $file_name3 = md5(time().rand(1000, 10000)).".png";
      file_put_contents(ADMIN_UPLOAD_IMAGES_DIR.$file_name3, $data3);
      require_once("thumblib/ThumbLib.inc.php");
      $thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$file_name3);
      $thumb->resize(640, 0);
      $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($file_name3, '640'));
      $thumb->resize(120, 0);
      $thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($file_name3, '120'));
    }

    if ($order_id == 0) {
      $result_templates =mysqli_query($conn, "SELECT * FROM `templates` WHERE `image` LIKE '$image'");
      $row_templates = mysqli_fetch_assoc($result_templates);
      if ($row_templates['third_image'] != "") {
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['third_image']);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['third_image'], '120'));
      }
      if ($row_templates['preview'] != "") {
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['preview']);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview'], '120'));
      }
      if ($row_templates['preview2'] != "") {
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_templates['preview2']);
        @unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_templates['preview2'], '120'));
      }
      mysqli_query($conn, "UPDATE `templates` SET `third_image` = '$file_name', `preview` = '$file_name2', `preview2` = '$file_name3', `data` = '".str_replace('--', '==', $json)."' WHERE `image` LIKE '$image'");
    } else {
    	if (!isset($template_id)) {
    		$template_id = 0;
    		$data = "";
    	} else {
    		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `template_id` = ".$template_id." AND `data` != ''");
    		if (mysqli_num_rows($result_orders) != 0) {
    			$row_orders = mysqli_fetch_assoc($result_orders);
    			$data = $row_orders['data'];
    		} else {
    			$data = "";
    		}
    	}

    	if (isset($_SESSION['order']['event_id'])) {
    		mysqli_query($conn, "UPDATE `events` SET `image` = '$file_name', `data` = '$data'  WHERE `id` = ".$_SESSION['order']['event_id']);
    	} else {

    		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".abs($order_id));
		    $row_orders = mysqli_fetch_assoc($result_orders);

		    file_put_contents('mydata.txt', "UPDATE `orders_new` SET `template_id` = '$template_id', `image_vegas` = '$file_name', `data` = '$data', `template_status` = '1' WHERE `id` = ".abs($order_id));

    		//if ($row_orders['template_id'] == 0 || $row_orders['template_id'] == $template_id) {
	      	if (isset($vegas) && $vegas == 1) {
	      		mysqli_query($conn, "UPDATE `orders_new` SET `template_id` = '$template_id', `image_vegas` = '$file_name', `data` = '$data', `template_status` = '1' WHERE `id` = ".abs($order_id));
	      	} else {
	      		mysqli_query($conn, "UPDATE `orders_new` SET `template_id` = '$template_id', `image` = '$file_name', `data` = '$data', `template_status` = '1' WHERE `id` = ".abs($order_id));
	      	}
	      /*} else {
	      	$result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".abs($order_id)." AND template_id` = ".$template_id);
	      	if (mysqli_num_rows($result_orders_images) == 0) {
	      		mysqli_query($conn, "INSERT INTO `orders_images`(`id`, `order_id`, `template_id`, `image`, `template_status`, `data`) VALUES (NULL, '".abs($order_id)."', '$template_id', '$file_name', '0', '$data')");
	      	} else {
	      		$row_orders_images = mysqli_fetch_assoc($result_orders_images);
        		@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_orders_images['image']);
        		@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_orders_images['image'], '120'));
	      		mysqli_query($conn, "UPDATE `orders_images` SET `image` = '$file_name', `data` = '$data' WHERE `order_id` = ".abs($order_id)." AND `template_id` = ".$template_id);
	      	}
	      }*/

	      if ($order_id > 0) {
		      $_SESSION['order']['image'] = $file_name;

		      $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".abs($order_id));
		      $row_orders = mysqli_fetch_assoc($result_orders);

		      $subject = "Votre création de contour photo est à présent finalisée ".date("d-m-Y H:i", time());
		      $mailheaders = "MIME-Version: 1.0\r\n";
		      $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		      $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		      $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		      $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		          <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
		            <head>
		              <meta charset="UTF-8">
		              <meta content="width=device-width, initial-scale=1" name="viewport">
		              <meta name="x-apple-disable-message-reformatting">
		              <meta http-equiv="X-UA-Compatible" content="IE=edge">
		              <meta content="telephone=no" name="format-detection">
		              <title></title>
		              <!--[if (mso 16)]>
		                <style type="text/css">
		                  a {text-decoration: none;}
		                </style>
		              <![endif]-->
		              <!--[if gte mso 9]><style>
		                  sup { font-size: 100% !important; }
		                </style><![endif]-->
		              <!--[if gte mso 9]>
		                <xml>
		                  <o:OfficeDocumentSettings>
		                    <o:AllowPNG></o:AllowPNG>
		                    <o:PixelsPerInch>96</o:PixelsPerInch>
		                  </o:OfficeDocumentSettings>
		                </xml>
		              <![endif]-->
		              <!--[if !mso]><!-- -->
		                <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
		                <!--<![endif]-->
		                <style type="text/css">
		                  /* CONFIG STYLES Please do not delete and edit CSS styles below */
		                  /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
		                  a[x-apple-data-detectors] {
		                      color: inherit !important;
		                      text-decoration: none !important;
		                      font-size: inherit !important;
		                      font-family: inherit !important;
		                      font-weight: inherit !important;
		                      line-height: inherit !important;
		                  }
		                  /*
		                  END OF IMPORTANT
		                  */
		                  /*
		                  END CONFIG STYLES
		                  */
		                  /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
		                  @media only screen and (max-width: 600px) {
		                      p,
		                      ul li,
		                      ol li,
		                      a {
		                          line-height: 150% !important;
		                      }
		                      h1,
		                      h2,
		                      h3,
		                      h1 a,
		                      h2 a,
		                      h3 a {
		                          line-height: 120%;
		                      }
		                      h1 {
		                          font-size: 30px !important;
		                          text-align: center;
		                      }
		                      h2 {
		                          font-size: 26px !important;
		                          text-align: center;
		                      }
		                      h3 {
		                          font-size: 20px !important;
		                          text-align: center;
		                      }
		                      .es-header-body h1 a,
		                      .es-content-body h1 a,
		                      .es-footer-body h1 a {
		                          font-size: 30px !important;
		                      }
		                      .es-header-body h2 a,
		                      .es-content-body h2 a,
		                      .es-footer-body h2 a {
		                          font-size: 26px !important;
		                      }
		                      .es-header-body h3 a,
		                      .es-content-body h3 a,
		                      .es-footer-body h3 a {
		                          font-size: 20px !important;
		                      }
		                      .es-menu td a {
		                          font-size: 16px !important;
		                      }
		                      .es-header-body p,
		                      .es-header-body ul li,
		                      .es-header-body ol li,
		                      .es-header-body a {
		                          font-size: 16px !important;
		                      }
		                      .es-content-body p,
		                      .es-content-body ul li,
		                      .es-content-body ol li,
		                      .es-content-body a {
		                          font-size: 16px !important;
		                      }
		                      .es-footer-body p,
		                      .es-footer-body ul li,
		                      .es-footer-body ol li,
		                      .es-footer-body a {
		                          font-size: 16px !important;
		                      }
		                      .es-infoblock p,
		                      .es-infoblock ul li,
		                      .es-infoblock ol li,
		                      .es-infoblock a {
		                          font-size: 12px !important;
		                      }
		                      *[class="gmail-fix"] {
		                          display: none !important;
		                      }
		                      .es-m-txt-c,
		                      .es-m-txt-c h1,
		                      .es-m-txt-c h2,
		                      .es-m-txt-c h3 {
		                          text-align: center !important;
		                      }
		                      .es-m-txt-r,
		                      .es-m-txt-r h1,
		                      .es-m-txt-r h2,
		                      .es-m-txt-r h3 {
		                          text-align: right !important;
		                      }
		                      .es-m-txt-l,
		                      .es-m-txt-l h1,
		                      .es-m-txt-l h2,
		                      .es-m-txt-l h3 {
		                          text-align: left !important;
		                      }
		                      .es-m-txt-r img,
		                      .es-m-txt-c img,
		                      .es-m-txt-l img {
		                          display: inline !important;
		                      }
		                      .es-button-border {
		                          display: block !important;
		                      }
		                      a.es-button,
		                      button.es-button {
		                          font-size: 20px !important;
		                          display: block !important;
		                          border-width: 10px 0px 10px 0px !important;
		                      }
		                      .es-adaptive table,
		                      .es-left,
		                      .es-right {
		                          width: 100% !important;
		                      }
		                      .es-content table,
		                      .es-header table,
		                      .es-footer table,
		                      .es-content,
		                      .es-footer,
		                      .es-header {
		                          width: 100% !important;
		                          max-width: 600px !important;
		                      }
		                      .es-adapt-td {
		                          display: block !important;
		                          width: 100% !important;
		                      }
		                      .adapt-img {
		                          width: 100% !important;
		                          height: auto !important;
		                      }
		                      .es-m-p0 {
		                          padding: 0 !important;
		                      }
		                      .es-m-p0r {
		                          padding-right: 0 !important;
		                      }
		                      .es-m-p0l {
		                          padding-left: 0 !important;
		                      }
		                      .es-m-p0t {
		                          padding-top: 0 !important;
		                      }
		                      .es-m-p0b {
		                          padding-bottom: 0 !important;
		                      }
		                      .es-m-p20b {
		                          padding-bottom: 20px !important;
		                      }
		                      .es-mobile-hidden,
		                      .es-hidden {
		                          display: none !important;
		                      }
		                      tr.es-desk-hidden,
		                      td.es-desk-hidden,
		                      table.es-desk-hidden {
		                          width: auto !important;
		                          overflow: visible !important;
		                          float: none !important;
		                          max-height: inherit !important;
		                          line-height: inherit !important;
		                      }
		                      tr.es-desk-hidden {
		                          display: table-row !important;
		                      }
		                      table.es-desk-hidden {
		                          display: table !important;
		                      }
		                      td.es-desk-menu-hidden {
		                          display: table-cell !important;
		                      }
		                      .es-menu td {
		                          width: 1% !important;
		                      }
		                      table.es-table-not-adapt,
		                      .esd-block-html table {
		                          width: auto !important;
		                      }
		                      table.es-social {
		                          display: inline-block !important;
		                      }
		                      table.es-social td {
		                          display: inline-block !important;
		                      }
		                      .m_pic {
		                          width: 60%;
		                      }
		                      .es-m-p5 {
		                          padding: 5px !important;
		                      }
		                      .es-m-p5t {
		                          padding-top: 5px !important;
		                      }
		                      .es-m-p5b {
		                          padding-bottom: 5px !important;
		                      }
		                      .es-m-p5r {
		                          padding-right: 5px !important;
		                      }
		                      .es-m-p5l {
		                          padding-left: 5px !important;
		                      }
		                      .es-m-p10 {
		                          padding: 10px !important;
		                      }
		                      .es-m-p10t {
		                          padding-top: 10px !important;
		                      }
		                      .es-m-p10b {
		                          padding-bottom: 10px !important;
		                      }
		                      .es-m-p10r {
		                          padding-right: 10px !important;
		                      }
		                      .es-m-p10l {
		                          padding-left: 10px !important;
		                      }
		                      .es-m-p15 {
		                          padding: 15px !important;
		                      }
		                      .es-m-p15t {
		                          padding-top: 15px !important;
		                      }
		                      .es-m-p15b {
		                          padding-bottom: 15px !important;
		                      }
		                      .es-m-p15r {
		                          padding-right: 15px !important;
		                      }
		                      .es-m-p15l {
		                          padding-left: 15px !important;
		                      }
		                      .es-m-p20 {
		                          padding: 20px !important;
		                      }
		                      .es-m-p20t {
		                          padding-top: 20px !important;
		                      }
		                      .es-m-p20r {
		                          padding-right: 20px !important;
		                      }
		                      .es-m-p20l {
		                          padding-left: 20px !important;
		                      }
		                      .es-m-p25 {
		                          padding: 25px !important;
		                      }
		                      .es-m-p25t {
		                          padding-top: 25px !important;
		                      }
		                      .es-m-p25b {
		                          padding-bottom: 25px !important;
		                      }
		                      .es-m-p25r {
		                          padding-right: 25px !important;
		                      }
		                      .es-m-p25l {
		                          padding-left: 25px !important;
		                      }
		                      .es-m-p30 {
		                          padding: 30px !important;
		                      }
		                      .es-m-p30t {
		                          padding-top: 30px !important;
		                      }
		                      .es-m-p30b {
		                          padding-bottom: 30px !important;
		                      }
		                      .es-m-p30r {
		                          padding-right: 30px !important;
		                      }
		                      .es-m-p30l {
		                          padding-left: 30px !important;
		                      }
		                      .es-m-p35 {
		                          padding: 35px !important;
		                      }
		                      .es-m-p35t {
		                          padding-top: 35px !important;
		                      }
		                      .es-m-p35b {
		                          padding-bottom: 35px !important;
		                      }
		                      .es-m-p35r {
		                          padding-right: 35px !important;
		                      }
		                      .es-m-p35l {
		                          padding-left: 35px !important;
		                      }
		                      .es-m-p40 {
		                          padding: 40px !important;
		                      }
		                      .es-m-p40t {
		                          padding-top: 40px !important;
		                      }
		                      .es-m-p40b {
		                          padding-bottom: 40px !important;
		                      }
		                      .es-m-p40r {
		                          padding-right: 40px !important;
		                      }
		                      .es-m-p40l {
		                          padding-left: 40px !important;
		                      }
		                      .es-content-body .fz14 {
		                          font-size: 14px !important;
		                      }
		                  }
		                  /* END RESPONSIVE STYLES */
		                </style>
		              </head>
		              <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
		                <div class="es-wrapper-color" style="background-color:#f6f6f6;">
		                  <!--[if gte mso 9]>
		                    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
		                      <v:fill type="tile" color="#f6f6f6"></v:fill>
		                    </v:background>
		                  <![endif]-->
		                  <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;">
		                    <tbody>
		                      <tr>
		                        <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
		                          <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
		                            <tbody>
		                              <tr>
		                                <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
		                                  <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
		                                    <tbody>
		                                      <tr>
		                                        <td class="esd-structure es-p30t es-p20r es-p20l es-m-p10t" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;background-image: url(https://ftp.shootnbox.fr/manager/mail/bg_header.png); background-repeat: no-repeat; background-position: center top; background-size: 100%;" background="https://ftp.shootnbox.fr/manager/mail/bg_header.png" align="left">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img class="m_pic" src="https://ftp.shootnbox.fr/manager/mail/logo3.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="350"></a></td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                      <tr>
		                                        <td class="esd-structure es-p25t es-p25r es-p25l es-m-p10r es-m-p10l" align="left" style="padding:0;Margin:0;padding-top:25px;padding-left:25px;padding-right:25px;">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="550" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-text es-p10b" align="left" style="padding:0;Margin:0;padding-bottom:10px;">
		                                                          <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong>Bonjour,</strong></p>
		                                                        </td>
		                                                      </tr>
		                                                      <tr>
		                                                        <td class="esd-block-text" align="left" style="padding:0;Margin:0;">
		                                                          <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong>Merci ! Votre création de contour photo est à présent finalisée !</strong></p>
		                                                        </td>
		                                                      </tr>
		                                                      <tr>
		                                                        <td class="esd-block-text" esd-links-color="#333333" align="left" style="padding:0;Margin:0;">
		                                                          <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;">Pour rappel, vous trouverez en pièce jointe de <a href="https://ftp.shootnbox.fr/uploads/images/'.$row_orders['image'].'">ce lien</a> le fichier image de votre personnalisation. Celle-ci est définitive. En cas de modification, merci de contacter la Team afin de réactiver vos identifiants. Ce service sera facturé 10€.</p>
		                                                        </td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                      <tr>
		                                        <td class="esd-structure es-p20t es-p25b es-p25r es-p25l es-m-p10r es-m-p10l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-bottom:25px;padding-left:25px;padding-right:25px;">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="550" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-text" align="left" style="padding:0;Margin:0;">
		                                                          <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;">La Team Shootnbox <strong>🙂</strong><br>
		                                                          </p>
		                                                        </td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                      <tr>
		                                        <td class="esd-structure es-p15t es-p15b es-p20r es-p20l" style="padding:0;Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;background-color: #171b1d;" bgcolor="#171b1d" align="left">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-image es-p10b" style="padding:0;Margin:0;padding-bottom:10px;font-size: 0px;" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://ftp.shootnbox.fr/manager/mail/logo_footer_2x.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="139"></a></td>
		                                                      </tr>
		                                                      <tr>
		                                                        <td class="esd-block-text" esd-links-underline="none" esd-links-color="#ffffff" align="center" style="padding:0;Margin:0;">
		                                                          <p class="fz14" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;color: #ffffff;"><a target="_blank" href="mailto:contact@shootnbox.fr" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;text-decoration: none; color: #ffffff;">contact@shootnbox.fr</a> | 01 45 01 66 66</p>
		                                                        </td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                    </tbody>
		                                  </table>
		                                </td>
		                              </tr>
		                            </tbody>
		                          </table>
		                        </td>
		                      </tr>
		                    </tbody>
		                  </table>
		                </div>
		              </body>
		            </html>';
		            mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		      mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);

		      $subject = "Maquette (".$row_orders['num_id'].") ".$row_orders['last_name']." ".$row_orders['first_name']." ".date("d-m-Y H:i", time());
		      $mailheaders = "MIME-Version: 1.0\r\n";
		      $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		      $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		      $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		      $msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		          <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
		            <head>
		              <meta charset="UTF-8">
		              <meta content="width=device-width, initial-scale=1" name="viewport">
		              <meta name="x-apple-disable-message-reformatting">
		              <meta http-equiv="X-UA-Compatible" content="IE=edge">
		              <meta content="telephone=no" name="format-detection">
		              <title></title>
		              <!--[if (mso 16)]>
		                <style type="text/css">
		                  a {text-decoration: none;}
		                </style>
		              <![endif]-->
		              <!--[if gte mso 9]><style>
		                  sup { font-size: 100% !important; }
		                </style><![endif]-->
		              <!--[if gte mso 9]>
		                <xml>
		                  <o:OfficeDocumentSettings>
		                    <o:AllowPNG></o:AllowPNG>
		                    <o:PixelsPerInch>96</o:PixelsPerInch>
		                  </o:OfficeDocumentSettings>
		                </xml>
		              <![endif]-->
		              <!--[if !mso]><!-- -->
		                <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
		                <!--<![endif]-->
		                <style type="text/css">
		                  /* CONFIG STYLES Please do not delete and edit CSS styles below */
		                  /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
		                  a[x-apple-data-detectors] {
		                      color: inherit !important;
		                      text-decoration: none !important;
		                      font-size: inherit !important;
		                      font-family: inherit !important;
		                      font-weight: inherit !important;
		                      line-height: inherit !important;
		                  }
		                  /*
		                  END OF IMPORTANT
		                  */
		                  /*
		                  END CONFIG STYLES
		                  */
		                  /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
		                  @media only screen and (max-width: 600px) {
		                      p,
		                      ul li,
		                      ol li,
		                      a {
		                          line-height: 150% !important;
		                      }
		                      h1,
		                      h2,
		                      h3,
		                      h1 a,
		                      h2 a,
		                      h3 a {
		                          line-height: 120%;
		                      }
		                      h1 {
		                          font-size: 30px !important;
		                          text-align: center;
		                      }
		                      h2 {
		                          font-size: 26px !important;
		                          text-align: center;
		                      }
		                      h3 {
		                          font-size: 20px !important;
		                          text-align: center;
		                      }
		                      .es-header-body h1 a,
		                      .es-content-body h1 a,
		                      .es-footer-body h1 a {
		                          font-size: 30px !important;
		                      }
		                      .es-header-body h2 a,
		                      .es-content-body h2 a,
		                      .es-footer-body h2 a {
		                          font-size: 26px !important;
		                      }
		                      .es-header-body h3 a,
		                      .es-content-body h3 a,
		                      .es-footer-body h3 a {
		                          font-size: 20px !important;
		                      }
		                      .es-menu td a {
		                          font-size: 16px !important;
		                      }
		                      .es-header-body p,
		                      .es-header-body ul li,
		                      .es-header-body ol li,
		                      .es-header-body a {
		                          font-size: 16px !important;
		                      }
		                      .es-content-body p,
		                      .es-content-body ul li,
		                      .es-content-body ol li,
		                      .es-content-body a {
		                          font-size: 16px !important;
		                      }
		                      .es-footer-body p,
		                      .es-footer-body ul li,
		                      .es-footer-body ol li,
		                      .es-footer-body a {
		                          font-size: 16px !important;
		                      }
		                      .es-infoblock p,
		                      .es-infoblock ul li,
		                      .es-infoblock ol li,
		                      .es-infoblock a {
		                          font-size: 12px !important;
		                      }
		                      *[class="gmail-fix"] {
		                          display: none !important;
		                      }
		                      .es-m-txt-c,
		                      .es-m-txt-c h1,
		                      .es-m-txt-c h2,
		                      .es-m-txt-c h3 {
		                          text-align: center !important;
		                      }
		                      .es-m-txt-r,
		                      .es-m-txt-r h1,
		                      .es-m-txt-r h2,
		                      .es-m-txt-r h3 {
		                          text-align: right !important;
		                      }
		                      .es-m-txt-l,
		                      .es-m-txt-l h1,
		                      .es-m-txt-l h2,
		                      .es-m-txt-l h3 {
		                          text-align: left !important;
		                      }
		                      .es-m-txt-r img,
		                      .es-m-txt-c img,
		                      .es-m-txt-l img {
		                          display: inline !important;
		                      }
		                      .es-button-border {
		                          display: block !important;
		                      }
		                      a.es-button,
		                      button.es-button {
		                          font-size: 20px !important;
		                          display: block !important;
		                          border-width: 10px 0px 10px 0px !important;
		                      }
		                      .es-adaptive table,
		                      .es-left,
		                      .es-right {
		                          width: 100% !important;
		                      }
		                      .es-content table,
		                      .es-header table,
		                      .es-footer table,
		                      .es-content,
		                      .es-footer,
		                      .es-header {
		                          width: 100% !important;
		                          max-width: 600px !important;
		                      }
		                      .es-adapt-td {
		                          display: block !important;
		                          width: 100% !important;
		                      }
		                      .adapt-img {
		                          width: 100% !important;
		                          height: auto !important;
		                      }
		                      .es-m-p0 {
		                          padding: 0 !important;
		                      }
		                      .es-m-p0r {
		                          padding-right: 0 !important;
		                      }
		                      .es-m-p0l {
		                          padding-left: 0 !important;
		                      }
		                      .es-m-p0t {
		                          padding-top: 0 !important;
		                      }
		                      .es-m-p0b {
		                          padding-bottom: 0 !important;
		                      }
		                      .es-m-p20b {
		                          padding-bottom: 20px !important;
		                      }
		                      .es-mobile-hidden,
		                      .es-hidden {
		                          display: none !important;
		                      }
		                      tr.es-desk-hidden,
		                      td.es-desk-hidden,
		                      table.es-desk-hidden {
		                          width: auto !important;
		                          overflow: visible !important;
		                          float: none !important;
		                          max-height: inherit !important;
		                          line-height: inherit !important;
		                      }
		                      tr.es-desk-hidden {
		                          display: table-row !important;
		                      }
		                      table.es-desk-hidden {
		                          display: table !important;
		                      }
		                      td.es-desk-menu-hidden {
		                          display: table-cell !important;
		                      }
		                      .es-menu td {
		                          width: 1% !important;
		                      }
		                      table.es-table-not-adapt,
		                      .esd-block-html table {
		                          width: auto !important;
		                      }
		                      table.es-social {
		                          display: inline-block !important;
		                      }
		                      table.es-social td {
		                          display: inline-block !important;
		                      }
		                      .m_pic {
		                          width: 60%;
		                      }
		                      .es-m-p5 {
		                          padding: 5px !important;
		                      }
		                      .es-m-p5t {
		                          padding-top: 5px !important;
		                      }
		                      .es-m-p5b {
		                          padding-bottom: 5px !important;
		                      }
		                      .es-m-p5r {
		                          padding-right: 5px !important;
		                      }
		                      .es-m-p5l {
		                          padding-left: 5px !important;
		                      }
		                      .es-m-p10 {
		                          padding: 10px !important;
		                      }
		                      .es-m-p10t {
		                          padding-top: 10px !important;
		                      }
		                      .es-m-p10b {
		                          padding-bottom: 10px !important;
		                      }
		                      .es-m-p10r {
		                          padding-right: 10px !important;
		                      }
		                      .es-m-p10l {
		                          padding-left: 10px !important;
		                      }
		                      .es-m-p15 {
		                          padding: 15px !important;
		                      }
		                      .es-m-p15t {
		                          padding-top: 15px !important;
		                      }
		                      .es-m-p15b {
		                          padding-bottom: 15px !important;
		                      }
		                      .es-m-p15r {
		                          padding-right: 15px !important;
		                      }
		                      .es-m-p15l {
		                          padding-left: 15px !important;
		                      }
		                      .es-m-p20 {
		                          padding: 20px !important;
		                      }
		                      .es-m-p20t {
		                          padding-top: 20px !important;
		                      }
		                      .es-m-p20r {
		                          padding-right: 20px !important;
		                      }
		                      .es-m-p20l {
		                          padding-left: 20px !important;
		                      }
		                      .es-m-p25 {
		                          padding: 25px !important;
		                      }
		                      .es-m-p25t {
		                          padding-top: 25px !important;
		                      }
		                      .es-m-p25b {
		                          padding-bottom: 25px !important;
		                      }
		                      .es-m-p25r {
		                          padding-right: 25px !important;
		                      }
		                      .es-m-p25l {
		                          padding-left: 25px !important;
		                      }
		                      .es-m-p30 {
		                          padding: 30px !important;
		                      }
		                      .es-m-p30t {
		                          padding-top: 30px !important;
		                      }
		                      .es-m-p30b {
		                          padding-bottom: 30px !important;
		                      }
		                      .es-m-p30r {
		                          padding-right: 30px !important;
		                      }
		                      .es-m-p30l {
		                          padding-left: 30px !important;
		                      }
		                      .es-m-p35 {
		                          padding: 35px !important;
		                      }
		                      .es-m-p35t {
		                          padding-top: 35px !important;
		                      }
		                      .es-m-p35b {
		                          padding-bottom: 35px !important;
		                      }
		                      .es-m-p35r {
		                          padding-right: 35px !important;
		                      }
		                      .es-m-p35l {
		                          padding-left: 35px !important;
		                      }
		                      .es-m-p40 {
		                          padding: 40px !important;
		                      }
		                      .es-m-p40t {
		                          padding-top: 40px !important;
		                      }
		                      .es-m-p40b {
		                          padding-bottom: 40px !important;
		                      }
		                      .es-m-p40r {
		                          padding-right: 40px !important;
		                      }
		                      .es-m-p40l {
		                          padding-left: 40px !important;
		                      }
		                      .es-content-body .fz14 {
		                          font-size: 14px !important;
		                      }
		                  }
		                  /* END RESPONSIVE STYLES */
		                </style>
		              </head>
		              <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;">
		                <div class="es-wrapper-color" style="background-color:#f6f6f6;">
		                  <!--[if gte mso 9]>
		                    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
		                      <v:fill type="tile" color="#f6f6f6"></v:fill>
		                    </v:background>
		                  <![endif]-->
		                  <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;">
		                    <tbody>
		                      <tr>
		                        <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
		                          <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
		                            <tbody>
		                              <tr>
		                                <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
		                                  <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
		                                    <tbody>
		                                      <tr>
		                                        <td class="esd-structure es-p30t es-p20r es-p20l es-m-p10t" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;background-image: url(https://ftp.shootnbox.fr/manager/mail/bg_header.png); background-repeat: no-repeat; background-position: center top; background-size: 100%;" background="https://ftp.shootnbox.fr/manager/mail/bg_header.png" align="left">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-image" style="padding:0;Margin:0;font-size: 0px;" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img class="m_pic" src="https://ftp.shootnbox.fr/manager/mail/logo3.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="350"></a></td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                      <tr>
		                                        <td class="esd-structure es-p25t es-p25r es-p25l es-m-p10r es-m-p10l" align="left" style="padding:0;Margin:0;padding-top:25px;padding-left:25px;padding-right:25px;">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="550" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-text" align="left" style="padding:0;Margin:0;">
		                                                          <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong>Une nouvelle maquette est validée !</strong></p>
		                                                        </td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                      <tr>
		                                      <td class="esd-structure es-p30t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
		                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                          <tbody>
		                                            <tr>
		                                              <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                  <tbody>
		                                                    <tr>
		                                                      <td class="esd-block-text" esd-links-underline="none" esd-links-color="#333333" align="center" style="padding:0;Margin:0;">
		                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">Nom </span>: '.$row_orders['last_name'].' '.$row_orders['first_name'].'</strong></p>
		                                                      </td>
		                                                    </tr>
		                                                    <tr>
		                                                      <td class="esd-block-text" align="center" style="padding:0;Margin:0;">
		                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">ID </span>: '.$row_orders['email'].'</strong><br>
		                                                        </p>
		                                                      </td>
		                                                    </tr>
		                                                    <tr>
		                                                      <td class="esd-block-text" align="center" style="padding:0;Margin:0;">
		                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">Date </span>: '.date("d.m.Y", time()).'</strong><br>
		                                                        </p>
		                                                      </td>
		                                                    </tr>
		                                                    <tr>
		                                                      <td class="esd-block-text" align="center" style="padding:0;Margin:0;">
		                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">Type </span>: '.$row_orders['box_type'].'</strong><br>
		                                                        </p>
		                                                      </td>
		                                                    </tr>';
		                                                    if ($row_orders['image'] != "") {
		                                                      $msg .= '<tr>
		                                                      	<td class="esd-block-text" align="center" style="padding:0;Margin:0;">
			                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">URL </span>: <a href="https://ftp.shootnbox.fr/uploads/images/'.$row_orders['image'].'">https://ftp.shootnbox.fr/uploads/images/'.$row_orders['image'].'</a></strong><br>
			                                                        </p>
		                                                       	</td>
		                                                    	</tr>';
		                                                    }
		                                                    $result_orders_images = mysqli_query($conn, "SELECT * FROM `orders_images` WHERE `order_id` = ".$row_orders['id']);
																				                while($row_orders_images = mysqli_fetch_assoc($result_orders_images)) {
																				                  $msg .= '<tr>
		                                                      	<td class="esd-block-text" align="center" style="padding:0;Margin:0;">
			                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">URL </span>: <a href="https://ftp.shootnbox.fr/uploads/images/'.$row_orders_images['image'].'">https://ftp.shootnbox.fr/uploads/images/'.$row_orders_images['image'].'</a></strong><br>
			                                                        </p>
		                                                       	</td>
		                                                    	</tr>';
																				                }
																				                $result_template_images = mysqli_query($conn, "SELECT * FROM `template_images` WHERE `order_id` = ".$row_orders['id']);
																				                while($row_template_images = mysqli_fetch_assoc($result_template_images)) {
																													$msg .= '<tr>
		                                                      	<td class="esd-block-text" align="center" style="padding:0;Margin:0;">
			                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;"><strong><span style="color:#e9209b;">URL </span>: <a href="https://ftp.shootnbox.fr/uploads/images/'.$row_template_images['image'].'">https://ftp.shootnbox.fr/uploads/images/'.$row_template_images['image'].'</a></strong><br>
			                                                        </p>
		                                                       	</td>
		                                                    	</tr>';
																				                 }
		                                                		$msg .= '</tbody>
		                                                </table>
		                                              </td>
		                                            </tr>
		                                          </tbody>
		                                        </table>
		                                      </td>
		                                    </tr>
		                                      <tr>
		                                        <td class="esd-structure es-p20t es-p25b es-p25r es-p25l es-m-p10r es-m-p10l" align="left" style="padding:0;Margin:0;padding-top:20px;padding-bottom:25px;padding-left:25px;padding-right:25px;">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="550" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-text" align="left" style="padding:0;Margin:0;">
		                                                          <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;font-size: 15px;">La Team Shootnbox <strong>🙂</strong><br>
		                                                          </p>
		                                                        </td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                      <tr>
		                                        <td class="esd-structure es-p15t es-p15b es-p20r es-p20l" style="padding:0;Margin:0;padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;background-color: #171b1d;" bgcolor="#171b1d" align="left">
		                                          <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                            <tbody>
		                                              <tr>
		                                                <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
		                                                  <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
		                                                    <tbody>
		                                                      <tr>
		                                                        <td class="esd-block-image es-p10b" style="padding:0;Margin:0;padding-bottom:10px;font-size: 0px;" align="center"><a target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;"><img src="https://ftp.shootnbox.fr/manager/mail/logo_footer_2x.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;display: block;" width="139"></a></td>
		                                                      </tr>
		                                                      <tr>
		                                                        <td class="esd-block-text" esd-links-underline="none" esd-links-color="#ffffff" align="center" style="padding:0;Margin:0;">
		                                                          <p class="fz14" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:\'open sans\', \'helvetica neue\', helvetica, arial, sans-serif;line-height:150%;color:#333333;font-size:14px;color: #ffffff;"><a target="_blank" href="mailto:contact@shootnbox.fr" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2cb543;font-size:14px;text-decoration: none; color: #ffffff;">contact@shootnbox.fr</a> | 01 45 01 66 66</p>
		                                                        </td>
		                                                      </tr>
		                                                    </tbody>
		                                                  </table>
		                                                </td>
		                                              </tr>
		                                            </tbody>
		                                          </table>
		                                        </td>
		                                      </tr>
		                                    </tbody>
		                                  </table>
		                                </td>
		                              </tr>
		                            </tbody>
		                          </table>
		                        </td>
		                      </tr>
		                    </tbody>
		                  </table>
		                </div>
		              </body>
		            </html>';
		            mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		      mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		    }
		  }
    }
    echo"done";
  }

 if ($event == "bornes_list") {
    /*if (strpos($select_type, 'entreprise') !== false) {
      $prices_arr = json_decode(file_get_contents("../enterprise_price.ini"), true);
    } else {
      $prices_arr = json_decode(file_get_contents("../particulier_price.ini"), true);
    }
    $html = '<option value="" data-price="0" selected>Choisissez le type de stand</option>';
    foreach ($prices_arr as $key => $value) {
      switch($key) {
        case 'ring_price': $title = "Ring"; $group = 1; break;
        case 'vegas_price': $title = "Vegas"; $group = 2; break;
        case 'vegas_price_2': $title = "Vegas_800"; $group = 2; break;
        case 'vegas_price_1200': $title = "Vegas_1200"; $group = 2; break;
        case 'vegas_price_1600': $title = "Vegas_1600"; $group = 2; break;
        case 'vegas_price_2000': $title = "Vegas_2000"; $group = 2; break;
        case 'miroir_price': $title = "Miroir"; $group = 3; break;
        case 'miroir_price_2': $title = "Miroir_800"; $group = 3; break;
        case 'miroir_price_1200': $title = "Miroir_1200"; $group = 3; break;
        case 'miroir_price_1600': $title = "Miroir_1600"; $group = 3; break;
        case 'miroir_price_2000': $title = "Miroir_2000"; $group = 3; break;
        case 'spinner_price': $title = "Spinner_360"; $group = 4; break;
        case 'vr_price': $title = "Réalité_Virtuelle"; $group = 4; break;
      }
      $html .= '<option value="'.$title.'" data-price="'.$value.'" data-group="'.$group.'">'.$title.' - '.$value.'€</option>';
    }*/
    if (strpos($select_type, 'entreprise') !== false) {
       $price_prefix = 'e';
    } else {
      $price_prefix = '';
    }
    $html = '<option value="" data-price="0" selected>Choisissez le type de stand</option>';
    $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types`");
    while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
    	$prices_arr = explode(",", $row_bornes_types[$price_prefix.'price']);
      $html .= '<option value="'.$row_bornes_types['title'].'" data-price="'.$prices_arr[0].'" data-group="1"'.(urldecode($box_id) == $row_bornes_types['title'] ? ' selected' : '').'>'.$row_bornes_types['title'].' - '.$prices_arr[0].'€</option>';
    }
    echo $html;
  }

  if ($event == "box_id_list") {
    if ($agency_id != 0) {
      $prefix = $agency_id == 1 ? "P" : "B";
      $box_ids = explode(",", $box_id);
      $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types` WHERE `id` = 1");
      $row_bornes_types = mysqli_fetch_assoc($result_bornes_types);
      $html = '<option value="">Numéro de la borne...</option>';

          for ($i = 1; $i <= $row_bornes_types['amount']; $i++) {
            $result_repair = mysqli_query($conn, "SELECT * FROM `repair` WHERE `box_id` LIKE 'R$i/$prefix'");
            if (mysqli_num_rows($result_repair) == 0) {
              if ($take_date != "") {
                $show = true;
                $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `box_id` LIKE '%R$i%'");
                while($row_orders = mysqli_fetch_assoc($result_orders)) {
                  if (substr($take_date, 0, 10) == substr($row_orders['take_date'], 0, 10)) {
                    $show = false;
                  }
                }
                if ($show) {
                  $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>'.$i.'/'.$prefix.'</option>';
                }
              } else {
                $html .= '<option value="R'.$i.'/'.$prefix.'"'.(in_array('R'.$i.'/'.$prefix, $box_ids) ? ' selected' : '').'>'.$i.'/'.$prefix.'</option>';
              }
            }
          }

      echo $html;
    }
  }

  if ($event == "options_list") {
    /*
    if (strpos($select_type, 'entreprise') !== false) {
      $data = json_decode(file_get_contents("../enterprise.ini"), true);

      switch($box_type) {
        case "Ring": $options = $data['ring2']['options']; break;
        case "Vegas":
        case "Vegas_800":
        case "Vegas_1200":
        case "Vegas_1600":
        case "Vegas_2000": $options = $data['vegas']['options']; break;
        case "Miroir":
        case "Miroir_800":
        case "Miroir_1200":
        case "Miroir_1600":
        case "Miroir_2000": $options = $data['miroir']['options']; break;
        case "Spinner_360": $options = $data['spinner']['options']; break;
        case "Réalité_Virtuelle": $options = $data['vr2']['options']; break;
      }
    } else {
      $data = json_decode(file_get_contents("../particulier.ini"), true);
      switch($box_type) {
        case "Ring": $options = $data['ring']['options']; break;
        case "Vegas":
        case "Vegas_800":
        case "Vegas_1200":
        case "Vegas_1600":
        case "Vegas_2000": $options = $data['vegas']['options']; break;;
        case "Miroir_800":
        case "Miroir_1200":
        case "Miroir_1600":
        case "Miroir_2000": $options = $data['miroir']['options']; break;
        case "Spinner_360": $options = $data['spinner']['options']; break;
        case "Réalité_Virtuelle": $options = $data['vr2']['options']; break;
      }
    }
    $html = "";
    if (isset($txt_options)) {
      $txt_options_arr = explode(";", $_POST['txt_options']);
    } else {
      $txt_options_arr = array();
    }
    foreach ($options as $key => $value) {
      $html .= '<div class="window_inputs">
        <input type="checkbox" id="option'.$key.'" name="option'.$key.'" value="'.$value['name'].'" class="box_option" data-price="'.$value['price'].'" '.(in_array($value['name'], $txt_options_arr) ? ' checked' : '').'/>
        <label for="option'.$key.'">'.$value['name'].'</label>
      </div>';
    }
    echo $html.'<button class="window_btn">Valider</div>';
    */
    if (strpos($select_type, 'entreprise') !== false) {
       $price_prefix = 'e';
    } else {
      $price_prefix = '';
    }

    $html = "";
    if (isset($txt_options)) {
      $txt_options_arr = explode(";", urldecode($_POST['txt_options']));
    } else {
      $txt_options_arr = array();
    }
    $result_bornes_types = mysqli_query($conn, "SELECT * FROM `bornes_types`");
    while($row_bornes_types = mysqli_fetch_assoc($result_bornes_types)) {
      if ($row_bornes_types['title'] == urldecode($box_type)) {
        $options_ids = $row_bornes_types[$price_prefix.'options_ids'];
      }
    }
     $result_options = mysqli_query($conn, "SELECT * FROM `options` WHERE `id` IN (".$options_ids.")");
                $i = 0;
     while($row_options = mysqli_fetch_assoc($result_options)) {

      $html .= '<div class="window_inputs">
        <input type="checkbox" id="option'.$key.'" name="option'.$row_options['id'].'" value="'.$row_options['title'].'" class="box_option" data-price="'.$row_options[$price_prefix.'price'].'" '.(in_array($row_options['title'], $txt_options_arr) ? ' checked' : '').'/>
        <label for="option'.$row_options['id'].'">'.$row_options['title'].'</label>
      </div>';
    }
    echo $html.'<button class="window_btn">Valider</div>';
  }

  if ($event == "delivery_list") {
    /*
    if (strpos($select_type, 'entreprise') !== false) {
      $data = json_decode(file_get_contents("../enterprise.ini"), true);
      //$box_type = 'Vegas';
      switch($box_type) {
        case "Ring": $deliverys = $data['ring2']['delivery']; break;
        case "Vegas":
        case "Vegas_800":
        case "Vegas_1200":
        case "Vegas_1600":
        case "Vegas_2000": $deliverys = $data['vegas']['delivery']; break;
        case "Miroir":
        case "Miroir_800":
        case "Miroir_1200":
        case "Miroir_1600":
        case "Miroir_2000": $deliverys = $data['miroir']['delivery']; break;
        case "Spinner_360": $deliverys = $data['spinner']['delivery']; break;
        case "Réalité_Virtuelle": $deliverys = $data['vr2']['delivery']; break;
      }
    } else {
      $data = json_decode(file_get_contents("../particulier.ini"), true);
      //$box_type = 'Vegas';
      switch($box_type) {
        case "Ring": $deliverys = $data['ring']['delivery']; break;
        case "Vegas":
        case "Vegas_800":
        case "Vegas_1200":
        case "Vegas_1600":
        case "Vegas_2000": $deliverys = $data['vegas']['delivery']; break;
        case "Miroir":
        case "Miroir_800":
        case "Miroir_1200":
        case "Miroir_1600":
        case "Miroir_2000": $deliverys = $data['miroir']['delivery']; break;
        case "Spinner_360": $deliverys = $data['spinner']['delivery']; break;
        case "Réalité_Virtuelle": $deliverys = $data['vr2']['delivery']; break;
      }
    }
    $html = "";
    $i = 0;
    foreach ($deliverys as $key => $value) {
      if ($value['name'] != "Retrait boutique") {
        $html .= '<div class="window_inputs">
          <input type="checkbox" id="delivery'.$key.'" name="delivery'.$key.'" value="'.$value['name'].'" class="box_delivery" data-price="'.$value['price'].'" />
          <label for="delivery'.$key.'">'.$value['name'].'</label>
        </div>';
        $i++;
      }
    }
    $html .= '<div class="window_inputs">
      <input type="checkbox" id="delivery'.$i.'" name="delivery'.$i.'" value="Kilométriques supplémentaires" class="box_delivery" data-price="49" />
      <label for="delivery'.$i.'">Kilométriques supplémentaires</label>
    </div>';
    echo $html.'<button class="window_btn">Valider</div>';
    */
    if (strpos($select_type, 'entreprise') !== false) {
       $price_prefix = 'e';
    } else {
      $price_prefix = '';
    }
    $html = "";
    $result_delivery = mysqli_query($conn, "SELECT * FROM `delivery`");
    while($row_delivery = mysqli_fetch_assoc($result_delivery)) {
      if ($value['name'] != "Retrait boutique") {
        $html .= '<div class="window_inputs">
            <input type="checkbox" id="delivery'.$row_delivery['id'].'" name="delivery'.$row_delivery['id'].'" value="'.$row_delivery['title'].'" class="box_delivery" data-price="'.$row_delivery[$price_prefix.'price'].'" />
            <label for="delivery'.$row_delivery['id'].'">'.$row_delivery['title'].'</label>
          </div>';
      }
    }
    echo $html.'<button class="window_btn">Valider</div>';
  }

  if ($event == "add_event") {
		mysqli_query($conn, "INSERT INTO `events`(`id`, `order_id`, `title`, `start_date`, `end_date`, `image`, `data`) VALUES (NULL, '".$_SESSION['order']['id']."', '".urldecode($title)."', '".strtotime(urldecode($start_date))."', '".strtotime(urldecode($end_date))."', '$image', '')") or die(mysqli_error($conn));
		if ($image != "") {
			require_once("thumblib/ThumbLib.inc.php");
			$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
			$thumb->resize(120, 0);
			$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
		}
		echo"done";
	}

	if ($event == "edit_event") {
		$result_events = mysqli_query($conn, "SELECT `id` FROM `events` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_events) != 0) {
			mysqli_query($conn, "UPDATE `events` SET `title` = '".urldecode($title)."', `start_date` = '".strtotime(urldecode($start_date))."', `end_date` = '".strtotime(urldecode($end_date))."' WHERE `id` = ".$id);
			if ($image != "") {
				$result_events = mysqli_query($conn, "SELECT `image` FROM `events` WHERE `id` = ".$id);
				$row_events = mysqli_fetch_assoc($result_events);
				require_once("thumblib/ThumbLib.inc.php");
				$thumb = PhpThumbFactory::create(ADMIN_UPLOAD_IMAGES_DIR.$image);
				$thumb->resize(120, 0);
				$thumb->save(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($image, '120'));
				mysqli_query($conn, "UPDATE `events` SET `image` = '$image' WHERE `id` = ".$id);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_events['image']);
				@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_events['image'], '120'));
			}
			echo"done";
		} else {
			echo"error";
		}
	}

	if ($event == "delete_image_event") {
		$result_events = mysqli_query($conn, "SELECT `image` FROM `events` WHERE `id` = ".$id);
		if (mysqli_num_rows($result_events) != 0) {
			$row_events = mysqli_fetch_assoc($result_events);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.$row_events['image']);
			@unlink(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl($row_events['image'], '120'));
			mysqli_query($conn, "UPDATE `events` SET `image` = '' WHERE `id` = ".$id);
		}
	}

	if ($event == "delete_files") {
		if (isset($_SESSION['order']['admin'])) {
			$files_arr = explode(";", $files);
			foreach ($files_arr as $key => $value) {
				@unlink("../uploads/".$_SESSION['order']['album'].'/'.$value);
			}
		}
	}

	if ($event == "get_templates") {
		$result_templates = mysqli_query($conn, "SELECT * FROM `templates` WHERE `status` = 1  AND `photos_amount` = $photos_amount AND `format_id` = $format_id ORDER BY `id` DESC");
     while($row_templates = mysqli_fetch_assoc($result_templates)) {
      $types_ids_arr = explode(",", $row_templates['types_ids']);
      if (mb_strpos($row_templates['boxes'], "Vegas", 0) !== false) {
        $row_templates['boxes'] = $row_templates['boxes'].",Vegas_800,Vegas_1200,Vegas_1600,Vegas_2000";
      }
      if (mb_strpos($row_templates['boxes'], "Miroir", 0) !== false) {
        $row_templates['boxes'] = $row_templates['boxes'].",Miroir_800,Miroir_1200,Miroir_1600,Miroir_2000";
      }
      if ($row_templates['preview'] != "" && in_array($type_id, $types_ids_arr) && mb_strpos($row_templates['boxes'], $_SESSION['order']['box_type'], 0) !== false) {
        list($width, $height, $type, $attr) = getimagesize(ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '120'));
        $add_class = $width > $height ? 'horisontal' : 'vertical';
        echo'<a href="creator.php?template_id='.$row_templates['id'].'" class="open-popup">
          <span class="helper"></span>
          <img class="open-popup-img '.$add_class.'" src="'.ADMIN_UPLOAD_IMAGES_DIR.getImageUrl(($_SESSION['order']['pay'] == 0 ? $row_templates['preview'] : $row_templates['preview2']), '640').'" alt="">
        </a>';
      }
    }
	}

	if ($event == "contour") {
		$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  	$row_orders = mysqli_fetch_assoc($result_orders);
		$subject = "Contour photo (".$row_orders['num_id'].") ".trim($row_orders['societe']." ".$row_orders['last_name']." ".$row_orders['first_name'])." ".date("d-m-Y H:i", time());
		$mailheaders = "MIME-Version: 1.0\r\n";
		$mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
		$mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "Reply-To: =?utf-8?B?".base64_encode("ShootnBox")."?= <hello@shootnbox.fr>\r\n";
		$mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
			  <head>
			    <meta charset="UTF-8">
			    <meta content="width=device-width, initial-scale=1" name="viewport">
			    <meta name="x-apple-disable-message-reformatting">
			    <meta http-equiv="X-UA-Compatible" content="IE=edge">
			    <meta content="telephone=no" name="format-detection">
			    <title></title>
			    <!--[if gte mso 9]><style>
			        sup { font-size: 100% !important; }
			      </style><![endif]-->
			    <style type="text/css">
			      /* CONFIG STYLES Please do not delete and edit CSS styles below */
			      /* IMPORTANT THIS STYLES MUST BE ON FINAL EMAIL */
			      a[x-apple-data-detectors] {
			          color: inherit !important;
			          text-decoration: none !important;
			          font-size: inherit !important;
			          font-family: inherit !important;
			          font-weight: inherit !important;
			          line-height: inherit !important;
			      }
			      /*
			      END OF IMPORTANT
			      */
			      /*
			      END CONFIG STYLES
			      */
			      /* RESPONSIVE STYLES Please do not delete and edit CSS styles below. If you don\'t need responsive layout, please delete this section. */
			      @media only screen and (max-width: 600px) {
			          p,
			          ul li,
			          ol li,
			          a {
			        line-height: 150% !important;
			          }
			          h1,
			          h2,
			          h3,
			          h1 a,
			          h2 a,
			          h3 a {
			        line-height: 120%;
			          }
			          h1 {
			        font-size: 30px !important;
			        text-align: center;
			          }
			          h2 {
			        font-size: 26px !important;
			        text-align: center;
			          }
			          h3 {
			        font-size: 20px !important;
			        text-align: center;
			          }
			          .es-header-body h1 a,
			          .es-content-body h1 a,
			          .es-footer-body h1 a {
			        font-size: 30px !important;
			          }
			          .es-header-body h2 a,
			          .es-content-body h2 a,
			          .es-footer-body h2 a {
			        font-size: 26px !important;
			          }
			          .es-header-body h3 a,
			          .es-content-body h3 a,
			          .es-footer-body h3 a {
			        font-size: 20px !important;
			          }
			          .es-menu td a {
			        font-size: 16px !important;
			          }
			          .es-header-body p,
			          .es-header-body ul li,
			          .es-header-body ol li,
			          .es-header-body a {
			        font-size: 16px !important;
			          }
			          .es-content-body p,
			          .es-content-body ul li,
			          .es-content-body ol li,
			          .es-content-body a {
			        font-size: 16px !important;
			          }
			          .es-footer-body p,
			          .es-footer-body ul li,
			          .es-footer-body ol li,
			          .es-footer-body a {
			        font-size: 16px !important;
			          }
			          .es-infoblock p,
			          .es-infoblock ul li,
			          .es-infoblock ol li,
			          .es-infoblock a {
			        font-size: 12px !important;
			          }
			          *[class="gmail-fix"] {
			        display: none !important;
			          }
			          .es-m-txt-c,
			          .es-m-txt-c h1,
			          .es-m-txt-c h2,
			          .es-m-txt-c h3 {
			        text-align: center !important;
			          }
			          .es-m-txt-r,
			          .es-m-txt-r h1,
			          .es-m-txt-r h2,
			          .es-m-txt-r h3 {
			        text-align: right !important;
			          }
			          .es-m-txt-l,
			          .es-m-txt-l h1,
			          .es-m-txt-l h2,
			          .es-m-txt-l h3 {
			        text-align: left !important;
			          }
			          .es-m-txt-r img,
			          .es-m-txt-c img,
			          .es-m-txt-l img {
			        display: inline !important;
			          }
			          .es-button-border {
			        display: block !important;
			          }
			          a.es-button,
			          button.es-button {
			        font-size: 20px !important;
			        display: block !important;
			        padding: 10px 0px 10px 0px !important;
			          }
			          .es-adaptive table,
			          .es-left,
			          .es-right {
			        width: 100% !important;
			          }
			          .es-content table,
			          .es-header table,
			          .es-footer table,
			          .es-content,
			          .es-footer,
			          .es-header {
			        width: 100% !important;
			        max-width: 600px !important;
			          }
			          .es-adapt-td {
			        display: block !important;
			        width: 100% !important;
			          }
			          .adapt-img {
			        width: 100% !important;
			        height: auto !important;
			          }
			          .es-m-p0 {
			        padding: 0 !important;
			          }
			          .es-m-p0r {
			        padding-right: 0 !important;
			          }
			          .es-m-p0l {
			        padding-left: 0 !important;
			          }
			          .es-m-p0t {
			        padding-top: 0 !important;
			          }
			          .es-m-p0b {
			        padding-bottom: 0 !important;
			          }
			          .es-m-p20b {
			        padding-bottom: 20px !important;
			          }
			          .es-mobile-hidden,
			          .es-hidden {
			        display: none !important;
			          }
			          tr.es-desk-hidden,
			          td.es-desk-hidden,
			          table.es-desk-hidden {
			        width: auto !important;
			        overflow: visible !important;
			        float: none !important;
			        max-height: inherit !important;
			        line-height: inherit !important;
			          }
			          tr.es-desk-hidden {
			        display: table-row !important;
			          }
			          table.es-desk-hidden {
			        display: table !important;
			          }
			          td.es-desk-menu-hidden {
			        display: table-cell !important;
			          }
			          .es-menu td {
			        width: 1% !important;
			          }
			          table.es-table-not-adapt,
			          .esd-block-html table {
			        width: auto !important;
			          }
			          table.es-social {
			        display: inline-block !important;
			          }
			          table.es-social td {
			        display: inline-block !important;
			          }
			          .es-desk-hidden {
			        display: table-row !important;
			        width: auto !important;
			        overflow: visible !important;
			        max-height: inherit !important;
			          }
			          .es-m-p5 {
			        padding: 5px !important;
			          }
			          .es-m-p5t {
			        padding-top: 5px !important;
			          }
			          .es-m-p5b {
			        padding-bottom: 5px !important;
			          }
			          .es-m-p5r {
			        padding-right: 5px !important;
			          }
			          .es-m-p5l {
			        padding-left: 5px !important;
			          }
			          .es-m-p10 {
			        padding: 10px !important;
			          }
			          .es-m-p10t {
			        padding-top: 10px !important;
			          }
			          .es-m-p10b {
			        padding-bottom: 10px !important;
			          }
			          .es-m-p10r {
			        padding-right: 10px !important;
			          }
			          .es-m-p10l {
			        padding-left: 10px !important;
			          }
			          .es-m-p15 {
			        padding: 15px !important;
			          }
			          .es-m-p15t {
			        padding-top: 15px !important;
			          }
			          .es-m-p15b {
			        padding-bottom: 15px !important;
			          }
			          .es-m-p15r {
			        padding-right: 15px !important;
			          }
			          .es-m-p15l {
			        padding-left: 15px !important;
			          }
			          .es-m-p20 {
			        padding: 20px !important;
			          }
			          .es-m-p20t {
			        padding-top: 20px !important;
			          }
			          .es-m-p20r {
			        padding-right: 20px !important;
			          }
			          .es-m-p20l {
			        padding-left: 20px !important;
			          }
			          .es-m-p25 {
			        padding: 25px !important;
			          }
			          .es-m-p25t {
			        padding-top: 25px !important;
			          }
			          .es-m-p25b {
			        padding-bottom: 25px !important;
			          }
			          .es-m-p25r {
			        padding-right: 25px !important;
			          }
			          .es-m-p25l {
			        padding-left: 25px !important;
			          }
			          .es-m-p30 {
			        padding: 30px !important;
			          }
			          .es-m-p30t {
			        padding-top: 30px !important;
			          }
			          .es-m-p30b {
			        padding-bottom: 30px !important;
			          }
			          .es-m-p30r {
			        padding-right: 30px !important;
			          }
			          .es-m-p30l {
			        padding-left: 30px !important;
			          }
			          .es-m-p35 {
			        padding: 35px !important;
			          }
			          .es-m-p35t {
			        padding-top: 35px !important;
			          }
			          .es-m-p35b {
			        padding-bottom: 35px !important;
			          }
			          .es-m-p35r {
			        padding-right: 35px !important;
			          }
			          .es-m-p35l {
			        padding-left: 35px !important;
			          }
			          .es-m-p40 {
			        padding: 40px !important;
			          }
			          .es-m-p40t {
			        padding-top: 40px !important;
			          }
			          .es-m-p40b {
			        padding-bottom: 40px !important;
			          }
			          .es-m-p40r {
			        padding-right: 40px !important;
			          }
			          .es-m-p40l {
			        padding-left: 40px !important;
			          }
			          .es-content-body .fz40 {
			        font-size: 40px !important;
			          }
			      }
			      /* END RESPONSIVE STYLES */
			    </style>
			  </head>
			  <body style="width:100%;font-family:arial, \'helvetica neue\', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;">
			    <div class="es-wrapper-color" style="background-color:#f6f6f6;">
			      <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-image:;background-repeat:repeat;background-position:center top;background-color:#f6f6f6;">
			        <tbody>
			          <tr>
			            <td class="esd-email-paddings" valign="top" style="padding:0;Margin:0;">
			              <table class="es-content esd-footer-popover" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;table-layout:fixed !important;">
			                <tbody>
			                  <tr>
			                    <td class="esd-stripe" align="center" style="padding:0;Margin:0;">
			                      <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;">
			                        <tbody>
			                          <tr>
			                            <td class="esd-structure es-p30t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;">
			                              <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
			                                <tbody>
			                                  <tr>
			                                    <td class="esd-container-frame" width="560" valign="top" align="center" style="padding:0;Margin:0;">
			                                      <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
			                                        <tbody>
			                                          <tr>
			                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;color: #707070; font-size: 19px;"><strong>'.trim($row_orders['societe'].' '.$row_orders['last_name'].' '.$row_orders['first_name']).' - '.$row_orders['event_date'].' - '.$row_orders['box_type'].' a ajouté de nouvelles informations dans l’espace client.</strong></p>
			                                            </td>
			                                          </tr>
			                                        </tbody>
			                                      </table>
			                                    </td>
			                                  </tr>
			                                </tbody>
			                              </table>
			                            </td>
			                          </tr>
			                          <tr>
			                            <td class="esd-structure es-p40t es-p20r es-p20l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
			                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
			                                <tbody>
			                                  <tr>
			                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
			                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
			                                        <tbody>
			                                          <tr>
			                                            <td align="center" class="esd-block-text" style="padding:0;Margin:0;">
			                                              <p class="fz40" style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 46px; color: #4dad58;"><strong>Contour photo</strong></p>
			                                            </td>
			                                          </tr>
			                                        </tbody>
			                                      </table>
			                                    </td>
			                                  </tr>
			                                </tbody>
			                              </table>
			                            </td>
			                          </tr>
			                          <tr>
			                            <td class="esd-structure es-p40t es-p20r es-p20l es-m-p15r es-m-p15l" align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px;">
			                              <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
			                                <tbody>
			                                  <tr>
			                                    <td width="560" class="esd-container-frame" align="center" valign="top" style="padding:0;Margin:0;">
			                                      <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
			                                        <tbody>
			                                          <tr>
			                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Adresse mail : </span>'.$row_orders['email'].'</strong></p>
			                                            </td>
			                                          </tr>
			                                          <tr>
			                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Texte à ajouter sur le contour : </span>'.$text.'</strong></p>
			                                            </td>
			                                          </tr>
			                                          <tr>
			                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Nom des typographies : </span>'.$typographies.'</strong></p>
			                                            </td>
			                                          </tr>
			                                          <tr>
			                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Thématique de l\'évènement : </span>'.$palmier.'</strong></p>
			                                            </td>
			                                          </tr>
			                                          <tr>
			                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Code couleur : </span>'.$code.'</strong></p>
			                                            </td>
			                                          </tr>
			                                          <tr>
			                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
			                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Autres remarques : </span>'.$autres.'</strong></p>
			                                            </td>
			                                          </tr>';
			                                          if ($image != "") {
				                                          $images_arr = explode(" ", $image);
				                                          foreach ($images_arr as $key => $value) {
					                                          $msg .= '<tr>
					                                            <td align="center" class="esd-block-text es-p15b" style="padding:0;Margin:0;padding-bottom:15px;">
					                                              <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, \'helvetica neue\', helvetica, sans-serif;line-height:150%;font-size: 15px; color: #707070;"><strong><span style="color:#4DAD58;">Fichier client : </span>'.($value != '' ? '<a href="https://ftp.shootnbox.fr/uploads/images/'.$value.'">https://ftp.shootnbox.fr/uploads/images/'.$value.'</a>' : '').'</strong></p>
					                                            </td>
					                                          </tr>';
					                                        }
					                                      }
			                                          $msg .= '<tr>
			                                            <td align="center" class="esd-block-spacer" height="200" style="padding:0;Margin:0;"></td>
			                                          </tr>
			                                        </tbody>
			                                      </table>
			                                    </td>
			                                  </tr>
			                                </tbody>
			                              </table>
			                            </td>
			                          </tr>
			                        </tbody>
			                      </table>
			                    </td>
			                  </tr>
			                </tbody>
			              </table>
			            </td>
			          </tr>
			        </tbody>
			      </table>
			    </div>
			  </body>
			</html>';
		mail($row_orders['email'], "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail("graphisme@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
		mysqli_query($conn, "UPDATE `orders_new` SET `gallery_valid` = 1 WHERE id = ".$_SESSION['order']['id']) or die(mysqli_error($conn));
	}

if ($event == "payment") {
    mysqli_query($conn, "UPDATE `orders_new` SET  `entreprise_pdf` = '$entreprise_pdf', `address_pdf` = '".htmlspecialchars($_POST['address_pdf'], ENT_QUOTES, 'UTF-8')."', `city_pdf` = '$city_pdf', `cp_pdf` = '$cp_pdf', `number_pdf` = '$number_pdf', `ord` = '$ord', `other_pdf` = '$other_pdf', `payment_by` = '".trim($payment_by, " / ")."', `payment_facture` = '$payment_facture', `payment_valid` = 1 WHERE id = ".$_SESSION['order']['id']) or die(mysqli_error($conn));
    $result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  	$row_orders = mysqli_fetch_assoc($result_orders);

     $subject = "INFOS PAIEMENT - ".$row_orders['num_id']." - ".$entreprise_pdf." - ".date("d/m/Y", time())." - ".$row_orders['box_type'];
     $mailheaders = "MIME-Version: 1.0\r\n";
     $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
     $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <contact@shootnbox.fr>\r\n";
     $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
     $msg = '<!DOCTYPE html>
          <head>
            <meta charset="UTF-8">
            <meta content="width=device-width, initial-scale=1" name="viewport">
            <meta name="x-apple-disable-message-reformatting">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="telephone=no" name="format-detection">
            <title>'.$row_configure_orders['email_subject'].'</title>
            <style type="text/css">
              font-family: \'Arial\';
              font-size: 11px;
            </style>
          </head>
          <body>
            	<center>
	            	INFOS PAIEMENT - '.$row_orders['num_id'].' - '.$entreprise_pdf.' - '.date("d/m/Y", time()).' - '.$row_orders['box_type'].'<br />
	            	<h2 style="color: #3da6dc;">Paiement</h2>
	            	<br />
	            	Entreprise : '.$entreprise_pdf.'<br />
								Adresse de facturation : '.$address_pdf.'<br />
								Ville : '.$city_pdf.'<br />
								Code postal : '.$cp_pdf.'<br />
								Numéro de siret : '.$number_pdf.'<br />
								Bon de commande : '.$ord.'<br />
								Autre : '.$other_pdf.'<br />
								Mode de règlement : '.trim($payment_by, " / ").
								($row_orders['payment_doc'] != "" ? '<br />Document: <a href="https://ftp.shootnbox.fr/uploads/images/'.$row_orders['payment_doc'].'">'.$row_orders['payment_doc'].'</a>' : '').
							'</center>
          </body>
        </html>';
        mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        mail("info@evput.com", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
    echo"done";
}

if ($event == "payment_doc") {
    mysqli_query($conn, "UPDATE `orders_new` SET  `payment_doc` = '$image' WHERE id = ".$_SESSION['order']['id']) or die(mysqli_error($conn));
    /*$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  	$row_orders = mysqli_fetch_assoc($result_orders);

     $subject = "INFOS PAIEMENT - ".$row_orders['num_id']." - Document - ".date("d/m/Y", time())." - ".$row_orders['box_type'];
     $mailheaders = "MIME-Version: 1.0\r\n";
     $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
     $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <contact@shootnbox.fr>\r\n";
     $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
     $msg = '<!DOCTYPE html>
          <head>
            <meta charset="UTF-8">
            <meta content="width=device-width, initial-scale=1" name="viewport">
            <meta name="x-apple-disable-message-reformatting">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="telephone=no" name="format-detection">
            <title>'.$row_configure_orders['email_subject'].'</title>
            <style type="text/css">
              font-family: \'Arial\';
              font-size: 11px;
            </style>
          </head>
          <body>
            	<center>
	            	INFOS PAIEMENT - '.$row_orders['num_id'].' - Document - '.date("d/m/Y", time()).' - '.$row_orders['box_type'].'<br />
	            	<h2 style="color: #3da6dc;">Paiement</h2>
	            	<br />
	            	Document: <a href="https://ftp.shootnbox.fr/uploads/images/'.$image.'">'.$image.'</a>
							</center>
          </body>
        </html>';
        mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        mail("info@evput.com", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);*/
    echo"done";
}

if ($event == "deposit_link_view") {
	mysqli_query($conn, "UPDATE `orders_new` SET `deposit_link_view` = 1 WHERE id = ".$_SESSION['order']['id']) or die(mysqli_error($conn));
	echo"done";
}

if ($event == "sale_link_view") {
	mysqli_query($conn, "UPDATE `orders_new` SET `sale_link_view` = 1 WHERE id = ".$_SESSION['order']['id']) or die(mysqli_error($conn));
	echo"done";
}

if ($event == "livraison") {
	$result_orders = mysqli_query($conn, "SELECT * FROM `orders_new` WHERE `id` = ".$_SESSION['order']['id']);
  	$row_orders = mysqli_fetch_assoc($result_orders);
    mysqli_query($conn, "UPDATE `orders_new` SET `take_date` = '".date("d.m.Y", strtotime($take_date))."', `take_time` = '$take_time', `event_place` = '$event_place', `take_contact` = '$take_contact', `take_access` = '$take_access', `take_stairs` = '$take_stairs', `return_date` = '".date("d.m.Y", strtotime($return_date))."', `return_time` = '$return_time', `return_place` = '$return_place', `return_contact` = '$return_contact', `take_phone` = '$take_phone', `return_phone` = '$return_phone', `return_access` = '$return_access', `return_stairs` = '$return_stairs', `plan_access` = '$plan_access', `description` = '$take_description', `return_description` = '$return_description', `delivery_valid` = 1 WHERE id = ".$_SESSION['order']['id']) or die(mysqli_error($conn));

    $subject = $row_orders['entreprise_pdf']." - ".date("d/m/Y", time())." - ".$row_orders['box_type']." a ajouté de nouvelles informations dans l'espace client";
     $mailheaders = "MIME-Version: 1.0\r\n";
     $mailheaders.= "Content-Type: text/html; charset=utf-8\r\n";
     $mailheaders.= "From: =?utf-8?B?".base64_encode("ShootnBox")."?= <contact@shootnbox.fr>\r\n";
     $mailheaders.= "X-Mailer: PHP/".phpversion()."\r\n";
     $msg = '<!DOCTYPE html>
          <head>
            <meta charset="UTF-8">
            <meta content="width=device-width, initial-scale=1" name="viewport">
            <meta name="x-apple-disable-message-reformatting">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="telephone=no" name="format-detection">
            <title>'.$row_configure_orders['email_subject'].'</title>
            <style type="text/css">
              font-family: \'Arial\';
              font-size: 11px;
            </style>
          </head>
          <body>
            	<center>
	            	'.$row_orders['entreprise_pdf'].' - '.date("d/m/Y", time()).' - '.$row_orders['box_type'].' a ajouté de nouvelles informations dans l\'espace client<br />
	            	<h1 style="color: #e3763e;">Livraison/Reprise</h1>
	            	<br />
	            	<h2 style="color: #e3763e;">Livraison</h2>
	            	<br />
	            	Jour de livraison : '.$take_date.'<br />
								Créneau horaire : '.$take_time.'<br />
								Adresse de livraison : '.$take_contact.'<br />
								Contact(s) sur place : '.$event_place.' '.$take_phone.'<br />
								Code d\'accès : '.$take_access.'<br />
								Information complémentaires : '.$take_description.'<br />
								Escalier : '.($take_stairs == 1 ? 'OUI' : 'NON').'<br />
								Plan d\'accès : '.$plan_access.'
	            	<br />
	            	<h2 style="color: #e3763e;">Reprise</h2>
	            	<br />
	            	Jour de livraison : '.$return_date.'<br />
								Créneau horaire : '.$return_time.'<br />
								Adresse de livraison : '.$return_contact.'<br />
								Contact(s) sur place : '.$return_place.' '.$return_phone.'<br />
								Code d\'accès : '.$return_access.'<br />
								Information complémentaires : '.$return_description.'<br />
								Escalier : '.($return_stairs == 1 ? 'OUI' : 'NON').'<br />
							</center>
          </body>
        </html>';
        mail("zazurka@mail.ru", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        mail("contact@shootnbox.fr", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);
        mail("info@evput.com", "=?utf-8?B?".base64_encode($subject)."?=", $msg, $mailheaders);

    echo"done";
}

@mysqli_close($conn);
?>
