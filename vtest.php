<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require 'vendor/autoload.php';

$ffmpeg = FFMpeg\FFMpeg::create();
//$ffmpeg = FFMpeg\FFMpeg::create(array( 'ffmpeg.binaries' => '/usr/bin/ffmpeg', 'ffprobe.binaries' => '/usr/bin/ffprobe', 'timeout' => 3600, 'ffmpeg.threads' => 12 ));

$video = $ffmpeg->open('0c6b86a7d03a3cfa496cd460c1ad7527.mp4');
/*$video->filters()->rotate('hflip,vflip');
$video->filters()->rotate('transpose=2');
$video->filters()->rotate('transpose=2');
$video->save(new FFMpeg\Format\Video\X264(), '90.mp4');*/

//clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds(5))
//$video->gif(FFMpeg\Coordinate\TimeCode::fromSeconds(2), new FFMpeg\Coordinate\Dimension(640, 480), 3)->save('test.gif');

$video
    ->filters()
    ->custom('reverse', '')
    ->synchronize();

$video
    ->filters()
    ->custom('colorchannelmixer=.393:.769:.189:0:.349:.686:.168:0:.272:.534:.131 -pix_fmt yuv420p')
    ->synchronize();
/*$video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(1))
    ->save('frame.jpg');*/
$video
    ->save(new FFMpeg\Format\Video\X264(), 'video2.mp4');

/*$format = new FFMpeg\Format\Video\X264();
$format->setAudioCodec("libmp3lame");
$video
    ->concat(array('0c6b86a7d03a3cfa496cd460c1ad7527.mp4', 'video2.mp4'))
    ->saveFromDifferentCodecs($format, 'export.mp4');*/

echo"done";
?>