<?php
require_once __DIR__ . '/phpqrcode/qrlib.php';
QRcode::png($_GET['url'], false, 'Q', 10, 3);
?>