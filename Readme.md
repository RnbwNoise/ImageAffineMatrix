# ImageAffineMatrix - affine transformations in PHP made easy

This is a PHP class for performing affine transformations on Gd images and points in 2D space.


## Example

    $transform = new ImageAffineMatrix();
    $transform->skew(deg2rad(-20), deg2rad(-5))->rotate(deg2rad(45))->scale(0.5, 0.5);
    
    $image = imageCreateFromPNG('example-source.png');
    $newImage = $transform->transformImage($image);
    
    $transform->translate(-10, 5);
    
    list($newX, $newY) = $transform->transformPoint(10, 20);


## API

### ImageAffineMatrix($a, $b, $c, $d, $tx, $ty)

Creates a new transformation matrix. If no arguments are provided, an identity matrix will be created.

* `float $a`: The 'a' component of the matrix.
* `float $b`: The 'b' component of the matrix.
* `float $c`: The 'c' component of the matrix.
* `float $d`: The 'd' component of the matrix.
* `float $tx`: The translation along the x axis.
* `float $ty`: The translation along the y axis.

### translate($x, $y)

Applies translation to the matrix.

* `float $x`: Translation along the x axis.
* `float $y`: Translation along the y axis.

### scale($x, $y)

Applies scaling transformation to the matrix.

* `float $x`: Coefficient of the x axis.
* `float $y`: Coefficient of the y axis.

### rotate($a)

Applies rotation transformation to the matrix.

* `float $a`: Rotation angle in radians.

### skew($x, $y)

Applies skewing transformation to the matrix.

* `float $x`: Skew angle along the x axis in radians.
* `float $y`: Skew angle along the y axis in radians.

### invert()

Causes this matrix to perform an opposite transformation.

### multiply($matrix)

Multiplies this matrix by another matrix.

* `ImageAffineMatrix $matrix`: The other matrix.

### transformPoint($x, $y)

Returns a transformed point.

* `float $x`: The x coordinate of the point.
* `float $y`: The y coordinate of the point.

### transformImage($image)

Returns a transformed image. (Requires PHP >= 5.5.0 and GD 2 library)

* `resource $image`: An image resource.

### getDeterminant()

Returns the determinant of this matrix.

### setArray($values)

Sets the values of the matrix to the ones found in an array.

* `array $values` [ a, b, c, d, tx, ty ]

### getArray()

Returns an array of values [ a, b, c, d, tx, ty ] from this matrix.

### getTransform()

Returns a string for this matrix that can be used for SVG transform attribute or CSS transform property.

### setA($a)

Sets the 'a' component of the matrix.

* `float $a`: The new value of 'a'.

### getA()

Returns the 'a' component of the matrix.

### setB($b)

Sets the 'b' component of the matrix.

* `float $b`: The new value of 'b'.

### getB()

Returns the 'b' component of the matrix.

### setC($b)

Sets the 'c' component of the matrix.

* `float $c`: The new value of 'c'.

### getC()

Returns the 'c' component of the matrix.

### setD($d)

Sets the 'd' component of the matrix.

* `float $d`: The new value of 'd'.

### getD()

Returns the 'd' component of the matrix.

### setTx($x)

Sets the translation along the x axis.

* `float $x`: The new translation along the x axis.

### getTx()

Returns the translation along the x axis.

### setTy($y)

Sets the translation along the y axis.

* `float $y`: The new translation along the y axis.

### getTy()

Returns the translation along the y axis.


## License

Copyright (C) 2014 Vladimir P.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.