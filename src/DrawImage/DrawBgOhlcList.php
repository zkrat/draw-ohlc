<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 21.02.19
 * Time: 22:49
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\Helper\FontHelper;
use DrawOHLC\Helper\OhlcLengthHelper;
use DrawOHLC\HistoryData\OhlcList;
use DrawOHLC\DrawImage\Exception\DrawBgOhlcListException;
use Nette\Utils\Image;

class DrawBgOhlcList extends DrawOhlcList {

	/**
	 * @var string
	 */
	private $units='%f';


	/**
	 * @var int
	 */
	private $timeGrid;
	/**
	 * @var int
	 */
	private $priceGrid;

	/**
	 * @var DrawOhlcList
	 */
	private $drawOhlcList;

	protected $fontSize=8;

	private $colorTimeline;
	private $extraWidth=0;
	private $extraHeight=0;

	private $productName='';

	private $dateFormat='Y-m-d G:i';

	protected $timeFrame;

	protected $dateFormatTimeFrame='Y-m-d';


	protected function __construct(DrawOhlcList $drawOhlcList) {
		$this->drawOhlcList  =$drawOhlcList;
		$drawOhlcList->addDrawCanvas($this);
	}

	public static function create(OhlcList $ohlcList,AbstractDrawCanvas $parent  ):DrawOhlcList {
		throw new DrawBgOhlcListException(DrawBgOhlcListException::MSG_NOT_IMPLEMENTER,DrawBgOhlcListException::NOT_IMPLEMENTER);
	}

	/**
	 * @return OhlcList
	 */
	public function getOhlcList(): OhlcList {
		return $this->drawOhlcList->getOhlcList();
	}

	public function extraSize($width,$height){
		$this->extraWidth=$width;
		$this->extraHeight=$height;
	}


	public static function createBg(DrawOhlcList $drawOhlcList,int $timeGrid=3,int $priceGrid=4 ):DrawBgOhlcList {
		$class = new static($drawOhlcList);
		$class->timeGrid=$timeGrid;
		$class->priceGrid=$priceGrid;

		return $class;
	}
	public function draw() {
		$this->drawLegend();
		$y1=$this->getY1();
		$timeArray=$this->getPostionTimeArray();
		$y2=$this->getY2();

		foreach ($timeArray as $postion){
			$drawOhlc=$this->drawOhlcList->getDrawOhlcByPosition($postion);
			$x=intval($drawOhlc->getX1());
			$this->getImage()->line($x,$y1,$x,$y2,$this->colorTimeline);
			$text=$drawOhlc->getOhlc()->getDatetime()->format($this->dateFormatTimeFrame);

			[$w,$h]=$this->getFontBox($text);

			$this->getImage()->ttfText($this->fontSize,0,$x-$w,$y2-2,$this->colorTimeline,$this->fontPath,$text);
		}
		$priceArray=$this->getPostionPriceArray();


		$x1=$this->drawOhlcList->getX1();
		$x2=$this->drawOhlcList->getX2();
		$multiplicator=$this->getDrawOhlcList()->getOhlcList()->getMultipicator();
		foreach ($priceArray as $price){
			$y =$this->drawOhlcList->countY($price);
			$this->getImage()->line($x1,$y,$x2,$y,$this->colorTimeline);
			if($multiplicator!==1)
				$price=round($price*$multiplicator);
			$text=sprintf($this->units,$price);
			[$w,$h]=$this->getFontBox($text);
			$this->getImage()->ttfText($this->fontSize,0,$x2-$w,$y,$this->colorTimeline,$this->fontPath,$text);
		}
		$this->getImage()->rectangle($x1,$y1,$x2,$y2,$this->colorTimeline);
		parent::draw();
	}

	private function getPostionTimeArray(  ):array {
		$firstPostion = $this->drawOhlcList->getFirstDrawPosition();
		$lastPostion = $this->drawOhlcList->getOhlcList()->count();
		$pos=floor(($lastPostion-$firstPostion)/($this->timeGrid+1));
		$postionArray=[];
		for($i=1;$i<=$this->timeGrid;$i++){
			$postionArray[]=$firstPostion+$pos*$i;
		}
		return $postionArray;
	}

	private function drawLegend() {
		$fistPostion = $this->drawOhlcList->getFirstDrawPosition();
		$lastPostion = $this->drawOhlcList->getOhlcList()->count();
		$from =$this->drawOhlcList->getOhlcList()->getOhlcByPostion($fistPostion)->getDatetime()->format($this->dateFormat);
		$lastOhlc=$this->drawOhlcList->getOhlcList()->getOhlcByPostion($lastPostion);
		$to =$lastOhlc->getDatetime()->format($this->dateFormat);

		if($lastOhlc->hasPrevOhlc() && is_null($this->timeFrame)){
			$timeDiff =$lastOhlc->getDatetime()->getTimestamp()- $lastOhlc->getPrevOhlc()->getDatetime()->getTimestamp();
			$this->timeFrame=OhlcLengthHelper::getTimeFrame($timeDiff);
		}



		$fontSize=ceil($this->fontSize*1.3);
		$extraSize=intval(round($fontSize*0.25,0,PHP_ROUND_HALF_UP));
		$extraSizeY=intval(round($extraSize+ceil($extraSize*0.3))) ;
		$xm=$this->getX1()+$extraSize;
		$ym=$this->getY1()+$this->fontSize+$extraSizeY+2;
		$text=$this->getProductName();
		$textFromTo=$from . ' - ' . $to;
		[$w,$h]=$this->getFontBox($text);
		[$w2,$h2]=$this->getFontBox($textFromTo,$fontSize);


		;
		$fontDateSize=ceil($this->fontSize*1.2);
		$extraSize=intval(round($this->fontSize*0.25,0,PHP_ROUND_HALF_UP));
		$y=intval($this->getY1()+$this->fontSize+$fontDateSize+$this->fontSize+$extraSizeY+2);
		$x=$this->getX1()+$extraSize;
		$this->getImage()->filledRectangle($this->getX1(),$y,$x+$w2,$this->getY1(),$this->getBgColor());

		$this->ttfText($xm,$ym,$text,$this->colorTimeline,$fontSize);
		$this->ttfText($x,$y,$textFromTo,$this->colorTimeline);
	}

	private function getProductName(){
		return $this->productName;
	}

	private function getPostionPriceArray() {

		$height=$this->drawOhlcList->getMaxHigh()-$this->drawOhlcList->getMinLow();
		$step =$height/($this->priceGrid+1);

		$priceArray=[];

		for($i=1;$i<=$this->priceGrid;$i++){
			$priceArray[] = $this->drawOhlcList->getMinLow()+$step*$i;
		}
		return $priceArray;
	}

	/**
	 * @return DrawOhlcList
	 */
	public function getDrawOhlcList(): DrawOhlcList {
		return $this->drawOhlcList;
	}

	public function getCountOfCandels(){
		return $this->drawOhlcList->getCountOfCandels();
	}

	public function getMaxVolume(){
		return $this->drawOhlcList->getMaxVolume();
	}

	/**
	 * @return int
	 */
	public function getCandelBodyWidth(): int {
		return $this->drawOhlcList->getCandelBodyWidth();
	}

	/**
	 * @param mixed $productName
	 */
	public function setProductName( $productName ): DrawBgOhlcList {
		$this->productName = $productName;
		return $this;
	}

	/**
	 * @param string $dateFormat
	 *
	 * @return DrawBgOhlcList
	 */
	public function setDateFormat( string $dateFormat ): DrawBgOhlcList {
		$this->dateFormat = $dateFormat;
		return $this;
	}

	public function setUnits( $format='%f'):DrawBgOhlcList {
		$this->units=$format;

		return $this;

	}

	/**
	 * @param mixed $timeFrame
	 *
	 * @return DrawBgOhlcList
	 */
	public function setTimeFrame( $timeFrame ):DrawBgOhlcList {
		$this->timeFrame = $timeFrame;
		return $this;
	}

	/**
	 * @param string $dateFormatTimeFrame
	 *
	 * @return DrawBgOhlcList
	 */
	public function setDateFormatTimeFrame( string $dateFormatTimeFrame ): DrawBgOhlcList {
		$this->dateFormatTimeFrame = $dateFormatTimeFrame;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getColorTimeline(): ?array {
		return $this->colorTimeline;
	}

	/**
	 * @param array $colorTimeline
	 */
	public function setColorTimeline( array $colorTimeline ): void {
		$this->colorTimeline = $colorTimeline;
	}


}