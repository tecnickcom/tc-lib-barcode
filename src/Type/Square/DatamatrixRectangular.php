<?php
/**
 * DatamatrixR.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2020 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode\Type\Square;

use \Com\Tecnick\Barcode\Exception as BarcodeException;
use \Com\Tecnick\Barcode\Type\Square\Datamatrix\Data;
use \Com\Tecnick\Barcode\Type\Square\Datamatrix\Encode;

/**
 * Com\Tecnick\Barcode\Type\Square\DatamatrixRectangular
 *
 * Datamatrix Rectangular Barcode type class
 * DATAMATRIXR (ISO/IEC 16022)
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2016 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class DatamatrixRectangular extends \Com\Tecnick\Barcode\Type\Square\Datamatrix
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = 'DATAMATRIXR';

    /**
     * Get the codewords
     *
     * @return array params
     *
     * @throws BarcodeException in case of error
     */
    protected function getCodewords()
    {
        if (strlen((string)$this->code) == 0) {
            throw new BarcodeException('Empty input');
        }

        // get data codewords
        $this->cdw = $this->getHighLevelEncoding($this->code);

        // number of data codewords
        $ncw = count($this->cdw);

        // check size
        if ($ncw > 1560) {
            throw new BarcodeException('the input is too large to fit the barcode');
        }

        // get minimum required matrix size.
        foreach (Data::$symbattr_rectangular as $params) {
            if ($params[11] >= $ncw) {
                break;
            }
        }
        if ($params[11] > $ncw) {
            $this->addPadding($params[11], $ncw);
        }

        $errorCorrection = new \Com\Tecnick\Barcode\Type\Square\Datamatrix\ErrorCorrection;
        $this->cdw = $errorCorrection->getErrorCorrection($this->cdw, $params[13], $params[14], $params[15]);

        return $params;
    }
}
