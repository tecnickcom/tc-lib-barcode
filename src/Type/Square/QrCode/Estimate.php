<?php

/**
 * Estimate.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode\Type\Square\QrCode;

use Com\Tecnick\Barcode\Exception as BarcodeException;
use Com\Tecnick\Barcode\Type\Square\QrCode\Data;

/**
 * Com\Tecnick\Barcode\Type\Square\QrCode\Estimate
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
abstract class Estimate
{
    /**
     * Encoding mode
     *
     * @var int
     */
    protected $hint = 2;

    /**
     * QR code version.
     * The Size of QRcode is defined as version. Version is an integer value from 1 to 40.
     * Version 1 is 21*21 matrix. And 4 modules increases whenever 1 version increases.
     * So version 40 is 177*177 matrix.
     *
     * @var int
     */
    public $version = 0;

    /**
     * Error correction level
     *
     * @var int
     */
    protected $level = 0;


    /**
     * Return the size of length indicator for the mode and version
     *
     * @param int $mode    Encoding mode
     * @param int $version Version
     *
     * @return int the size of the appropriate length indicator (bits).
     */
    public function getLengthIndicator($mode, $version)
    {
        if ($mode == Data::ENC_MODES['ST']) {
            return 0;
        }
        if ($version <= 9) {
            $len = 0;
        } elseif ($version <= 26) {
            $len = 1;
        } else {
            $len = 2;
        }
        return Data::LEN_TABLE_BITS[$mode][$len];
    }

    /**
     * estimateBitsModeNum
     *
     * @param int $size
     *
     * @return int number of bits
     */
    public function estimateBitsModeNum($size)
    {
        $wdt = (int)($size / 3);
        $bits = ($wdt * 10);
        switch ($size - ($wdt * 3)) {
            case 1:
                $bits += 4;
                break;
            case 2:
                $bits += 7;
                break;
        }
        return $bits;
    }

    /**
     * estimateBitsModeAn
     *
     * @param int $size
     *
     * @return int number of bits
     */
    public function estimateBitsModeAn($size)
    {
        $bits = (int)($size * 5.5); // (size / 2 ) * 11
        if ($size & 1) {
            $bits += 6;
        }
        return $bits;
    }

    /**
     * estimateBitsMode8
     *
     * @param int $size
     *
     * @return int number of bits
     */
    public function estimateBitsMode8($size)
    {
        return (int)($size * 8);
    }

    /**
     * estimateBitsModeKanji
     *
     * @param int $size
     *
     * @return int number of bits
     */
    public function estimateBitsModeKanji($size)
    {
        return (int)($size * 6.5); // (size / 2 ) * 13
    }

    /**
     * Estimate version
     *
     * @param array $items
     * @param int   $level
     *
     * @return int version
     */
    public function estimateVersion($items, $level)
    {
        $version = 0;
        $prev = 0;
        do {
            $prev = $version;
            $bits = $this->estimateBitStreamSize($items, $prev);
            $version = $this->getMinimumVersion((int)(($bits + 7) / 8), $level);
            if ($version < 0) {
                return -1;
            }
        } while ($version > $prev);
        return $version;
    }

    /**
     * Return a version number that satisfies the input code length.
     *
     * @param int $size  Input code length (bytes)
     * @param int $level Error correction level
     *
     * @return int version number
     *
     * @throws BarcodeException
     */
    protected function getMinimumVersion($size, $level)
    {
        for ($idx = 1; $idx <= Data::QRSPEC_VERSION_MAX; ++$idx) {
            $words = (Data::CAPACITY[$idx][Data::QRCAP_WORDS] - Data::CAPACITY[$idx][Data::QRCAP_EC][$level]);
            if ($words >= $size) {
                return $idx;
            }
        }
        throw new BarcodeException(
            'The size of input data is greater than Data::QR capacity, try to lower the error correction mode'
        );
    }

    /**
     * estimateBitStreamSize
     *
     * @param array $items
     * @param int   $version
     *
     * @return int bits
     */
    protected function estimateBitStreamSize($items, $version)
    {
        $bits = 0;
        if ($version == 0) {
            $version = 1;
        }
        foreach ($items as $item) {
            switch ($item['mode']) {
                case Data::ENC_MODES['NM']:
                    $bits = $this->estimateBitsModeNum($item['size']);
                    break;
                case Data::ENC_MODES['AN']:
                    $bits = $this->estimateBitsModeAn($item['size']);
                    break;
                case Data::ENC_MODES['8B']:
                    $bits = $this->estimateBitsMode8($item['size']);
                    break;
                case Data::ENC_MODES['KJ']:
                    $bits = $this->estimateBitsModeKanji($item['size']);
                    break;
                case Data::ENC_MODES['ST']:
                    return Data::STRUCTURE_HEADER_BITS;
                default:
                    return 0;
            }
            $len = $this->getLengthIndicator($item['mode'], $version);
            $mod = 1 << $len;
            $num = (int)(($item['size'] + $mod - 1) / $mod);
            $bits += $num * (4 + $len);
        }
        return $bits;
    }
}
