<?php

/**
 * Aztec.php
 *
 * @since       2023-10-12
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode\Type\Square;

use Com\Tecnick\Barcode\Type\Square\Aztec\Data;
use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Square\Aztec
 *
 * Aztec Barcode type class
 *
 * @since       2023-10-12
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class Aztec extends \Com\Tecnick\Barcode\Type\Square
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = 'AZTEC';

    /**
     * Error correction code percentage of error check words.
     * A minimum of 23% + 3 words is recommended by ISO/IEC 24778:2008a.
     *
     * @var int
     */
    protected $ecc = 33;

    /**
     * Encoding mode
     *
     * @var string
     */
    protected $hint = 'D';

    /**
     * Number of layers (0 = auto)
     *
     * @var int
     */
    protected $layers = 0;

    /**
     * Extended Channel Interpretation (ECI) code.
     * Valid codes are:
     *      0: FNC1, Function 1 character
     *      2: Cp437, Code page 437
     *      3: ISO-8859-1, ISO/IEC 8859-1 - Latin-1 (Default encoding)
     *      4: ISO-8859-2, ISO/IEC 8859-2 - Latin-2
     *      5: ISO-8859-3, ISO/IEC 8859-3 - Latin-3
     *      6: ISO-8859-4, ISO/IEC 8859-4 - Latin-4
     *      7: ISO-8859-5, ISO/IEC 8859-5 - Latin/Cyrillic
     *      8: ISO-8859-6, ISO/IEC 8859-6 - Latin/Arabic
     *      9: ISO-8859-7, ISO/IEC 8859-7 - Latin/Greek
     *     10: ISO-8859-8, ISO/IEC 8859-8 - Latin/Hebrew
     *     11: ISO-8859-9, ISO/IEC 8859-9 - Latin-5
     *     12: ISO-8859-10, ISO/IEC 8859-10 - Latin-6
     *     13: ISO-8859-11, ISO/IEC 8859-11 - Latin/Thai
     *     15: ISO-8859-13, ISO/IEC 8859-13 - Latin-7
     *     16: ISO-8859-14, ISO/IEC 8859-14 - Latin-8 (Celtic)
     *     17: ISO-8859-15, ISO/IEC 8859-15 - Latin-9
     *     18: ISO-8859-16, ISO/IEC 8859-16 - Latin-10
     *     20: Shift JIS
     *     21: Cp1250, Windows-1250 - Superset of Latin-2
     *     22: Cp1251, Windows-1251 - Latin/Cyrillic
     *     23: Cp1252, Windows-1252 - Superset of Latin-1
     *     24: Cp1256, Windows-1256 - Arabic
     *     25: UTF-16BE, UnicodeBig, UnicodeBigUnmarked
     *     26: UTF-8
     *     27: US-ASCII
     *     28: Big5
     *     29: GB18030, GB2312, EUC_CN, GBK
     *     30: EUC-KR
     *
     * @var int
     */
    protected $eci = -1;

    /**
     * Set extra (optional) parameters:
     *     1: ECC - Error correction code percentage of error check words.
     *              A minimum of 23% + 3 words is recommended by ISO/IEC 24778:2008a.
     *     2: HINT - Encoding mode: B=Binary, D=Dynamic.
     *     3: LAYERS - Custom number of layers (0 = auto).
     *     4: ECI - Extended Channel Interpretation (ECI) code. Use -1 for FNC1. See $this->eci.
     */
    protected function setParameters()
    {
        parent::setParameters();

        // ecc percentage
        if (!isset($this->params[0]) || !in_array($this->params[0], range(1, 100))) {
            $this->params[0] = 33;
        }
        $this->ecc = intval($this->params[0]);

        // hint
        if (!isset($this->params[1]) || !in_array($this->params[1], ['B', 'D'])) {
            $this->params[1] = 'D';
        }
        $this->hint = $this->params[1];

        // layers
        if (
            !isset($this->params[2]) ||
            !in_array($this->params[2], range(DATA::LAYERS_MAX_FULL_COMPACT, DATA::LAYERS_MAX_FULL_MAX))
        ) {
            $this->params[0] = 0;
        }
        $this->layers = intval($this->params[2]);

        // eci code. Used to set the charset encoding. See $this->eci.
        if (
            !isset($this->params[3]) ||
            !in_array($this->params[3], array(2,3,4,5,6,7,8,9,10,11,12,13,15,16,17,18,20,21,22,23,24,25,26,27,28,29,30))
        ) {
            $this->params[3] = -1;
        }
        $this->eci = intval($this->params[3]);
    }

    /**
     * Get the bars array
     *
     * @throws BarcodeException in case of error
     */
    protected function setBars()
    {
        if (strlen((string)$this->code) == 0) {
            throw new BarcodeException('Empty input');
        }
    }
}
