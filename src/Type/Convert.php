<?php

/**
 * Convert.php
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

namespace Com\Tecnick\Barcode\Type;

use Com\Tecnick\Color\Model\Rgb as Color;

/**
 * Com\Tecnick\Barcode\Type\Convert
 *
 * Barcode Convert class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
abstract class Convert
{
    /**
     * Barcode type (linear or square)
     *
     * @var string
     */
    protected const TYPE = '';

    /**
     * Barcode format
     *
     * @var string
     */
    protected const FORMAT = '';

    /**
     * Array containing extra parameters for the specified barcode type
     */
    protected array $params = [];

    /**
     * Code to convert (barcode content)
     */
    protected string|array $code = '';

    /**
     * Resulting code after applying checksum etc.
     */
    protected string|array $extcode = '';

    /**
     * Total number of columns
     */
    protected int $ncols = 0;

    /**
     * Total number of rows
     */
    protected int $nrows = 1;

    /**
     * Array containing the position and dimensions of each barcode bar
     * (x, y, width, height)
     */
    protected array $bars = [];

    /**
     * Barcode width
     */
    protected int $width = 0;

    /**
     * Barcode height
     */
    protected int $height = 0;

    /**
     * Additional padding to add around the barcode (top, right, bottom, left) in user units.
     * A negative value indicates the multiplication factor for each row or column.
     *
     * @var array{'T': int, 'R': int, 'B': int, 'L': int}
     */
    protected array $padding = [
        'T' => 0,
        'R' => 0,
        'B' => 0,
        'L' => 0,
    ];

    /**
     * Ratio between the barcode width and the number of rows
     */
    protected float $width_ratio = 0;

    /**
     * Ratio between the barcode height and the number of columns
     */
    protected float $height_ratio = 0;

    /**
     * Foreground Color object
     */
    protected ?Color $color_obj = null;

    /**
     * Backgorund Color object
     */
    protected ?Color $bg_color_obj = null;

    /**
     * Import a binary sequence of comma-separated 01 strings
     *
     * @param string|array $code Code to process
     */
    protected function processBinarySequence(string|array $code): void
    {
        $raw = new Raw($code, $this->width, $this->height);
        $data = $raw->getArray();
        $this->ncols = $data['ncols'];
        $this->nrows = $data['nrows'];
        $this->bars = $data['bars'];
    }

    /**
     * Convert large integer number to hexadecimal representation.
     *
     * @param string $number Number to convert (as string)
     *
     * @return string hexadecimal representation
     */
    protected function convertDecToHex(string $number): string
    {
        if ($number == 0) {
            return '00';
        }

        $hex = [];
        while ($number > 0) {
            $hex[] = strtoupper(dechex((int) bcmod($number, '16')));
            $number = bcdiv($number, '16', 0);
        }

        $hex = array_reverse($hex);
        return implode('', $hex);
    }

    /**
     * Convert large hexadecimal number to decimal representation (string).
     *
     * @param string $hex Hexadecimal number to convert (as string)
     *
     * @return string hexadecimal representation
     */
    protected function convertHexToDec(string $hex): string
    {
        $dec = '0';
        $bitval = '1';
        $len = strlen($hex);
        for ($pos = ($len - 1); $pos >= 0; --$pos) {
            $dec = bcadd($dec, bcmul((string) hexdec($hex[$pos]), $bitval));
            $bitval = bcmul($bitval, '16');
        }

        return $dec;
    }

    /**
     * Get a raw barcode grid array
     *
     * @param string $space_char Character or string to use for filling empty spaces
     * @param string $bar_char   Character or string to use for filling bars
     */
    public function getGridArray(
        string $space_char = '0',
        string $bar_char = '1'
    ): array {
        $raw = array_fill(0, $this->nrows, array_fill(0, $this->ncols, $space_char));
        foreach ($this->bars as $bar) {
            if ($bar[2] <= 0) {
                continue;
            }

            if ($bar[3] <= 0) {
                continue;
            }

            for ($vert = 0; $vert < $bar[3]; ++$vert) {
                for ($horiz = 0; $horiz < $bar[2]; ++$horiz) {
                    $raw[($bar[1] + $vert)][($bar[0] + $horiz)] = $bar_char;
                }
            }
        }

        return $raw;
    }

    /**
     * Returns the bars array ordered by columns
     */
    protected function getRotatedBarArray(): array
    {
        $grid = $this->getGridArray();
        $cols = array_map(...[
            -1 => null,
        ] + $grid);
        $bars = [];
        foreach ($cols as $posx => $col) {
            $prevrow = '';
            $bar_height = 0;
            $col[] = '0';
            for ($posy = 0; $posy <= $this->nrows; ++$posy) {
                if ($col[$posy] != $prevrow) {
                    if ($prevrow == '1') {
                        $bars[] = [$posx, ($posy - $bar_height), 1, $bar_height];
                    }

                    $bar_height = 0;
                }

                ++$bar_height;
                $prevrow = $col[$posy];
            }
        }

        return $bars;
    }

    /**
     * Get the adjusted rectangular coordinates (x1,y1,x2,y2) for the specified bar
     *
     * @param array $bar Raw bar coordinates
     *
     * @return array Bar coordinates
     */
    protected function getBarRectXYXY(array $bar): array
    {
        return [
            ($this->padding['L'] + ($bar[0] * $this->width_ratio)),
            ($this->padding['T'] + ($bar[1] * $this->height_ratio)),
            ($this->padding['L'] + (($bar[0] + $bar[2]) * $this->width_ratio) - 1),
            ($this->padding['T'] + (($bar[1] + $bar[3]) * $this->height_ratio) - 1),
        ];
    }

    /**
     * Get the adjusted rectangular coordinates (x,y,w,h) for the specified bar
     *
     * @param array $bar Raw bar coordinates
     *
     * @return array Bar coordinates
     */
    protected function getBarRectXYWH(array $bar): array
    {
        return [
            ($this->padding['L'] + ($bar[0] * $this->width_ratio)),
            ($this->padding['T'] + ($bar[1] * $this->height_ratio)),
            ($bar[2] * $this->width_ratio),
            ($bar[3] * $this->height_ratio),
        ];
    }
}
