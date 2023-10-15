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
     *$chrlen
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
        $chrlen = count($chars);
        for ($idx = 0; $idx < $chrlen; $idx++) {
            if ($this->processBinaryChars($chars, $idx, $chrlen)) {
                continue;
            }
            if ($this->processPunctPairs($chars, $idx, $chrlen)) {
                continue;
            }
            $this->processModeChars($chars, $idx, $chrlen);
        }
        return $this->cdws;
    }

    /**
     * Process mode characters.
     *
     * @param array $chars The array of characters.
     * @param int $idx The current character index.
     * @param int $chrlen The total numebr of characters to process.
     */
    protected function processModeChars(&$chars, &$idx, $chrlen)
    {
        $ord = $chars[$idx];
        if ($this->isSameMode($this->encmode, $ord)) {
            $mode = $this->encmode;
        } else {
            $mode = $this->charMode($ord);
        }
        $nchr = $this->countModeChars($chars, $idx, $chrlen, $mode);
        if ($this->encmode != $mode) {
            if (($nchr == 1) && (!empty(Data::SHIFT_MAP[$this->encmode][$mode]))) {
                $this->addShift($mode);
            } else {
                $this->addLatch($mode);
            }
        }
        $this->mergeTmpCwd();
    }

    /**
    * Count consecutive characters in the same mode.
    *
    * @param array $chars The array of characters.
    * @param int $idx The current character index.
    * @param int $chrlen The total numebr of characters to process.
    * @param int $mode The current mode.
    *
    * @return int
    */
    protected function countModeChars(&$chars, $idx, $chrlen, $mode)
    {
        $this->tmpCdws = array();
        $nbits = Data::MODE_BITS[$mode];
        $count = 0;
        do {
            $ord = $chars[$idx];
            if (!$this->isSameMode($mode, $ord)) {
                return $count;
            }
            $this->tmpCdws[] = array($nbits, $this->charEnc($mode, $ord));
            $count++;
            $idx++;
        } while ($idx < $chrlen);
        return $count;
    }

    /**
     * Checks if current character is supported by the current code.
     *
     * @param int $mode The mode to check.
     * @param int $ord The character ASCII value to compare against.
     *
     * @return bool Returns true if the mode is the same as the ordinal value, false otherwise.
     */
    protected function isSameMode($mode, $ord)
    {
        return (
            ($mode == $this->charMode($ord))
            || (($ord == 32) && ($mode != Data::MODE_PUNCT))
            || (($mode == Data::MODE_PUNCT) && (($ord == 13) || ($ord == 44) || ($ord == 46)))
        );
    }

    /**
     * Process consecutive binary characters.
     *
     * @param array $chars The array of characters.
     * @param int $idx The current character index.
     * @param int $chrlen The total numebr of characters to process.
     *
     * @return bool True if binary characters have been found and processed.
     */
    protected function processBinaryChars(&$chars, &$idx, $chrlen)
    {
        $binchrs = $this->countBinaryChars($chars, $idx, $chrlen);
        if ($binchrs == 0) {
            return false;
        }
        $this->addShift(Data::MODE_BINARY);
        if ($binchrs > 62) {
            $this->addRawCwd(5, 0);
            $this->addRawCwd(11, $binchrs);
            $this->mergeTmpCwdRaw();
            return true;
        }
        if ($binchrs > 31) {
            $this->addRawCwd(5, 31);
            for ($bcw = 0; $bcw < 31; $bcw++) {
                $this->cdws[] = $this->tmpCdws[$bcw];
                $this->totbits += 8;
            }
            $this->addShift(Data::MODE_BINARY);
            $this->addRawCwd(5, ($binchrs - 31));
            for ($bcw = 31; $bcw < $binchrs; $bcw++) {
                $this->cdws[] = $this->tmpCdws[$bcw];
                $this->totbits += 8;
            }
            return true;
        }
        $this->addRawCwd(5, $binchrs);
        $this->mergeTmpCwdRaw();
        return true;
    }

    /**
    * Count consecutive binary characters.
    *
    * @param array $chars The array of characters.
    * @param int $idx The current character index.
    * @param int $chrlen The total numebr of characters to process.
    *
    * @return int
    *
    * @SuppressWarnings(PHPMD.CyclomaticComplexity)
    */
    protected function countBinaryChars(&$chars, $idx, $chrlen)
    {
        $this->tmpCdws = array();
        $count = 0;
        while (($idx < $chrlen) && ($count < 2048)) {
            $ord = $chars[$idx];
            if ($this->charMode($ord) != Data::MODE_BINARY) {
                return $count;
            }
            $this->tmpCdws[] = array(8, $ord);
            $count++;
            $idx++;
        }
        return $count;
    }

    /**
     * Process consecutive special Punctuation Pairs.
     *
     * @param array $chars The array of characters.
     * @param int $idx The current character index.
     * @param int $chrlen The total numebr of characters to process.
     *
     * @return bool True if pair characters have been found and processed.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function processPunctPairs(&$chars, &$idx, $chrlen)
    {
        $ppairs = $this->countPunctPairs($chars, $idx, $chrlen);
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
                $common = $this->countPunctAndDigitChars($chars, $idx, $chrlen);
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
     * Count consecutive special Punctuation Pairs.
     *
     * @param array $chars The array of characters.
     * @param int $idx The current character index.
     * @param int $chrlen The total numebr of characters to process.
     *
     * @return int
     */
    protected function countPunctPairs(&$chars, $idx, $chrlen)
    {
        $this->tmpCdws = array();
        $pairs = 0;
        $maxidx = $chrlen - 1;
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

    /**
     * Counts the number of consecutive charcters that are in both PUNCT or DIGIT modes.
     *
     * @param string &$chars The string to count the characters in.
     * @param int $idx The starting index to count from.
     * @param int $chrlen The length of the string to count.
     *
     * @return int The number of punctuation and digit characters in the string.
     */
    protected function countPunctAndDigitChars(&$chars, $idx, $chrlen)
    {
        $this->tmpCdws = array();
        $count = 0;
        while ($idx < $chrlen) {
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

    /**
     * Encodes a character using the specified mode and ordinal value.
     *
     * @param int $mode The encoding mode.
     * @param int $ord The ordinal value of the character to encode.
     *
     * @return int The encoded character.
     */
    protected function charEnc($mode, $ord)
    {
        return isset(DATA::CHAR_ENC[$mode][$ord]) ? DATA::CHAR_ENC[$mode][$ord] : 0;
    }

    /**
     * Merges the temporary codewords array with the current codewords array.
     * Shift to the specified mode.
     *
     * @param int $mode The encoding mode for the codewords.
     */
    protected function mergeTmpCwdWithShift($mode)
    {
        foreach ($this->tmpCdws as $item) {
            $this->addShift($mode);
            $this->cdws[] = $item;
            $this->totbits += $item[0];
        }
    }

    /**
     * Merges the temporary codewords array with the current codewords array.
     * No shift is performed.
     */
    protected function mergeTmpCwdRaw()
    {
        foreach ($this->tmpCdws as $item) {
            $this->cdws[] = $item;
            $this->totbits += $item[0];
        }
    }

    /**
     * Merge temporary codewords with current codewords based on the encoding mode.
     *
     * @param int $mode The encoding mode to use for merging codewords.
     *                  If negative, the current encoding mode will be used.
     */
    protected function mergeTmpCwd($mode = -1)
    {
        if (($mode < 0) || ($this->encmode == $mode)) {
            $this->mergeTmpCwdRaw();
        } else {
            $this->mergeTmpCwdWithShift($mode);
        }
        $this->tmpCdws = array();
    }

    /**
     * Add a new Codeword.
     *
     * @param int $bits The number of bits in the codeword.
     * @param int $value The value of the codeword.
     */
    protected function addRawCwd($bits, $value)
    {
        $this->cdws[] = array($bits, $value);
        $this->totbits += $bits;
    }

    /**
     * Adds a Codeword.
     *
     * @param int $mode The encoding mode.
     * @param int $value The value to encode.
     */
    protected function addCdw($mode, $value)
    {
        $this->addRawCwd(Data::MODE_BITS[$mode], $value);
    }

    /**
     * Latch to another mode.
     *
     * @param int $mode The new encoding mode.
     */
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

    /**
     * Shift to another mode.
     *
     * @param int $mode The new encoding mode.
     */
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

    /**
     * Adds the FLG (Function Length Group) codeword to the data codewords.
     *
     * @param int $eci Extended Channel Interpretation value. If negative, the function does nothing.
     */
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

    /**
     * Returns the character mode for a given ASCII code.
     *
     * @param int $ord The ASCII code of the character.
     *
     * @return int The character mode.
     */
    protected function charMode($ord)
    {
        return isset(DATA::CHAR_MODES[$ord]) ? DATA::CHAR_MODES[$ord] : Data::MODE_BINARY;
    }
}
