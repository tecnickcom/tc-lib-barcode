<?php
/**
 * QrCodeTest.php
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
class QrCodeTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    /**
     * @dataProvider getGridDataProvider
     */
    public function testGetGrid($options, $code, $expected)
    {
        $bobj = $this->obj->getBarcodeObj('QRCODE'.$options, $code);
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, md5($grid));
    }

    public function getGridDataProvider()
    {
        return array(
            array('', '0123456789', '89e599523008751db7eef3b5befc37ed'),
            array(',L', '0123456789', '89e599523008751db7eef3b5befc37ed'),
            array(',H', '0123456789', '3c4ecb6cc99b7843de8d2d3274a43d9e'),
            array(',L,8B,0,0', '123aeiouàèìòù', '1622068066c77d3e6ea0a3ad420d105c'),
            array(',H,KJ,0,0', 'ぎポ亊', '1d429dd6a1627f6dc1620b3f56862d52'),
        );
    }

    public function testInvalidInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('QRCODE', '');
    }
}
