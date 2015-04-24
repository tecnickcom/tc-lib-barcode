<?php
/**
 * DatamatrixTest.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Square;

/**
 * Barcode class test
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
class DatamatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    public function testGetGrid()
    {
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', '0123456789');
        $grid = $bobj->getGrid();
        $expected = "101010101010\n"
            ."110111000101\n"
            ."101111101000\n"
            ."100101101011\n"
            ."101101111110\n"
            ."110011001101\n"
            ."100010000010\n"
            ."101100010001\n"
            ."110101111100\n"
            ."110101101101\n"
            ."110111010010\n"
            ."111111111111\n";
        $this->assertEquals($expected, $grid);

        $code = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('3dee31111519b71fdf624efda75e4b4a', md5($grid));

        $code = '10f27ce-acb7-4e4e-a7ae-a0b98da6ed4a';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('67b3bd23c0ab6de2e90a8ebd2143ed2b', md5($grid));

        $code = 'Hello World';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('e72650689027fe75d1f9377ec759c710', md5($grid));

        $code = 'https://github.com/tecnickcom/tc-lib-barcode';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('ff3faf34d07c75fb99051fd2b9d72e21', md5($grid));

        $code = 'abcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcd'
            .'abcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcd';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('296d2971c50f3302ad993d4b722a055f', md5($grid));

        $code = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\'
            .'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\'
            .'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('62e6552f3bbb51b0e2e22fbd5bbdc2bf', md5($grid));

        $code = chr(128).chr(138).chr(148).chr(158);
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('01aa230c9a4c35ea69f23ecbc58d8e3e', md5($grid));

        $code = '!"£$%^&*()-+_={}[]\'#@~;:/?,.<>|';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('03e8a96b0d0e41bded21c22f865da9c7', md5($grid));

        $code = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\1234567890';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('561a64e10c4def637cd1eec6c8150f2b', md5($grid));

        $code = chr(254).chr(253).'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\';
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals('6fd37d191dabc540464723f7747455bf', md5($grid));
    }

    public function testInvalidInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('DATAMATRIX', '');
    }

    public function testLongInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $code = str_pad('', 3000, 'X');
        $this->obj->getBarcodeObj('DATAMATRIX', $code);
    }
}
