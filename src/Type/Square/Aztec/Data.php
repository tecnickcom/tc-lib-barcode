<?php

/**
 * Data.php
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

use Com\Tecnick\Barcode\Exception as BarcodeException;

/**
 * Com\Tecnick\Barcode\Type\Square\Aztec\Data
 *
 * Data for Aztec Barcode type class
 *
 * @since       2023-10-13
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class Data
{
    /**
     * Maximum number of layers supported by the compact core.
     */
    const LAYERS_MAX_FULL_COMPACT = 4;

    /**
     * Maximum number of layers supported by the full core.
     */
    const LAYERS_MAX_FULL_MAX = 32;

    /**
     * Code character encoding mode for uppercase letters.
     */
    const MODE_UPPER = 0;

    /**
     * Code character encoding mode for lowercase letters.
     */
    const MODE_LOWER = 1;

    /**
     * Code character encoding mode for digits.
     */
    const MODE_DIGIT = 2;

    /**
     * Code character encoding mode for mixed cases.
     */
    const MODE_MIXED = 3;

    /**
     * Code character encoding mode for punctuation.
     */
    const MODE_PUNCT = 4;

    /**
     * Code character encoding mode for binary.
     */
    const MODE_BINARY = 5;

    /**
     * Number of bits for each character encoding mode.
     */
    const MODE_BITS = array(
        5, // 0 = MODE_UPPER
        5, // 1 = MODE_LOWER
        4, // 2 = MODE_DIGIT
        5, // 3 = MODE_MIXED
        5, // 4 = MODE_PUNCT
        8  // 5 = MODE_BINARY
    );

    /**
     * Code character encoding for each mode.
     */
    const CHAR_ENC = array(
        // MODE_UPPER (initial mode)
        0 => array(
          32 =>  1, // ' ' (SP)
          65 =>  2, // 'A'
          66 =>  3, // 'B'
          67 =>  4, // 'C'
          68 =>  5, // 'D'
          69 =>  6, // 'E'
          70 =>  7, // 'F'
          71 =>  8, // 'G'
          72 =>  9, // 'H'
          73 => 10, // 'I'
          74 => 11, // 'J'
          75 => 12, // 'K'
          76 => 13, // 'L'
          77 => 14, // 'M'
          78 => 15, // 'N'
          79 => 16, // 'O'
          80 => 17, // 'P'
          81 => 18, // 'Q'
          82 => 19, // 'R'
          83 => 20, // 'S'
          84 => 21, // 'T'
          85 => 22, // 'U'
          86 => 23, // 'V'
          87 => 24, // 'W'
          88 => 25, // 'X'
          89 => 26, // 'Y'
          90 => 27  // 'Z'
        ),
        // MODE_LOWER
        1 => array(
           32 =>  1, // ' ' (SP)
           97 =>  2, // 'a'
           98 =>  3, // 'b'
           99 =>  4, // 'c'
          100 =>  5, // 'd'
          101 =>  6, // 'e'
          102 =>  7, // 'f'
          103 =>  8, // 'g'
          104 =>  9, // 'h'
          105 => 10, // 'i'
          106 => 11, // 'j'
          107 => 12, // 'k'
          108 => 13, // 'l'
          109 => 14, // 'm'
          110 => 15, // 'n'
          111 => 16, // 'o'
          112 => 17, // 'p'
          113 => 18, // 'q'
          114 => 19, // 'r'
          115 => 20, // 's'
          116 => 21, // 't'
          117 => 22, // 'u'
          118 => 23, // 'v'
          119 => 24, // 'w'
          120 => 25, // 'x'
          121 => 26, // 'y'
          122 => 27  // 'z'
        ),
        // MODE_DIGIT
        2 => array(
          32 =>  1, // ' ' (SP)
          44 => 12, // ','
          46 => 13, // '.'
          48 =>  2, // '0'
          49 =>  3, // '1'
          50 =>  4, // '2'
          51 =>  5, // '3'
          52 =>  6, // '4'
          53 =>  7, // '5'
          54 =>  8, // '6'
          55 =>  9, // '7'
          56 => 10, // '8'
          57 => 11  // '9'
        ),
        // MODE_MIXED
        3 => array(
            1 =>  2, // '^A' (SOH)
            2 =>  3, // '^B' (STX)
            3 =>  4, // '^C' (ETX)
            4 =>  5, // '^D' (EOT)
            5 =>  6, // '^E' (ENQ)
            6 =>  7, // '^F' (ACK)
            7 =>  8, // '^G' (BEL)
            8 =>  9, // '^H' (BS)
            9 => 10, // '^I' (HT)
           10 => 11, // '^J' (LF)
           11 => 12, // '^K' (VT)
           12 => 13, // '^L' (FF)
           13 => 14, // '^M' (CR)
           27 => 15, // '^[' (ESC)
           28 => 16, // '^\' (FS)
           29 => 17, // '^]' (GS)
           30 => 18, // '^^' (RS)
           31 => 19, // '^_' (US)
           64 => 20, // '@'
           92 => 21, // '\'
           94 => 22, // '^'
           95 => 23, // '_'
           96 => 24, // '`'
          124 => 25, // '|'
          126 => 26, // '~'
          177 => 27  // '±'
        ),
        // MODE_PUNCT
        4 => array(
           13 =>  1, // '\r' (CR)
           33 =>  6, // '!'
           34 =>  7, // '"'
           35 =>  8, // '#'
           36 =>  9, // '$'
           37 => 10, // '%'
           38 => 11, // '&'
           39 => 12, // '''
           40 => 13, // '('
           41 => 14, // ')'
           42 => 15, // '*'
           43 => 16, // '+'
           44 => 17, // ','
           45 => 18, // '-'
           46 => 19, // '.'
           47 => 20, // '/'
           58 => 21, // ':'
           59 => 22, // ';'
           60 => 23, // '<'
           61 => 24, // '='
           62 => 25, // '>'
           63 => 26, // '?'
           91 => 27, // '['
           93 => 28, // ']'
          123 => 29, // '{'
          125 => 30  // '}'
        ),
        // MODE_BINARY (all 8-bit values are valid)
        5 => array()
    );

    /**
     * Map character ASCII codes to supported non-binary modes.
     */
    const CHAR_MODES = array(
        1 => array(3),     // '^A' (SOH)
        2 => array(3),     // '^B' (STX)
        3 => array(3),     // '^C' (ETX)
        4 => array(3),     // '^D' (EOT)
        5 => array(3),     // '^E' (ENQ)
        6 => array(3),     // '^F' (ACK)
        7 => array(3),     // '^G' (BEL)
        8 => array(3),     // '^H' (BS)
        9 => array(3),     // '^I' (HT)
       10 => array(3),     // '^J' (LF)
       11 => array(3),     // '^K' (VT)
       12 => array(3),     // '^L' (FF)
       13 => array(3,4),   // '^M' (CR)
       27 => array(3),     // '^[' (ESC)
       28 => array(3),     // '^\' (FS)
       29 => array(3),     // '^]' (GS)
       30 => array(3),     // '^^' (RS)
       31 => array(3),     // '^_' (US)
       32 => array(0,1,2), // ' '
       33 => array(4),     // '!'
       34 => array(4),     // '"'
       35 => array(4),     // '#'
       36 => array(4),     // '$'
       37 => array(4),     // '%'
       38 => array(4),     // '&'
       39 => array(4),     // '''
       40 => array(4),     // '('
       41 => array(4),     // ')'
       42 => array(4),     // '*'
       43 => array(4),     // '+'
       44 => array(2,4),   // ','
       45 => array(4),     // '-'
       46 => array(2,4),   // '.'
       47 => array(4),     // '/'
       48 => array(2),     // '0'
       49 => array(2),     // '1'
       50 => array(2),     // '2'
       51 => array(2),     // '3'
       52 => array(2),     // '4'
       53 => array(2),     // '5'
       54 => array(2),     // '6'
       55 => array(2),     // '7'
       56 => array(2),     // '8'
       57 => array(2),     // '9'
       58 => array(4),     // ':'
       59 => array(4),     // ';'
       60 => array(4),     // '<'
       61 => array(4),     // '='
       62 => array(4),     // '>'
       63 => array(4),     // '?'
       64 => array(3),     // '@'
       65 => array(0),     // 'A'
       66 => array(0),     // 'B'
       67 => array(0),     // 'C'
       68 => array(0),     // 'D'
       69 => array(0),     // 'E'
       70 => array(0),     // 'F'
       71 => array(0),     // 'G'
       72 => array(0),     // 'H'
       73 => array(0),     // 'I'
       74 => array(0),     // 'J'
       75 => array(0),     // 'K'
       76 => array(0),     // 'L'
       77 => array(0),     // 'M'
       78 => array(0),     // 'N'
       79 => array(0),     // 'O'
       80 => array(0),     // 'P'
       81 => array(0),     // 'Q'
       82 => array(0),     // 'R'
       83 => array(0),     // 'S'
       84 => array(0),     // 'T'
       85 => array(0),     // 'U'
       86 => array(0),     // 'V'
       87 => array(0),     // 'W'
       88 => array(0),     // 'X'
       89 => array(0),     // 'Y'
       90 => array(0),     // 'Z'
       91 => array(4),     // '['
       92 => array(3),     // '\'
       93 => array(4),     // ']'
       94 => array(3),     // '^'
       95 => array(3),     // '_'
       96 => array(3),     // '`'
       97 => array(1),     // 'a'
       98 => array(1),     // 'b'
       99 => array(1),     // 'c'
      100 => array(1),     // 'd'
      101 => array(1),     // 'e'
      102 => array(1),     // 'f'
      103 => array(1),     // 'g'
      104 => array(1),     // 'h'
      105 => array(1),     // 'i'
      106 => array(1),     // 'j'
      107 => array(1),     // 'k'
      108 => array(1),     // 'l'
      109 => array(1),     // 'm'
      110 => array(1),     // 'n'
      111 => array(1),     // 'o'
      112 => array(1),     // 'p'
      113 => array(1),     // 'q'
      114 => array(1),     // 'r'
      115 => array(1),     // 's'
      116 => array(1),     // 't'
      117 => array(1),     // 'u'
      118 => array(1),     // 'v'
      119 => array(1),     // 'w'
      120 => array(1),     // 'x'
      121 => array(1),     // 'y'
      122 => array(1),     // 'z'
      123 => array(4),     // '{'
      124 => array(3),     // '|'
      125 => array(4),     // '}'
      126 => array(3),     // '~'
      177 => array(3)      // '±'
    );

    /**
     * Latch map for changing character encoding mode.
     * Numbers represent: [number of bits to change, latch code value].
     */
    const LATCH_MAP = array(
        // MODE_UPPER
        0 => array (
          1 => array(array(5,28)), // -> LOWER
          2 => array(array(5,30)), // -> DIGIT
          3 => array(array(5,29)), // -> MIXED
          4 => array(array(5,29),array(5,30)) // -> MIXED -> PUNCT
      ),
      // MODE_LOWER
      1 => array (
          0 => array(array(5,30),array(4,14)), // -> DIGIT -> UPPER
          2 => array(array(5,30)), // -> DIGIT
          3 => array(array(5,29)), // -> MIXED
          4 => array(array(5,29),array(5,30)) // -> MIXED -> PUNCT
      ),
      // MODE_DIGIT
      2 => array (
          0 => array(array(4,14)), // -> UPPER
          1 => array(array(4,14),array(5,28)), // -> UPPER -> LOWER
          3 => array(array(4,14),array(5,29)), // -> UPPER -> MIXED
          4 => array(array(4,14),array(5,29),array(5,30)) // -> UPPER -> MIXED -> PUNCT
      ),
      // MODE_MIXED
      3 => array (
          0 => array(array(5,29)), // -> UPPER
          1 => array(array(5,28)), // -> LOWER
          2 => array(array(5,29),array(5,30)), // -> UPPER -> DIGIT
          4 => array(array(5, 30)) // -> PUNCT
      ),
      // MODE_PUNCT
      4 => array (
          0 => array(array(5,31)), // -> UPPER
          1 => array(array(5,31),array(5,28)), // -> UPPER -> LOWER
          2 => array(array(5,31),array(5,30)), // -> UPPER -> DIGIT
          3 => array(array(5,31),array(5,29)), // -> UPPER -> MIXED
      )
    );

    /**
     * Shift map for changing character encoding mode.
     * Numbers represent: [number of bits to change, shift code value].
     */
    const SHIFT_MAP = array(
      // MODE_UPPER
      0 => array(
        1 => array(),
        2 => array(),
        3 => array(),
        4 => array(array(5,0)), // -> PUNCT
        5 => array(array(5,31)) // -> BINARY
      ),
      // MODE_LOWER
      1 => array (
        0 => array(array(5,28)), // -> UPPER
        2 => array(),
        3 => array(),
        4 => array(array(5,0)), // -> PUNCT
        5 => array(array(5,31)) // -> BINARY
      ),
      // MODE_DIGIT
      2 => array (
        0 => array(array(4,15)), // -> UPPER
        1 => array(),
        3 => array(),
        4 => array(array(4,0)), // -> PUNCT
        5 => array(array(4,14),array(5,31)) // -> LATCH UPPER -> BINARY
      ),
      // MODE_MIXED
      3 => array (
        0 => array(),
        1 => array(),
        2 => array(),
        4 => array(array(5,0)), // -> PUNCT
        5 => array(array(5,31)) // -> BINARY
      ),
      // MODE_PUNCT
      4 => array (
        0 => array(),
        1 => array(),
        2 => array(),
        3 => array(),
        5 => array(array(5,31),array(5,31)) // -> LATCH UPPER -> BINARY
      )
    );
}
