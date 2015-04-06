<?php
/**
 * EanEight.php
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

namespace Com\Tecnick\Barcode\Type\Linear;

use \Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Linear\EanEight;
 *
 * EanEight Barcode type class
 * EAN 8
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
class EanEight extends \Com\Tecnick\Barcode\Type\Linear\EanOneThree
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = 'EAN8';

    /**
     * Fixed code length
     *
     * @var int
     */
    protected $code_length = 8;
    
    /**
     * Get the bars array
     *
     * @throws BarcodeException in case of error
     */
    protected function setBars()
    {
        $code = $this->formatCode();
        $seq = '101'; // left guard bar
        $half_len = intval(ceil($this->code_length / 2));
        for ($pos = 0; $pos < $half_len; ++$pos) {
            $seq .= $this->chbar['A'][$code[$pos]];
        }
        $seq .= '01010'; // center guard bar
        for ($pos = $half_len; $pos < $this->code_length; ++$pos) {
            $seq .= $this->chbar['C'][$code[$pos]];
        }
        $seq .= '101'; // right guard bar
        $this->processBinarySequence($seq);
    }
}
