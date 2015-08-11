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
class BarcodeTestPhp54 extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->markTestSkipped("Need PHP >= 5.4"); // skip this test
        }
    }


    /**
     * @runInSeparateProcess
     */
    public function testGetPng()
    {
        ob_start();
        \Com\Tecnick\Barcode\Barcode::getBarcodeObj(
            'LRAW,AB,12,E3F',
            '01001100011100001111,10110011100011110000'
        )->setSize(-1,-1)
         ->setColor('purple')
         ->setPadding(array(-1,-1,-1,-1))
         ->getPng();
        $png = ob_get_clean();
        $this->assertEquals('PNG', substr($png, 1, 3));
    }
}
