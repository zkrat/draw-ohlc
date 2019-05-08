<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 07.03.19
 * Time: 23:13
 */
namespace DrawOHLC\HistoryData\Volume;


use DrawOHLC\HistoryData\Ohlc;
use DrawOHLC\MovingAverage\AbstractSingleValueOhlc;

class VolumeAvgOhlc extends AbstractSingleValueOhlc {


	private $avgValue;

	/**
	 * @var VolumeAvg
	 */
	protected $parent;

	const UNCOUNTABLE=true;

	/**
	 * @return Ohlc
	 */
	public function getOhlc(): Ohlc {
		return $this->ohlc;
	}

	/**
	 * @return mixed
	 */
	public function getAvgValue() {
		return $this->avgValue;
	}




}