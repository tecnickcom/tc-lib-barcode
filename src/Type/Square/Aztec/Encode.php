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
use Com\Tecnick\Barcode\Type\Square\Aztec\ErrorCorrection;
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

        $wsize = $this->layer[2];
        $nbits = $this->layer[3];
        $this->addCheckWords($this->tmpCdws, $this->bitstream, $this->totbits, $nbits, $wsize);

       // TODO:
       //  - mode message
       //  - Arranging the complete message in a spiral around the core.
    }

    /**
     * Returns the Check Codewords array for the given data words.
     *
     * @param array $cdw Array of codewords.
     * @param array $bitstream Array of bits.
     * @param int   $totbits   Number of bits in the bitstream.
     * @param int   $nbits     Number of bits per layer.
     * @param int   $wsize     Word size.
     */
    protected function addCheckWords(array &$cdw, array &$bitstream, &$totbits, $nbits, $wsize)
    {
        $nwords = count($cdw);
        $totwords = intval($nbits / $wsize);
        $eccwords = ($totwords - $nwords);
        $ecc = new ErrorCorrection($wsize);
        $checkwords = $ecc->checkwords($cdw, $eccwords);
        // append check codewords
        foreach ($checkwords as $val) {
                $cdw[] = array($wsize, $val);
                $this->appendWordToBitstream($bitstream, $totbits, $wsize, $val);
        }
        // insert padding at the beginning of the codewords and bitstream
        $pad = intval($nbits % $wsize);
        if ($pad > 0) {
                array_unshift($cdw, array($pad, 0));
                array_unshift($bitstream, array_fill(0, $pad, 0));
        }
    }
}
