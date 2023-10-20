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
use Com\Tecnick\Barcode\Type\Square\Aztec\Encode;
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
    protected $hint = 'A';

    /**
     *  Mode:
     *    - A = Automatic selection between Compact (priority) and Full Range.
     *    - F = Force Full Range mode.
     *
     * @var string
     */
    protected $mode = 'A';

    /**
     * Extended Channel Interpretation (ECI) code to be added at the beginning of the stream.
     * See Data:ECI for the list of supported codes.
     * NOTE: Even if special FNC1 or ECI flag characters could be inserted
     *       at any points in the stream, this will only be added at the beginning of the stream.
     *
     * @var int
     */
    protected $eci = -1;

    /**
     * Set extra (optional) parameters:
     *     1: ECC     : Error correction code percentage of error check words.
     *                  A minimum of 23% + 3 words is recommended by ISO/IEC 24778:2008a.
     *     2: HINT    : Encoding mode: A=Automatic, B=Binary.
     *     3: LAYERS  : Custom number of layers (0 = auto).
     *     4: ECI     : Extended Channel Interpretation (ECI) code. Use -1 for FNC1. See $this->eci.
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
        if (!isset($this->params[1]) || !in_array($this->params[1], ['A', 'B'])) {
            $this->params[1] = 'A';
        }
        $this->hint = $this->params[1];

        // mode
        if (!isset($this->params[2]) || !in_array($this->params[2], ['A', 'F'])) {
            $this->params[2] = 'A';
        }
        $this->mode = $this->params[2];

        // eci code. Used to set the charset encoding. See $this->eci.
        if (!isset($this->params[3]) || !array_key_exists($this->params[3], Data::ECI)) {
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
        try {
            $enc = new Encode($this->code, $this->ecc, $this->eci, $this->hint, $this->mode);
            $grid = $enc->getGrid();
            $this->processBinarySequence($grid);
        } catch (BarcodeException $e) {
            throw new BarcodeException('AZTEC: ' . $e->getMessage());
        }
    }
}
