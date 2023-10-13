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
     * Code character encoding for each mode.
     */
    const CHAR_ENC = array(
        // MODE_UPPER (initial mode)
        0 => array(
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,
              1, //  32 ' ' (SP)
            0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,
              2, //  65 'A'
              3, //  66 'B'
              4, //  67 'C'
              5, //  68 'D'
              6, //  69 'E'
              7, //  70 'F'
              8, //  71 'G'
              9, //  72 'H'
             10, //  73 'I'
             11, //  74 'J'
             12, //  75 'K'
             13, //  76 'L'
             14, //  77 'M'
             15, //  78 'N'
             16, //  79 'O'
             17, //  80 'P'
             18, //  81 'Q'
             19, //  82 'R'
             20, //  83 'S'
             21, //  84 'T'
             22, //  85 'U'
             23, //  86 'V'
             24, //  87 'W'
             25, //  88 'X'
             26, //  89 'Y'
             27, //  90 'Z'
            0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0
        ),
        // MODE_LOWER
        1 => array(
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,
              1, //  32 ' ' (SP)
            0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,
              2, //  97 'a'
              3, //  98 'b'
              4, //  99 'c'
              5, // 100 'd'
              6, // 101 'e'
              7, // 102 'f'
              8, // 103 'g'
              9, // 104 'h'
             10, // 105 'i'
             11, // 106 'j'
             12, // 107 'k'
             13, // 108 'l'
             14, // 109 'm'
             15, // 110 'n'
             16, // 111 'o'
             17, // 112 'p'
             18, // 113 'q'
             19, // 114 'r'
             20, // 115 's'
             21, // 116 't'
             22, // 117 'u'
             23, // 118 'v'
             24, // 119 'w'
             25, // 120 'x'
             26, // 121 'y'
             27, // 122 'z'
            0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0
        ),
        // MODE_DIGIT
        2 => array(
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,
              1, //  32 ' ' (SP)
            0,0,0,0,0,0,0,
            0,0,0,0,
             12, //  44 ','
            0,
             13, //  46 '.'
            0,
              2, //  48 '0'
              3, //  49 '1'
              4, //  50 '2'
              5, //  51 '3'
              6, //  52 '4'
              7, //  53 '5'
              8, //  54 '6'
              9, //  55 '7'
             10, //  56 '8'
             11, //  57 '9'
            0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0
        ),
        // MODE_MIXED
        3 => array(
            0,
              2, //   1 '^A' (SOH)
              3, //   2 '^B' (STX)
              4, //   3 '^C' (ETX)
              5, //   4 '^D' (EOT)
              6, //   5 '^E' (ENQ)
              7, //   6 '^F' (ACK)
              8, //   7 '^G' (BEL)
              9, //   8 '^H' (BS)
             10, //   9 '^I' (HT)
             11, //  10 '^J' (LF)
             12, //  11 '^K' (VT)
             13, //  12 '^L' (FF)
             14, //  13 '^M' (CR)
            0,0,0,0,0,0,
            0,0,0,0,0,0,0,
             15, //  27 '^[' (ESC)
             16, //  28 '^\' (FS)
             17, //  29 '^]' (GS)
             18, //  30 '^^' (RS)
             19, //  31 '^_' (US)
            0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,
             20, //  64 '@'
            0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,
             21, //  92 '\'
            0,
             22, //  94 '^'
             23, //  95 '_'
             24, //  96 '`'
            0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,
             25, // 124 '|'
            0,
             26, // 126 '~'
            0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,
             27, // 177 '±'
            0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0
        ),
        // MODE_PUNCT
        4 => array(
            0,0,
            0,0,0,0, // Punct codes 2–5 encode two bytes each
            0,0,0,0,0,0,0,
              1, //  13 '\r' (CR)
            0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,
              6, //  33 '!'
              7, //  34 '"'
              8, //  35 '#'
              9, //  36 '$'
             10, //  37 '%'
             11, //  38 '&'
             12, //  39 '''
             13, //  40 '('
             14, //  41 ')'
             15, //  42 '*'
             16, //  43 '+'
             17, //  44 ','
             18, //  45 '-'
             19, //  46 '.'
             20, //  47 '/'
            0,0,
            0,0,0,0,0,0,0,0,
             21, //  58 ':'
             22, //  59 ';'
             23, //  60 '<'
             24, //  61 '='
             25, //  62 '>'
             26, //  63 '?'
            0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,
             27, //  91 '['
             28, //  93 ']'
            0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,
             29, // 123 '{'
            0,
             30, // 125 '}'
            0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0,0,0,0,0,
            0,0,0,0,0,0
        )
    );

    /**
     * Latch map for changing character encoding mode.
     * Numbers represent: [number of bits to change, latch code value].
     */
    const LATCH_MAP = array(
        // MODE_UPPER
        0 => array (
          0 => array(), // UPPER
          1 => array(array(5,28)), // -> LOWER
          2 => array(array(5,30)), // -> DIGIT
          3 => array(array(5,29)), // -> MIXED
          4 => array(array(5,29),array(5,30)) // -> MIXED -> PUNCT
      ),
      // MODE_LOWER
      1 => array (
          0 => array(array(5,30),array(4,14)), // -> DIGIT -> UPPER
          1 => array(), // LOWER
          2 => array(array(5,30)), // -> DIGIT
          3 => array(array(5,29)), // -> MIXED
          4 => array(array(5,29),array(5,30)) // -> MIXED -> PUNCT
      ),
      // MODE_DIGIT
      2 => array (
          0 => array(array(4,14)), // -> UPPER
          1 => array(array(4,14),array(5,28)), // -> UPPER -> LOWER
          2 => array(), // DIGIT
          3 => array(array(4,14),array(5,29)), // -> UPPER -> MIXED
          4 => array(array(4,14),array(5,29),array(5,30)) // -> UPPER -> MIXED -> PUNCT
      ),
      // MODE_MIXED
      3 => array (
          0 => array(array(5,29)), // -> UPPER
          1 => array(array(5,28)), // -> LOWER
          2 => array(array(5,29),array(5,30)), // -> UPPER -> DIGIT
          3 => array(), // MIXED
          4 => array(array(5, 30)) // -> PUNCT
      ),
      // MODE_PUNCT
      4 => array (
          0 => array(array(5,31)), // -> UPPER
          1 => array(array(5,31),array(5,28)), // -> UPPER -> LOWER
          2 => array(array(5,31),array(5,30)), // -> UPPER -> DIGIT
          3 => array(array(5,31),array(5,29)), // -> UPPER -> MIXED
          4 => array() // PUNCT
      )
    );

    /**
     * Shift map for changing character encoding mode.
     */
    const SHIFT_MAP = array(
      // MODE_UPPER
      0 => array(
        0 => -1,
        1 => -1,
        2 => -1,
        3 => -1,
        4 =>  0 // -> PUNCT
      ),
      // MODE_LOWER
      1 => array (
        0 => 28, // -> UPPER
        1 => -1,
        2 => -1,
        3 => -1,
        4 =>  0 // -> PUNCT
      ),
      // MODE_DIGIT
      2 => array (
        0 => 15, // -> UPPER
        1 => -1,
        2 => -1,
        3 => -1,
        4 =>  0 // -> PUNCT
      ),
      // MODE_MIXED
      3 => array (
        0 => -1,
        1 => -1,
        2 => -1,
        3 => -1,
        4 =>  0 // -> PUNCT
      ),
      // MODE_PUNCT
      4 => array (
        0 => -1,
        1 => -1,
        2 => -1,
        3 => -1,
        4 => -1
      )
    );
}
