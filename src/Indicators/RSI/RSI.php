<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 11.02.19
 * Time: 22:38
 */
namespace DrawOHLC\Indicators\RSI;

use DrawOHLC\MovingAverage\AbstractSingleValue;


class RSI extends AbstractSingleValue {


	const FUNCTION_NAME='trader_rsi';

	const SUB_CLASS_NAME='DrawOHLC\Indicators\RSI\RSIOhlc';


	public function getLabel() {
		return 'RSI'.$this->length;
	}
}