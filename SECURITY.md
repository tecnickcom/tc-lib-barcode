# Security Policy (tc-lib-barcode 1.x)

This policy covers the tc-lib-barcode 1.x series, the code shipped in this branch.

## CRITICAL NOTICE

**tc-lib-barcode 1.x is DEPRECATED, unmaintained, and receives no security fixes.**

No security patches, advisories, or CVE remediations will be issued for this series. Known and unknown vulnerabilities in this code base will remain unfixed.

Using tc-lib-barcode 1.x in production constitutes [CWE-1104: Use of Unmaintained Third Party Components](https://cwe.mitre.org/data/definitions/1104.html).

## Structural Vulnerability Classes

The list below describes structural exposure inherent to the 1.x design. It is not an inventory of specific known defects.

| Class | CWE | Origin in tc-lib-barcode 1.x |
|---|---|---|
| Reliance on an unsupported runtime | [CWE-1104](https://cwe.mitre.org/data/definitions/1104.html) | The package declares `php >= 5.6` and keeps code paths for PHP versions that no longer receive security fixes. |
| Uncontrolled resource consumption | [CWE-400](https://cwe.mitre.org/data/definitions/400.html), [CWE-1333](https://cwe.mitre.org/data/definitions/1333.html) | The 2D encoders (QR Code, Datamatrix, PDF417, Aztec) build matrices and run recursive splitting and regular expressions over unbounded caller input. |
| Unchecked allocation from caller-supplied dimensions | [CWE-789](https://cwe.mitre.org/data/definitions/789.html), [CWE-681](https://cwe.mitre.org/data/definitions/681.html) | Width, height, and padding multipliers are converted to integers and passed to GD image allocation without an upper bound. |
| Insufficient input validation | [CWE-20](https://cwe.mitre.org/data/definitions/20.html) | Barcode payloads and the comma-separated type parameters are parsed with minimal length and range checking, and several encoders index fixed tables with caller-derived values. |
| Error messages containing caller input | [CWE-209](https://cwe.mitre.org/data/definitions/209.html) | `BarcodeException` messages embed the rejected barcode type, check digit, or character code, which is reflected back to the caller. |

## Supported Versions

| Version | Supported |
|---|---|
| 2.x | ✅ Yes |
| 1.x | ❌ No |
| < 1.x | ❌ No |

No release in the 1.x series or earlier is supported.

## Reporting a Vulnerability

Vulnerability reports against tc-lib-barcode 1.x are not accepted and will not be triaged, patched, or assigned an advisory.

Report vulnerabilities affecting the maintained 2.x series privately to info@tecnick.com with the subject line `[SECURITY] tc-lib-barcode - <brief description>`. **Do not open a public GitHub issue for security vulnerabilities.**

## Required Action

Upgrade to tc-lib-barcode 2.x:

```bash
composer require tecnickcom/tc-lib-barcode ^2
```

Until the upgrade is complete, treat every tc-lib-barcode 1.x deployment as an accepted risk:

- Never pass unvalidated user input as barcode data or as barcode type parameters.
- Bound the length of the encoded data and the width, height, and padding values before calling the library.
- Do not surface `BarcodeException` messages to end users.
- Record the exposure in the project risk register.
