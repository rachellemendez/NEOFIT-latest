<?php
// Set the content type to image/png
header('Content-Type: image/png');

// Create a 200x200 image
$image = imagecreatetruecolor(200, 200);

// Define colors
$bg_color = imagecolorallocate($image, 240, 240, 240);
$text_color = imagecolorallocate($image, 150, 150, 150);

// Fill the background
imagefilledrectangle($image, 0, 0, 200, 200, $bg_color);

// Add text
$text = "No Image";
$font = 5; // Built-in font (1-5)
$text_width = imagefontwidth($font) * strlen($text);
$text_height = imagefontheight($font);

// Center the text
$x = (200 - $text_width) / 2;
$y = (200 - $text_height) / 2;

// Draw the text
imagestring($image, $font, $x, $y, $text, $text_color);

// Create the uploads directory if it doesn't exist
if (!file_exists('../uploads')) {
    mkdir('../uploads', 0777, true);
}

// Save the image
imagepng($image, '../uploads/placeholder.jpg');
imagedestroy($image);

echo "Placeholder image created successfully.";
?> 