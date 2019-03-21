<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 07.03.19
 * Time: 23:11
 */
namespace DrawOHLC\HistoryData\Volume;



use DrawOHLC\Collection\DataCollection;
use DrawOHLC\HistoryData\OhlcList;

class VolumeAvg extends DataCollection {

	/**
	 * @var OhlcList
	 */
	private $ohlcList;

	/**
	 * @var int
	 */
	private $length;

	private function __construct(OhlcList $ohlcList, int $length) {
		$this->ohlcList=$ohlcList;
		$this->length=$length;

		$this->load();
	}


	public static function create(OhlcList $ohlcList, int $length=21 ):VolumeAvg {
		return new static($ohlcList,$length);
	}

	private function load() {

		if($this->ohlcList->count()>=$this->length){
			$volumeArray=$this->ohlcList->getVolumeArray();
			$avgArray = trader_sma($volumeArray,$this->length);
			foreach ($this->ohlcList as $ohlc){
				/**
				 * @var Ohlc $ohlc
				 */
				$position=$ohlc->getPosition();
				$avgVolume = isset($avgArray[$position-1]) ? $avgArray[$position-1] : VolumeAvgOhlc::UNCOUNTABLE;
				$volumeAvgOhlc= VolumeAvgOhlc::create($ohlc,$avgVolume,$this);
				$this->data[$position]=$volumeAvgOhlc;
			}
		}
	}
}