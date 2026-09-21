<?php

declare(strict_types=1);

/**
 * index.php
 *
 * @since       2015-02-21
 * @category    Library
 * @package     Barcode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2015-2026 Nicola Asuni - Tecnick.com LTD
 * @license     https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link        https://github.com/tecnickcom/tc-lib-barcode
 *
 * This file is part of tc-lib-barcode software library.
 */

// autoloader when using Composer
require __DIR__ . '/../vendor/autoload.php';

// autoloader when using RPM or DEB package installation
//require ('/usr/share/php/Com/Tecnick/Barcode/autoload.php');

// data to generate for each barcode type
$linear = [
    'AUSPOST' => ['6254516251ABC123', 'Australia Post 4-State Customer Barcode'],
    'C128A' => ['0123456789', 'CODE 128 A'],
    'C128B' => ['0123456789', 'CODE 128 B'],
    'C128C' => ['0123456789', 'CODE 128 C'],
    'C128' => ['0123456789', 'CODE 128'],
    'C16K' => ['ab0123456789', 'CODE 16K (stacked CODE 128)'],
    'C32' => ['012345676', 'CODE 32 (Italian Pharmacode - IMH - Radix 32)'],
    'C49' => ['MULTIPLE ROWS IN CODE 49', 'CODE 49 (ANSI/AIM BC6)'],
    'C39E+' => ['0123456789', 'CODE 39 EXTENDED + CHECKSUM'],
    'C39E' => ['0123456789', 'CODE 39 EXTENDED'],
    'C39+' => ['0123456789', 'CODE 39 + CHECKSUM'],
    'C39' => ['0123456789', 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9'],
    'C93' => ['0123456789', 'CODE 93 - USS-93'],
    'CODABAR' => ['0123456789', 'CODABAR'],
    'CODE11' => ['0123456789', 'CODE 11'],
    'DATABAR' => ['09501101530010', 'GS1 DataBar Omnidirectional (ISO/IEC 24724)'],
    'DATABAREXP' => ['(01)90614141000015(3202)000150', 'GS1 DataBar Expanded (ISO/IEC 24724)'],
    'DATABAREXPSTACK' => ['(01)90614141000015(3202)000150', 'GS1 DataBar Expanded Stacked (ISO/IEC 24724)'],
    'DATABARLIMITED' => ['15012345678907', 'GS1 DataBar Limited (ISO/IEC 24724)'],
    'DATABARSTACK' => ['00012345678905', 'GS1 DataBar Stacked (ISO/IEC 24724)'],
    'DATABARSTACKOMNI' => ['00034567890125', 'GS1 DataBar Stacked Omnidirectional (ISO/IEC 24724)'],
    'DATABARTRUNC' => ['00012345678905', 'GS1 DataBar Truncated (ISO/IEC 24724)'],
    'EAN13' => ['0123456789', 'EAN 13'],
    'EAN2' => ['12', 'EAN 2-Digits UPC-Based Extension'],
    'EAN5' => ['12345', 'EAN 5-Digits UPC-Based Extension'],
    'EAN8' => ['1234567', 'EAN 8'],
    'GS114' => ['9501101020917', 'GS1-14 - EAN-14 - SCC-14 (GS1-128 with the Application Identifier 01)'],
    'GS1128' => ['(01)09501101020917(10)AB-123', 'GS1-128 (CODE 128 with GS1 Application Identifiers)'],
    'HIBC128' => ['+A123BJC5D6E71', 'HIBC in CODE 128 (ANSI/HIBC 2.6 and ANSI/HIBC 1.3)'],
    'HIBC39' => ['+A123BJC5D6E71', 'HIBC in CODE 39 (ANSI/HIBC 2.6 and ANSI/HIBC 1.3)'],
    'I25+' => ['0123456789', 'Interleaved 2 of 5 + CHECKSUM'],
    'I25' => ['0123456789', 'Interleaved 2 of 5'],
    'IDENTCODE' => ['563102430313', 'Deutsche Post Identcode'],
    'IMB' => ['01234567094987654321-01234567891', 'IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200'],
    'IMBPRE' => ['AADTFFDFTDADTAADAATFDTDDAAADDTDTTDAFADADDDTFFFDDTTTADFAAADFTDAADA', 'IMB pre-processed'],
    'ITF14' => ['09312345678907', 'ITF-14 (GTIN-14 - GS1 General Specifications)'],
    'JPPOST' => ['910-00673-80-25J1-2B', 'Japan Post Customer Barcode'],
    'KIX' => ['0123456789', 'KIX (Klant index - Customer index)'],
    'LEITCODE' => ['21348075016401', 'Deutsche Post Leitcode'],
    'LOGMARS' => ['12345/ABCDE', 'LOGMARS (CODE 39 profile of MIL-STD-1189B)'],
    'MAILMARK' => ['41038422416563762EF61AH8T ', 'Royal Mail Mailmark 4-state barcode'],
    'MSI+' => ['0123456789', 'MSI + CHECKSUM (modulo 11)'],
    'MSI' => ['0123456789', 'MSI (Variation of Plessey code)'],
    'PHARMA2T' => ['123456', 'PHARMACODE TWO-TRACKS'],
    'PHARMA' => ['123456', 'PHARMACODE'],
    'PLANET' => ['0123456789', 'PLANET'],
    'PLESSEY' => ['0123456789ABCDEF', 'Plessey Code'],
    'POSTNET' => ['0123456789', 'POSTNET'],
    'PZN' => ['2758089', 'PZN (Pharmazentralnummer - IFA coding system)'],
    'RMS4CC' => ['0123456789', 'RMS4CC (Royal Mail 4-state Customer Bar Code)'],
    'S25+' => ['0123456789', 'Standard 2 of 5 + CHECKSUM'],
    'S25' => ['0123456789', 'Standard 2 of 5'],
    'S25DATALOGIC' => ['0123456789', '2 of 5 Datalogic (China Post Code)'],
    'S25IATA' => ['0123456789', '2 of 5 IATA (Computer Identics 2 of 5)'],
    'S25MATRIX' => ['0123456789', '2 of 5 Matrix'],
    'SSCC18' => ['39501101020917171', 'SSCC-18 (GS1-128 with the Application Identifier 00)'],
    'TELEPEN' => ['ABC123', 'Telepen (full ASCII)'],
    'UPCA' => ['72527273070', 'UPC-A'],
    'UPCE' => ['725277', 'UPC-E'],
];

// width and height multipliers for the linear types that do not fit the default single-row scaling
$linear_size = [
    'ITF14' => [-1, -1],
    'C16K' => [-3, -3],
    'C49' => [-3, -3],
    'PLESSEY' => [-1, -30],
    'DATABAR' => [-3, -3],
    'DATABAREXP' => [-3, -3],
    'DATABAREXPSTACK' => [-3, -3],
    'DATABARLIMITED' => [-3, -3],
    'DATABARSTACK' => [-3, -3],
    'DATABARSTACKOMNI' => [-3, -3],
    'DATABARTRUNC' => [-3, -3],
];

$square = [
    'LRAW' => ['0101010101', '1D RAW MODE (comma-separated rows of 01 strings)'],
    'SRAW' => ['0101,1010', '2D RAW MODE (comma-separated rows of 01 strings)'],
    'AZTEC' => ['ABCDabcd01234', 'AZTEC (ISO/IEC 24778:2008)'],
    'AZTEC,50,A,A' => ['ABCDabcd01234', 'AZTEC (ISO/IEC 24778:2008)'],
    'AZTECRUNE' => ['125', 'AZTEC Rune (ISO/IEC 24778:2008 Annex A)'],
    'PDF417' => ['0123456789', 'PDF417 (ISO/IEC 15438:2006)'],
    'PDF417C' => ['0123456789', 'Compact PDF417 - truncated (ISO/IEC 15438:2006)'],
    'QRCODE' => ['0123456789', 'QR-CODE'],
    'QRCODE,H,ST,0,0' => ['abcdefghijklmnopqrstuvwxy0123456789', 'QR-CODE WITH PARAMETERS'],
    'MICROQR' => ['0123456789', 'Micro QR Code (ISO/IEC 18004)'],
    'MICROQR,M,4,AN' => ['ABCDEFGHIJKLMNOPQR', 'Micro QR Code WITH PARAMETERS'],
    'HANXIN' => ['0123456789', 'Han Xin Code (GB/T 21049, ISO/IEC 20830)'],
    'HANXIN,L3,10,2' => ['1234567890ABCDEFGabcdefg,Han Xin Code', 'Han Xin Code WITH PARAMETERS'],
    'HIBCAZ' => ['+A123BJC5D6E71', 'HIBC in AZTEC Code (ANSI/HIBC 2.6 and ANSI/HIBC 1.3)'],
    'HIBCDM' => ['+A123BJC5D6E71', 'HIBC in DATAMATRIX (ANSI/HIBC 2.6 and ANSI/HIBC 1.3)'],
    'HIBCQR' => ['+A123BJC5D6E71', 'HIBC in QR-CODE (ANSI/HIBC 2.6 and ANSI/HIBC 1.3)'],
    'DATAMATRIX' => ['0123456789', 'DATAMATRIX (ISO/IEC 16022) SQUARE'],
    'DATAMATRIX,R' => [
        '0123456789012345678901234567890123456789',
        'DATAMATRIX Rectangular (ISO/IEC 16022) RECTANGULAR',
    ],
    'DATAMATRIX,S,GS1' => [
        \chr(232) . '01095011010209171719050810ABCD1234' . \chr(232) . '2110',
        'GS1 DATAMATRIX (ISO/IEC 16022) SQUARE GS1',
    ],
    'DATAMATRIX,R,GS1' => [
        \chr(232) . '01095011010209171719050810ABCD1234' . \chr(232) . '2110',
        'GS1 DATAMATRIX (ISO/IEC 16022) RECTANGULAR GS1',
    ],
    'DMRE' => ['A1B2C3D4E5F6G7H8I9J0K1L2', 'Data Matrix Rectangular Extension (ISO/IEC 21471)'],
    'DMRE,GS1' => [
        \chr(232) . '01095011010209171719050810ABCD1234' . \chr(232) . '2110',
        'GS1 Data Matrix Rectangular Extension (ISO/IEC 21471)',
    ],
    'DMRE,N,ASCII,8x144' => [
        'A1B2C3D4E5F6G7H8I9J0K1L2',
        'Data Matrix Rectangular Extension (ISO/IEC 21471) 8x144',
    ],
];

$barcode = new \Com\Tecnick\Barcode\Barcode();

$examples = '<h3>Linear</h3>' . "\n";
foreach ($linear as $type => $code) {
    [$lwidth, $lheight] = $linear_size[$type] ?? [-3, -30];
    $bobj = $barcode->getBarcodeObj($type, $code[0], $lwidth, $lheight, 'black', [0, 0, 0, 0]);
    $examples .=
        '<h4>[<span>'
        . $type
        . '</span>] '
        . $code[1]
        . '</h4><p style="font-family:monospace;">'
        . $bobj->getHtmlDiv()
        . '</p>'
        . "\n";
}

$examples .= '<h3>Square</h3>' . "\n";
foreach ($square as $type => $code) {
    $bobj = $barcode->getBarcodeObj($type, $code[0], -4, -4, 'black', [0, 0, 0, 0]);
    $examples .=
        '<h4>[<span>'
        . $type
        . '</span>] '
        . $code[1]
        . '</h4><p style="font-family:monospace;">'
        . $bobj->getHtmlDiv()
        . '</p>'
        . "\n";
}

$bobj = $barcode->getBarcodeObj('QRCODE,H', 'https://tecnick.com', -4, -4, 'black', [
    -2,
    -2,
    -2,
    -2,
])->setBackgroundColor('#f0f0f0');

echo
    '
<!DOCTYPE html>
<html>
    <head>
        <title>Usage example of tc-lib-barcode library</title>
        <meta charset="utf-8">
        <style>
            body {font-family:Arial, Helvetica, sans-serif;margin:30px;}
            table {border: 1px solid black;}
            th {border: 1px solid black;padding:4px;background-color:cornsilk;}
            td {border: 1px solid black;padding:4px;}
            h3 {color:darkblue;}
            h4 {color:darkgreen;}
            h4 span  {color:firebrick;}
        </style>
    </head>
    <body>
        <h1>Usage example of tc-lib-barcode library</h1>
        <p>This is a usage example of <a href="https://github.com/tecnickcom/tc-lib-barcode" title="tc-lib-barcode: PHP library to generate linear and bidimensional barcodes">tc-lib-barcode</a> library.</p>
        <h2>Output Formats</h2>
        <h3>PNG Image</h3>
        <p><img alt="Embedded Image" src="data:image/png;base64,'
        . \base64_encode($bobj->getPngData())
        . '" /></p>
        <h3>SVG Image</h3>
        <p style="font-family:monospace;">'
        . $bobj->getSvgCode()
        . '</p>
        <h3>HTML DIV</h3>
        <p style="font-family:monospace;">'
        . $bobj->getHtmlDiv()
        . '</p>
        <h3>Unicode String</h3>
        <pre style="font-family:monospace;line-height:0.61em;font-size:6px;">'
        . $bobj->getGrid((string) \json_decode('"\u00A0"'), (string) \json_decode('"\u2584"'))
        . '</pre>
        <h3>Binary String</h3>
        <pre style="font-family:monospace;">'
        . $bobj->getGrid()
        . '</pre>
        <h2>Barcode Types</h2>
        '
        . $examples
        . '
    </body>
</html>
';
