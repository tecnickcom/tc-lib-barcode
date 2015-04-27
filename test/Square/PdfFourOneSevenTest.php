<?php
/**
 * PdfFourOneSevenTest.php
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
class PdfFourOneSevenTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    public function testInvalidInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('PDF417', '');
    }

    public function testTooLong()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $code = str_pad('', 1000, 'X1');
        $this->obj->getBarcodeObj('PDF417', $code);
    }

    /**
     * @dataProvider getGridDataProvider
     */
    public function testGetGrid($options, $code, $expected)
    {
        $bobj = $this->obj->getBarcodeObj('PDF417'.$options, $code);
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, md5($grid));
    }

    public function getGridDataProvider()
    {
        return array(
            array('', '0123456789', '4f9cdac81d62f0020beb93fc3ecdd8ad'),
            array(',2,8,1,0,0,0,1,2', str_pad('', 1750, 'X'), 'f0874a35e15f11f9aa8bc070a4be24bf'),
            array(',15,8,1,0,0,0,1,2', str_pad('', 1750, 'X'), '0288f0a87cc069fc34d6168d7a9f7846'),
        );
    }
}
