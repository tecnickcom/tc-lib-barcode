<?php
/**
 * Util.php
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

namespace Com\Tecnick\Barcode\Type\Square\Datamatrix;

use \Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Square\Datamatrix\Util
 *
 * Error correction methods and other utilities for Datamatrix Barcode type class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
abstract class Util
{
    /**
     * Product of two numbers in a Power-of-Two Galois Field
     *
     * @param int   $numa First number to multiply.
     * @param int   $numb Second number to multiply.
     * @param array $log  Log table.
     * @param array $alog Anti-Log table.
     * @param array $ngf  Number of Factors of the Reed-Solomon polynomial.
     *
     * @return int product
     */
    protected function getGFProduct($numa, $numb, $log, $alog, $ngf)
    {
        if (($numa == 0) || ($numb == 0)) {
            return 0;
        }
        return ($alog[($log[$numa] + $log[$numb]) % ($ngf - 1)]);
    }

    /**
     * Generate the log ($log) and antilog ($alog) tables
     *
     * @param array $log  Log table
     * @param arrya $alog Anti-Log table
     * @param int   $ngf  Number of fields on log/antilog table (power of 2).
     * @param int   $vpp  The value of its prime modulus polynomial (301 for ECC200).
     */
    protected function genLogs(&$log, &$alog, $ngf, $vpp)
    {
        for ($i = 1; $i < $ngf; ++$i) {
            $alog[$i] = ($alog[($i - 1)] * 2);
            if ($alog[$i] >= $ngf) {
                $alog[$i] ^= $vpp;
            }
            $log[$alog[$i]] = $i;
        }
        ksort($log);
    }
}
