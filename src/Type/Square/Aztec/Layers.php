<?php

/**
 * Layers.php
 *
 * @since       2023-10-13
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode\Type\Square\Aztec;

use Com\Tecnick\Barcode\Type\Square\Aztec\Data;
use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Square\Aztec\Layers
 *
 * Layers for Aztec Barcode type class
 *
 * @since       2023-10-13
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
abstract class Layers extends \Com\Tecnick\Barcode\Type\Square\Aztec\Codeword
{
    /**
     * Number of data layers.
     *
     * @var int
     */
    protected $layers = 0;

    /**
     * True for compact mode (up to 4 layers), false for full-range mode (up to 32 layers).
     *
     * @var bool
     */
    protected $compact = true;

    /**
     * Returns the minimum number of layers required.
     *
     * @param array $data Either the Data::SIZE_COMPACT or Data::SIZE_FULL array.
     *
     * @return int
     */
    protected function getMinLayers($data)
    {
        if ($this->totbits > $data[count($data)][3]) {
            return 0;
        }
        foreach ($data as $layers => $size) {
            if ($this->totbits <= $size[3]) {
                return $layers;
            }
        }
        return 0;
    }

    protected function computeSize($ecc, $usrlayers = 0)
    {
        $this->eccbits = 11 + intval(($this->totbits * $ecc) / 100);
        $this->totbits += $this->eccbits;

        $this->compact = true;
        $this->layers = $this->getMinLayers(Data::SIZE_COMPACT);
        if ($this->layers == 0) {
            $this->layers = $this->getMinLayers(Data::SIZE_FULL);
            $this->compact = false;
        }

        if ($this->layers == 0) {
            throw new BarcodeException('Data too long for Aztec');
        }

        if ($usrlayers > 0) {
            if ($this->layers > $usrlayers) {
                throw new BarcodeException('Not enough layers for this data');
            }
            $this->layers = $usrlayers;
        }
    }
}
