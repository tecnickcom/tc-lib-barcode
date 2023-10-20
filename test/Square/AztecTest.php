<?php

/**
 * AztecTest.php
 *
 * @since       2023-10-20
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Square;

use PHPUnit\Framework\TestCase;
use Test\TestUtil;

/**
 * AZTEC Barcode class test
 *
 * @since       2023-10-20
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2023-2023 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class AztecTest extends TestUtil
{
    protected function getTestObject()
    {
        return new \Com\Tecnick\Barcode\Barcode();
    }

    public function testInvalidInput()
    {
        $this->bcExpectException('\Com\Tecnick\Barcode\Exception');
        $testObj = $this->getTestObject();
        $testObj->getBarcodeObj('AZTEC', '');
    }

    public function testCapacityException()
    {
        $this->bcExpectException('\Com\Tecnick\Barcode\Exception');
        $testObj = $this->getTestObject();
        $code = str_pad('', 2000, '0123456789');
        $testObj->getBarcodeObj('AZTEC,100,B,F,3', $code);
    }

    /**
     * @dataProvider getGridDataProvider
     */
    public function testGetGrid($options, $code, $expected)
    {
        $testObj = $this->getTestObject();
        $bobj = $testObj->getBarcodeObj('AZTEC' . $options, $code);
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, md5($grid));
    }

    public static function getGridDataProvider()
    {
        return array(
            array('', ' ABCDEFGHIJKLMNOPQRSTUVWXYZ', '74f1e68830f0c635cd01167245743098'),
            array('', ' abcdefghijklmnopqrstuvwxyz', '100ebf910c88922b0ccee88256ba0c81'),
            array('', ' ,.0123456789', 'ee2a70b7c88a9e0956b1896983e93f91'),
            array('', '\r!"#$%&\'()*+,-./:;<=>?[]{}', '0a3104f0ecc58700db0f724aa47c6226'),
            array('', chr(1) . chr(2) . chr(3) . chr(4) . chr(5)
                     . chr(6) . chr(7) . chr(8) . chr(9) . chr(10)
                     . chr(11) . chr(12) . chr(13) . chr(27) . chr(28)
                     . chr(29) . chr(30) . chr(31) . chr(64) . chr(92)
                     . chr(94) . chr(95) . chr(96) . chr(124) . chr(126)
                     . chr(127), 'b8961abf38519b529f7dc6a20e8f3e59'),
            array('', 'AaB0C#D' . chr(126), '9b1f2af28b8d9d222de93dfe6a09a047'),
            array('', 'aAb0c#d' . chr(126), 'f4c58cabbdb5d94fa0cc1c31d510936a'),
            array('', '#A$a%0&' . chr(126), 'a17634a1db6372efbf8ea25a303c38f8'),
            array('', chr(1) . 'A' . chr(1) . 'a' . chr(1) . '0' . chr(1) . '#', 'c1a585888c7a1eb424ff98bbf7b32d46'),
            array('', 'PUNCT pairs , . : ', 'f2d5f259fc8d556bc179e3ab90b0777a'),
            array('', 'ABCDEabcdeABCDE012345ABCDE?[]{}ABCDE'
            . chr(1) . chr(2) . chr(3) . chr(4) . chr(5), '4ae19b80469a1afff8e490f5afaa8b73'),
            array('', 'abcdeABCDEabcde012345abcde?[]{}abcde'
            . chr(1) . chr(2) . chr(3) . chr(4) . chr(5), 'b0158bfe19c6fe20042128d59e40ca3b'),
            array('', '?[]{}ABCDE?[]{}abcde?[]{}012345?[]{}'
            . chr(1) . chr(2) . chr(3) . chr(4) . chr(5), '71ba0ed8c308c93af6af7cd23a76355a'),
            array('', chr(1) . chr(2) . chr(3) . chr(4) . chr(5) . 'ABCDE'
            . chr(1) . chr(2) . chr(3) . chr(4) . chr(5) . 'abcde'
            . chr(1) . chr(2) . chr(3) . chr(4) . chr(5) . '012345'
            . chr(1) . chr(2) . chr(3) . chr(4) . chr(5) . '?[]{}', 'f31e14be0b2c1f903e77af11e6c901b0'),
            array('', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit,'
            . ' sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
            . ' Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris'
            . ' nisi ut aliquip ex ea commodo consequat.'
            . ' Duis aute irure dolor in reprehenderit in voluptate velit esse'
            . ' cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat'
            . ' cupidatat non proident,' .
            ' sunt in culpa qui officia deserunt mollit anim id est laborum.', 'bb2b103d59e035a581fed0619090f89c')
        );
    }

    /**
     * @dataProvider getStringDataProvider
     */
    public function testStrings($code)
    {
        $testObj = $this->getTestObject();
        $bobj = $testObj->getBarcodeObj('AZTEC,50,B,F', $code);
        $this->assertNotNull($bobj);
    }

    public static function getStringDataProvider()
    {
        return \Test\TestStrings::$data;
    }
}
