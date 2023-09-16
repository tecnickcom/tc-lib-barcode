<?php

/**
 * AztecTest.php
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

use PHPUnit\Framework\TestCase;
use \Test\TestUtil;

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

class AztecTest extends TestUtil
{
    protected function getTestObject()
    {
        return new \Com\Tecnick\Barcode\Barcode;
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
        $code = str_pad('', 3200, 'X');
        $testObj->getBarcodeObj('AZTEC', $code);
    }

    /**
     * @dataProvider getGridDataProvider
     */
    public function testGetGrid($options, $code, $expected)
    {
        $testObj = $this->getTestObject();
        $bobj = $testObj->getBarcodeObj('AZTEC'.$options, $code);
        $grid = $bobj->getGrid();
        $this->assertEquals($expected, md5($grid));
    }

    public function getGridDataProvider()
    {
        return array(
            array('', '0123456789', '334052ad702761015cd40336874d8408'),
            array(',200', '0123456789', '860d2cd151de3c6ca851975e86586201'),
            array(',200,binary', '0123456789', '6bcaf3679c91802268cd99c972bb054f'),
            array('', '0123456789ABC', '6c4e7e80034758c4cfe20550956a09de'),
            array(',200', '0123456789ABC', 'f54f01d9149e0656fe1bc998b1a01822'),
            array(',200,binary', '0123456789ABC', 'b3cc5389de00d407f7b54f2d570e8824'),
            array('', 'abcdefghijklmnopqrstuvwxyz01234567890123456789', 'ea8d8c08eb80f718fe333ffc5fb2f458'),
            array(',200', 'abcdefghijklmnopqrstuvwxyz01234567890123456789', '86eb21735f78ec8e8f9c28ff7923bee5'),
            array(',200,binary', 'abcdefghijklmnopqrstuvwxyz01234567890123456789', '89024db3a15780454524e1a30ae022ef'),
            array(
                ',200',
                chr(158).chr(19).chr(192).chr(8).chr(71).chr(113).chr(107).chr(252).chr(171).chr(169).chr(114)
                .chr(114).chr(204).chr(151).chr(183).chr(20).chr(180).chr(26).chr(73).chr(76).chr(193).chr(16)
                .chr(69).chr(212).chr(232).chr(90).chr(248).chr(115).chr(9).chr(104).chr(149).chr(167).chr(123)
                .chr(86).chr(175).chr(193).chr(199).chr(27).chr(190).chr(115).chr(196).chr(50).chr(228).chr(146)
                .chr(201).chr(156).chr(165).chr(126).chr(182).chr(237).chr(201).chr(121).chr(253).chr(15).chr(78)
                .chr(231).chr(105).chr(72).chr(92).chr(114).chr(175).chr(240).chr(26).chr(43).chr(71).chr(200)
                .chr(236).chr(15).chr(227).chr(172).chr(129).chr(169).chr(221).chr(103).chr(60).chr(167).chr(5)
                .chr(225).chr(39).chr(186).chr(208).chr(240).chr(52).chr(206).chr(254).chr(130).chr(183).chr(105)
                .chr(201).chr(20).chr(218).chr(122).chr(5).chr(244).chr(165).chr(76).chr(189).chr(146).chr(91)
                .chr(162).chr(63).chr(220).chr(76).chr(30).chr(68).chr(135).chr(196).chr(73).chr(106).chr(235)
                .chr(5).chr(59).chr(220).chr(56).chr(11).chr(220).chr(186).chr(194).chr(70).chr(132).chr(213)
                .chr(34).chr(254).chr(218).chr(23).chr(164).chr(40).chr(212).chr(56).chr(130).chr(119).chr(118)
                .chr(95).chr(194).chr(148).chr(163).chr(75).chr(90).chr(236).chr(180).chr(70).chr(240).chr(239)
                .chr(35).chr(42).chr(250).chr(254).chr(227).chr(189).chr(70).chr(105).chr(148).chr(103).chr(104)
                .chr(112).chr(126).chr(13).chr(151).chr(83).chr(68).chr(27).chr(201).chr(186).chr(121).chr(141)
                .chr(80).chr(30).chr(215).chr(169).chr(12).chr(141).chr(238).chr(251).chr(126).chr(18).chr(39)
                .chr(121).chr(18).chr(12).chr(56).chr(88).chr(116).chr(203).chr(190).chr(220).chr(60).chr(61)
                .chr(233).chr(211).chr(144).chr(47).chr(237).chr(90).chr(232).chr(104).chr(230).chr(57).chr(134)
                .chr(191).chr(226).chr(145).chr(77).chr(209).chr(142).chr(202).chr(227).chr(180).chr(69).chr(245)
                .chr(191).chr(124).chr(78).chr(53).chr(73).chr(13).chr(18).chr(133).chr(74).chr(250).chr(89)
                .chr(217).chr(42).chr(71).chr(53).chr(20).chr(175).chr(29).chr(77).chr(54).chr(219).chr(48).chr(198)
                .chr(41).chr(3).chr(85).chr(243).chr(229).chr(11).chr(57).chr(219).chr(201).chr(180).chr(43).chr(253)
                .chr(252).chr(56).chr(17).chr(131).chr(129).chr(12).chr(219).chr(92).chr(54).chr(36).chr(145).chr(74)
                .chr(210).chr(173).chr(151).chr(9).chr(137).chr(198).chr(207).chr(178).chr(201).chr(38).chr(166)
                .chr(175).chr(48).chr(223).chr(140).chr(249).chr(149).chr(182).chr(248).chr(147).chr(237).chr(10)
                .chr(23).chr(112).chr(22).chr(241).chr(204).chr(76).chr(23).chr(94).chr(150).chr(232).chr(13).chr(46)
                .chr(241).chr(149).chr(243).chr(193).chr(73).chr(190).chr(230).chr(239).chr(110).chr(24),
                '609ddb62f47d94c7169a34b1030cfd50'
            ),
            array(',200',
                chr(205).chr(146).chr(176).chr(79).chr(226).chr(154).chr(191).chr(118).chr(198).chr(215).chr(126)
                .chr(236).chr(12).chr(29).chr(243).chr(254).chr(4).chr(27).chr(150).chr(168).chr(96).chr(142).chr(160)
                .chr(176).chr(34).chr(42).chr(71).chr(182).chr(48).chr(192).chr(125).chr(252).chr(84).chr(46).chr(77)
                .chr(55).chr(200).chr(13).chr(173).chr(144).chr(227).chr(44).chr(125).chr(238).chr(73).chr(113)
                .chr(238).chr(76).chr(140).chr(133),
                'a884b7e6a182a7dec29e7c1548aa8a37'
            ),
		);
	}

    /**
     * @dataProvider getStringDataProvider
     */

    public function testStrings($code)
    {
        $testObj = $this->getTestObject();
        $bobj = $testObj->getBarcodeObj('AZTEC,200', $code);
        $this->assertNotNull($bobj);
    }

    public function getStringDataProvider()
    {
        return \Test\TestStrings::$data;
    }

}
