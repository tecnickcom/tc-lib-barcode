<?php
/**
 * Convert.php
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

namespace Com\Tecnick\Barcode\Type;

use \Com\Tecnick\Barcode\Exception as BarcodeException;
use \Com\Tecnick\Color\Exception as ColorException;

/**
 * Com\Tecnick\Barcode\Type\Convert
 *
 * Barcode Convert class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
abstract class Convert
{
    /**
     * Import a binary sequence of comma-separated 01 strings
     *
     * @param string $code Code to process
     */
    protected function processBinarySequence($code)
    {
        $raw = new \Com\Tecnick\Barcode\Type\Raw($code, $this->width, $this->height);
        $data = $raw->getArray();
        $this->ncols = $data['ncols'];
        $this->nrows = $data['nrows'];
        $this->bars = $data['bars'];
    }

    /**
     * Convert large integer number to hexadecimal representation.
     *
     * @param string $number Number to convert (as string)
     *
     * @return string hexadecimal representation
     */
    protected function convertDecToHex($number)
    {
        if ($number == 0) {
            return '00';
        }
        $hex = array();
        while ($number > 0) {
            array_push($hex, strtoupper(dechex(bcmod($number, '16'))));
            $number = bcdiv($number, '16', 0);
        }
        $hex = array_reverse($hex);
        return implode($hex);
    }

    /**
     * Convert large hexadecimal number to decimal representation (string).
     *
     * @param string $hex Hexadecimal number to convert (as string)
     *
     * @return string hexadecimal representation
     */
    protected function convertHexToDec($hex)
    {
        $dec = 0;
        $bitval = 1;
        $len = strlen($hex);
        for ($pos = ($len - 1); $pos >= 0; --$pos) {
            $dec = bcadd($dec, bcmul(hexdec($hex[$pos]), $bitval));
            $bitval = bcmul($bitval, 16);
        }
        return $dec;
    }
}
