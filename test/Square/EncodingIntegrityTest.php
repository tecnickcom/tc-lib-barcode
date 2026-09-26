<?php

/**
 * EncodingIntegrityTest.php
 *
 * @since       2026-08-27
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

namespace Test\Square;

use Com\Tecnick\Barcode\Barcode;
use Com\Tecnick\Barcode\Exception as BarcodeException;
use Com\Tecnick\Barcode\Type\Square\Datamatrix\Data;
use Com\Tecnick\Barcode\Type\Square\Datamatrix\Encode;
use Com\Tecnick\Barcode\Type\Square\QrCode\Data as QrData;
use PHPUnit\Framework\Attributes\DataProvider;
use Test\Fixture\AztecDecoder;
use Test\Fixture\DatamatrixDecoder;
use Test\Fixture\InternalAztec;
use Test\Fixture\InternalDatamatrix;
use Test\Fixture\InternalPdfFourOneSeven;
use Test\Fixture\InternalQrEncode;
use Test\TestUtil;

/**
 * Structural checks on the 2D high level encoders
 *
 * @since       2026-08-27
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 */
class EncodingIntegrityTest extends TestUtil
{
    protected function getTestObject(): Barcode
    {
        return new Barcode();
    }

    /**
     * A Base 256 field returns the decoder to ASCII, so a mode selected right after it
     * must be introduced by its own latch codeword.
     *
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testBaseTwoFiveSixEmitsTheLatchOfTheFollowingMode(): void
    {
        $dmx = new InternalDatamatrix('x');
        $cdw = $dmx->exposeHighLevelEncoding("\x80>>11\r>**1");

        // 231 latches Base 256, the length byte and the single 0x80 byte follow
        $this->assertSame(231, $cdw[0] ?? null);
        // the X12 data that follows must be preceded by its latch codeword
        $this->assertSame(Data::SWITCHCDW[Data::ENC_X12] ?? 0, $cdw[3] ?? null);
    }

    /**
     * A Base 256 field that consumes the whole payload ends in ASCII and is not followed
     * by a latch codeword.
     *
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testBaseTwoFiveSixWithoutModeSwitchHasNoTrailingLatch(): void
    {
        $dmx = new InternalDatamatrix('x');
        $cdw = $dmx->exposeHighLevelEncoding("\xC3\xA0\xC3\xA8\xC3\xAC\xC3\xB2");

        $this->assertSame(231, $cdw[0] ?? null);
        $this->assertNotContains(231, \array_slice($cdw, 1));
        $this->assertNotContains(238, $cdw);
    }

    /**
     * Payloads that exercise the C40, Text and X12 encodation boundaries.
     *
     * @return array<string, array{array<int, string>, string}>
     */
    public static function datamatrixRoundTripProvider(): array
    {
        return [
            // X12 has no shift sets: values 0, 1 and 2 are CR, * and >
            'x12 mixed alphabet' => [[], '2A*aCAAC>*>1*C'],
            'x12 mixed alphabet with carriage return' => [[], "1CA*>bC\r*C1CA\r"],
            'x12 shift 2 character' => [['S', 'N', 'X12'], 'AB!CD'],
            'x12 extended character' => [['S', 'N', 'X12'], "AB\xE9CD"],
            // a partial X12 triple has no pad value and must unlatch
            'x12 partial triple' => [['S', 'N', 'X12'], 'RCIAT'],
            'x12 partial triple from ascii' => [[], '037K4GJWJY'],
            'x12 partial triple after space' => [[], 'z *AA1B3A'],
            // the pending values of an incomplete character must be re-encoded whole
            'c40 extended character rewind' => [['S', 'N', 'C40'], "a\xE9"],
            'c40 end of symbol upper shift' => [['S', 'N', 'C40'], "\xFA"],
            'txt end of symbol upper shift' => [['S', 'N', 'TXT'], "\xDA"],
            // a character outside the EDIFACT set followed by a long EDIFACT run
            'edifact run after a lower case letter' => [[], 'n NRS 4 - 10"/></S></MP>'],
            'edifact run after a lower case letter from edifact' => [
                ['S', 'N', 'EDIFACT'],
                'an NRS 4 - 10"/></S></MP>',
            ],
            // the last EDIFACT characters are packed in EDIFACT or left to ASCII
            'edifact last three characters' => [['S', 'N', 'EDIFACT'], 'ABCDEFGHIJK'],
            'edifact last four characters' => [['S', 'N', 'EDIFACT'], 'ABCDEFGH'],
            'edifact last four characters before padding' => [['S', 'N', 'EDIFACT'], 'ABCDEFGHIJKLMNOP'],
            'edifact trailing digit pair' => [['S', 'N', 'EDIFACT'], 'ABCDEF12'],
            // a predefined encodation is not latched on a character it cannot encode
            'x12 lower case first character' => [['S', 'N', 'X12'], 'aBC*DEF>'],
            'edifact run in a long mixed payload' => [
                [],
                '<MP U="XXX" l="de-DE" v="028"><P g="Konias" f="Michael" egk="A123456789"/>'
                    . '<A n="Dr. med. NAME" lanr="010101010" t="2026-09-22T13:32:36"/>'
                    . '<M p="12345678" du="1" t="2" i="Alle 2 Stunden; bis zu alle 6 - 8 h, max. 8 x / d,'
                    . ' Mindesabstand 1 h" x="bei Schmerzen NRS 4 - 10"/></S></MP>',
            ],
        ];
    }

    /**
     * The data codeword region must decode back to the encoded payload.
     *
     * @param array<int, string> $params  Data Matrix parameters.
     * @param string             $payload Data to encode.
     *
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     * @throws \RuntimeException
     */
    #[DataProvider('datamatrixRoundTripProvider')]
    public function testDatamatrixCodewordsRoundTrip(array $params, string $payload): void
    {
        $dmx = new InternalDatamatrix('ABC', -1, -1, 'black', $params);
        $decoder = new DatamatrixDecoder();

        $this->assertSame($payload, $decoder->decode($dmx->exposeDataCodewords($payload)));
    }

    /**
     * A character with no representation in the active encodation is reported with its context.
     */
    public function testDatamatrixShiftFailureIsDiagnosable(): void
    {
        $encode = new Encode();
        $chr = -1;
        $enc = -1;
        $temp_cw = [];
        $ptr = 0;

        try {
            $encode->encodeTXTC40shift($chr, $enc, $temp_cw, $ptr);
        } catch (BarcodeException $exception) {
            $this->assertSame('Unable to encode the character -1 in the BAS encodation', $exception->getMessage());
            return;
        }

        $this->fail('the shift encoder must reject a character it cannot represent');
    }

    /**
     * The 30 column / 90 row limits can cap a PDF417 symbol below the required capacity:
     * the encoder widens the symbol to hold every codeword.
     *
     * @return array<int, array{string}>
     */
    public static function pdfAspectRatioProvider(): array
    {
        return [['1'], ['1.2'], ['2']];
    }

    /**
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('pdfAspectRatioProvider')]
    public function testPdfFourOneSevenNeverDropsCodewords(string $aspectratio): void
    {
        $pdf = new InternalPdfFourOneSeven(\str_repeat('A', 1850), -1, -1, 'black', [$aspectratio]);
        $probe = $pdf->exposeCapacity();

        $this->assertGreaterThanOrEqual(
            $probe['codewords'],
            $probe['capacity'],
            'the symbol must hold every generated codeword',
        );
    }

    /**
     * Data that cannot fit the largest PDF417 symbol is rejected.
     *
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     */
    public function testPdfFourOneSevenRejectsOversizedData(): void
    {
        $this->bcExpectException(BarcodeException::class);
        $this->getTestObject()->getBarcodeObj('PDF417,1', \str_repeat('A', 2600));
    }

    /**
     * An out of range default mask falls back to the documented default of 2.
     *
     * @return array<int, array{string}>
     */
    public static function outOfRangeMaskProvider(): array
    {
        return [['-1'], ['8'], ['99'], ['']];
    }

    /**
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     */
    #[DataProvider('outOfRangeMaskProvider')]
    public function testQrCodeOutOfRangeDefaultMaskFallsBackToTheDefault(string $mask): void
    {
        $barcode = $this->getTestObject();
        $expected = $barcode->getBarcodeObj('QRCODE,L,8B,0,1,0,0,2', 'test')->getGrid();
        $actual = $barcode->getBarcodeObj('QRCODE,L,8B,0,1,0,0,' . $mask, 'test')->getGrid();

        $this->assertSame($expected, $actual);
    }

    /**
     * getStreamBits() accumulates the bits of every segment.
     */
    public function testQrCodeBitStreamSizeAccumulatesEverySegment(): void
    {
        $encode = InternalQrEncode::forSymbol();
        $segment = [QrData::MODE_ALPHANUM, 0, 8];

        $one = $encode->exposeStreamBits([$segment], 0);
        $four = $encode->exposeStreamBits(\array_fill(0, 4, $segment), 0);

        $this->assertSame(4 * $one, $four);
    }

    /**
     * GS1 payloads whose FNC1 separators fall inside a C40, Text, X12, EDIFACT or
     * Base 256 field. The FNC1 is an ASCII encodation code word, so the field has to end
     * before it.
     *
     * @return array<string, array{array<int, string>, string}>
     */
    public static function datamatrixGsOneRoundTripProvider(): array
    {
        $payload = "\xE8" . '01034531200000111719112510ABCD1234' . "\xE8" . '2110';

        return [
            'ascii' => [['S', 'GS1'], $payload],
            'c40' => [['S', 'GS1', 'C40'], $payload],
            'txt' => [['S', 'GS1', 'TXT'], $payload],
            'x12' => [['S', 'GS1', 'X12'], $payload],
            'edf' => [['S', 'GS1', 'EDF'], $payload],
            'base256' => [['S', 'GS1', 'BASE256'], $payload],
            'separator inside a c40 field' => [['S', 'GS1'], "\xE8" . 'K66X ' . "\xE8" . 'YGNMNP'],
            'separator inside a text field' => [['S', 'GS1'], "\xE8" . 'k66x ' . "\xE8" . 'ygnmnp'],
            'separator inside an x12 field' => [['S', 'GS1'], "\xE8" . 'AB*CD>' . "\xE8" . 'EF*GH>'],
            'separator inside a base 256 field' => [['S', 'GS1'], "\xE8\xC0\xC1\xC2\xC3\xE8\xC4\xC5\xC6\xC7"],
            'consecutive separators' => [['S', 'GS1'], "\xE8\xE8" . 'ABCDEF' . "\xE8\xE8"],
            'separator at the end of the data' => [['S', 'GS1'], "\xE8" . 'ABCDEFGHIJ' . "\xE8"],
            // the field ends on a separator before its last triple is complete
            'separator inside a c40 triple' => [['S', 'GS1', 'C40'], "\xE8" . '*ABC*' . "\xE8" . '>DEF<'],
            'separator inside a text triple' => [['S', 'GS1', 'TXT'], "\xE8" . 'AB' . "\xE8" . 'CD'],
            'rectangular' => [['R', 'GS1'], $payload],
        ];
    }

    /**
     * A GS1 payload must decode back to itself, with the GS separators mapped to FNC1.
     *
     * @param array<int, string> $params  Data Matrix parameters.
     * @param string             $payload Data to encode.
     *
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     * @throws \RuntimeException
     */
    #[DataProvider('datamatrixGsOneRoundTripProvider')]
    public function testDatamatrixGsOneCodewordsRoundTrip(array $params, string $payload): void
    {
        $dmx = new InternalDatamatrix('ABC', -1, -1, 'black', $params);
        $decoder = new DatamatrixDecoder();

        $this->assertSame($payload, $decoder->decode($dmx->exposeDataCodewords($payload)));
    }

    /**
     * The GS separator is encoded as the same FNC1 code word as the FNC1 character.
     *
     * @throws BarcodeException
     * @throws \Com\Tecnick\Color\Exception
     * @throws \RuntimeException
     */
    public function testDatamatrixGsOneSeparatorMatchesFncOne(): void
    {
        $dmx = new InternalDatamatrix('ABC', -1, -1, 'black', ['S', 'GS1']);
        $decoder = new DatamatrixDecoder();

        $this->assertSame(
            "\xE8" . 'K66X ' . "\xE8" . 'YGNMNP',
            $decoder->decode($dmx->exposeDataCodewords("\xE8" . 'K66X ' . "\x1d" . 'YGNMNP')),
        );
    }

    /**
     * Payloads that exercise the Aztec mode latches, shifts and Binary Shift fields.
     *
     * @return array<string, array{string, string}>
     */
    public static function aztecRoundTripProvider(): array
    {
        return [
            // Digit and Punct have no Binary Shift: the shift map latches to Upper first
            'binary from digit mode' => ['A', "5\xff6"],
            'binary from punct mode' => ['A', ",\xc3:"],
            'binary inside a digit run' => ['A', "12345\xc367890"],
            'binary inside a punct run' => ['A', "!!!!\xc3????"],
            'utf-8 inside a digit run' => ['A', "Prix 9,99\xe2\x82\xac TTC"],
            'binary from lower mode' => ['A', "abc\xffdef"],
            'binary from mixed mode' => ['A', "\x01\x02\xff\x03\x04"],
            'upper, lower and digits' => ['A', 'Tecnick.com 2026'],
            // a Binary Shift field longer than the 11 bit extended length
            'forced binary at the extended length limit' => ['B', \str_repeat("\xC3", 2078)],
            'forced binary over the extended length limit' => ['B', \str_repeat("\xC3", 2079)],
            'forced binary short' => ['B', 'Tecnick'],
        ];
    }

    /**
     * The Aztec high level bitstream must decode back to the encoded payload.
     *
     * @param string $hint    Encoding hint.
     * @param string $payload Data to encode.
     *
     * @throws BarcodeException
     * @throws \RuntimeException
     */
    #[DataProvider('aztecRoundTripProvider')]
    public function testAztecBitstreamRoundTrip(string $hint, string $payload): void
    {
        $encode = new InternalAztec('x', 33, -1, $hint, 'A');
        $bitstream = $encode->exposeHighLevelBitstream($payload, -1, $hint);
        $decoder = new AztecDecoder();

        $this->assertSame($payload, $decoder->decode($bitstream['bits'], $bitstream['totbits']));
    }

    /**
     * FLG(n) is the Punct code word 0 followed by the 3 bit digit count.
     *
     * @return array<int, array{int}>
     */
    public static function aztecEciProvider(): array
    {
        return [[0], [3], [26], [899], [1000000]];
    }

    /**
     * @throws BarcodeException
     * @throws \RuntimeException
     */
    #[DataProvider('aztecEciProvider')]
    public function testAztecEciKeepsThePayload(int $eci): void
    {
        $encode = new InternalAztec('x', 33, $eci, 'A', 'A');
        $bitstream = $encode->exposeHighLevelBitstream('Test123', $eci, 'A');
        $decoder = new AztecDecoder();

        $this->assertSame('Test123', $decoder->decode($bitstream['bits'], $bitstream['totbits']));
        $this->assertSame([$eci], $decoder->getEci());
    }
}
