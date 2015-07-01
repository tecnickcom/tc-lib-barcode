<?php
/**
 * QrCode.php
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

namespace Com\Tecnick\Barcode\Type\Square;

use \Com\Tecnick\Barcode\Exception as BarcodeException;
use \Com\Tecnick\Barcode\Type\Square\QrCode\Data;
use \Com\Tecnick\Barcode\Type\Square\QrCode\ByteStream;
use \Com\Tecnick\Barcode\Type\Square\QrCode\Split;
use \Com\Tecnick\Barcode\Type\Square\QrCode\Encoder;

/**
 * Com\Tecnick\Barcode\Type\Square\QrCode
 *
 * QrCode Barcode type class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
class QrCode extends \Com\Tecnick\Barcode\Type\Square
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = 'QRCODE';

    /**
     * QR code version.
     * The Size of QRcode is defined as version. Version is an integer value from 1 to 40.
     * Version 1 is 21*21 matrix. And 4 modules increases whenever 1 version increases.
     * So version 40 is 177*177 matrix.
     *
     * @var int
     */
    protected $version = 0;

    /**
     * Error correction level
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Encoding mode
     *
     * @var int
     */
    protected $hint = 2;

    /**
     * Boolean flag, if false the input string will be converted to uppercase.
     *
     * @var boolean
     */
    protected $case_sensitive = true;

    /**
     * ByteStream class object
     *
     * @var \Com\Tecnick\Barcode\Type\Square\QrCode\ByteStream
     */
    protected $bsObj;

    /**
     * Set extra (optional) parameters
     */
    protected function setParameters()
    {
        // level
        if (!isset($this->params[0]) || !isset(Data::$errCorrLevels[$this->params[0]])) {
            $this->params[0] = 'L';
        }
        $this->level = Data::$errCorrLevels[$this->params[0]];

        // hint
        if (!isset($this->params[1]) || !isset(Data::$encodingModes[$this->params[1]])) {
            $this->params[1] = '8B';
        }
        $this->hint = Data::$encodingModes[$this->params[1]];

        // version
        if (!isset($this->params[2]) || ($this->params[2] < 0) || ($this->params[2] > Data::QRSPEC_VERSION_MAX)) {
            $this->params[2] = 0;
        }
        $this->version = intval($this->params[2]);

        // case sensitive
        if (!isset($this->params[3])) {
            $this->params[3] = 1;
        }
        $this->case_sensitive = (bool)$this->params[3];
    }

    /**
     * Get the bars array
     *
     * @throws BarcodeException in case of error
     */
    protected function setBars()
    {
        if (empty($this->code)) {
            throw new BarcodeException('Empty input');
        }
        $this->bsObj = new ByteStream($this->hint, $this->version, $this->level);
        // generate the qrcode
        $this->processBinarySequence(
            $this->binarize(
                $this->encodeString($this->code)
            )
        );
    }

    /**
     * Convert the frame in binary form
     *
     * @param array $frame Array to binarize
     *
     * @return array frame in binary form
     */
    protected function binarize($frame)
    {
        $len = count($frame);
        // the frame is square (width = height)
        foreach ($frame as &$frameLine) {
            for ($idx = 0; $idx < $len; ++$idx) {
                $frameLine[$idx] = (ord($frameLine[$idx]) & 1) ? '1' : '0';
            }
        }
        return $frame;
    }

    /**
     * Encode the input string
     *
     * @param string $data input string to encode
     */
    protected function encodeString($data)
    {
        if (!$this->case_sensitive) {
            $data = $this->toUpper($data);
        }
        $split = new Split($this->bsObj, $this->hint, $this->version);
        $datacode = $this->bsObj->getByteStream($split->getSplittedString($data));
        $this->version = $this->bsObj->version;
        $enc = new Encoder($this->version, $this->level);
        return $enc->encodeMask(-1, $datacode);
    }

    /**
     * Convert input string into upper case mode
     *
     * @param string $data Data
     *
     * @return
     */
    protected function toUpper($data)
    {
        $len = strlen($data);
        $pos = 0;
        
        while ($pos < $len) {
            $mode = $this->bsObj->getEncodingMode($data, $pos);
            if ($mode == Data::$encodingModes['KJ']) {
                $pos += 2;
            } else {
                if ((ord($data[$pos]) >= ord('a')) and (ord($data[$pos]) <= ord('z'))) {
                    $data[$pos] = chr(ord($data[$pos]) - 32);
                }
                $pos++;
            }
        }
        return $data;
    }
}
