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
abstract class Encode
{
    /**
     * Current character encoding mode.
     *
     * @var int
     */
    protected $encmode = Data::MODE_UPPER;

    /**
     * Array containing the high-level encoding codewords.
     *
     * @var array
     */
    protected $cdws = array();// -> MODE_adCdw

    /**
     * Count the total number of bits.
     *
     * @var int
     */
    protected $totbits = 0;

    /**
     * Returns the high-level encoding for the given code and ECI mode.
     *
     * @param string $code The code to encode.
     * @param int $eci The ECI mode to use.
     *
     * @return array
     */
    protected function getHighLevelEncoding($code, $eci = 0)
    {
        $this->addFLG($eci);
        $chars = array_values(unpack('C*', $code));
        $maxidx = (count($chars) - 1);
        for ($idx = 0; $idx < $maxidx; $idx += 2) {
            $chr = $chars[$idx];
            $nextchr = $chars[($idx + 1)];
            $pmode = $this->pairMode($chr, $nextchr);
            if ($pmode > 0) {
                // add PUNCT pair
                // ...
            } else {
                // add chars
                // ...
            }
        }
        return $this-> $cdws;
    }

    /**
     * Returns the PUNCT two-bytes code if the given two characters are a punctuation pair.
     * Punct codes 2–5 encode two bytes each.
     *
     * @param int $chr The current curacter code.
     * @param int $nextchr The next character code.
     *
     * @return int
     */
    protected function pairMode($chr, $nextchr)
    {
        $key = (($chr << 8) + $nextchr);
        switch ($key) {
            case ((13 << 8) + 10): // '\r\n' (CR LF)
                return 2;
            case ((46 << 8) + 32): // '. ' (. SP)
                return 3;
            case ((44 << 8) + 32): // ', ' (, SP)
                return 4;
            case ((58 << 8) + 32): // ': ' (: SP)
                return 5;
        }
        return 0;
    }


    protected function charEnc($mode, $ord)
    {
        return isset(DATA::CHAR_ENC[$mode][$ord]) ? DATA::CHAR_ENC[$mode][$ord] : 0;
    }

    protected function addRawCwd($bits, $value)
    {
        $this->$cdws[] = array($bits, $value);
        $this->totbits += $bits;
    }

    protected function addCdw($mode, $value)
    {
        $this->addRawCwd(Data::MODE_BITS[$mode], $value);
    }

    protected function addLatch($mode)
    {
        if ($mode == $this->$encmode) {
            return;
        }
        $latch = Data::LATCH_MAP[$this->$encmode][$mode];
        foreach ($latch as $cdw) {
            $this->$cdws[] = $cdw;
            $this->totbits += $cdw[0];
        }
        $this->encmode = $mode;
    }

    protected function addShift($mode)
    {
        if ($this->$encmode == $mode) {
            return $this->$encmode;
        }
        $shift = Data::SHIFT_MAP[$this->$encmode][$mode];
        if (empty($shift)) {
            return $this->$encmode;
        }
        foreach ($shift as $cdw) {
            $this->$cdws[] = $cdw;
            $this->totbits += $cdw[0];
        }
    }

    protected function addFLG($eci)
    {
        if ($eci < 0) {
            return;
        }
        $this->addShift(Data::MODE_PUNCT);
        if ($eci == 0) {
            $this->addRawCwd(3, 0); // FNC1
            return;
        }
        $seci = (string)$eci;
        $digits = strlen($seci);
        $this->addRawCwd(3, $digits); // 1–6 digits
        for ($idx = 0; $idx < $digits; $idx++) {
            $this->addCdw(
                Data::MODE_DIGIT,
                charEnc(Data::MODE_DIGIT, ord($seci[$idx]))
            );
        }
    }

    protected function isSupportedChar($ord)
    {
        for ($mode = 0; $mode < 5; $mode++) {
            if (isset(DATA::CHAR_ENC[$mode][$ord])) {
                return true;
            }
        }
        return false;
    }
}
