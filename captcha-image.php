<?php
session_start();
header("Content-type: image/png");

// Random text
$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ123456789";
$string = str_shuffle($string);
$random_text = substr($string, 0, 6);

$_SESSION['my_captcha'] = $random_text;

// Image size
$width = 200;
$height = 60;
$image = imagecreatetruecolor($width, $height);

// Background color (slightly random)
$bg = imagecolorallocate($image, rand(220,255), rand(220,255), rand(220,255));
imagefill($image, 0, 0, $bg);


// 🔹 1. HEAVY DOT NOISE
for ($i = 0; $i < 800; $i++) {
    $dot_color = imagecolorallocate($image, rand(100,255), rand(100,255), rand(100,255));
    imagesetpixel($image, rand(0,$width), rand(0,$height), $dot_color);
}


// 🔹 2. RANDOM LINES
for ($i = 0; $i < 10; $i++) {
    $line_color = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200));
    imageline(
        $image,
        rand(0,$width), rand(0,$height),
        rand(0,$width), rand(0,$height),
        $line_color
    );
}


// 🔹 3. RANDOM ARCS (CURVES)
for ($i = 0; $i < 5; $i++) {
    $arc_color = imagecolorallocate($image, rand(50,200), rand(50,200), rand(50,200));
    imagearc(
        $image,
        rand(0,$width), rand(0,$height),
        rand(30,150), rand(20,100),
        rand(0,360), rand(0,360),
        $arc_color
    );
}


// 🔹 4. TEXT WITH SPACING + RANDOM COLOR
$font_path = __DIR__ . '/src/arial.ttf'; // change if needed
$x = 20;

for ($i = 0; $i < strlen($random_text); $i++) {

    // Each character different color
    $text_color = imagecolorallocate($image, rand(0,150), rand(0,150), rand(0,150));

    imagettftext(
        $image,
        22,
        rand(-15, 15),
        $x,
        40,
        $text_color,
        $font_path,
        $random_text[$i]
    );

    $x += 28; // spacing
}


// Output
imagepng($image);
imagedestroy($image);
?>