<?php

namespace Com\Tecnick\Barcode\Type\Square;

use \Com\Tecnick\Barcode\Exception as BarcodeException;
use \Com\Tecnick\Barcode\Type\Square\Aztec\Encoder;

class Aztec extends \Com\Tecnick\Barcode\Type\Square
{
    /**
     * Barcode format
     *
     * @var string
     */
    protected $format = 'AZTEC';

    /**
     * Error correction level
     *
     * @var int
     */
    protected $ecc = 33;

    /**
     * Encoding mode
     *
     * @var string
     */
    protected $hint = 'dynamic';

    /**
     * Set extra (optional) parameters:
     *     0: ECC - Error correction level
     *     1: Encoding mode
     */
    protected function setParameters()
    {
        parent::setParameters();
        // ecc
        if (!isset($this->params[0]) OR !in_array($this->params[0], range(1, 200))) {
            $this->params[0] = 33;
        }
        $this->ecc = intval($this->params[0]);

        // hint
        if (!isset($this->params[1]) || !in_array($this->params[1], ["binary", "dynamic"])) {
            $this->params[1] = "dynamic";
        }
        $this->hint = $this->params[1];
    }

    protected function setBars()
    {
	$bits = (new Encoder())->encode($this->code, $this->ecc, $this->hint);

        // convert for tc-lib-barcode
        $w = count($bits);
        ksort($bits);
        for ($i = 0; $i < $w; ++$i) {
             ksort($bits[$i]);
        }
        for ($i = 0; $i < $w; ++$i) {
             for ($s = 0; $s < $w; ++$s) {
                  if (!array_key_exists($s, $bits[$i])) {
                      $bits[$i][$s] = 0;
                  }
             ksort($bits[$s]);
             }
        }
        for ($i = 0; $i < $w; ++$i) {
             for ($s = 0; $s < $w; ++$s) {
                  $pixelGrid[] = $bits[$s][$i];
             }
        }
        $this->processBinarySequence(str_split(implode($pixelGrid), $w));
    }

}
