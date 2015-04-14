<?php
/**
 * index.php
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

// autoloader when using Composer
require ('../vendor/autoload.php');

// autoloader when using RPM or DEB package installation
//require ('/usr/share/php/Com/Tecnick/Barcode/autoload.php');

$barcode = new \Com\Tecnick\Barcode\Barcode();
//$bobj = $barcode->getBarcodeObj('QRCODE,H', 'http://www.tecnick.com', -4, -4, 'black');
$bobj = $barcode->getBarcodeObj('C128', "HI34567A", -4, -100, 'black');

echo "
<!DOCTYPE html>
<html>
    <head>
        <title>Usage example of tc-lib-barcode library</title>
        <meta charset=\"utf-8\">
        <style>
            body {font-family:Arial, Helvetica, sans-serif;margin:30px;}
            table {border: 1px solid black;}
            th {border: 1px solid black;padding:4px;background-barcode:cornsilk;}
            td {border: 1px solid black;padding:4px;}
        </style>
    </head>
    <body>
        <h1>Barcode Example</h1>
        <p>This is an usage example of <a href=\"https://github.com/tecnickcom/tc-lib-barcode\" title=\"tc-lib-barcode: PHP library to generate linear and bidimensional barcodes\">tc-lib-barcode</a> library.</p>
        <h2>Output Formats</h2>
        <h3>Binary String</h3>
        <pre style=\"font-family:monospace;\">".$bobj->getGrid()."</pre>
        <h3>Unicode String</h3>
        <pre style=\"font-family:monospace;line-height:100%;font-size:6px;\">".$bobj->getGrid(json_decode('"\u00A0"'), json_decode('"\u2588"'))."</pre>
        <h3>HTML DIV</h3>
        <p style=\"font-family:monospace;\">".$bobj->getHtmlDiv()."</p>
        <h3>SVG Image</h3>
        <p style=\"font-family:monospace;\">".$bobj->getSvgCode()."</p>
        <h3>PNG Image</h3>
        <p><img alt=\"Embedded Image\" src=\"data:image/png;base64,".base64_encode($bobj->getPngData())."\" /></p>
    </body>
</html>
";
