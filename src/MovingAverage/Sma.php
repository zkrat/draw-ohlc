<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 21.10.18
 * Time: 20:41
 */

namespace DrawOHLC\MovingAverage;






class Sma extends AbstractSingleValue {

	const FUNCTION_NAME='trader_sma';
	const SUB_CLASS_NAME='DrawOHLC\MovingAverage\SmaOhlc';


	public function getLabel() {
		return 'SMA'.$this->length;
	}
}