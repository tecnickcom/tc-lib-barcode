<?php

namespace Com\Tecnick\Barcode\Type\Square\Aztec;

class Token implements \Countable
{
	protected $history = [];
	protected $mode = 0;
	protected $shiftByteCount = 0;
	protected $bitCount = 0;

	public function setState($mode, $binaryBytes, $bitCount = 0)
	{
		$this->mode = $mode;
		$this->shiftByteCount = $binaryBytes;
		$this->bitCount += $bitCount;
	}

	public function getMode()
	{
		return $this->mode;
	}

	public function getShiftByteCount()
	{
		return $this->shiftByteCount;
	}

//        #[\ReturnTypeWillChange]
	public function count(): int
	{
		return $this->bitCount;
	}

	public function getHistory()
	{
		return $this->history;
	}

	public function add($value, $bits)
	{
		$this->bitCount += $bits;
		$this->history[] = [$value, $bits];
	}

	public function endBinaryShift()
	{
		$this->shiftByteCount = 0;
	}
}
