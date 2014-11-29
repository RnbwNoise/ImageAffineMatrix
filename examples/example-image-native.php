<?php
    $transform = imageAffineMatrixGet(IMG_AFFINE_SHEAR_HORIZONTAL, -20);
    $transform = imageAffineMatrixConcat(imageAffineMatrixGet(IMG_AFFINE_SHEAR_VERTICAL, -5), $transform);
    $transform = imageAffineMatrixConcat(imageAffineMatrixGet(IMG_AFFINE_ROTATE, 45), $transform);
    $transform = imageAffineMatrixConcat(imageAffineMatrixGet(IMG_AFFINE_SCALE, [ 'x' => 0.5, 'y' => 0.5 ]), $transform);
    
    $image = imageCreateFromPNG('example-image.png');
    $newImage = imageAffine($image, $transform);
    
    header('Content-type: image/png');
    imagePNG($newImage);