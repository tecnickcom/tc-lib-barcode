<?php
/**
 * DatamatrixRectangularTest.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2020 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Square;

use PHPUnit\Framework\TestCase;

/**
 * Barcode class test
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class DatamatrixRectangularTest extends TestCase
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
    public function testGetGrid($code, $expected)
    {
        $bobj = $this->obj->getBarcodeObj('DATAMATRIXR', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, md5($grid));
    }

    public function getGridDataProvider()
    {
        return array(
            array('01234567890', 'd3811e018f960beed6d3fa5e675e290e'),
            array('01234567890123456789', 'fe3ecb042dabc4b40c5017e204df105b'),
            array('012345678901234567890123456789', '3f8e9aa4413b90f7e1c2e85b4471fd20'),
            array('0123456789012345678901234567890123456789', 'b748b02c1c4cae621a84c8dbba97c710'),
        );
    }

    /**
     * @dataProvider getStringDataProvider
     */
    public function testStrings($code)
    {
        $bobj = $this->obj->getBarcodeObj('DATAMATRIXR', $code);
        $this->assertNotNull($bobj);
    }

    public function getStringDataProvider()
    {
        return \Test\TestStrings::$data;
    }
}
