<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 09.03.19
 * Time: 20:35
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\MovingAverage\AbstractSingleValue;
use DrawOHLC\MovingAverage\AbstractSingleValueOhlc;
use Nette\Utils\Image;

class DrawSingleValue  extends AbstractDrawCanvas {


	private $thickness=3;

	/**
	 * @var AbstractSingleValue
	 */
	private $singleValue;
	/**
	 * @var DrawOhlcList
	 */
	private $drawOhlcList;

	private static $yExtra=0;



	private function __construct(AbstractSingleValue $singleValue, DrawOhlcList $drawOhlcList ) {
		$this->singleValue=$singleValue;
		$this->drawOhlcList=$drawOhlcList;

		$drawOhlcList->addDrawCanvas($this);
		$multipicator=$this->drawOhlcList->getOhlcList()->getMultipicator();

		foreach ($this->singleValue as $singleValueOhlc){
			/**
			 * @var AbstractSingleValueOhlc $singleValueOhlc
			 */
			$position=$singleValueOhlc->getPosition();
			if($singleValueOhlc->isCountable() && $this->drawOhlcList->isDrawPosition($position)){
				$value=round($singleValueOhlc->getValue()*$multipicator)/$multipicator;
				$this->drawOhlcList->testMinValue($value);
				$this->drawOhlcList->testMaxValue($value);
			}

		}

	}


	public static function create(AbstractSingleValue $singleValue, DrawOhlcList $drawOhlcList ) {
		$class = new static($singleValue,$drawOhlcList);
		return $class;
	}

	public function draw() {
		parent::draw();
		$this->getImage()->setThickness($this->thickness);
		foreach ($this->singleValue as $singleValueOhlc){
			/**
			 * @var AbstractSingleValueOhlc $singleValueOhlc
			 */
			$position=$singleValueOhlc->getPosition();
			if($singleValueOhlc->isCountable() && $this->drawOhlcList->isDrawPosition($position)){

				$drawOhlc=$this->drawOhlcList->getDrawOhlcByPosition($position);

				$x1=$drawOhlc->getX1() + $this->drawOhlcList->getWickWidth();
				$y1=$this->drawOhlcList->countY($singleValueOhlc->getValue());

				if(isset($x2) && isset($y2)){
					$this->getImage()->line($x1,$y1,$x2,$y2,$this->color);
				}
				$x2=$x1;
				$y2=$y1;
			}
		}

		$x=$this->getX1()+intval(round($this->getWidth()/2));
		self::$yExtra += $this->fontSize+2;
		$y=$this->getY1()+self::$yExtra;
		$width=10;
		$label=$this->singleValue->getLabel();
		$this->getImage()->setThickness(3);
		$this->getImage()->line($x,$y-4,$x+$width,$y-4,$this->color);
		$this->getImage()->setThickness(1);
		$this->ttfText($x+$width+3,$y,$label);

	}

	/**
	 * @return int
	 */
	public function getThickness(): int {
		return $this->thickness;
	}

	/**
	 * @param int $thickness
	 */
	public function setThickness( int $thickness ): DrawSingleValue {
		$this->thickness = $thickness;
		return $this;
	}

	/**
	 * @return AbstractSingleValue
	 */
	public function getSingleValue(): AbstractSingleValue {
		return $this->singleValue;
	}


}