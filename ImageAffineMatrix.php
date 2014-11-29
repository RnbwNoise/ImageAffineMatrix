<?php
    /**
     * self
     * Copyright (C) 2013-2014 Vladimir P.
     * 
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     * 
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     * 
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     * THE SOFTWARE.
     */
    
    /**
     * Represents an affine transformation matrix.
     * @copyright 2014 Vladimir P.
     * @license MIT
     */
    final class ImageAffineMatrix {
        /**
         * The 'a' component of the matrix.
         * @var float
         */
        private $a;
        
        /**
         * The 'b' component of the matrix.
         * @var float
         */
        private $b;
        
        /**
         * The 'c' component of the matrix.
         * @var float
         */
        private $c;
        
        /**
         * The 'd' component of the matrix.
         * @var float
         */
        private $d;
        
        /**
         * The translation along the x axis.
         * @var float
         */
        private $tx;
        
        /**
         * The translation along the y axis.
         * @var float
         */
        private $ty;
        
        /**
         * Creates a new affine transformation matrix.
         *
         * | A  C  Tx |
         * | B  D  Ty |
         * | 0  0  1  |
         *
         * @param float $a The 'a' component of the matrix.
         * @param float $b The 'b' component of the matrix.
         * @param float $c The 'c' component of the matrix.
         * @param float $d The 'd' component of the matrix.
         * @param float $tx The translation along the x axis.
         * @param float $ty The translation along the y axis.
         */
        public function __construct($a = 1, $b = 0, $c = 0, $d = 1, $tx = 0, $ty = 0) {
            $this->setArray([ $a, $b, $c, $d, $tx, $ty ]);
        }
        
        /**
         * Applies translation to the matrix.
         *
         * @param float $x Translation along the x axis.
         * @param float $y Translation along the y axis.
         * @return ImageAffineMatrix
         */
        public function translate($x, $y) {
            $this->tx += $x;
            $this->ty += $y;
            return $this;
        }
        
        /**
         * Applies scaling transformation to the matrix.
         *
         * @param float $x Coefficient of the x axis.
         * @param float $y Coefficient of the y axis.
         * @return ImageAffineMatrix
         */
        public function scale($x, $y) {
            $this->multiply(new self($x, 0, 0, $y, 0, 0));
            return $this;
        }
        
        /**
         * Applies rotation transformation to the matrix.
         *
         * @param float $a Rotation angle in radians.
         * @return ImageAffineMatrix
         */
        public function rotate($a) {
            $this->multiply(new self(cos($a), sin($a), -sin($a), cos($a), 0, 0));
            return $this;
        }
        
        /**
         * Applies skewing transformation to the matrix.
         *
         * @param float $x Skew angle along the x axis in radians.
         * @param float $y Skew angle along the y axis in radians.
         * @return ImageAffineMatrix
         */
        public function skew($x, $y) {
            $this->multiply(new self(1, tan($y), tan($x), 1, 0, 0));
            return $this;
        }
        
        /**
         * Causes this matrix to perform an opposite transformation.
         *
         * @return ImageAffineMatrix
         * @throws RuntimeException if the matrix is non-invertible.
         */
        public function invert() {
            $determinant = $this->getDeterminant();
            if($determinant == 0)
                throw new \RuntimeException('The matrix is non-invertible');
            $this->setArray([
                 $this->d / $determinant,
                -$this->b / $determinant,
                -$this->c / $determinant,
                 $this->a / $determinant,
                ( $this->c * $this->ty) - ($this->d * $this->tx) / $determinant,
                (-$this->a * $this->ty) + ($this->b * $this->tx) / $determinant
            ]);
            return $this;
        }
        
        /**
         * Multiplies this matrix by another matrix.
         *
         * @param ImageAffineMatrix $matrix The other matrix.
         * @return ImageAffineMatrix
         */
        public function multiply(self $matrix) {
            $this->setArray([
                $this->a * $matrix->getA() + $this->c * $matrix->getB(),
                $this->b * $matrix->getA() + $this->d * $matrix->getB(),
                $this->a * $matrix->getC() + $this->c * $matrix->getD(),
                $this->b * $matrix->getC() + $this->d * $matrix->getD(),
                $this->a * $matrix->getTx() + $this->c * $matrix->getTy() + $this->tx,
                $this->b * $matrix->getTx() + $this->d * $matrix->getTy() + $this->ty
            ]);
            return $this;
        }
        
        /**
         * Returns a transformed point.
         *
         * @param float $x The x coordinate of a point.
         * @param float $y The y coordinate of a point.
         * @return array [ x, y ]
         */
        public function transformPoint($x, $y) {
            return array($this->a * $x + $this->c * $y + $this->tx,
                         $this->b * $x + $this->d * $y + $this->ty);
        }
        
        /**
         * Returns a transformed image. (Requires PHP >= 5.5.0 and GD 2 library)
         *
         * @param resource $image An image resource.
         * @return resource
         */
        public function transformImage($image) {
            return imageAffine($image, $this->getArray());
        }
        
        /**
         * Returns the determinant of this matrix.
         *
         * @return float
         */
        public function getDeterminant() {
            return ($this->a * $this->d) - ($this->b * $this->c);
        }
        
        /**
         * Sets the values of the matrix to the ones found in an array.
         *
         * @param array $values [ a, b, c, d, tx, ty ]
         * @return void
         */
        public function setArray($values) {
            $this->setA($values[0]);
            $this->setB($values[1]);
            $this->setC($values[2]);
            $this->setD($values[3]);
            $this->setTx($values[4]);
            $this->setTy($values[5]);
        }
        
        /**
         * Returns an array of values from this matrix.
         *
         * @return array [ a, b, c, d, tx, ty ]
         */
        public function getArray() {
            return [ $this->a, $this->b, $this->c, $this->d, $this->tx, $this->ty ];
        }
        
        /**
         * Returns a string for this matrix that can be used for SVG transform attribute or CSS transform property.
         *
         * @return string
         */
        public function getTransform() {
            return "matrix({$this->a},{$this->b},{$this->c},{$this->d},{$this->tx},{$this->ty})";
        }
        
        /**
         * Sets the 'a' component of the matrix.
         *
         * @param float $a The new value of 'a'.
         * @return void
         */
        public function setA($a = 1) {
            $this->a = (float)$a;
        }
        
        /**
         * Returns the 'a' component of the matrix.
         *
         * @return float
         */
        public function getA() {
            return $this->a;
        }
        
        /**
         * Sets the 'b' component of the matrix.
         *
         * @param float $b The new value of 'b'.
         * @return void
         */
        public function setB($b = 0) {
            $this->b = (float)$b;
        }
        
        /**
         * Returns the 'b' component of the matrix.
         *
         * @return float
         */
        public function getB() {
            return $this->b;
        }
        
        /**
         * Sets the 'c' component of the matrix.
         *
         * @param float $c The new value of 'c'.
         * @return void
         */
        public function setC($c = 0) {
            $this->c = (float)$c;
        }
        
        /**
         * Returns the 'c' component of the matrix.
         *
         * @return float
         */
        public function getC() {
            return $this->c;
        }
        
        /**
         * Sets the 'd' component of the matrix.
         *
         * @param float $d The new value of 'd'.
         * @return void
         */
        public function setD($d = 1) {
            $this->d = (float)$d;
        }
        
        /**
         * Returns the 'd' component of the matrix.
         *
         * @return float
         */
        public function getD() {
            return $this->d;
        }
        
        /**
         * Sets the translation along the x axis.
         *
         * @param float $x
         * @return void
         */
        public function setTx($x) {
            $this->tx = (float)$x;
        }
        
        /**
         * Returns the translation along the x axis.
         *
         * @return float
         */
        public function getTx() {
            return $this->tx;
        }
        
        /**
         * Sets the translation along the y axis.
         *
         * @param float $y
         * @return void
         */
        public function setTy($y) {
            $this->ty = (float)$y;
        }
        
        /**
         * Returns the translation along the y axis.
         *
         * @return float
         */
        public function getTy() {
            return $this->ty;
        }
    }