<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 07.03.19
 * Time: 23:37
 */

namespace DrawOHLC\DrawImage;


use DrawOHLC\HistoryData\Volume\VolumeAvg;
use DrawOHLC\HistoryData\Volume\VolumeAvgOhlc;
use Nette\Utils\Image;

class DrawAvgVolume extends AbstractDrawCanvas {

	/**
	 * @var DrawVolume
	 */
	private $drawVolume;

	/**
	 * @var int
	 */
	private $length;


	/**
	 * @var VolumeAvg
	 */
	private $volumeAvg;

	private $size=3;

	private function __construct(DrawVolume $drawVolume ,$length=21 ) {
		$this->drawVolume=$drawVolume;
		$this->length=$length;
		$ohlcList=$this->drawVolume->getDrawOhlcList()->getOhlcList();
		$this->volumeAvg=VolumeAvg::create($ohlcList,$length);
		$drawVolume->addDrawCanvas($this);
	}


	public static function create(DrawVolume $drawVolume ,$length=21 ) {
		return new static($drawVolume,$length);
	}

	public function draw() {
		parent::draw();

		$x1=$y1=$x2=$y2=null;
		$xCentre=round($this->drawVolume->getDrawOhlcList()->getCandelBodyWidth()/2);

		$this->getImage()->setThickness($this->size);
		foreach ($this->volumeAvg as $volumeAvgOhlc){
			/**
			 * @var VolumeAvgOhlc $volumeAvgOhlc;
			 */

			$color=Image::rgb(0,0,0);

			$postion=$volumeAvgOhlc->getOhlc()->getPosition();
			$drawOhlc=$this->drawVolume->getDrawOhlcList()->getDrawOhlcByPosition($postion);

			if($drawOhlc->isDrawPostion()){

				$x2=intval($drawOhlc->getAbsolutOffsetX()+$xCentre);
				$y2=intval($this->drawVolume->getYPxByVolume($volumeAvgOhlc->getAvgValue()));

				if ( !is_null($x1)&& !is_null($x2) && !is_null($y1) && !is_null($y2))
					$this->getImage()->line($x1,$y1,$x2,$y2,$color);

				$x1=$x2;
				$y1=$y2;
			}

		}
		$this->getImage()->setThickness(1);

	}

	/**
	 * @param int $size
	 *
	 * @return DrawAvgVolume
	 */
	public function setSize( int $size ): DrawAvgVolume {
		$this->size = $size;
		return $this;
	}


}