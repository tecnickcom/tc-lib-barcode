<?php

/**
 * FallbackMath.php
 *
 * @since       2026-08-06
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Fixture;

use Com\Tecnick\Barcode\Math;

/**
 * Forces the pure-PHP arithmetic used when the bcmath extension is not available.
 */
trait FallbackMath
{
    /**
     * @param numeric-string $left
     * @param numeric-string $right
     *
     * @return numeric-string
     */
    protected function addNumeric(string $left, string $right): string
    {
        return Math::fallbackAdd($left, $right);
    }

    /**
     * @param numeric-string $left
     * @param numeric-string $right
     *
     * @return numeric-string
     */
    protected function mulNumeric(string $left, string $right): string
    {
        return Math::fallbackMul($left, $right);
    }

    /**
     * @param numeric-string $left
     * @param numeric-string $right
     *
     * @return numeric-string
     */
    protected function divNumeric(string $left, string $right): string
    {
        return Math::fallbackDiv($left, $right);
    }

    /**
     * @param numeric-string $left
     * @param numeric-string $right
     */
    protected function modNumeric(string $left, string $right): int
    {
        return (int) Math::fallbackMod($left, $right);
    }
}
