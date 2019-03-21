<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 28.01.19
 * Time: 13:44
 */

namespace Models\HistoryData\Statistics;


use Helper\Math;
use Models\HistoryData\Ohlc;
use Models\HistoryData\OhlcList;

class NormalDistributionOhlcList extends NormalDistribution {

	/**
	 * @var int
	 */
	private $multimiplicator=1;

	/**
	 * @var OhlcList
	 */
	private $ohlcList;

	public static function createOhlcList(OhlcList $ohlcList, $multimiplicator=1):NormalDistributionOhlcList {
		$class= new static();
		$class->ohlcList=$ohlcList;
		$class->multimiplicator=$multimiplicator;
		$class->run();
		return $class;

	}

	private function run() {
		foreach ($this->ohlcList as $ohlc){
			/**
			 * @var Ohlc $ohlc
			 */

			$min=round($ohlc->getLow()*$this->multimiplicator);
			$max =round($ohlc->getHigh()*$this->multimiplicator);


			if ($min<$max){
				$range=range($min,$max);
				foreach ($range as $value){
					if ($value!==$min && $value!==$max)
						$this->addValue($value);
				}
			}elseif($min==$max){
//				$this->addValue($min);
			}

		}
	}

}