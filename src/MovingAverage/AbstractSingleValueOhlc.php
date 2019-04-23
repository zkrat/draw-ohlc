<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 22.10.18
 * Time: 12:18
 */

namespace DrawOHLC\MovingAverage;


use DrawOHLC\HistoryData\Ohlc;

abstract class AbstractSingleValueOhlc {

	const UNCOUNTABLE=TRUE;

	/**
	 * @var Ohlc
	 */
	protected $ohlc;

	protected $value;

	/**
	 * @var AbstractSingleValue
	 */
	protected $parent;

	protected function __construct($value,Ohlc $ohlc,AbstractSingleValue $singleValue) {
		$this->ohlc  =$ohlc;
		$this->value = $value;
		$this->parent =$singleValue;

	}

	public static function create($value,Ohlc $ohlc,AbstractSingleValue $singleValue) {
		return new static($value, $ohlc, $singleValue);
	}


	/**
	 * @return Ohlc
	 */
	public function getOhlc(): Ohlc {
		return $this->ohlc;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	public function isCountable():bool{
		return $this->getValue() !== AbstractSingleValueOhlc::UNCOUNTABLE;
	}

	/**
	 * @return mixed
	 */
	public function getPosition() {
		return $this->ohlc->getPosition();
	}



	/**
	 * @return AbstractSingleValue
	 */
	public function getParent(): AbstractSingleValue {
		return $this->parent;
	}

	/**
	 * @return bool
	 */
	public function hasPrev():bool{
		$position=$this->getPosition()-1;
		return $this->parent->hasPosition($position);
	}

	/**
	 * @return bool
	 */
	public function hasNext():bool{
		$position=$this->getPosition()+1;
		return $this->parent->hasPosition($position);
	}

	public function getPrev(){
		$position=$this->getPosition()-1;
		return $this->parent->getPosition($position);
	}

	public function getNext():AbstractSingleValueOhlc{
		$position=$this->getPosition()+1;
		return $this->parent->getPosition($position);
	}


}