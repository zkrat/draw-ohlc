<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 22.02.19
 * Time: 10:27
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\Indicators\RSI\RSI;
use DrawOHLC\Indicators\RSI\RSIOhlc;
use DrawOHLC\MovingAverage\UncountableSingleValueOhlc;
use Nette\Utils\Image;

class DrawRSI extends AbstractDrawIndicator {


	/**
	 * @var RSI
	 */
	private $rsi;

	private $topLine=70;
	private $bottomLine=30;
	/**
	 * @var array
	 */
	protected $colorBg;



	protected function loadIndicator(){
			$this->rsi = RSI::create($this->drawOhlcList->getOhlcList(),$this->length);
	}


	public static function create(int $height=30,DrawOhlcList $drawOhlcList,int $length=14) {
		$class= new static($height,$drawOhlcList,$length);
		$class->color=Image::rgb(160,30, 160);
		$class->colorBg=Image::rgb(241, 193, 241);
		return $class;
	}

	/**
	 * @return int
	 */
	public function getHeight(): int {
		return $this->height;
	}

	public function draw() {

		$pxHeight=$this->getY2()-$this->getY1();
		$pxRatio=$pxHeight/100;
		$y1=intval($this->getY2()-$pxRatio*$this->topLine);
		$y2=intval($this->getY2()-$pxRatio*$this->bottomLine);
		$this->getImage()->filledRectangle($this->getX1(),$y1,$this->getX2(),$y2,$this->colorBg);
		$this->getImage()->line($this->getX1(),$y1,$this->getX2(),$y1,$this->color);
		$this->getImage()->line($this->getX1(),$y2,$this->getX2(),$y2,$this->color);
		$x1=$x2=$y1=$y2=null;
		$xCentre=round($this->drawOhlcList->getCandelBodyWidth()/2);
		foreach ($this->rsi as $rsiOhlc){
			/**
			 * @var RSIOhlc $rsiOhlc
			 */

			$postion=$rsiOhlc->getOhlc()->getPosition();
			$drawOhlc=$this->drawOhlcList->getDrawOhlcByPosition($postion);

			if($drawOhlc->isDrawPostion() && !$rsiOhlc instanceof UncountableSingleValueOhlc){

				$x2=intval(round($drawOhlc->getAbsolutOffsetX()+$xCentre));
				$y2=intval(round($this->getY2()-$pxRatio*$rsiOhlc->getValue()));
				if ( !is_null($x1)&& !is_null($x2) && !is_null($y1) && !is_null($y2))
					$this->getImage()->line($x1,$y1,$x2,$y2,$this->color);

				$x1=$x2;
				$y1=$y2;
			}

		}
//		$black=Image::rgb(0,0,0);
//		$this->getImage()->ttfText($size,0,$volumeX,$volumeY,$black,ARIAL_FONT_DIR,'Max. volume: '.$volume);

		parent::draw();
		$x=$this->getX1()+2;
		$y=$this->getY1()+$this->fontSize+2;
		$text='RSI('.$this->length.')';
		$this->ttfText($x,$y,$text);
		$color=Image::rgb(0,0,0);
		$this->getImage()->line($this->getX1(),$this->getY2(),$this->getX2(),$this->getY2(),$color);
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
	 * @return int
	 */
	public function getTopLine(): int {
		return $this->topLine;
	}

	/**
	 * @return int
	 */
	public function getBottomLine(): int {
		return $this->bottomLine;
	}

	/**
	 * @param int $topLine
	 */
	public function setTopLine( int $topLine ): void {
		$this->topLine = $topLine;
	}

	/**
	 * @param int $bottomLine
	 */
	public function setBottomLine( int $bottomLine ): void {
		$this->bottomLine = $bottomLine;
	}


	/**
	 * @param array $colorBg
	 *
	 * @return DrawRSI
	 */
	public function setColorBg( array $colorBg ): DrawRSI {
		$this->colorBg = $colorBg;
		return $this;
	}





}