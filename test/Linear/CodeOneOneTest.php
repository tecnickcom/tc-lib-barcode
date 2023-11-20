<?php

/**
 * CodeOneOneTest.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Linear;

use Test\TestUtil;

/**
 * Barcode class test
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class CodeOneOneTest extends TestUtil
{
    protected function getTestObject(): \Com\Tecnick\Barcode\Barcode
    {
        return new \Com\Tecnick\Barcode\Barcode();
    }

    public function testGetGrid(): void
    {
        $barcode = $this->getTestObject();
        $bobj = $barcode->getBarcodeObj('CODE11', '0123456789');
        $grid = $bobj->getGrid();
        $expected = "10110010101011011010110100101101100101010110110110"
            . "11010100110101010011011010010110101010101101011001\n";
        $this->assertEquals($expected, $grid);

        $bobj = $barcode->getBarcodeObj('CODE11', '123-456-789');
        $grid = $bobj->getGrid();
        $expected = "10110010110101101001011011001010101101010110110110110"
            . "10100110101011010101001101101001011010101101101011010101011001\n";
        $this->assertEquals($expected, $grid);

        $bobj = $barcode->getBarcodeObj('CODE11', '-');
        $grid = $bobj->getGrid();
        $expected = "10110010101101010110101011001\n";
        $this->assertEquals($expected, $grid);
    }

    public function testInvalidInput(): void
    {
        $this->bcExpectException('\\' . \Com\Tecnick\Barcode\Exception::class);
        $barcode = $this->getTestObject();
        $barcode->getBarcodeObj('CODE11', chr(255));
    }
}
