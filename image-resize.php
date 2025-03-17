<?php
// File and new size
$filename =  $_GET['file'] ?? 'assets/uploads/1708354917_5cee00848a35b36040d5.jpg';

// Content type
header('Content-Type: image/jpeg');

// Get new sizes
list($width, $height) = getimagesize($filename);
$newwidth = $_GET['width'] ?? 150;
$newheight = $_GET['height'] ?? 160;

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$ext = end(explode('.', basename($filename, PATHINFO_EXTENSION)));
if ($ext == 'png') {
    $source = imagecreatefrompng($filename);
} else {
    $source = imagecreatefromjpeg($filename);
}

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagejpeg($thumb);
