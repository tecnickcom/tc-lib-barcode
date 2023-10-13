<?php

/**
 * Data.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Com\Tecnick\Barcode\Type\Square\Datamatrix;

use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Square\Datamatrix\Data
 *
 * Data for Datamatrix Barcode type class
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2010-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class Data
{
    /**
     * ASCII encoding: ASCII character 0 to 127 (1 byte per CW)
     */
    const ENC_ASCII = 0;

    /**
     * C40 encoding: Upper-case alphanumeric (3/2 bytes per CW)
     */
    const ENC_C40 = 1;

    /**
     * TEXT encoding: Lower-case alphanumeric (3/2 bytes per CW)
     */
    const ENC_TXT = 2;

    /**
     * X12 encoding: ANSI X12 (3/2 byte per CW)
     */
    const ENC_X12 = 3;

    /**
     * EDIFACT encoding: ASCII character 32 to 94 (4/3 bytes per CW)
     */
    const ENC_EDF = 4;

    /**
     * BASE 256 encoding: ASCII character 0 to 255 (1 byte per CW)
     */
    const ENC_BASE256 = 5;

    /**
     * ASCII extended encoding: ASCII character 128 to 255 (1/2 byte per CW)
     */
    const ENC_ASCII_EXT = 6;

    /**
     * ASCII number encoding: ASCII digits (2 bytes per CW)
     */
    const ENC_ASCII_NUM = 7;

    /**
     * Table of Data Matrix ECC 200 Symbol Attributes:
     * <ul><li>SHAPE<ul>
     * <li>total matrix rows (including finder pattern)</li>
     * <li>total matrix cols (including finder pattern)</li>
     * <li>total matrix rows (without finder pattern)</li>
     * <li>total matrix cols (without finder pattern)</li>
     * <li>region data rows (with finder pattern)</li>
     * <li>region data col (with finder pattern)</li>
     * <li>region data rows (without finder pattern)</li>
     * <li>region data col (without finder pattern)</li>
     * <li>horizontal regions</li>
     * <li>vertical regions</li>
     * <li>regions</li>
     * <li>data codewords</li>
     * <li>error codewords</li>
     * <li>blocks</li>
     * <li>data codewords per block</li>
     * <li>error codewords per block</li>
     * </ul></li></ul>
     */
    const SYMBATTR = array(
        'S' => array( // square form
            // 10x10
            array(
                0x00a,
                0x00a,
                0x008,
                0x008,
                0x00a,
                0x00a,
                0x008,
                0x008,
                0x001,
                0x001,
                0x001,
                0x003,
                0x005,
                0x001,
                0x003,
                0x005
            ),
            // 12x12
            array(
                0x00c,
                0x00c,
                0x00a,
                0x00a,
                0x00c,
                0x00c,
                0x00a,
                0x00a,
                0x001,
                0x001,
                0x001,
                0x005,
                0x007,
                0x001,
                0x005,
                0x007
            ),
            // 14x14
            array(
                0x00e,
                0x00e,
                0x00c,
                0x00c,
                0x00e,
                0x00e,
                0x00c,
                0x00c,
                0x001,
                0x001,
                0x001,
                0x008,
                0x00a,
                0x001,
                0x008,
                0x00a
            ),
            // 16x16
            array(
                0x010,
                0x010,
                0x00e,
                0x00e,
                0x010,
                0x010,
                0x00e,
                0x00e,
                0x001,
                0x001,
                0x001,
                0x00c,
                0x00c,
                0x001,
                0x00c,
                0x00c
            ),
            // 18x18
            array(
                0x012,
                0x012,
                0x010,
                0x010,
                0x012,
                0x012,
                0x010,
                0x010,
                0x001,
                0x001,
                0x001,
                0x012,
                0x00e,
                0x001,
                0x012,
                0x00e
            ),
            // 20x20
            array(
                0x014,
                0x014,
                0x012,
                0x012,
                0x014,
                0x014,
                0x012,
                0x012,
                0x001,
                0x001,
                0x001,
                0x016,
                0x012,
                0x001,
                0x016,
                0x012
            ),
            // 22x22
            array(
                0x016,
                0x016,
                0x014,
                0x014,
                0x016,
                0x016,
                0x014,
                0x014,
                0x001,
                0x001,
                0x001,
                0x01e,
                0x014,
                0x001,
                0x01e,
                0x014
            ),
            // 24x24
            array(
                0x018,
                0x018,
                0x016,
                0x016,
                0x018,
                0x018,
                0x016,
                0x016,
                0x001,
                0x001,
                0x001,
                0x024,
                0x018,
                0x001,
                0x024,
                0x018
            ),
            // 26x26
            array(
                0x01a,
                0x01a,
                0x018,
                0x018,
                0x01a,
                0x01a,
                0x018,
                0x018,
                0x001,
                0x001,
                0x001,
                0x02c,
                0x01c,
                0x001,
                0x02c,
                0x01c
            ),
            // 32x32
            array(
                0x020,
                0x020,
                0x01c,
                0x01c,
                0x010,
                0x010,
                0x00e,
                0x00e,
                0x002,
                0x002,
                0x004,
                0x03e,
                0x024,
                0x001,
                0x03e,
                0x024
            ),
            // 36x36
            array(
                0x024,
                0x024,
                0x020,
                0x020,
                0x012,
                0x012,
                0x010,
                0x010,
                0x002,
                0x002,
                0x004,
                0x056,
                0x02a,
                0x001,
                0x056,
                0x02a
            ),
            // 40x40
            array(
                0x028,
                0x028,
                0x024,
                0x024,
                0x014,
                0x014,
                0x012,
                0x012,
                0x002,
                0x002,
                0x004,
                0x072,
                0x030,
                0x001,
                0x072,
                0x030
            ),
            // 44x44
            array(
                0x02c,
                0x02c,
                0x028,
                0x028,
                0x016,
                0x016,
                0x014,
                0x014,
                0x002,
                0x002,
                0x004,
                0x090,
                0x038,
                0x001,
                0x090,
                0x038
            ),
            // 48x48
            array(
                0x030,
                0x030,
                0x02c,
                0x02c,
                0x018,
                0x018,
                0x016,
                0x016,
                0x002,
                0x002,
                0x004,
                0x0ae,
                0x044,
                0x001,
                0x0ae,
                0x044
            ),
            // 52x52
            array(
                0x034,
                0x034,
                0x030,
                0x030,
                0x01a,
                0x01a,
                0x018,
                0x018,
                0x002,
                0x002,
                0x004,
                0x0cc,
                0x054,
                0x002,
                0x066,
                0x02a
            ),
            // 64x64
            array(
                0x040,
                0x040,
                0x038,
                0x038,
                0x010,
                0x010,
                0x00e,
                0x00e,
                0x004,
                0x004,
                0x010,
                0x118,
                0x070,
                0x002,
                0x08c,
                0x038
            ),
            // 72x72
            array(
                0x048,
                0x048,
                0x040,
                0x040,
                0x012,
                0x012,
                0x010,
                0x010,
                0x004,
                0x004,
                0x010,
                0x170,
                0x090,
                0x004,
                0x05c,
                0x024
            ),
            // 80x80
            array(
                0x050,
                0x050,
                0x048,
                0x048,
                0x014,
                0x014,
                0x012,
                0x012,
                0x004,
                0x004,
                0x010,
                0x1c8,
                0x0c0,
                0x004,
                0x072,
                0x030
            ),
            // 88x88
            array(
                0x058,
                0x058,
                0x050,
                0x050,
                0x016,
                0x016,
                0x014,
                0x014,
                0x004,
                0x004,
                0x010,
                0x240,
                0x0e0,
                0x004,
                0x090,
                0x038
            ),
            // 96x96
            array(
                0x060,
                0x060,
                0x058,
                0x058,
                0x018,
                0x018,
                0x016,
                0x016,
                0x004,
                0x004,
                0x010,
                0x2b8,
                0x110,
                0x004,
                0x0ae,
                0x044
            ),
            // 104x104
            array(
                0x068,
                0x068,
                0x060,
                0x060,
                0x01a,
                0x01a,
                0x018,
                0x018,
                0x004,
                0x004,
                0x010,
                0x330,
                0x150,
                0x006,
                0x088,
                0x038
            ),
            // 120x120
            array(
                0x078,
                0x078,
                0x06c,
                0x06c,
                0x014,
                0x014,
                0x012,
                0x012,
                0x006,
                0x006,
                0x024,
                0x41a,
                0x198,
                0x006,
                0x0af,
                0x044
            ),
            // 132x132
            array(
                0x084,
                0x084,
                0x078,
                0x078,
                0x016,
                0x016,
                0x014,
                0x014,
                0x006,
                0x006,
                0x024,
                0x518,
                0x1f0,
                0x008,
                0x0a3,
                0x03e
            ),
            // 144x144
            array(
                0x090,
                0x090,
                0x084,
                0x084,
                0x018,
                0x018,
                0x016,
                0x016,
                0x006,
                0x006,
                0x024,
                0x618,
                0x26c,
                0x00a,
                0x09c,
                0x03e
            )
        ),
        'R' => array( // rectangular form
            // 8x18
            array(
                0x008,
                0x012,
                0x006,
                0x010,
                0x008,
                0x012,
                0x006,
                0x010,
                0x001,
                0x001,
                0x001,
                0x005,
                0x007,
                0x001,
                0x005,
                0x007
            ),
            // 8x32
            array(
                0x008,
                0x020,
                0x006,
                0x01c,
                0x008,
                0x010,
                0x006,
                0x00e,
                0x001,
                0x002,
                0x002,
                0x00a,
                0x00b,
                0x001,
                0x00a,
                0x00b
            ),
            // 12x26
            array(
                0x00c,
                0x01a,
                0x00a,
                0x018,
                0x00c,
                0x01a,
                0x00a,
                0x018,
                0x001,
                0x001,
                0x001,
                0x010,
                0x00e,
                0x001,
                0x010,
                0x00e
            ),
            // 12x36
            array(
                0x00c,
                0x024,
                0x00a,
                0x020,
                0x00c,
                0x012,
                0x00a,
                0x010,
                0x001,
                0x002,
                0x002,
                0x00c,
                0x012,
                0x001,
                0x00c,
                0x012
            ),
            // 16x36
            array(
                0x010,
                0x024,
                0x00e,
                0x020,
                0x010,
                0x012,
                0x00e,
                0x010,
                0x001,
                0x002,
                0x002,
                0x020,
                0x018,
                0x001,
                0x020,
                0x018
            ),
            // 16x48
            array(
                0x010,
                0x030,
                0x00e,
                0x02c,
                0x010,
                0x018,
                0x00e,
                0x016,
                0x001,
                0x002,
                0x002,
                0x031,
                0x01c,
                0x001,
                0x031,
                0x01c
            )
        )
    );

        /**
        * Map encodation modes whit character sets.
        */
    const CHSET_ID = array(
            self::ENC_C40 => 'C40',
            self::ENC_TXT => 'TXT',
            self::ENC_X12 => 'X12'
        );

        /**
        * Basic set of characters for each encodation mode.
        */
    const CHSET = array(
        'C40' => array( // Basic set for C40
            'S1' => 0x00,
            'S2' => 0x01,
            'S3' => 0x02,
            0x20 => 0x03,
            0x30 => 0x04,
            0x31 => 0x05,
            0x32 => 0x06,
            0x33 => 0x07,
            0x34 => 0x08,
            0x35 => 0x09,
            0x36 => 0x0a,
            0x37 => 0x0b,
            0x38 => 0x0c,
            0x39 => 0x0d,
            0x41 => 0x0e,
            0x42 => 0x0f,
            0x43 => 0x10,
            0x44 => 0x11,
            0x45 => 0x12,
            0x46 => 0x13,
            0x47 => 0x14,
            0x48 => 0x15,
            0x49 => 0x16,
            0x4a => 0x17,
            0x4b => 0x18,
            0x4c => 0x19,
            0x4d => 0x1a,
            0x4e => 0x1b,
            0x4f => 0x1c,
            0x50 => 0x1d,
            0x51 => 0x1e,
            0x52 => 0x1f,
            0x53 => 0x20,
            0x54 => 0x21,
            0x55 => 0x22,
            0x56 => 0x23,
            0x57 => 0x24,
            0x58 => 0x25,
            0x59 => 0x26,
            0x5a => 0x27
        ),
        'TXT' => array( // Basic set for TEXT
            'S1' => 0x00,
            'S2' => 0x01,
            'S3' => 0x02,
            0x20 => 0x03,
            0x30 => 0x04,
            0x31 => 0x05,
            0x32 => 0x06,
            0x33 => 0x07,
            0x34 => 0x08,
            0x35 => 0x09,
            0x36 => 0x0a,
            0x37 => 0x0b,
            0x38 => 0x0c,
            0x39 => 0x0d,
            0x61 => 0x0e,
            0x62 => 0x0f,
            0x63 => 0x10,
            0x64 => 0x11,
            0x65 => 0x12,
            0x66 => 0x13,
            0x67 => 0x14,
            0x68 => 0x15,
            0x69 => 0x16,
            0x6a => 0x17,
            0x6b => 0x18,
            0x6c => 0x19,
            0x6d => 0x1a,
            0x6e => 0x1b,
            0x6f => 0x1c,
            0x70 => 0x1d,
            0x71 => 0x1e,
            0x72 => 0x1f,
            0x73 => 0x20,
            0x74 => 0x21,
            0x75 => 0x22,
            0x76 => 0x23,
            0x77 => 0x24,
            0x78 => 0x25,
            0x79 => 0x26,
            0x7a => 0x27
        ),
        'SH1' => array( // Shift 1 set
            0x00 => 0x00,
            0x01 => 0x01,
            0x02 => 0x02,
            0x03 => 0x03,
            0x04 => 0x04,
            0x05 => 0x05,
            0x06 => 0x06,
            0x07 => 0x07,
            0x08 => 0x08,
            0x09 => 0x09,
            0x0a => 0x0a,
            0x0b => 0x0b,
            0x0c => 0x0c,
            0x0d => 0x0d,
            0x0e => 0x0e,
            0x0f => 0x0f,
            0x10 => 0x10,
            0x11 => 0x11,
            0x12 => 0x12,
            0x13 => 0x13,
            0x14 => 0x14,
            0x15 => 0x15,
            0x16 => 0x16,
            0x17 => 0x17,
            0x18 => 0x18,
            0x19 => 0x19,
            0x1a => 0x1a,
            0x1b => 0x1b,
            0x1c => 0x1c,
            0x1d => 0x1d,
            0x1e => 0x1e,
            0x1f => 0x1f
        ),
        'SH2' => array( // Shift 2 set
            0x21 => 0x00,
            0x22 => 0x01,
            0x23 => 0x02,
            0x24 => 0x03,
            0x25 => 0x04,
            0x26 => 0x05,
            0x27 => 0x06,
            0x28 => 0x07,
            0x29 => 0x08,
            0x2a => 0x09,
            0x2b => 0x0a,
            0x2c => 0x0b,
            0x2d => 0x0c,
            0x2e => 0x0d,
            0x2f => 0x0e,
            0x3a => 0x0f,
            0x3b => 0x10,
            0x3c => 0x11,
            0x3d => 0x12,
            0x3e => 0x13,
            0x3f => 0x14,
            0x40 => 0x15,
            0x5b => 0x16,
            0x5c => 0x17,
            0x5d => 0x18,
            0x5e => 0x19,
            0x5f => 0x1a,
            'F1' => 0x1b,
            'US' => 0x1e
        ),
        'S3C' => array( // Shift 3 set for C40
            0x60 => 0x00,
            0x61 => 0x01,
            0x62 => 0x02,
            0x63 => 0x03,
            0x64 => 0x04,
            0x65 => 0x05,
            0x66 => 0x06,
            0x67 => 0x07,
            0x68 => 0x08,
            0x69 => 0x09,
            0x6a => 0x0a,
            0x6b => 0x0b,
            0x6c => 0x0c,
            0x6d => 0x0d,
            0x6e => 0x0e,
            0x6f => 0x0f,
            0x70 => 0x10,
            0x71 => 0x11,
            0x72 => 0x12,
            0x73 => 0x13,
            0x74 => 0x14,
            0x75 => 0x15,
            0x76 => 0x16,
            0x77 => 0x17,
            0x78 => 0x18,
            0x79 => 0x19,
            0x7a => 0x1a,
            0x7b => 0x1b,
            0x7c => 0x1c,
            0x7d => 0x1d,
            0x7e => 0x1e,
            0x7f => 0x1f
        ),
        'S3T' => array( // Shift 3 set for TEXT
            0x60 => 0x00,
            0x41 => 0x01,
            0x42 => 0x02,
            0x43 => 0x03,
            0x44 => 0x04,
            0x45 => 0x05,
            0x46 => 0x06,
            0x47 => 0x07,
            0x48 => 0x08,
            0x49 => 0x09,
            0x4a => 0x0a,
            0x4b => 0x0b,
            0x4c => 0x0c,
            0x4d => 0x0d,
            0x4e => 0x0e,
            0x4f => 0x0f,
            0x50 => 0x10,
            0x51 => 0x11,
            0x52 => 0x12,
            0x53 => 0x13,
            0x54 => 0x14,
            0x55 => 0x15,
            0x56 => 0x16,
            0x57 => 0x17,
            0x58 => 0x18,
            0x59 => 0x19,
            0x5a => 0x1a,
            0x7b => 0x1b,
            0x7c => 0x1c,
            0x7d => 0x1d,
            0x7e => 0x1e,
            0x7f => 0x1f
        ),
        'X12' => array( // Set for X12
            0x0d => 0x00,
            0x2a => 0x01,
            0x3e => 0x02,
            0x20 => 0x03,
            0x30 => 0x04,
            0x31 => 0x05,
            0x32 => 0x06,
            0x33 => 0x07,
            0x34 => 0x08,
            0x35 => 0x09,
            0x36 => 0x0a,
            0x37 => 0x0b,
            0x38 => 0x0c,
            0x39 => 0x0d,
            0x41 => 0x0e,
            0x42 => 0x0f,
            0x43 => 0x10,
            0x44 => 0x11,
            0x45 => 0x12,
            0x46 => 0x13,
            0x47 => 0x14,
            0x48 => 0x15,
            0x49 => 0x16,
            0x4a => 0x17,
            0x4b => 0x18,
            0x4c => 0x19,
            0x4d => 0x1a,
            0x4e => 0x1b,
            0x4f => 0x1c,
            0x50 => 0x1d,
            0x51 => 0x1e,
            0x52 => 0x1f,
            0x53 => 0x20,
            0x54 => 0x21,
            0x55 => 0x22,
            0x56 => 0x23,
            0x57 => 0x24,
            0x58 => 0x25,
            0x59 => 0x26,
            0x5a => 0x27
        )
    );

    /**
     * Get the required codewords padding size
     *
     * @return array params
     *
     * @throws BarcodeException in case of error
     */
    public static function getPaddingSize($shape, $ncw)
    {
        foreach (Data::SYMBATTR[$shape] as $params) {
            if ($params[11] >= $ncw) {
                return $params;
            }
        }
        throw new BarcodeException('Unable to find the correct size');
    }
}
