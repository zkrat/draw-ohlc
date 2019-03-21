<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 28.02.19
 * Time: 16:47
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\HistoryData\OhlcList;

class DrawBasicImage {

	/**
	 * @var OhlcList
	 */
	private $ohlcList;

	/**
	 * @var int
	 */
	private $width;

	/**
	 * @var int
	 */
	private $height;

	/**
	 * @var int
	 */
	private $margin=12;


	/**
	 * @var int
	 */
	private $border=0;

	/**
	 * @var int
	 */
	private $padding=0;

	private $rsiHeight=75;

	private $macdHeight=75;

	private $volumeHeight=75;

	private $showRSI=true;

	private $showMACD=true;

	private $showVolume=true;

	private function __construct(OhlcList $ohlcList,int $width, int $height) {
		$this->ohlcList=$ohlcList;
		$this->width=$width;
		$this->height=$height;
	}


	public static function create( OhlcList $ohlcList,int $width=800, int $height=500 ) {
		$class =new  static($ohlcList,$width,$height);
		return $class;



	}

	public function drawImage(){
		$canvas=DrawCanvas::createCanvas($this->width,$this->height);
		$border=DrawBorder::create($canvas,$this->margin,$this->border,$this->padding);
		$drawBgOhlc=DrawBgOhlcList::createBg($this->ohlcList,$border);

		if($this->showRSI)
			DrawRSI::create($this->rsiHeight,$drawBgOhlc);

		if($this->showMACD)
			DrawMACD::create($this->macdHeight,$drawBgOhlc);
		if($this->showVolume)
			DrawVolume::create($this->volumeHeight,$drawBgOhlc);

		$canvas->drawImage();
	}

	/**
	 * @return int
	 */
	public function getWidth(): int {
		return $this->width;
	}

	/**
	 * @param int $width
	 *
	 * @return DrawBasicImage
	 */
	public function setWidth( int $width ): DrawBasicImage {
		$this->width = $width;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHeight(): int {
		return $this->height;
	}

	/**
	 * @param int $height
	 *
	 * @return DrawBasicImage
	 */
	public function setHeight( int $height ): DrawBasicImage {
		$this->height = $height;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMargin(): int {
		return $this->margin;
	}

	/**
	 * @param int $margin
	 *
	 * @return DrawBasicImage
	 */
	public function setMargin( int $margin ): DrawBasicImage {
		$this->margin = $margin;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBorder(): int {
		return $this->border;
	}

	/**
	 * @param int $border
	 *
	 * @return DrawBasicImage
	 */
	public function setBorder( int $border ): DrawBasicImage {
		$this->border = $border;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPadding(): int {
		return $this->padding;
	}

	/**
	 * @param int $padding
	 *
	 * @return DrawBasicImage
	 */
	public function setPadding( int $padding ): DrawBasicImage {
		$this->padding = $padding;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRsiHeight(): int {
		return $this->rsiHeight;
	}

	/**
	 * @param int $rsiHeight
	 *
	 * @return DrawBasicImage
	 */
	public function setRsiHeight( int $rsiHeight ): DrawBasicImage {
		$this->rsiHeight = $rsiHeight;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMacdHeight(): int {
		return $this->macdHeight;
	}

	/**
	 * @param int $macdHeight
	 *
	 * @return DrawBasicImage
	 */
	public function setMacdHeight( int $macdHeight ): DrawBasicImage {
		$this->macdHeight = $macdHeight;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVolumeHeight(): int {
		return $this->volumeHeight;
	}

	/**
	 * @param int $volumeHeight
	 *
	 * @return DrawBasicImage
	 */
	public function setVolumeHeight( int $volumeHeight ): DrawBasicImage {
		$this->volumeHeight = $volumeHeight;
		return $this;
	}


	public function setAllIndicatorHeight( int $height ): DrawBasicImage {
		$this->volumeHeight = $height;
		$this->rsiHeight = $height;
		$this->macdHeight=$height;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isShowRSI(): bool {
		return $this->showRSI;
	}

	/**
	 * @return DrawBasicImage
	 */
	public function showRSI( ): DrawBasicImage {
		$this->showRSI = true;
		return $this;
	}

	/**
	 * @return DrawBasicImage
	 */
	public function hideRSI( ): DrawBasicImage {
		$this->showRSI = false;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isShowMACD(): bool {
		return $this->showMACD;
	}

	/**
	 * @return DrawBasicImage
	 */
	public function showMACD(  ): DrawBasicImage {
		$this->showMACD = true;
		return $this;
	}

	/**
	 * @return DrawBasicImage
	 */
	public function hideMACD(  ): DrawBasicImage {
		$this->showMACD = false;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isShowVolume(): bool {
		return $this->showVolume;
	}

	/**
	 * @return DrawBasicImage
	 */
	public function showVolume():DrawBasicImage {
		$this->showVolume = true;
		return $this;
	}

	/**
	 * @return DrawBasicImage
	 */
	public function hideVolume():DrawBasicImage {
		$this->showVolume = false;
		return $this;
	}

}