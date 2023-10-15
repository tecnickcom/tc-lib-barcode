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
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
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
    protected $cdws = array();

    /**
     * Temporary array of codewords.
     *
     * @var array
     */
    protected $tmpCdws = array();

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
        for ($idx = 0; $idx < $maxidx; $idx++) {
            if ($this->processPunctPairs($chars, $idx, $maxidx)) {
                continue;
            }
        }
        return $this->cdws;
    }

    /**
     * Process special Punctuation Pairs.
     *
     * @param array $chars The array of characters.
     * @param int $idx The current character index.
     * @param int $maxidx The maximum character index.
     *
     * @return bool True if pair characters have been found and processed.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function processPunctPairs(&$chars, &$idx, $maxidx)
    {
        $ppairs = $this->countPunctPairs($chars, $idx, $maxidx);
        if ($ppairs == 0) {
            return false;
        }
        switch ($this->encmode) {
            case Data::MODE_PUNCT:
                break;
            case Data::MODE_MIXED:
                $this->addLatch(Data::MODE_PUNCT);
                break;
            case Data::MODE_UPPER:
            case Data::MODE_LOWER:
                if ($ppairs > 1) {
                    $this->addLatch(Data::MODE_PUNCT);
                }
                break;
            case Data::MODE_DIGIT:
                $common = $this->countPunctAndDigitChars($chars, $idx, $maxidx);
                if ($common < 6) {
                    $this->mergeTmpCwdRaw();
                    $idx += $common;
                    return true;
                }
                if ($ppairs > 2) {
                    $this->addLatch(Data::MODE_PUNCT);
                }
                break;
            default:
                return false;
        }
        $this->mergeTmpCwd(Data::MODE_PUNCT);
        $idx += ($ppairs * 2);
        return true;
    }

    /**
     * Returns the PUNCT two-bytes code if the given two characters are a punctuation pair.
     * Punct codes 2–5 encode two bytes each.
     *
     * @param int $ord The current curacter code.
     * @param int $next The next character code.
     *
     * @return int
     */
    protected function punctPairMode($ord, $next)
    {
        $key = (($ord << 8) + $next);
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
        return 0; // no punct pair
    }

    protected function countPunctPairs(&$chars, $idx, $maxidx)
    {
        $this->tmpCdws = array();
        $pairs = 0;
        while ($idx < $maxidx) {
            $pmode = $this->punctPairMode($chars[$idx], $chars[($idx + 1)]);
            if ($pmode == 0) {
                return $pairs;
            }
            $this->tmpCdws[] = array(5, $pmode);
            $pairs++;
            $idx += 2;
        }
        return $pairs;
    }

    /**
     * Returns true if the character is in common between the PUNCT and DIGIT modes.
     * Characters ' ' (32), '.' (46) and ',' (44) are in common between the PUNCT and DIGIT modes.
     *
     * @param int $ord Integer ASCII code of the character to check.
     *
     * @return bool
     */
    protected function isPunctAndDigitChar($ord)
    {
        return (($ord == 32) || ($ord == 44) || ($ord == 46));
    }

    protected function countPunctAndDigitChars(&$chars, $idx, $maxidx)
    {
        $this->tmpCdws = array();
        $count = 0;
        while ($idx < $maxidx) {
            $ord = $chars[$idx];
            if (!$this->isPunctAndDigitChar($ord)) {
                return $count;
            }
            $this->tmpCdws[] = array(4, $this->charEnc(Data::MODE_DIGIT, $ord));
            $count++;
            $idx++;
        }
        return $count;
    }

    protected function charEnc($mode, $ord)
    {
        return isset(DATA::CHAR_ENC[$mode][$ord]) ? DATA::CHAR_ENC[$mode][$ord] : 0;
    }

    protected function addRawCwd($bits, $value)
    {
        $this->cdws[] = array($bits, $value);
        $this->totbits += $bits;
    }

    protected function mergeTmpCwdWithShift($mode)
    {
        foreach ($this->tmpCdws as $item) {
            $this->addShift($mode);
            $this->cdws[] = $item;
            $this->totbits += $item[0];
        }
    }

    protected function mergeTmpCwdRaw()
    {
        foreach ($this->tmpCdws as $item) {
            $this->cdws[] = $item;
            $this->totbits += $item[0];
        }
    }

    protected function mergeTmpCwd($mode = -1)
    {
        if (($mode < 0) || ($this->encmode == $mode)) {
            $this->mergeTmpCwdRaw();
        } else {
            $this->mergeTmpCwdWithShift($mode);
        }
        $this->tmpCdws = array();
    }

    protected function addCdw($mode, $value)
    {
        $this->addRawCwd(Data::MODE_BITS[$mode], $value);
    }

    protected function addLatch($mode)
    {
        if ($this->encmode == $mode) {
            return;
        }
        $latch = Data::LATCH_MAP[$this->encmode][$mode];
        foreach ($latch as $cdw) {
            $this->cdws[] = $cdw;
            $this->totbits += $cdw[0];
        }
        $this->encmode = $mode;
    }

    protected function addShift($mode)
    {
        if ($this->encmode == $mode) {
            return $this->encmode;
        }
        $shift = Data::SHIFT_MAP[$this->encmode][$mode];
        if (empty($shift)) {
            return $this->encmode;
        }
        foreach ($shift as $cdw) {
            $this->cdws[] = $cdw;
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
                $this->charEnc(Data::MODE_DIGIT, ord($seci[$idx]))
            );
        }
    }

    protected function charMode($ord)
    {
        return isset(DATA::CHAR_MODES[$ord]) ? DATA::CHAR_MODES[$ord] : -1;
    }
}
