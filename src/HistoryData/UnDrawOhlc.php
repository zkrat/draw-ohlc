<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 11.05.19
 * Time: 13:38
 */

namespace DrawOHLC\HistoryData;


use DrawOHLC\DrawImage\IUndrawCanvas;
use Nette\Utils\DateTime;

class UnDrawOhlc extends Ohlc implements IUndrawCanvas {
	/**
	 * @var Ohlc
	 */
	private $ohlc;



	public static function createFromOhlc(Ohlc $ohlc,OhlcList $ohlcList=null) {
		$class = new static();;
		$class->ohlc=$ohlc;
		$class->parent=$ohlcList;
		$class->position=null;
		if ($class->parent instanceof OhlcList)
			$class->parent->addOhlc($class);

		return $class;
	}

	public function getLow(){
		return $this->ohlc->getLow();
	}

	public function getDatetime():DateTime{
		return $this->ohlc->getDatetime();
	}

	public function getHigh(){
		return $this->ohlc->getHigh();
	}

	public function getOpen(){
		return $this->ohlc->getOpen();
	}

	public function getClose(){
		return $this->ohlc->getClose();
	}

	public function getTradesCount(){
		return $this->ohlc->getTradesCount();
	}

	public function getValue($type){
		return $this->ohlc->getValue($type);
	}

	public function getVolume(){
		return $this->ohlc->getVolume();
	}
}