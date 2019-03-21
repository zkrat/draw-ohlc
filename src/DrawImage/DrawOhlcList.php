<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 14.02.19
 * Time: 20:24
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\HistoryData\Ohlc;
use DrawOHLC\HistoryData\OhlcList;
use Nette\Utils\Image;

class DrawOhlcList extends AbstractDrawCanvas {

	const BORDER_WIDTH=2;

	const CANDEL_WICK_WIDTH=1;

	const CANDEL_BODY_WIDTH=5;

	protected $colorBerish;

	protected $colorBullish;

	protected static $borderWidth=DrawOhlcList::BORDER_WIDTH;

	protected static $candelBodyWidth=DrawOhlcList::CANDEL_BODY_WIDTH;
	protected $candelWickWidth=DrawOhlcList::CANDEL_WICK_WIDTH;

	/**
	 * @var OhlcList
	 */
	protected $ohlcList;

	/**
	 * @var array
	 */
	protected $dataByPosition=[];

	protected $minLow;

	protected $maxHigh;

	protected $maxVolume;

	protected $minVolume;

	protected $wickColor;

	/**
	 * @return mixed
	 */
	public function getMaxVolume() {
		return $this->maxVolume;
	}

	protected function __construct(OhlcList $ohlcList,AbstractDrawCanvas $parent=null) {
		$this->ohlcList     =$ohlcList;
		$this->colorBerish  =Image::rgb(255,0,0);
		$this->colorBullish =Image::rgb(0,255,0);

		$this->wickColor=Image::rgb(90,90,90);
		if(!is_null($parent))
			$parent->addDrawCanvas($this);

		$this->setOffset(0,0);
		$this->countWidths();

		$this->load();
	}

	public function countY($value){
		$volueSize = $this->getMaxHigh() - $this->getMinLow();
		$heightPX  = $this->getY2() - $this->getY1();
		$volumePxY = ($heightPX/$volueSize);

		$x=$this->getY2()-$volumePxY*($value- $this->getMinLow());
		return intval(round($x));
	}

	public function load(){
		$firstPosition=$this->getFirstDrawPosition();
		foreach ($this->getOhlcList() as $ohlc){
			/**
			 * @var Ohlc $ohlc
			 */
			DrawOhlc::create($ohlc,$this);
			if($ohlc->getPosition()>=$firstPosition){
				$this->testMinValue($ohlc->getLow());
				$this->testMaxValue($ohlc->getHigh());
				$this->testMaxValume($ohlc->getVolume());
			}

		}
	}

	public function addDrawPosition(DrawOhlc $drawOhlc){
		$this->dataByPosition[$drawOhlc->getOhlc()->getPosition()]=$drawOhlc;

	}

	public static function create( OhlcList $ohlcList,AbstractDrawCanvas $parent  ):DrawOhlcList {
		$class = new static($ohlcList,$parent);
		return $class;
	}


	/**
	 * @param array $colorBerish
	 *
	 * @return DrawOhlcList
	 */
	public function setColorBerish( array $colorBerish ): DrawOhlcList {
		$this->colorBerish = $colorBerish;
		return $this;
	}

	/**
	 * @param array $colorBullish
	 *
	 * @return DrawOhlcList
	 */
	public function setColorBullish( array $colorBullish ): DrawOhlcList {
		$this->colorBullish = $colorBullish;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getColorBearish(): array {
		return $this->colorBerish;
	}

	/**
	 * @return array
	 */
	public function getColorBullish(): array {
		return $this->colorBullish;
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
	public function getBorderWidth(): int {
		return self::$borderWidth;
	}

	/**
	 * @param int $borderWidth
	 *
	 * @return DrawOhlcList
	 */
	public function setBorderWidth( int $borderWidth ): DrawOhlcList {
		self::$borderWidth = $borderWidth;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCandelBodyWidth(): int {
		return self::$candelBodyWidth;
	}

	/**
	 * @param int $candelBodyWidth
	 */
	public function setCandelBodyWidth( int $candelBodyWidth ): DrawOhlcList {
		self::$candelBodyWidth = $candelBodyWidth;
		return $this;

	}

	public function isDrawPosition($postion){
		return $postion>=$this->getFirstDrawPosition();
	}

	public function getFirstDrawPosition(){
		$count=$this->getOhlcList()->count()-$this->getCountOfCandels()+2;
		if($count<1)
			return 1;
		return $count;
	}

	public static function  getCountOfCandelsByWidth($width){
		return floor( ($width-self::$borderWidth) / ( self::$candelBodyWidth + self::$borderWidth));
	}

	public function getCountOfCandels(){
		return floor( ($this->getWidth()-self::$borderWidth) / ( self::$candelBodyWidth + self::$borderWidth));
	}

	public function getPostionWidth(int $postion=null){
		if(is_null($postion)){
			$count1= $this->getCountOfCandels();
			$count2=$count1+1;
		}else{
			$count2=$count1=$postion-$this->getFirstDrawPosition()+1;
		}
		return self::$candelBodyWidth * $count1 + self::$borderWidth * ( $count2);
	}

	protected function countWidths() {
		$ratio = $this->getWidth()/$this->getPostionWidth();


		if($ratio>1){
			$borderTotalWidth=null;
			$count                 = $this->getOhlcList()->count();
			self::$borderWidth     =min(round(self::$borderWidth*$ratio),5);
			$borderTotalWidth      =($count+1)*self::$borderWidth;
			self::$candelBodyWidth =max(self::$candelBodyWidth,floor( ( $this->getWidth() - $borderTotalWidth) / $count));
		}
	}

	/**
	 * @return string
	 */
	public function getCandelWickWidth(): string {
		return $this->candelWickWidth;
	}

	/**
	 * @param string $candelWickWidth
	 */
	public function setCandelWickWidth( string $candelWickWidth ): DrawOhlcList {
		$this->candelWickWidth = $candelWickWidth;
		return $this;
	}

	public function getDrawOhlcByPosition( $postion ):DrawOhlc {
		return $this->dataByPosition[$postion];
	}

	public function getMaxHigh() {
		return $this->maxHigh;
	}

	public function getMinLow() {
		return $this->minLow;
	}

	public function testMinValue( $value ) {
		if (is_null($this->minLow))
			$this->minLow=$value;
		else
			$this->minLow=min($this->minLow,$value);
	}

	public function testMaxValue( $value ) {
		if (is_null($this->maxHigh))
			$this->maxHigh=$value;
		else
			$this->maxHigh=max($this->maxHigh,$value);
	}

	public function testMaxValume( $valume ) {
		if ( is_null( $this->maxVolume ) ) {
			$this->maxVolume = $valume;
		} else {
			$this->maxVolume = max( $this->maxVolume, $valume );
		}
	}

	/**
	 * @return mixed
	 */
	public function getWickColor() {
		return $this->wickColor;
	}

	/**
	 * @param mixed $wickColor
	 */
	public function setWickColor( $wickColor ): DrawOhlcList {
		$this->wickColor = $wickColor;
		return $this;
	}

}