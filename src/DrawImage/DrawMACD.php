<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 22.02.19
 * Time: 10:27
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\Indicators\MACD\MACD;
use DrawOHLC\Indicators\MACD\MACDOhlc;
use Nette\Utils\Image;

class DrawMACD extends AbstractDrawIndicator {

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

	/**
	 * @var MACD
	 */
	private $macd;

	protected $fastColor;
	protected $slowColor;
	protected $histogramColor;


	protected function loadIndicator() {
	}


	public static function create(int $height=30,DrawOhlcList $drawOhlcList, int $fastLength=12, int $slowLength=26,int $signalSmoothing=9) {
		$class= new static($height,$drawOhlcList);
		$class->fastColor=Image::rgb(255,0,0);
		$class->slowColor=Image::rgb(0,0,255);
		$class->histogramColor=Image::rgb(0,255,0);
		$class->slowLength=$slowLength;
		$class->fastLength=$fastLength;
		$class->signalSmoothing=$signalSmoothing;
		$class->macd = MACD::create($class->drawOhlcList->getOhlcList(),$class->fastLength,$class->slowLength,$class->signalSmoothing);
		$class->color = Image::rgb(0,0,0);
		return $class;
	}

	/**
	 * @return int
	 */
	public function getHeight(): int {
		return $this->height;
	}

	public function draw() {

		$maxValue=$this->macd->getMaxValue()*2;
		$pxHeight=$this->getY2()-$this->getY1();
		$pxRatio=($pxHeight/$maxValue)*-1;
		$halfYposition=round($this->getY1()+$pxHeight/2);

		$xCentre=round($this->drawOhlcList->getCandelBodyWidth()/2);


		$histogramX1=$histogramX2=$histogramY1=$histogramY2=$x2=$x1=$slowY2=$slowY1=$fastY2=$fastY1=null;
		foreach ($this->macd as $macdOhlc){
			/**
			 * @var MACDOhlc $macdOhlc
			 */
			//$macdOhlc->get
			$position=$macdOhlc->getOhlc()->getPosition();
			$drawOhlc=$this->drawOhlcList->getDrawOhlcByPosition($position);
			$x1=intval(round($drawOhlc->getAbsolutOffsetX()+$xCentre));
			$slowY1=intval(round($halfYposition+$pxRatio*$macdOhlc->getMacd()));
			$fastY1=intval(round($halfYposition+$pxRatio*$macdOhlc->getSignal()));


			if(!is_null($x2) && $drawOhlc->isDrawPostion()){
				$histogramX1=intval($drawOhlc->getAbsolutOffsetX());
				$histogramX2=intval($drawOhlc->getAbsolutOffsetX()+$this->drawOhlcList->getCandelBodyWidth());
				$histogramY1=intval($halfYposition);
				$histogramY2=intval($halfYposition+$pxRatio*$macdOhlc->getHistogram());
				$this->getImage()->filledRectangle($histogramX1,$histogramY1,$histogramX2,$histogramY2,$this->histogramColor);

				$this->getImage()->line($x1,$fastY1,$x2,$fastY2,$this->fastColor);
				$this->getImage()->line($x1,$slowY1,$x2,$slowY2,$this->slowColor);
			}

			$x2=$x1;
			$slowY2=$slowY1;
			$fastY2=$fastY1;
		}


		parent::draw();
		$x=$this->getX1()+2;
		$y=$this->getY1()+$this->fontSize+2;
		$this->getImage()->ttfText($this->fontSize,0,$x,$y,$this->color,$this->fontPath,'MACD('.$this->fastLength.', '.$this->slowLength.', '.$this->signalSmoothing.')');
		$this->getImage()->line($this->getX1(),$this->getY2(),$this->getX2(),$this->getY2(),$this->color);
	}

	/**
	 * @return DrawOhlcList
	 */
	public function getDrawOhlcList(): DrawOhlcList {
		if ($this->drawOhlcList instanceof  DrawBgOhlcList)
			return $this->drawOhlcList->getDrawOhlcList();

		return $this->drawOhlcList;
	}

	/**
	 * @return MACD
	 */
	public function getMacd(): MACD {
		return $this->macd;
	}

}