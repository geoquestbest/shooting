<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require "AnimGif.php";
$frames = array(
    "uploads/DE2222/ce89f2cfda4ae39588976ce2d1f52cae.png",
    "uploads/DE2222/1f6489b85039d28c3d04a4c78982efd3.png",
    "uploads/DE2222/504a50d157bd50b5496c39fa9864f030.png",
    "uploads/DE2222/eb8206465ced7e094d2f27dfca79be5d.png",
);
$durations = array(20, 30, 10, 10);

$anim = new GifCreator\AnimGif();
$anim->create($frames, $durations);
$anim->save("animated.gif");
?>