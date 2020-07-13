<?php
/**
 * GS1Datamatrix.php
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
 * Com\Tecnick\Barcode\Type\Square\GS1Datamatrix
 *
 * GS1 Datamatrix Barcode type class
 * GS1DATAMATRIX (ISO/IEC 16022)
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2016 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class GS1Datamatrix extends \Com\Tecnick\Barcode\Type\Square\Datamatrix
{

    /**
     * Get high level encoding using the minimum symbol data characters for ECC 200
     *
     * @param $data (string) data to encode
     *
     * @return array of codewords
     */
    protected function getHighLevelEncoding($data)
    {
        // STEP A. Start in ASCII encodation.
        $enc = Data::ENC_ASCII; // current encoding mode
        $this->dmx->last_enc = $enc; // last used encoding
        $pos = 0; // current position
        $cdw = array(); // array of codewords to be returned
        $cdw_num = 0; // number of data codewords
        $data_length = strlen($data); // number of chars
        while ($pos < $data_length) {
            // Determine if current char is FNC1 (don't encode it, just pass it through)
            if($data[$pos] == chr(232)){
              $cdw[] = 232;
              ++$pos;
              ++$cdw_num;
              continue;
            }
            switch ($enc) {
                case Data::ENC_ASCII:
                    // STEP B. While in ASCII encodation
                    $this->dmx->encodeASCII($cdw, $cdw_num, $pos, $data_length, $data, $enc);
                    break;
                case Data::ENC_C40:
                    // Upper-case alphanumeric
                case Data::ENC_TXT:
                    // Lower-case alphanumeric
                case Data::ENC_X12:
                    // ANSI X12
                    $this->dmx->encodeTXT($cdw, $cdw_num, $pos, $data_length, $data, $enc);
                    break;
                case Data::ENC_EDF:
                    // F. While in EDIFACT (EDF) encodation
                    $this->dmx->encodeEDF($cdw, $cdw_num, $pos, $data_length, $field_length, $data, $enc);
                    break;
                case Data::ENC_BASE256:
                    // G. While in Base 256 (B256) encodation
                    $this->dmx->encodeBase256($cdw, $cdw_num, $pos, $data_length, $field_length, $data, $enc);
                    break;
            }
            $this->dmx->last_enc = $enc;
        }
        return $cdw;
    }
}
