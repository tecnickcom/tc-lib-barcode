<?php

/**
 * CodeOneOne.php
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

namespace Com\Tecnick\Barcode\Type\Linear;

use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Linear\CodeOneOne;
 *
 * CodeOneOne Barcode type class
 * CODE 11
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class CodeOneOne extends \Com\Tecnick\Barcode\Type\Linear
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected const FORMAT = 'CODE11';

    /**
     * Map characters to barcodes
     *
     * @var array<int|string, string>
     */
    protected const CHBAR = [
        '0' => '111121',
        '1' => '211121',
        '2' => '121121',
        '3' => '221111',
        '4' => '112121',
        '5' => '212111',
        '6' => '122111',
        '7' => '111221',
        '8' => '211211',
        '9' => '211111',
        '-' => '112111',
        'S' => '112211',
    ];

    /**
     * Calculate the checksum.
     *
     * @param string $code Code to represent.
     *
     * @return string char checksum.
     */
    protected function getChecksum(string $code): string
    {
        $len = strlen($code);
        // calculate check digit C
        $ptr = 1;
        $ccheck = 0;
        for ($pos = ($len - 1); $pos >= 0; --$pos) {
            $digit = $code[$pos];
            $dval = $digit == '-' ? 10 : (int) $digit;

            $ccheck += ($dval * $ptr);
            ++$ptr;
            if ($ptr > 10) {
                $ptr = 1;
            }
        }

        $ccheck %= 11;
        if ($ccheck == 10) {
            $ccheck = '-';
        }

        if ($len <= 10) {
            return ((string) $ccheck);
        }

        // calculate check digit K
        $code .= $ccheck;
        $ptr = 1;
        $kcheck = 0;
        for ($pos = $len; $pos >= 0; --$pos) {
            $digit = $code[$pos];
            $dval = $digit == '-' ? 10 : (int) $digit;

            $kcheck += ($dval * $ptr);
            ++$ptr;
            if ($ptr > 9) {
                $ptr = 1;
            }
        }

        $kcheck %= 11;
        return ((string) $ccheck . $kcheck);
    }

    /**
     * Format code
     */
    protected function formatCode(): void
    {
        $this->extcode = 'S' . $this->code . $this->getChecksum($this->code) . 'S';
    }

    /**
     * Set the bars array.
     *
     * @throws BarcodeException in case of error
     */
    protected function setBars(): void
    {
        $this->ncols = 0;
        $this->nrows = 1;
        $this->bars = [];
        $this::FORMATCode();
        $clen = strlen($this->extcode);
        for ($chr = 0; $chr < $clen; ++$chr) {
            $char = $this->extcode[$chr];
            if (! isset($this::CHBAR[$char])) {
                throw new BarcodeException('Invalid character: chr(' . ord($char) . ')');
            }

            for ($pos = 0; $pos < 6; ++$pos) {
                $bar_width = (int) $this::CHBAR[$char][$pos];
                if ((($pos % 2) == 0) && ($bar_width > 0)) {
                    $this->bars[] = [$this->ncols, 0, $bar_width, 1];
                }

                $this->ncols += $bar_width;
            }
        }

        --$this->ncols;
    }
}
