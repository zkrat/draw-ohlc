<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 12.02.19
 * Time: 9:51
 */
namespace DrawOHLC\Indicators\MACD;


use DrawOHLC\HistoryData\Ohlc;

class MACDOhlc {
	/**
	 * @var  Ohlc
	 */
	private $ohlc;
	/**
	 * @var MACD
	 */
	private $macdClass;

	private $macd;

	private $signal;

	private $histogram;

	private $postion;

	const UNCOUNTABLE=true;

	public function __construct(Ohlc $ohlc, MACD $macd) {
		$this->ohlc      =$ohlc;
		$this->macdClass =$macd;
		$this->postion   =$ohlc->getPosition();

	}

	public static function createMACD( Ohlc $ohlc, MACD $macd,$macdValue,$signal,$historgam ) {
		$class = new static($ohlc,$macd);
		$macd->addMACDOhlc($class);
		$class->macd=$macdValue;
		$class->signal=$signal;
		$class->histogram=$historgam;
		return $class;

	}

	public static function create( Ohlc $ohlc, MACD $macd ) {
		$class = new static($ohlc,$macd);
		$macd->addMACDOhlc($class);
		return $class;

	}

	/**
	 * @return Ohlc
	 */
	public function getOhlc(): Ohlc {
		return $this->ohlc;
	}

	/**
	 * @return MACD
	 */
	public function getMacdClass(): MACD {
		return $this->macdClass;
	}

	/**
	 * @return mixed
	 */
	public function getMacd() {
		return $this->macd;
	}

	/**
	 * @return mixed
	 */
	public function getSignal() {
		return $this->signal;
	}

	/**
	 * @return mixed
	 */
	public function getHistogram() {
		return $this->histogram;
	}

	/**
	 * @return int
	 */
	public function getPostion(): int {
		return $this->postion;
	}



}