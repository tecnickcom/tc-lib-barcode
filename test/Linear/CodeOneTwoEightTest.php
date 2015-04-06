<?php
/**
 * CodeOneTwoEightTest.php
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

namespace Test\Linear;

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
class CodeOneTwoEightTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    public function testGetGrid()
    {
        $bobj = $this->obj->getBarcodeObj('C128', '0123456789');
        $grid = $bobj->getGrid();
        $expected = "110100111001001110110010011100110110011100101100101110011001001110110111001001100"
            ."1110100111011011101110100110011100101100100011110101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', '1PBK500EI');
        $grid = $bobj->getGrid();
        $expected = "110100100001001110011011101110110100010110001011000111011011100100100111011001001"
            ."11011001000110100011000100010111011001001100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', 'SCB500J1C3Y');
        $grid = $bobj->getGrid();
        $expected = "110100100001101110100010001000110100010110001101110010010011101100100111011001011"
            ."011100010011100110100010001101100101110011101101000110110111101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', '067023611120229212');
        $grid = $bobj->getGrid();
        $expected = "11010011100100111011001100111010011101101110100111011001100111001011001011100110"
            ."011101001001110011010011100110100111001101100111001010011101100110011100101100111001011"
            ."100101100110011100101001110011011001110010100001100101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', 'Data:28102003');
        $grid = $bobj->getGrid();
        $expected = "11010010000110011100101110100110010011100110100111011001100111001010011101100100"
            ."1110110011001011100100110011101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', '12345678901');
        $grid = $bobj->getGrid();
        $expected = "1101001110010011100110110010111001100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', '1234');
        $grid = $bobj->getGrid();
        $expected = "1101001110010011100110110011100101100101110011001001110110110111101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', 'hello');
        $grid = $bobj->getGrid();
        $expected = "110100100001001100001010110010000110010100001100101000010001111010100010011001100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', 'HI345678');
        $grid = $bobj->getGrid();
        $expected = "11010010000110010111001100100111011011100100110011101001110110"
            ."111011101001100111011000101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', 'HI34567A');
        $grid = $bobj->getGrid();
        $expected = "110100100001110110111010100011000110111101101100011101011\n";
        $this->assertEquals($expected, $grid);
        
        $bobj = $this->obj->getBarcodeObj('C128', 'Barcode 1');
        $grid = $bobj->getGrid();
        $expected = "110100100001000101100010010110000100100111101000010110010001111010100001001101011001"
            ."00001101100110010011100110111011000101100011101011\n";
        $this->assertEquals($expected, $grid);
    }
}
