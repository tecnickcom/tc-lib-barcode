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
abstract class Type extends \Com\Tecnick\Barcode\Type\Convert
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
     * Additional padding to add around the barcode (top, right, bottom, left) in user units.
     * A negative value indicates the multiplication factor for each row or column.
     *
     * @var array
     */
    protected $padding = array('T' => 0, 'R' => 0, 'B' => 0, 'L' => 0);
    
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
     * @param int    $width   Barcode width in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each column.
     * @param int    $height  Barcode height in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each row.
     * @param string $color   Foreground color in Web notation (color name, or hexadecimal code, or CSS syntax)
     * @param array  $params  Array containing extra parameters for the specified barcode type
     * @param array  $padding Additional padding to add around the barcode (top, right, bottom, left) in user units.
     *                        A negative value indicates the number or rows or columns.
     *
     * @throws BarcodeException in case of error
     * @throws ColorException in case of color error
     */
    public function __construct(
        $code,
        $width = -1,
        $height = -1,
        $color = 'black',
        $params = array(),
        $padding = array(0, 0, 0, 0)
    ) {
        $this->code = $code;
        $this->params = $params;
        $this->setParameters();
        $this->setBars();
        $this->setSize($width, $height, $padding);
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
     * @param int    $width   Barcode width in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each column.
     * @param int    $height  Barcode height in user units (excluding padding).
     *                        A negative value indicates the multiplication factor for each row.
     * @param array  $padding Additional padding to add around the barcode (top, right, bottom, left) in user units.
     *                        A negative value indicates the number or rows or columns.
     */
    public function setSize($width, $height, $padding = array(0, 0, 0, 0))
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
     * @param array  $padding Additional padding to add around the barcode (top, right, bottom, left) in user units.
     *                        A negative value indicates the number or rows or columns.
     *
     * @throws BarcodeException in case of error
     */
    protected function setPadding($padding)
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

        return $this;
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
            'padding'      => $this->padding,
            'full_width'   => ($this->width + $this->padding['L'] + $this->padding['R']),
            'full_height'  => ($this->height + $this->padding['T'] + $this->padding['B']),
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
        // flags for htmlspecialchars
        $hflag = ENT_NOQUOTES;
        if (defined('ENT_XML1')) {
            $hflag = ENT_XML1;
        }
        $svg = '<?xml version="1.0" standalone="no" ?>'."\n"
            .'<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n"
            .'<svg width="'.sprintf('%F', ($this->width + $this->padding['L'] + $this->padding['R']))
            .'" height="'.sprintf('%F', ($this->height + $this->padding['T'] + $this->padding['B'])).'"'
            .' version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n"
            ."\t".'<desc>'.htmlspecialchars($this->code, $hflag, 'UTF-8').'</desc>'."\n"
            ."\t".'<g id="bars" fill="'.$this->color_obj->getCssColor().'"'
            .' stroke="none" stroke-width="0" stroke-linecap="square">'."\n";
        foreach ($this->bars as $bar) {
            if (($bar[2] > 0) && ($bar[3] > 0)) {
                $svg .= "\t\t".'<rect'
                    .' x="'.sprintf('%F', ($this->padding['L'] + ($bar[0] * $this->width_ratio))).'"'
                    .' y="'.sprintf('%F', ($this->padding['T'] + ($bar[1] * $this->height_ratio))).'"'
                    .' width="'.sprintf('%F', ($bar[2] * $this->width_ratio)).'"'
                    .' height="'.sprintf('%F', ($bar[3] * $this->height_ratio)).'"'
                    .' />'."\n";
            }
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
            .'width:'.sprintf('%F', ($this->width + $this->padding['L'] + $this->padding['R'])).'px;'
            .'height:'.sprintf('%F', ($this->height + $this->padding['T'] + $this->padding['B'])).'px;'
            .'position:relative;'
            .'font-size:0;">'."\n";
        foreach ($this->bars as $bar) {
            if (($bar[2] > 0) && ($bar[3] > 0)) {
                $html .= "\t".'<div style="background-color:'.$this->color_obj->getCssColor().';'
                    .'left:'.sprintf('%F', ($this->padding['L'] + ($bar[0] * $this->width_ratio))).'px;'
                    .'top:'.sprintf('%F', ($this->padding['T'] + ($bar[1] * $this->height_ratio))).'px;'
                    .'width:'.sprintf('%F', ($bar[2] * $this->width_ratio)).'px;'
                    .'height:'.sprintf('%F', ($bar[3] * $this->height_ratio)).'px;'
                    .'position:absolute;'
                    .'">&nbsp;</div>'."\n";
            }
        }
        $html .= '</div>'."\n";
        return $html;
    }

    /**
     * Get Barcode as PNG Image (requires GD or Imagick library)
     */
    public function getPng()
    {
        $data = $this->getPngData();
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
     *
     * @return string PNG image data
     */
    public function getPngData($imagick = true)
    {
        if ($imagick && extension_loaded('imagick')) {
            return $this->getPngDataImagick();
        }
        $img = $this->getGd();
        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        return $png;
    }

    /**
     * Get the barcode as PNG image (requires Imagick library)
     *
     * @return object
     *
     * @throws BarcodeException if the Imagick library is not installed
     */
    public function getPngDataImagick()
    {
        $rgbcolor = $this->color_obj->getNormalizedArray(255);
        $bar_color = new \imagickpixel('rgb('.$rgbcolor['R'].','.$rgbcolor['G'].','.$rgbcolor['B'].')');
        $img = new \Imagick();
        $img->newImage(
            ($this->width + $this->padding['L'] + $this->padding['R']),
            ($this->height + $this->padding['T'] + $this->padding['B']),
            'none',
            'png'
        );
        $barcode = new \imagickdraw();
        $barcode->setfillcolor($bar_color);
        foreach ($this->bars as $bar) {
            if (($bar[2] > 0) && ($bar[3] > 0)) {
                $barcode->rectangle(
                    ($this->padding['L'] + ($bar[0] * $this->width_ratio)),
                    ($this->padding['T'] + ($bar[1] * $this->height_ratio)),
                    ($this->padding['L'] + (($bar[0] + $bar[2]) * $this->width_ratio) - 1),
                    ($this->padding['T'] + (($bar[1] + $bar[3]) * $this->height_ratio) - 1)
                );
            }
        }
        $img->drawimage($barcode);
        return $img;
    }

    /**
     * Get the barcode as GD image object (requires GD library)
     *
     * @return object
     *
     * @throws BarcodeException if the GD library is not installed
     */
    public function getGd()
    {
        $rgbcolor = $this->color_obj->getNormalizedArray(255);
        $img = imagecreate(
            ($this->width + $this->padding['L'] + $this->padding['R']),
            ($this->height + $this->padding['T'] + $this->padding['B'])
        );
        $background_color = imagecolorallocate($img, 255, 255, 255);
        imagecolortransparent($img, $background_color);
        $bar_color = imagecolorallocate($img, $rgbcolor['R'], $rgbcolor['G'], $rgbcolor['B']);
        foreach ($this->bars as $bar) {
            if (($bar[2] > 0) && ($bar[3] > 0)) {
                imagefilledrectangle(
                    $img,
                    ($this->padding['L'] + ($bar[0] * $this->width_ratio)),
                    ($this->padding['T'] + ($bar[1] * $this->height_ratio)),
                    ($this->padding['L'] + (($bar[0] + $bar[2]) * $this->width_ratio) - 1),
                    ($this->padding['T'] + (($bar[1] + $bar[3]) * $this->height_ratio) - 1),
                    $bar_color
                );
            }
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
    public function getGrid($space_char = '0', $bar_char = '1')
    {
        $raw = array_fill(0, $this->nrows, array_fill(0, $this->ncols, $space_char));
        foreach ($this->bars as $bar) {
            if (($bar[2] > 0) && ($bar[3] > 0)) {
                for ($vert = 0; $vert < $bar[3]; ++$vert) {
                    for ($horiz = 0; $horiz < $bar[2]; ++$horiz) {
                        $raw[($bar[1] + $vert)][($bar[0] + $horiz)] = $bar_char;
                    }
                }
            }
        }
        $grid = '';
        foreach ($raw as $row) {
            $grid .= implode($row)."\n";
        }
        return $grid;
    }
}
