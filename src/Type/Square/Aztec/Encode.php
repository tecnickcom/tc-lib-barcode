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
     * Bidimensional grid containing the encoded data.
     *
     * @var array
     */
    protected $grid = array();

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

    protected function setGrid()
    {
        // initialize grid
        $size = $this->layer[0];
        $row = array_fill(0, $size, 0);
        $this->grid = array_fill(0, $size, $row);
        // draw center
        $center = intval(($size - 1) / 2);
        $this->grid[$center][$center] = 1;
        // draw bulls-eye and reference patterns
        $bewidth = $this->compact ? 11 : 15;
        $bemid = intval(($bewidth - 1) / 2);
        for ($rng = 2; $rng < $bemid; $rng += 2) {
            // center cross points
            $this->grid[($center + $rng)][($center)] = 1;
            $this->grid[($center - $rng)][($center)] = 1;
            $this->grid[($center)][($center + $rng)] = 1;
            $this->grid[($center)][($center - $rng)] = 1;
            // corner points
            $this->grid[($center + $rng)][($center + $rng)] = 1;
            $this->grid[($center + $rng)][($center - $rng)] = 1;
            $this->grid[($center - $rng)][($center + $rng)] = 1;
            $this->grid[($center - $rng)][($center - $rng)] = 1;
            for ($pos = 1; $pos < $rng; $pos++) {
                // horizontal points
                $this->grid[($center + $rng)][($center + $pos)] = 1;
                $this->grid[($center + $rng)][($center - $pos)] = 1;
                $this->grid[($center - $rng)][($center + $pos)] = 1;
                $this->grid[($center - $rng)][($center - $pos)] = 1;
                // vertical points
                $this->grid[($center + $pos)][($center + $rng)] = 1;
                $this->grid[($center + $pos)][($center - $rng)] = 1;
                $this->grid[($center - $pos)][($center + $rng)] = 1;
                $this->grid[($center - $pos)][($center - $rng)] = 1;
            }
        }
        // draw orientation patterns
        $this->grid[($center - $bemid)][($center - $bemid)] = 1;
        $this->grid[($center - $bemid)][($center - $bemid + 1)] = 1;
        $this->grid[($center - $bemid)][($center + $bemid)] = 1;
        $this->grid[($center - $bemid - 1)][($center - $bemid)] = 1;
        $this->grid[($center - $bemid - 1)][($center + $bemid)] = 1;
        $this->grid[($center + $bemid - 1)][($center + $bemid)] = 1;
    }
}
