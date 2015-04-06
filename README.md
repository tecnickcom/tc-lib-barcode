# tc-lib-barcode

*Please consider supporting this project by making a donation to <paypal@tecnick.com>*

* **category**    Library
* **package**     \Com\Tecnick\Barcode
* **author**      Nicola Asuni <info@tecnick.com>
* **copyright**   2015-2015 Nicola Asuni - Tecnick.com LTD
* **license**     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
* **link**        https://github.com/tecnick.com/tc-lib-barcode

## Description

This library includes utility PHP classes to generate linear and bidimensional barcodes:

* C39        : CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9
* C39+       : CODE 39 with checksum
* C39E       : CODE 39 EXTENDED
* C39E+      : CODE 39 EXTENDED + CHECKSUM
* C93        : CODE 93 - USS-93
* S25        : Standard 2 of 5
* S25+       : Standard 2 of 5 + CHECKSUM
* I25        : Interleaved 2 of 5
* I25+       : Interleaved 2 of 5 + CHECKSUM
* C128       : CODE 128
* C128A      : CODE 128 A
* C128B      : CODE 128 B
* C128C      : CODE 128 C
* EAN2       : 2-Digits UPC-Based Extension
* EAN5       : 5-Digits UPC-Based Extension
* EAN8       : EAN 8
* EAN13      : EAN 13
* UPCA       : UPC-A
* UPCE       : UPC-E
* MSI        : MSI (Variation of Plessey code)
* MSI+       : MSI + CHECKSUM (modulo 11)
* POSTNET    : POSTNET
* PLANET     : PLANET
* RMS4CC     : RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
* KIX        : KIX (Klant index - Customer index)
* IMB        : IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
* IMBPRE     : IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200- pre-processed
* CODABAR    : CODABAR
* CODE11     : CODE 11
* PHARMA     : PHARMACODE
* PHARMA2T   : PHARMACODE TWO-TRACKS
* DATAMATRIX : DATAMATRIX (ISO/IEC 16022)
* PDF417     : PDF417 (ISO/IEC 15438:2006)
* QRCODE     : QR-CODE
* RAW        : 2D RAW MODE comma-separated rows
* RAW2       : 2D RAW MODE rows enclosed in square parentheses

The initial source code has been extracted from TCPDF (<http://www.tcpdf.org>).


## Getting started

First, you need to install all development dependencies using [Composer](https://getcomposer.org/):

```bash
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
```

This project include a Makefile that allows you to test and build the project with simple commands.
To see all available options:

```bash
make help
```

To install all the development dependencies:

```bash
make build_dev
```

## Running all tests

Before committing the code, please check if it passes all tests using

```bash
make qa_all
```
this generates the phpunit coverage report in target/coverage.
Please check if the tests are covering all code.

Generate the documentation:

```bash
make docs
```

Generate static analysis reports in target/report:

```bash
make reports
```

Other make options allows you to install this library globally and build an RPM package.
Please check all the available options using `make help`.


## Example

Examples are located in the `example` directory.

Start a development server (requires PHP 5.5) using the command:

```
make server
```

and point your browser to <http://localhost:8000/index.php>


## Installation

Create a composer.json in your projects root-directory:

```json
{
    "require": {
        "tecnick.com/tc-lib-barcode": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:tecnickcom/tc-lib-barcode.git"
        }
    ]
}
```


## Packaging

This library is mainly intended to be used and included in other PHP projects using Composer.
However, since some production environments dictates the installation of any application as RPM or DEB packages,
this library includes make targets for building these packages (`make rpm` and `make deb`).
The packages are generated under the `target` directory.

When this library is installed using an RPM or DEB package, you can use it your code by including the autoloader:
```
require_once ('/usr/share/php/Com/Tecnick/Barcode/autoload.php');
```

## Developer(s) Contact

* Nicola Asuni <info@tecnick.com>
