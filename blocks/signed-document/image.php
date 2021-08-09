<?php

//Set the Content Type
header('Content-type: image/jpeg');

// Create Image From Existing File
$jpg_document = imagecreatefromjpeg('document.jpg');

// Allocate A Color For The Text
$white = imagecolorallocate($jpg_document, 0, 0, 0);

// Set Path to Font File
$font_path = './font.ttf';

// Set Text to Be Printed On Image
$text = $_REQUEST['name'];
$day = date('jS');
$month_year = date('F Y');

// Signature
imagettftext($jpg_document, 25, 0, 160, 1425, $white, $font_path, $text);

// Day
imagettftext($jpg_document, 25, 0, 700, 1425, $white, $font_path, $day);

// Month & Year
imagettftext($jpg_document, 25, 0, 900, 1425, $white, $font_path, $month_year);

// Send Image to Browser
imagejpeg($jpg_document);

// Clear Memory
imagedestroy($jpg_document);
?>