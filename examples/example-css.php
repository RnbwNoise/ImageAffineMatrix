<?php
    require_once(__DIR__ . '/../ImageAffineMatrix.php');
    
    $transform = new ImageAffineMatrix();
    $transform->skew(deg2rad(-20), deg2rad(-5))->rotate(deg2rad(45))->translate(100, 100);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>ImageAffineMatrix example</title>
        <style>
            .example {
                width: 500px;
                height: 500px;
                border: 1px dashed rgb(0, 160, 220);
                background-image: url('example-image.png');
                background-position: -3px -3px;
                
                transform: <?php echo $transform->getTransform(); ?>;
            }
        </style>
    </head>
    <body>
        <div class="example"><h1>Hello, world!</h1></div>
    </body>
</html>