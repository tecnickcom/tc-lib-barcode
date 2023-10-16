<?php

/**
 * Encode.php
 *
 * @since       2023-10-13
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode\Type\Square\Aztec;

use Com\Tecnick\Barcode\Type\Square\Aztec\Data;
use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Square\Aztec\Encode
 *
 * Encode for Aztec Barcode type class
 *
 * @since       2023-10-13
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class Encode extends \Com\Tecnick\Barcode\Type\Square\Aztec\Bitstream
{
     /**
     * Aztec main encoder.
     *
     * @param string $code The code to encode.
     * @param int $ecc The error correction code percentage of error check words.
     * @param int $eci The ECI mode to use.
     * @param string $hint The mode to use.
     */
    public function __construct($code, $ecc = 33, $eci = 0, $hint = 'A')
    {
        $this->highLevelEncoding($code, $eci, $hint);
        if ($this->totbits == 0) {
              throw new BarcodeException('No input data');
        }
        if (!$this->sizeAndBitStuffing($ecc)) {
                throw new BarcodeException('Data too long');
        }
       // TODO:
       //  - Appending check codewords.
       //  - Arranging the complete message in a spiral around the core.
    }
}
