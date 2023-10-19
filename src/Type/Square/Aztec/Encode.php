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
     * Coordinate of the grid center.
     *
     * @var int
     */
    protected $gridcenter = 0;

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
        $numcdw = count($this->tmpCdws);
        $wsize = $this->layer[2];
        $nbits = $this->layer[3];
        $this->addCheckWords($this->tmpCdws, $this->bitstream, $this->totbits, $nbits, $wsize);
        $this->setGrid();
        ($this->compact) ? $this->drawModeCompact($numcdw) : $this->drawModeFull($numcdw);
        $this->drawData();
    }

    /**
     * Returns the bidimensional grid containing the encoded data.
     *
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
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

    /**
     * Initialize the grid with all patterns.
     */
    protected function setGrid()
    {
        // initialize grid
        $size = $this->layer[0];
        $row = array_fill(0, $size, 0);
        $this->grid = array_fill(0, $size, $row);
        // draw center
        $center = intval(($size - 1) / 2);
        $this->gridcenter = $center;
        $this->grid[$center][$center] = 1;
        // draw finder pattern (bulls-eye)
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
        if ($this->compact) {
            return;
        }
        // draw reference grid for full mode
        $halfsize = intval(($size - 1) / 2);
        // central cross
        for ($pos = 8; $pos <= $halfsize; $pos += 2) {
            // horizontal
            $this->grid[($center)][($center + $pos)] = 1;
            $this->grid[($center)][($center - $pos)] = 1;
            // vertical
            $this->grid[($center + $pos)][($center)] = 1;
            $this->grid[($center + $pos)][($center)] = 1;
        }
        // grid lines
        for ($pos = 2; $pos <= $halfsize; $pos += 2) {
            for ($ref = 16; $ref <= $halfsize; $ref += 16) {
                // horizontal
                $this->grid[($center + $ref)][($center + $pos)] = 1;
                $this->grid[($center + $ref)][($center - $pos)] = 1;
                $this->grid[($center - $ref)][($center + $pos)] = 1;
                $this->grid[($center - $ref)][($center - $pos)] = 1;
                // vertical
                $this->grid[($center + $pos)][($center + $ref)] = 1;
                $this->grid[($center + $pos)][($center - $ref)] = 1;
                $this->grid[($center - $pos)][($center + $ref)] = 1;
                $this->grid[($center - $pos)][($center - $ref)] = 1;
            }
        }
    }

    /**
     * Add the compact mode message to the grid.
     *
     * @param int $numcdw Number of data codewords.
     */
    protected function drawModeCompact($numcdw)
    {
        $modecwd = array();
        $modebs = array();
        $nbits = 0;
        $modecwd[] = array(2, ($this->numlayers - 1));
        $this->appendWordToBitstream($modebs, $nbits, $modecwd[0][0], $modecwd[0][1]);
        $modecwd[] = array(6, ($numcdw - 1));
        $this->appendWordToBitstream($modebs, $nbits, $modecwd[1][0], $modecwd[1][1]);
        $this->addCheckWords($modecwd, $modebs, $nbits, 28, 4);
        // draw the mode message in the grid (7 bits per side clockwise)
        $rowt = $coll = ($this->gridcenter - 5);
        $rowl = $rowr = $colt = ($this->gridcenter - 3);
        $rowb = $colr = ($this->gridcenter + 5);
        $colb = ($this->gridcenter + 3);
        for ($pos = 0; $pos < 7; $pos++) {
            // top
            if (!empty($modebs[$pos])) {
                $this->grid[$rowt][($colt + $pos)] = 1;
            }
            // right
            if (!empty($modebs[($pos + 7)])) {
                $this->grid[($rowr + $pos)][$colr] = 1;
            }
            // bottom
            if (!empty($modebs[($pos + 14)])) {
                $this->grid[$rowb][($colb - $pos)] = 1;
            }
            // left
            if (!empty($modebs[($pos + 21)])) {
                $this->grid[($rowl - $pos)][$coll] = 1;
            }
        }
    }

    /**
     * Add the full mode message to the grid.
     *
     * @param int $numcdw Number of data codewords.
     */
    protected function drawModeFull($numcdw)
    {
        $modecwd = array();
        $modebs = array();
        $nbits = 0;
        $modecwd[] = array(5, ($this->numlayers - 1));
        $this->appendWordToBitstream($modebs, $nbits, $modecwd[0][0], $modecwd[0][1]);
        $modecwd[] = array(11, ($numcdw - 1));
        $this->appendWordToBitstream($modebs, $nbits, $modecwd[1][0], $modecwd[1][1]);
        $this->addCheckWords($modecwd, $modebs, $nbits, 40, 4);
        // draw the mode message in the grid (10 bits per side clockwise)
        $rowt = $coll = ($this->gridcenter - 7);
        $rowl = $rowr = $colt = ($this->gridcenter - 5);
        $rowb = $colr = ($this->gridcenter + 7);
        $colb = ($this->gridcenter + 5);
        for ($pos = 0; $pos < 10; $pos++) {
            $skip = intval($pos / 5); // used to skip the center position
            // top
            if (!empty($modebs[$pos])) {
                $this->grid[$rowt][($colt + $pos + $skip)] = 1;
            }
            // right
            if (!empty($modebs[($pos + 10)])) {
                $this->grid[($rowr + $pos + $skip)][$colr] = 1;
            }
            // bottom
            if (!empty($modebs[($pos + 20)])) {
                $this->grid[$rowb][($colb - $pos - $skip)] = 1;
            }
            // left
            if (!empty($modebs[($pos + 30)])) {
                $this->grid[($rowl - $pos - $skip)][$coll] = 1;
            }
        }
    }

    /**
     * Returns a bit from the end of the bitstream and update the index.
     *
     * @param int $bit Index of the bit to pop.
     *
     * @return int
     */
    protected function popBit(&$bit)
    {
        return (empty($this->bitstream[$bit--]) ? 0 : 1);
    }


    /**
     * Returns the offset for the specified position to skip the reference grid.
     *
     * @param int $pos Position in the grid.
     *
     * @return int
     */
    protected function skipRefGrid($pos)
    {
        return intval($this->compact && (($pos % 16) == 0));
    }

    /**
     * Draw the data bitstream in the grid in Full mode.
     */
    protected function drawData()
    {
        $center = $this->gridcenter;
        $llen = 17; // width of the first layer side
        $srow = -8; // start top row offset from the center (LSB)
        $scol = -7; // start top column offset from the center (LSB)
        if ($this->compact) {
            $llen -= 4;
            $srow += 2;
            $scol += 2;
        }
        $dmoff = 1; // offset to the second bit of a domino
        $bit = ($this->totbits - 1); // index of last bitstream bit (first to draw)
        for ($layer = 0; $layer < $this->numlayers; $layer++) {
            // top
            $ypos = ($center + $srow);
            $xpos = ($center + $scol);
            for ($pos = 0; $pos < $llen; $pos++) {
                $xpos += $this->skipRefGrid($xpos - $center); // skip reference grid
                $this->grid[$ypos][$xpos] = $this->popBit($bit);
                $this->grid[($ypos - $dmoff)][$xpos] = $this->popBit($bit);
                $xpos++;
            }
            // right
            $ypos++;
            $xpos--;
            for ($pos = 0; $pos < $llen; $pos++) {
                $ypos += $this->skipRefGrid($ypos - $center); // skip reference grid
                $this->grid[$ypos][$xpos] = $this->popBit($bit);
                $this->grid[$ypos][($xpos + $dmoff)] = $this->popBit($bit);
                $ypos++;
            }
            // bottom
            $ypos--;
            $xpos -= 2;
            for ($pos = 0; $pos < $llen; $pos++) {
                $xpos -= $this->skipRefGrid($xpos - $center); // skip reference grid
                $this->grid[$ypos][$xpos] = $this->popBit($bit);
                $this->grid[($ypos + $dmoff)][$xpos] = $this->popBit($bit);
                $xpos--;
            }
            // left
            $ypos -= 2;
            $xpos++;
            for ($pos = 0; $pos < $llen; $pos++) {
                $ypos -= $this->skipRefGrid($ypos - $center); // skip reference grid
                $this->grid[$ypos][$xpos] = $this->popBit($bit);
                $this->grid[$ypos][($xpos - $dmoff)] = $this->popBit($bit);
                $ypos--;
            }
            $scol = ($xpos - $center);
            $srow = ($ypos - $center - 1);
            $srow -= $this->skipRefGrid($srow);
            $dmoff = (1 + $this->skipRefGrid($srow - 1));
            $llen += 4;
        }
    }
}
