<?php
/**
 * DatamatrixTest.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Square;

/**
 * Barcode class test
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnick.com/tc-lib-barcode
 */
class DatamatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    /**
     * @dataProvider getGridDataProvider
     */
    public function testGetGrid($code, $expected)
    {
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, md5($grid));
    }
    
    public function getGridDataProvider()
    {
        return array(
            array('30Q324343430794<OQQ', 'e67808f91114fb021851098c4cc65b88'),
            array('0123456789', 'cc1fd942bc919b2d09b3c7cf508c6ae4'),
            array('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', '3dee31111519b71fdf624efda75e4b4a'),
            array('10f27ce-acb7-4e4e-a7ae-a0b98da6ed4a', '67b3bd23c0ab6de2e90a8ebd2143ed2b'),
            array('Hello World', 'e72650689027fe75d1f9377ec759c710'),
            array('https://github.com/tecnickcom/tc-lib-barcode', 'ff3faf34d07c75fb99051fd2b9d72e21'),
            array(
                'abcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcd'
                .'abcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcdabcd',
                '296d2971c50f3302ad993d4b722a055f'
            ),
            array(
                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\'
                .'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\'
                .'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\',
                '62e6552f3bbb51b0e2e22fbd5bbdc2bf'
            ),
            array(chr(128).chr(138).chr(148).chr(158), '01aa230c9a4c35ea69f23ecbc58d8e3e'),
            array('!"£$%^&*()-+_={}[]\'#@~;:/?,.<>|', '03e8a96b0d0e41bded21c22f865da9c7'),
            array(
                'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\1234567890',
                '561a64e10c4def637cd1eec6c8150f2b'
            ),
            array(
                chr(254).chr(253)
                .'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*(),./\\'
                .chr(252).chr(251),
                '15c9151a9db02542938e8682afecdb7b'
            ),
            array('aABCDEFG', '368e35b2aea50a4477f54560d1456599'),
            array('123 45678', '28a7c011640a3d548b360661343730df'),
            array('DATA MATRIX', 'df3239390d1b76ba848b5bf7899fbb5d'),
            array('123ABCD89', '7ce2f8433b82c16e80f4a4c59cad5d10'),
            array('AB/C123-X', '703318e1964c63d5d500d14a821827cd'),
            array(str_pad('', 300, chr(254).chr(253).chr(252).chr(251)), 'b9f1929925d2ee3c88ddbd7c50bffc87')
        );
    }

    public function testInvalidInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('DATAMATRIX', '');
    }

    public function testLongInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $code = str_pad('', 3000, 'X');
        $this->obj->getBarcodeObj('DATAMATRIX', $code);
    }
}
