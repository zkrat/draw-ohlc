<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 12.02.19
 * Time: 9:51
 */
namespace DrawOHLC\Indicators\MACD;

use DrawOHLC\Collection\DataCollection;
use DrawOHLC\HistoryData\Ohlc;
use DrawOHLC\HistoryData\OhlcList;



class MACD extends DataCollection {
	/**
	 * @var OhlcList
	 */
	private $ohlcList;

	/**
	 * @var int
	 */
	private $fastLength;

	/**
	 * @var int
	 */
	private $slowLength;
	/**
	 * @var int
	 */
	private $signalSmoothing;

	private $position=1;



	private $maxValue=null;


	private function __construct(OhlcList $ohlcList, int $fastLength=12, int $slowLength=26,int $signalSmoothing=9 ) {
		$this->ohlcList   =$ohlcList;
		$this->fastLength =$fastLength;
		$this->slowLength =$slowLength;
		$this->signalSmoothing=$signalSmoothing;
		$this->load();
	}


	public static function create(OhlcList $ohlcList, int $fastLength=12, int $slowLength=26,int $signalSmoothing=9 ):MACD {
		return new static($ohlcList,$fastLength,$slowLength,$signalSmoothing);
	}

	private function load() {
		$array=$this->ohlcList->getCloseArray($this->ohlcList->getMultipicator());
		$macdArray=trader_macd($array,$this->fastLength,$this->slowLength,$this->signalSmoothing);
		foreach ($this->ohlcList as $ohlc){
			/**
			 * @var Ohlc $ohlc
			 */
			$arrayKey=$ohlc->getPosition()-1;
			$macdValue=isset($macdArray[0][$arrayKey])? $macdArray[0][$arrayKey] : MACDOhlc::UNCOUNTABLE;
			$signal=isset($macdArray[1][$arrayKey])? $macdArray[1][$arrayKey] : MACDOhlc::UNCOUNTABLE;
			$historgam=isset($macdArray[2][$arrayKey])? $macdArray[2][$arrayKey] : MACDOhlc::UNCOUNTABLE;
			MACDOhlc::createMACD($ohlc,$this,$macdValue,$signal,$historgam);

			if ($macdValue!==MACDOhlc::UNCOUNTABLE){
				if(is_null($this->maxValue))
					$this->maxValue=abs($macdValue);
				$this->maxValue=max($this->maxValue,abs($macdValue),abs($signal),abs($historgam));
			}


		}
	}

	public function addMACDOhlc(MACDOhlc $MACDOhlc){
		$this->data[$this->position]=$MACDOhlc;
		$this->position++;
	}

	public function hasMACDOhlc($position):bool{
		return isset($this->data[$position]);
	}

	public function getMACDOhlc($position):MACDOhlc{
		return $this->data[$position];
	}


	/**
	 * @return int
	 */
	public function getFastLength(): int {
		return $this->fastLength;
	}

	/**
	 * @return int
	 */
	public function getSlowLength(): int {
		return $this->slowLength;
	}

	/**
	 * @return OhlcList
	 */
	public function getOhlcList(): OhlcList {
		return $this->ohlcList;
	}

	/**
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}

	/**
	 * @return int
	 */
	public function getSignalSmoothing(): int {
		return $this->signalSmoothing;
	}


	/**
	 * @return float
	 */
	public function getMaxValue() {
		return $this->maxValue;
	}



}