<?php
/**
 * BarcodeTest.php
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

namespace Test;

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
class BarcodeTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    public function testGetTypes()
    {
        $types = $this->obj->getTypes();
        $this->assertEquals(36, count($types));
    }

    public function testGetBarcodeObjException()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj(
            'ERROR',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple'
        );
    }

    public function testEmptyColumns()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('LRAW', '');
    }

    public function testEmptyInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('LRAW', array());
    }

    public function testExportMethods()
    {
        $bobj = $this->obj->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple'
        );

        $barr = $bobj->getArray();
        $this->assertEquals('linear', $barr['type']);
        $this->assertEquals('LRAW', $barr['format']);
        $this->assertEquals(array('AB', '12', 'E3F'), $barr['params']);
        $this->assertEquals('01001100011100001111,10110011100011110000', $barr['code']);
        $this->assertEquals(20, $barr['ncols']);
        $this->assertEquals(2, $barr['nrows']);
        $this->assertEquals(40, $barr['width']);
        $this->assertEquals(4, $barr['height']);
        $this->assertEquals(2, $barr['width_ratio']);
        $this->assertEquals(2, $barr['height_ratio']);
        $expected = array(
            array(1,0,1,1),
            array(4,0,2,1),
            array(9,0,3,1),
            array(16,0,4,1),
            array(0,1,1,1),
            array(2,1,2,1),
            array(6,1,3,1),
            array(12,1,4,1),
        );
        $this->assertEquals($expected, $barr['bars']);
        $this->assertEquals('#800080ff', $barr['color_obj']->getRgbaHexColor());

        $grid = $bobj->getGrid('A', 'B');
        $expected = "ABAABBAAABBBAAAABBBB\nBABBAABBBAAABBBBAAAA\n";
        $this->assertEquals($expected, $grid);

        $svg = $bobj->getSvgCode();
        $expected = '<?xml version="1.0" standalone="no" ?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg width="40.000000" height="4.000000" version="1.1" xmlns="http://www.w3.org/2000/svg">
	<desc>01001100011100001111,10110011100011110000</desc>
	<g id="bars" fill="rgba(50%,0%,50%,1)" stroke="none" stroke-width="0" stroke-linecap="square">
		<rect x="2.000000" y="0.000000" width="2.000000" height="2.000000" />
		<rect x="8.000000" y="0.000000" width="4.000000" height="2.000000" />
		<rect x="18.000000" y="0.000000" width="6.000000" height="2.000000" />
		<rect x="32.000000" y="0.000000" width="8.000000" height="2.000000" />
		<rect x="0.000000" y="2.000000" width="2.000000" height="2.000000" />
		<rect x="4.000000" y="2.000000" width="4.000000" height="2.000000" />
		<rect x="12.000000" y="2.000000" width="6.000000" height="2.000000" />
		<rect x="24.000000" y="2.000000" width="8.000000" height="2.000000" />
	</g>
</svg>
';
        $this->assertEquals($expected, $svg);

        $hdiv = $bobj->getHtmlDiv();
        $expected = '<div style="width:40.000000px;height:4.000000px;position:relative;font-size:0;">
	<div style="background-color:rgba(50%,0%,50%,1);left:2.000000px;top:0.000000px;'
        .'width:2.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:8.000000px;top:0.000000px;'
        .'width:4.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:18.000000px;top:0.000000px;'
        .'width:6.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:32.000000px;top:0.000000px;'
        .'width:8.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:0.000000px;top:2.000000px;'
        .'width:2.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:4.000000px;top:2.000000px;'
        .'width:4.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:12.000000px;top:2.000000px;'
        .'width:6.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
	<div style="background-color:rgba(50%,0%,50%,1);left:24.000000px;top:2.000000px;'
        .'width:8.000000px;height:2.000000px;position:absolute;">&nbsp;</div>
</div>
';
        $this->assertEquals($expected, $hdiv);

        $pngik = $bobj->getPngData(true);
        $this->assertEquals('Imagick', get_class($pngik));
        
        $pnggd = $bobj->getPngData(false);
        $this->assertEquals('PNG', substr($pnggd, 1, 3));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testGetSvg()
    {
        $bobj = $this->obj->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple'
        );
        ob_start();
        $bobj->getSvg();
        $svg = ob_get_clean();
        $this->assertEquals('e0a3c04f3f67b88961cca4fe52d6a2ab', md5($svg));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testGetPng()
    {
        $bobj = $this->obj->getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000',
            -2,
            -2,
            'purple'
        );
        ob_start();
        $bobj->getPng();
        $png = ob_get_clean();
        $this->assertEquals('PNG', substr($png, 1, 3));
    }
}
