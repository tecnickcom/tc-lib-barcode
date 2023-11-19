<?php

/**
 * Type.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode;

use Com\Tecnick\Color\Pdf;
use Com\Tecnick\Color\Model\Rgb;
use Com\Tecnick\Color\Exception as ColorException;
use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type
 *
 * Barcode Type class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
abstract class Type extends \Com\Tecnick\Barcode\Type\Convert
{
    /**
     * Initialize a new barcode object
     *
     * @param string|array $code    Barcode content
     * @param int    $width   Barcode width in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each column.
     * @param int    $height  Barcode height in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each row.
     * @param string $color   Foreground color in Web notation (color name, or hexadecimal code, or CSS syntax)
     * @param array  $params  Array containing extra parameters for the specified barcode type
     * @param array{int, int, int, int}  $padding Additional padding to add around the barcode (top, right, bottom, left) in user units.
     *                        A negative value indicates the number or rows or columns.
     *
     * @throws BarcodeException in case of error
     * @throws ColorException in case of color error
     */
    public function __construct(
        string|array $code,
        int $width = -1,
        int $height = -1,
        string $color = 'black',
        array $params = [],
        array $padding = [0, 0, 0, 0]
    ) {
        $this->code = $code;
        $this->extcode = $code;
        $this->params = $params;
        $this->setParameters();
        $this->setBars();
        $this->setSize($width, $height, $padding);
        $this->setColor($color);
    }

    /**
     * Set extra (optional) parameters
     */
    protected function setParameters(): void
    {
    }

    /**
     * Set the bars array
     *
     * @throws BarcodeException in case of error
     */
    abstract protected function setBars(): void;

    /**
     * Set the size of the barcode to be exported
     *
     * @param int    $width   Barcode width in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each column.
     * @param int    $height  Barcode height in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each row.
     * @param array{int, int, int, int}  $padding Additional padding to add around the barcode (top, right, bottom, left) in user units.
     *                        A negative value indicates the number or rows or columns.
     */
    public function setSize(
        int $width, 
        int $height, 
        array $padding = [0, 0, 0, 0]
        ): static
    {
        $this->width = intval($width);
        if ($this->width <= 0) {
            $this->width = (abs(min(-1, $this->width)) * $this->ncols);
        }

        $this->height = intval($height);
        if ($this->height <= 0) {
            $this->height = (abs(min(-1, $this->height)) * $this->nrows);
        }

        $this->width_ratio = ($this->width / $this->ncols);
        $this->height_ratio = ($this->height / $this->nrows);

        $this->setPadding($padding);

        return $this;
    }

    /**
     * Set the barcode padding
     *
     * @param array{int, int, int, int} $padding Additional padding to add around the barcode (top, right, bottom, left) in user units.
     *                        A negative value indicates the number or rows or columns.
     *
     * @throws BarcodeException in case of error
     */
    protected function setPadding(array $padding): static
    {
        if (!is_array($padding) || (count($padding) != 4)) {
            throw new BarcodeException('Invalid padding, expecting an array of 4 numbers (top, right, bottom, left)');
        }
        $map = array(
            array('T', $this->height_ratio),
            array('R', $this->width_ratio),
            array('B', $this->height_ratio),
            array('L', $this->width_ratio)
        );
        foreach ($padding as $key => $val) {
            $val = intval($val);
            if ($val < 0) {
                $val = (abs(min(-1, $val)) * $map[$key][1]);
            }
            $this->padding[$map[$key][0]] = $val;
        }

        return $this;
    }

    /**
     * Set the color of the bars.
     * If the color is transparent or empty it will be set to the default black color.
     *
     * @param string $color Foreground color in Web notation (color name, or hexadecimal code, or CSS syntax)
     *
     * @throws ColorException in case of color error
     * @throws BarcodeException in case of empty or transparent color
     */
    public function setColor(string $color): static
    {
        $this->color_obj = $this->getRgbColorObject($color);
        if ($this->color_obj === null) {
            throw new BarcodeException('The foreground color cannot be empty or transparent');
        }
        return $this;
    }

    /**
     * Set the background color
     *
     * @param string $color Background color in Web notation (color name, or hexadecimal code, or CSS syntax)
     *
     * @throws ColorException in case of color error
     */
    public function setBackgroundColor(string $color): static
    {
        $this->bg_color_obj = $this->getRgbColorObject($color);
        return $this;
    }

    /**
     * Get the RGB Color object for the given color representation
     *
     * @param string $color Color in Web notation (color name, or hexadecimal code, or CSS syntax)
     *
     * @return Rgb|null
     *
     * @throws ColorException in case of color error
     */
    protected function getRgbColorObject(string $color): ?Rgb
    {
        $conv = new Pdf();
        $cobj = $conv->getColorObject($color);
        if ($cobj !== null) {
            return new Rgb($cobj->toRgbArray());
        }
        return null;
    }

    /**
     * Get the barcode raw array
     *
     * @return array
     */
    public function getArray(): array
    {
        return array(
            'type'         => $this->type,
            'format'       => $this->format,
            'params'       => $this->params,
            'code'         => $this->code,
            'extcode'      => $this->extcode,
            'ncols'        => $this->ncols,
            'nrows'        => $this->nrows,
            'width'        => $this->width,
            'height'       => $this->height,
            'width_ratio'  => $this->width_ratio,
            'height_ratio' => $this->height_ratio,
            'padding'      => $this->padding,
            'full_width'   => ($this->width + $this->padding['L'] + $this->padding['R']),
            'full_height'  => ($this->height + $this->padding['T'] + $this->padding['B']),
            'color_obj'    => $this->color_obj,
            'bg_color_obj' => $this->bg_color_obj,
            'bars'         => $this->bars
        );
    }

    /**
     * Get the extended code (code + checksum)
     *
     * @return string|array
     */
    public function getExtendedCode(): string|array
    {
        return $this->extcode;
    }

    /**
     * Get the barcode as SVG image object
     */
    public function getSvg(): void
    {
        $data = $this->getSvgCode();
        header('Content-Type: application/svg+xml');
        header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
        header('Pragma: public');
        header('Expires: Thu, 04 jan 1973 00:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: inline; filename="' . md5($data) . '.svg";');
        if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            // the content length may vary if the server is using compression
            header('Content-Length: ' . strlen($data));
        }
        echo $data;
    }

    /**
     * Get the barcode as SVG code
     *
     * @return string SVG code
     */
    public function getSvgCode(): string
    {
        // flags for htmlspecialchars
        $hflag = ENT_NOQUOTES;
        if (defined('ENT_XML1') && defined('ENT_DISALLOWED')) {
            $hflag = ENT_XML1 | ENT_DISALLOWED;
        }
        $width = sprintf('%F', ($this->width + $this->padding['L'] + $this->padding['R']));
        $height = sprintf('%F', ($this->height + $this->padding['T'] + $this->padding['B']));
        $svg = '<?xml version="1.0" standalone="no" ?>' . "\n"
            . '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'
            . "\n"
            . '<svg'
            . ' width="' . $width . '"'
            . ' height="' . $height . '"'
            . ' viewBox="0 0 ' . $width . ' ' . $height . '"'
            . ' version="1.1"'
            . ' xmlns="http://www.w3.org/2000/svg"'
            . '>' . "\n"
            . "\t" . '<desc>' . htmlspecialchars($this->code, $hflag, 'UTF-8') . '</desc>' . "\n";
        if ($this->bg_color_obj !== null) {
            $svg .= "\t" . '<rect'
                . ' x="0"'
                . ' y="0"'
                . ' width="' . $width . '"'
                . ' height="' . $height . '"'
                . ' fill="' . $this->bg_color_obj->getRgbHexColor() . '"'
                . ' stroke="none"'
                . ' stroke-width="0"'
                . ' stroke-linecap="square"'
                . ' />' . "\n";
        }
        $svg .= "\t" . '<g'
            . ' id="bars"'
            . ' fill="' . $this->color_obj->getRgbHexColor() . '"'
            . ' stroke="none"'
            . ' stroke-width="0"'
            . ' stroke-linecap="square"'
            . '>' . "\n";
        $bars = $this->getBarsArray('XYWH');
        foreach ($bars as $rect) {
            $svg .= "\t\t" . '<rect'
                . ' x="' . sprintf('%F', $rect[0]) . '"'
                . ' y="' . sprintf('%F', $rect[1]) . '"'
                . ' width="' . sprintf('%F', $rect[2]) . '"'
                . ' height="' . sprintf('%F', $rect[3]) . '"'
                . ' />' . "\n";
        }
        $svg .= "\t" . '</g>' . "\n"
            . '</svg>' . "\n";
        return $svg;
    }

    /**
     * Get an HTML representation of the barcode.
     *
     * @return string HTML code (DIV block)
     */
    public function getHtmlDiv(): string
    {
        $html = '<div style="'
            . 'width:' . sprintf('%F', ($this->width + $this->padding['L'] + $this->padding['R'])) . 'px;'
            . 'height:' . sprintf('%F', ($this->height + $this->padding['T'] + $this->padding['B'])) . 'px;'
            . 'position:relative;'
            . 'font-size:0;'
            . 'border:none;'
            . 'padding:0;'
            . 'margin:0;';
        if ($this->bg_color_obj !== null) {
            $html .= 'background-color:' . $this->bg_color_obj->getCssColor() . ';';
        }
        $html .= '">' . "\n";
        $bars = $this->getBarsArray('XYWH');
        foreach ($bars as $rect) {
            $html .= "\t" . '<div style="background-color:' . $this->color_obj->getCssColor() . ';'
                . 'left:' . sprintf('%F', $rect[0]) . 'px;'
                . 'top:' . sprintf('%F', $rect[1]) . 'px;'
                . 'width:' . sprintf('%F', $rect[2]) . 'px;'
                . 'height:' . sprintf('%F', $rect[3]) . 'px;'
                . 'position:absolute;'
                . 'border:none;'
                . 'padding:0;'
                . 'margin:0;'
                . '">&nbsp;</div>' . "\n";
        }
        $html .= '</div>' . "\n";
        return $html;
    }

    /**
     * Get Barcode as PNG Image (requires GD or Imagick library)
     */
    public function getPng(): void
    {
        $data = $this->getPngData();
        header('Content-Type: image/png');
        header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
        header('Pragma: public');
        header('Expires: Thu, 04 jan 1973 00:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: inline; filename="' . md5($data) . '.png";');
        if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            // the content length may vary if the server is using compression
            header('Content-Length: ' . strlen($data));
        }
        echo $data;
    }

    /**
     * Get the barcode as PNG image (requires GD or Imagick library)
     *
     * @param bool $imagick If true try to use the Imagick extension
     *
     * @return string PNG image data
     */
    public function getPngData(bool $imagick = true): string
    {
        if ($imagick && extension_loaded('imagick')) {
            return $this->getPngDataImagick();
        }
        $img = $this->getGd();
        ob_start();
        imagepng($img);
        return ob_get_clean();
    }

    /**
     * Get the barcode as PNG image (requires Imagick library)
     *
     * @return string
     *
     * @throws BarcodeException if the Imagick library is not installed
     */
    public function getPngDataImagick(): string
    {
        $img = new \Imagick();
        $width = (int)ceil($this->width + $this->padding['L'] + $this->padding['R']);
        $height = (int)ceil($this->height + $this->padding['T'] + $this->padding['B']);
        $img->newImage($width, $height, 'none', 'png');
        $barcode = new \imagickdraw();
        if ($this->bg_color_obj !== null) {
            $rgbcolor = $this->bg_color_obj->getNormalizedArray(255);
            $bg_color = new \imagickpixel('rgb(' . $rgbcolor['R'] . ',' . $rgbcolor['G'] . ',' . $rgbcolor['B'] . ')');
            $barcode->setfillcolor($bg_color);
            $barcode->rectangle(0, 0, $width, $height);
        }
        $rgbcolor = $this->color_obj->getNormalizedArray(255);
        $bar_color = new \imagickpixel('rgb(' . $rgbcolor['R'] . ',' . $rgbcolor['G'] . ',' . $rgbcolor['B'] . ')');
        $barcode->setfillcolor($bar_color);
        $bars = $this->getBarsArray('XYXY');
        foreach ($bars as $rect) {
            $barcode->rectangle($rect[0], $rect[1], $rect[2], $rect[3]);
        }
        $img->drawimage($barcode);
        return $img->getImageBlob();
    }

    /**
     * Get the barcode as GD image object (requires GD library)
     *
     * @return \GdImage
     *
     * @throws BarcodeException if the GD library is not installed
     */
    public function getGd(): \GdImage
    {
        $width = (int)ceil($this->width + $this->padding['L'] + $this->padding['R']);
        $height = (int)ceil($this->height + $this->padding['T'] + $this->padding['B']);
        $img = imagecreate($width, $height);
        if ($this->bg_color_obj === null) {
            $bgobj = clone $this->color_obj;
            $rgbcolor = $bgobj->invertColor()->getNormalizedArray(255);
            $background_color = imagecolorallocate($img, $rgbcolor['R'], $rgbcolor['G'], $rgbcolor['B']);
            imagecolortransparent($img, $background_color);
        } else {
            $rgbcolor = $this->bg_color_obj->getNormalizedArray(255);
            $bg_color = imagecolorallocate($img, $rgbcolor['R'], $rgbcolor['G'], $rgbcolor['B']);
            imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);
        }
        $rgbcolor = $this->color_obj->getNormalizedArray(255);
        $bar_color = imagecolorallocate($img, $rgbcolor['R'], $rgbcolor['G'], $rgbcolor['B']);
        $bars = $this->getBarsArray('XYXY');
        foreach ($bars as $rect) {
            imagefilledrectangle(
                $img,
                (int)floor($rect[0]),
                (int)floor($rect[1]),
                (int)floor($rect[2]),
                (int)floor($rect[3]),
                $bar_color
            );
        }
        return $img;
    }

    /**
     * Get a raw barcode string representation using characters
     *
     * @param string $space_char Character or string to use for filling empty spaces
     * @param string $bar_char   Character or string to use for filling bars
     *
     * @return string
     */
    public function getGrid(
        string $space_char = '0', 
        string $bar_char = '1'
    ): string
    {
        $raw = $this->getGridArray($space_char, $bar_char);
        $grid = '';
        foreach ($raw as $row) {
            $grid .= implode($row) . "\n";
        }
        return $grid;
    }

    /**
     * Get the array containing all the formatted bars coordinates
     *
     * @param string $type Type of coordinates to return: 'XYXY' or 'XYWH'
     *
     * @return array
     */
    public function getBarsArray(
        string $type = 'XYXY'
    ): array
    {
        $mtd = match ($type) {
            'XYXY' => 'getBarRectXYXY',
            'XYWH' => 'getBarRectXYWH',
        };

        $rect = array();
        foreach ($this->bars as $bar) {
            if (($bar[2] > 0) && ($bar[3] > 0)) {
                $rect[] = $this->$mtd($bar);
            }
        }

        if ($this->nrows > 1) {
            // reprint rotated to cancel row gaps
            $rot = $this->getRotatedBarArray();
            foreach ($rot as $bar) {
                if (($bar[2] > 0) && ($bar[3] > 0)) {
                    $rect[] = $this->$mtd($bar);
                }
            }
        }
        return $rect;
    }
}
