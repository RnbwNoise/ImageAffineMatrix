<?php
    require_once(__DIR__ . '/../ImageAffineMatrix.php');
    
    $transform = new ImageAffineMatrix();
    $transform->translate(10, 20)->scale(100, 150);

    var_dump($transform->transformPoint(1, 1));