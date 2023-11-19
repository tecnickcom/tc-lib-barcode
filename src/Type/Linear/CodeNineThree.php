<?php

/**
 * CodeNineThree.php
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
 * Com\Tecnick\Barcode\Type\Linear\CodeNineThree;
 *
 * CodeNineThree Barcode type class
 * CODE 93 - USS-93
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class CodeNineThree extends \Com\Tecnick\Barcode\Type\Linear\CodeThreeNineExtCheck
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected const FORMAT = 'C93';

    /**
     * Map characters to barcodes
     *
     * @var array<int, string>
     */
    protected const CHBAR = [
        32  => '311211', // space
        36  => '321111', // $
        37  => '211131', // %
        42  => '111141', // start-stop
        43  => '113121', // +
        45  => '121131', // -
        46  => '311112', // .
        47  => '112131', // /
        48  => '131112', // 0
        49  => '111213', // 1
        50  => '111312', // 2
        51  => '111411', // 3
        52  => '121113', // 4
        53  => '121212', // 5
        54  => '121311', // 6
        55  => '111114', // 7
        56  => '131211', // 8
        57  => '141111', // 9
        65  => '211113', // A
        66  => '211212', // B
        67  => '211311', // C
        68  => '221112', // D
        69  => '221211', // E
        70  => '231111', // F
        71  => '112113', // G
        72  => '112212', // H
        73  => '112311', // I
        74  => '122112', // J
        75  => '132111', // K
        76  => '111123', // L
        77  => '111222', // M
        78  => '111321', // N
        79  => '121122', // O
        80  => '131121', // P
        81  => '212112', // Q
        82  => '212211', // R
        83  => '211122', // S
        84  => '211221', // T
        85  => '221121', // U
        86  => '222111', // V
        87  => '112122', // W
        88  => '112221', // X
        89  => '122121', // Y
        90  => '123111', // Z
        128 => '121221', // ($)
        129 => '311121', // (/)
        130 => '122211', // (+)
        131 => '312111'  // (%)
    ];

    /**
     * Map for extended characters
     *
     * @var array<string>
     */
    protected const EXTCODES = array(
        "\83U",
        "\80A",
        "\80B",
        "\80C",
        "\80D",
        "\80E",
        "\80F",
        "\80G",
        "\80H",
        "\80I",
        "\80J",
        "\80K",
        "\80L",
        "\80M",
        "\80N",
        "\80O",
        "\80P",
        "\80Q",
        "\80R",
        "\80S",
        "\80T",
        "\80U",
        "\80V",
        "\80W",
        "\80X",
        "\80Y",
        "\80Z",
        "\83A",
        "\83B",
        "\83C",
        "\83D",
        "\83E",
        " ",
        "\81A",
        "\81B",
        "\81C",
        "\81D",
        "\81E",
        "\81F",
        "\81G",
        "\81H",
        "\81I",
        "\81J",
        "\81K",
        "\81L",
        "-",
        ".",
        "\81O",
        "0",
        "1",
        "2",
        "3",
        "4",
        "5",
        "6",
        "7",
        "8",
        "9",
        "\81Z",
        "\83F",
        "\83G",
        "\83H",
        "\83I",
        "\83J",
        "\83V",
        "A",
        "B",
        "C",
        "D",
        "E",
        "F",
        "G",
        "H",
        "I",
        "J",
        "K",
        "L",
        "M",
        "N",
        "O",
        "P",
        "Q",
        "R",
        "S",
        "T",
        "U",
        "V",
        "W",
        "X",
        "Y",
        "Z",
        "\83K",
        "\83L",
        "\83M",
        "\83N",
        "\83O",
        "\83W",
        "\82A",
        "\82B",
        "\82C",
        "\82D",
        "\82E",
        "\82F",
        "\82G",
        "\82H",
        "\82I",
        "\82J",
        "\82K",
        "\82L",
        "\82M",
        "\82N",
        "\82O",
        "\82P",
        "\82Q",
        "\82R",
        "\82S",
        "\82T",
        "\82U",
        "\82V",
        "\82W",
        "\82X",
        "\82Y",
        "\82Z",
        "\83P",
        "\83Q",
        "\83R",
        "\83S",
        "\83T"
    );

    /**
     * Characters used for checksum
     *
     * @var array<string>
     */
    protected const CHKSUM = array(
        '0',
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '7',
    '8',
    '9',
        'A',
    'B',
    'C',
    'D',
    'E',
    'F',
    'G',
    'H',
    'I',
    'J',
    'K',
        'L',
    'M',
    'N',
    'O',
    'P',
    'Q',
    'R',
    'S',
    'T',
    'U',
    'V',
        'W',
    'X',
    'Y',
    'Z',
    '-',
    '.',
    ' ',
    '$',
    '/',
    '+',
    '%',
        '<',
    '=',
    '>',
    '?'
    );

    /**
     * Calculate CODE 93 checksum (modulo 47).
     *
     * @param string $code Code to represent.
     *
     * @return string char checksum.
     */
    protected function getChecksum(string $code): string
    {
        // translate special characters
        $code = strtr($code, chr(128) . chr(131) . chr(129) . chr(130), '<=>?');
        $clen = strlen($code);
        // calculate check digit C
        $pck = 1;
        $check = 0;
        for ($idx = ($clen - 1); $idx >= 0; --$idx) {
            $key = array_keys($this::CHKSUM, $code[$idx]);
            $check += ($key[0] * $pck);
            ++$pck;
            if ($pck > 20) {
                $pck = 1;
            }
        }
        $check %= 47;
        $chk = $this::CHKSUM[$check];
        $code .= $chk;
        // calculate check digit K
        $pck = 1;
        $check = 0;
        for ($idx = $clen; $idx >= 0; --$idx) {
            $key = array_keys($this::CHKSUM, $code[$idx]);
            $check += ($key[0] * $pck);
            ++$pck;
            if ($pck > 15) {
                $pck = 1;
            }
        }
        $check %= 47;
        $key = $this::CHKSUM[$check];
        $checksum = $chk . $key;
        // restore special characters
        $checksum = strtr(
            $checksum,
            '<=>?',
            chr(128) . chr(131) . chr(129) . chr(130)
        );
        return $checksum;
    }

    /**
     * Set the bars array.
     *
     * @throws BarcodeException in case of error
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function setBars(): void
    {
        $this->ncols = 0;
        $this->nrows = 1;
        $this->bars = array();
        $this::FORMATCode();
        $clen = strlen($this->extcode);
        for ($chr = 0; $chr < $clen; ++$chr) {
            $char = ord($this->extcode[$chr]);
            for ($pos = 0; $pos < 6; ++$pos) {
                $bar_width = intval($this::CHBAR[$char][$pos]);
                if (($pos % 2) == 0) {
                    $this->bars[] = array($this->ncols, 0, $bar_width, 1);
                }
                $this->ncols += $bar_width;
            }
        }
        $this->bars[] = array($this->ncols, 0, 1, 1);
        $this->ncols += 1;
    }
}
