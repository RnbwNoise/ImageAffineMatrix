<?php
    require_once(__DIR__ . '/../ImageAffineMatrix.php');
    
    $transform = new ImageAffineMatrix();
    $transform->skew(deg2rad(-20), deg2rad(-5))->rotate(deg2rad(45))->scale(0.5, 0.5);
    
    $image = imageCreateFromPNG('example-image.png');
    $newImage = $transform->transformImage($image);
    
    header('Content-type: image/png');
    imagePNG($newImage);