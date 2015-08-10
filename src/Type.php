<?php
/**
 * Type.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode;

use \Com\Tecnick\Barcode\Exception as BarcodeException;
use \Com\Tecnick\Color\Exception as ColorException;

/**
 * Com\Tecnick\Barcode\Type
 *
 * Barcode Type class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
abstract class Type
{
    /**
     * Barcode type (linear or square)
     *
     * @var string
     */
    protected $type = '';

    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = '';
    
    /**
     * Array containing extra parameters for the specified barcode type
     *
     * @var array
     */
    protected $params;

    /**
     * Code to convert (barcode content)
     *
     * @var string
     */
    protected $code = '';

    /**
     * Total number of columns
     *
     * @var int
     */
    protected $ncols = 0;

    /**
     * Total number of rows
     *
     * @var int
     */
    protected $nrows = 1;

    /**
     * Array containing the position and dimensions of each barcode bar
     * (x, y, width, height)
     *
     * @var array
     */
    protected $bars = array();

    /**
     * Barcode width
     *
     * @var float
     */
    protected $width;
    
    /**
     * Barcode height
     *
     * @var float
     */
    protected $height;
    
    /**
     * Ratio between the barcode width and the number of rows
     *
     * @var float
     */
    protected $width_ratio;
    
    /**
     * Ratio between the barcode height and the number of columns
     *
     * @var float
     */
    protected $height_ratio;
    
    /**
     * Color object
     *
     * @var Color object
     */
    protected $color_obj;

    /**
     * Initialize a new barcode object
     *
     * @param string $code    Barcode content
     * @param int    $width   Barcode width in user units.
     *                        A negative values indicate the multiplication factor for each column.
     * @param int    $height  Barcode height in user units
     *                        A negative values indicate the multiplication factor for each row.
     * @param string $color   Foreground color in Web notation (color name, or hexadecimal code, or CSS syntax)
     * @param array  $params  Array containing extra parameters for the specified barcode type
     *
     * @throws BarcodeException in case of error
     * @throws ColorException in case of color error
     */
    public function __construct($code, $width = -1, $height = -1, $color = 'black', $params = array())
    {
        $this->code = $code;
        $this->params = $params;
        $this->setParameters();
        $this->setBars();
        $this->setSize($width, $height);
        $this->setColor($color);
    }

    /**
     * Set extra (optional) parameters
     */
    protected function setParameters()
    {
    }

    /**
     * Set the bars array
     *
     * @throws BarcodeException in case of error
     */
    abstract protected function setBars();

    /**
     * Set the size of the barcode to be exported
     *
     * @param int    $width  Barcode width in user units.
     *                       A negative values indicate the multiplication factor for each column.
     * @param int    $height Barcode height in user units
     *                       A negative values indicate the multiplication factor for each row.
     */
    public function setSize($width, $height)
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
    }

    /**
     * Set the color of the bars
     *
     * @param string $color Foreground color in Web notation (color name, or hexadecimal code, or CSS syntax)
     *
     * @throws ColorException in case of color error
     */
    public function setColor($color)
    {
        $webcolor = new \Com\Tecnick\Color\Web();
        $rgb = $webcolor->getColorObj($color)->toRgbArray();
        $this->color_obj = new \Com\Tecnick\Color\Model\Rgb($rgb);
    }

    /**
     * Get the barcode raw array
     *
     * @return array
     */
    public function getArray()
    {
        return array(
            'type'         => $this->type,
            'format'       => $this->format,
            'params'       => $this->params,
            'code'         => $this->code,
            'ncols'        => $this->ncols,
            'nrows'        => $this->nrows,
            'width'        => $this->width,
            'height'       => $this->height,
            'width_ratio'  => $this->width_ratio,
            'height_ratio' => $this->height_ratio,
            'color_obj'    => $this->color_obj,
            'bars'         => $this->bars
        );
    }

    /**
     * Get the barcode as SVG image object
     */
    public function getSvg()
    {
        $data = $this->getSvgCode();
        header('Content-Type: application/svg+xml');
        header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
        header('Pragma: public');
        header('Expires: Thu, 04 jan 1973 00:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Content-Disposition: inline; filename="'.md5($data).'.svg";');
        if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            // the content length may vary if the server is using compression
            header('Content-Length: '.strlen($data));
        }
        echo $data;
    }

    /**
     * Get the barcode as SVG code
     *
     * @return string SVG code
     */
    public function getSvgCode()
    {
        $svg = '<?xml version="1.0" standalone="no" ?>'."\n"
            .'<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n"
            .'<svg width="'.sprintf('%F', $this->width).'" height="'.sprintf('%F', $this->height).'"'
            .' version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n"
            ."\t".'<desc>'.htmlspecialchars($this->code, ENT_XML1, 'UTF-8').'</desc>'."\n"
            ."\t".'<g id="bars" fill="'.$this->color_obj->getCssColor().'"'
            .' stroke="none" stroke-width="0" stroke-linecap="square">'."\n";
        foreach ($this->bars as $bar) {
            if (($bar[2] <= 0) || ($bar[3] <= 0)) {
                continue;
            }
            $svg .= "\t\t".'<rect'
                .' x="'.sprintf('%F', ($bar[0] * $this->width_ratio)).'"'
                .' y="'.sprintf('%F', ($bar[1] * $this->height_ratio)).'"'
                .' width="'.sprintf('%F', ($bar[2] * $this->width_ratio)).'"'
                .' height="'.sprintf('%F', ($bar[3] * $this->height_ratio)).'"'
                .' />'."\n";
        }
        $svg .= "\t".'</g>'."\n".'</svg>'."\n";
        return $svg;
    }

    /**
     * Get an HTML representation of the barcode.
     *
     * @return string HTML code (DIV block)
     */
    public function getHtmlDiv()
    {
        $html = '<div style="'
            .'width:'.sprintf('%F', $this->width).'px;'
            .'height:'.sprintf('%F', $this->height).'px;'
            .'position:relative;'
            .'font-size:0;">'."\n";
        foreach ($this->bars as $bar) {
            if (($bar[2] <= 0) || ($bar[3] <= 0)) {
                continue;
            }
            $html .= "\t".'<div style="background-color:'.$this->color_obj->getCssColor().';'
                .'left:'.sprintf('%F', ($bar[0] * $this->width_ratio)).'px;'
                .'top:'.sprintf('%F', ($bar[1] * $this->height_ratio)).'px;'
                .'width:'.sprintf('%F', ($bar[2] * $this->width_ratio)).'px;'
                .'height:'.sprintf('%F', ($bar[3] * $this->height_ratio)).'px;'
                .'position:absolute;'
                .'">&nbsp;</div>'."\n";
        }
        $html .= '</div>'."\n";
        return $html;
    }

    /**
     * Get Barcode as PNG Image (requires GD or Imagick library)
     *
     * @param int    $border   Additional space around the barcode
     */
    public function getPng($border = 0)
    {
        $data = $this->getPngData(true, $border);
        header('Content-Type: image/png');
        header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
        header('Pragma: public');
        header('Expires: Thu, 04 jan 1973 00:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header('Content-Disposition: inline; filename="'.md5($data).'.png";');
        if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            // the content length may vary if the server is using compression
            header('Content-Length: '.strlen($data));
        }
        echo $data;
    }

    /**
     * Get the barcode as PNG image (requires GD or Imagick library)
     *
     * @param bool $imagick If true try to use the Imagick extension
     * @param int  $border  Additional space around the barcode
     *
     * @return string PNG image data
     */
    public function getPngData($imagick = true, $border = 0)
    {
        if ($imagick && extension_loaded('imagick')) {
            return $this->getPngDataImagick($border);
        }
        $img = $this->getGd($border);
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        return $png;
    }

    /**
     * Get the barcode as PNG image (requires Imagick library)
     *
     * @param int    $border   Additional space around the barcode
     *
     * @return object
     *
     * @throws BarcodeException if the Imagick library is not installed
     */
    public function getPngDataImagick($border = 0)
    {
        $rgbcolor = $this->color_obj->getNormalizedArray(255);
        $bar_color = new \imagickpixel('rgb('.$rgbcolor['R'].','.$rgbcolor['G'].','.$rgbcolor['B'].')');
        $img = new \Imagick();
        $img->newImage($this->width + $border + $border, $this->height + $border + $border, 'none', 'png');
        $barcode = new \imagickdraw();
        $barcode->setfillcolor($bar_color);
        foreach ($this->bars as $bar) {
            if (($bar[2] <= 0) || ($bar[3] <= 0)) {
                continue;
            }
            $barcode->rectangle(
                $border + ($bar[0] * $this->width_ratio),
                $border + ($bar[1] * $this->height_ratio),
                $border + ((($bar[0] + $bar[2]) * $this->width_ratio) - 1),
                $border + ((($bar[1] + $bar[3]) * $this->height_ratio) - 1)
            );
        }
        $img->drawimage($barcode);
        return $img;
    }

    /**
     * Get the barcode as GD image object (requires GD library)
     *
     * @param int    $border   Additional space around the barcode
     *
     * @return object
     *
     * @throws BarcodeException if the GD library is not installed
     */
    public function getGd($border=0)
    {
        $rgbcolor = $this->color_obj->getNormalizedArray(255);
        $img = imagecreate($this->width + $border + $border, $this->height + $border + $border);
        $background_color = imagecolorallocate($img, 255, 255, 255);
        imagecolortransparent($img, $background_color);
        $bar_color = imagecolorallocate($img, $rgbcolor['R'], $rgbcolor['G'], $rgbcolor['B']);
        foreach ($this->bars as $bar) {
            if (($bar[2] <= 0) || ($bar[3] <= 0)) {
                continue;
            }
            imagefilledrectangle(
                $img,
                $border + ($bar[0] * $this->width_ratio),
                $border + ($bar[1] * $this->height_ratio),
                $border + ((($bar[0] + $bar[2]) * $this->width_ratio) - 1),
                $border + ((($bar[1] + $bar[3]) * $this->height_ratio) - 1),
                $bar_color
            );
        }
        return $img;
    }

    /**
     * Get a raw barcode string representation using 0 and 1
     *
     * @param string $space_char Character or string to use for filling empty spaces
     * @param string $bar_char   Character or string to use for filling bars
     *
     * @return string
     */
    public function getGrid($space_char = '0', $bar_char = '1')
    {
        $raw = array_fill(0, $this->nrows, array_fill(0, $this->ncols, $space_char));
        foreach ($this->bars as $bar) {
            if (($bar[2] <= 0) || ($bar[3] <= 0)) {
                continue;
            }
            for ($vert = 0; $vert < $bar[3]; ++$vert) {
                for ($horiz = 0; $horiz < $bar[2]; ++$horiz) {
                    $raw[($bar[1] + $vert)][($bar[0] + $horiz)] = $bar_char;
                }
            }
        }
        $grid = '';
        foreach ($raw as $row) {
            $grid .= implode($row)."\n";
        }
        return $grid;
    }

    /**
     * Import a binary sequence of comma-separated 01 strings
     *
     * @param string $code Code to process
     */
    protected function processBinarySequence($code)
    {
        $raw = new \Com\Tecnick\Barcode\Type\Raw($code, $this->width, $this->height);
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
    protected function convertDecToHex($number)
    {
        $hex = array();
        if ($number == 0) {
            return '00';
        }
        while ($number > 0) {
            array_push($hex, strtoupper(dechex(bcmod($number, '16'))));
            $number = bcdiv($number, '16', 0);
        }
        $hex = array_reverse($hex);
        return implode($hex);
    }

    /**
     * Convert large hexadecimal number to decimal representation (string).
     *
     * @param string $hex Hexadecimal number to convert (as string)
     *
     * @return string hexadecimal representation
     */
    protected function convertHexToDec($hex)
    {
        $dec = 0;
        $bitval = 1;
        $len = strlen($hex);
        for ($pos = ($len - 1); $pos >= 0; --$pos) {
            $dec = bcadd($dec, bcmul(hexdec($hex[$pos]), $bitval));
            $bitval = bcmul($bitval, 16);
        }
        return $dec;
    }
}
