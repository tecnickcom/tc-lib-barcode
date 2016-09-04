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
 * @link        https://github.com/tecnickcom/tc-lib-barcode
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
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class DatamatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $obj = null;

    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
        $this->obj = new \Com\Tecnick\Barcode\Barcode;
    }

    public function testInvalidInput()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $this->obj->getBarcodeObj('DATAMATRIX', '');
    }

    public function testCapacityException()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $code = str_pad('', 3000, 'X');
        $this->obj->getBarcodeObj('DATAMATRIX', $code);
    }
 
    public function testEncodeTXTC40shiftException()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $obj = new \Com\Tecnick\Barcode\Type\Square\Datamatrix\Encode();
        $chr = null;
        $enc = null;
        $temp_cw = null;
        $ptr = null;
        $obj->encodeTXTC40shift($chr, $enc, $temp_cw, $ptr);
    }
 
    public function testEncodeTXTC40Exception()
    {
        $this->setExpectedException('\Com\Tecnick\Barcode\Exception');
        $obj = new \Com\Tecnick\Barcode\Type\Square\Datamatrix\Encode();
        $data = array(chr(0x80));
        $enc = \Com\Tecnick\Barcode\Type\Square\Datamatrix\Data::ENC_X12;
        $temp_cw = null;
        $ptr = null;
        $epos = 0;
        $charset = null;
        $obj->encodeTXTC40($data, $enc, $temp_cw, $ptr, $epos, $charset);
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
                '0b2921466e097ff9cc1ad63719430540'
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
            array(str_pad('', 300, chr(254).chr(253).chr(252).chr(251)), 'b9f1929925d2ee3c88ddbd7c50bffc87'),
            array('ec:b47'.chr(127).'4#P d*b}gI2#DB|hl{!~[EYH*=cmR{lf'
                .chr(127).'=gcGIa.st286. #*"!eG[.Ryr?Kn,1mIyQqC3 6\'3N>',
                'c99bc399273c299fe56bfa8da8017f99'
            ),
            array('eA211101A2raJTGL/r9o93CVk4gtpEvWd2A2Qz8jvPc7l8ybD3m'
                .'Wel91ih727kldinPeHJCjhr7fIBX1KQQfsN7BFMX00nlS8FlZG+',
                '8aed7cb88565682df74a8aa66ba18601'
            ),
            array(
                chr(255).chr(254).chr(253).chr(252).chr(251).chr(250).chr(249).chr(248).chr(247).chr(246).chr(245)
                .chr(244).chr(243).chr(242).chr(241).chr(240).chr(239).chr(238).chr(237).chr(236).chr(235).chr(234)
                .chr(233).chr(232).chr(231).chr(230).chr(229).chr(228).chr(227).chr(226).chr(225).chr(224).chr(223)
                .chr(222).chr(221).chr(220).chr(219).chr(218).chr(217).chr(216).chr(215).chr(214).chr(213).chr(212)
                .chr(211).chr(210).chr(209).chr(208).chr(207).chr(206).chr(205).chr(204).chr(203).chr(202).chr(201)
                .chr(200).chr(199).chr(198).chr(197).chr(196).chr(195).chr(194).chr(193).chr(192).chr(191).chr(190)
                .chr(189).chr(188).chr(187).chr(186).chr(185).chr(184).chr(183).chr(182).chr(181).chr(180).chr(179)
                .chr(178).chr(177).chr(176).chr(175).chr(174).chr(173).chr(172).chr(171).chr(170).chr(169).chr(168)
                .chr(167).chr(166).chr(165).chr(164).chr(163).chr(162).chr(161).chr(160).chr(159).chr(158).chr(157)
                .chr(156).chr(155).chr(154).chr(153).chr(152).chr(151).chr(150).chr(149).chr(148).chr(147).chr(146)
                .chr(145).chr(144).chr(143).chr(142).chr(141).chr(140).chr(139).chr(138).chr(137).chr(136).chr(135)
                .chr(134).chr(133).chr(132).chr(131).chr(130).chr(129).chr(128).chr(127).chr(126).chr(125).chr(124)
                .chr(123).chr(122).chr(121).chr(120).chr(119).chr(118).chr(117).chr(116).chr(115).chr(114).chr(113)
                .chr(112).chr(111).chr(110).chr(109).chr(108).chr(107).chr(106).chr(105).chr(104).chr(103).chr(102)
                .chr(101).chr(100).chr(99).chr(98).chr(97).chr(96).chr(95).chr(94).chr(93).chr(92).chr(91).chr(90)
                .chr(89).chr(88).chr(87).chr(86).chr(85).chr(84).chr(83).chr(82).chr(81).chr(80).chr(79).chr(78)
                .chr(77).chr(76).chr(75).chr(74).chr(73).chr(72).chr(71).chr(70).chr(69).chr(68).chr(67).chr(66)
                .chr(65).chr(64).chr(63).chr(62).chr(61).chr(60).chr(59).chr(58).chr(57).chr(56).chr(55).chr(54)
                .chr(53).chr(52).chr(51).chr(50).chr(49).chr(48).chr(47).chr(46).chr(45).chr(44).chr(43).chr(42)
                .chr(41).chr(40).chr(39).chr(38).chr(37).chr(36).chr(35).chr(34).chr(33).chr(32).chr(31).chr(30)
                .chr(29).chr(28).chr(27).chr(26).chr(25).chr(24).chr(23).chr(22).chr(21).chr(20).chr(19).chr(18)
                .chr(17).chr(16).chr(15).chr(14).chr(13).chr(12).chr(11).chr(10).chr(9).chr(8).chr(7).chr(6)
                .chr(5).chr(4).chr(3).chr(2).chr(1),
                '7097885c0a5c42f00dabd0e3034319e8'
            ),
        );
    }

    /**
     * @dataProvider getStringDataProvider
     */
    public function testStrings($code)
    {
        $bobj = $this->obj->getBarcodeObj('DATAMATRIX', $code);
        $this->assertNotNull($bobj);
    }

    public function getStringDataProvider()
    {
        return \Test\TestStrings::$data;
    }
}
