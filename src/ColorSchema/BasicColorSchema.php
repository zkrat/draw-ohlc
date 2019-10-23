<?php


namespace DrawOHLC\ColorSchema;


use DrawOHLC\DrawImage\DrawAvgVolume;
use DrawOHLC\DrawImage\DrawBgOhlcList;
use DrawOHLC\DrawImage\DrawCanvas;
use DrawOHLC\DrawImage\DrawMACD;
use DrawOHLC\DrawImage\DrawOhlc;
use DrawOHLC\DrawImage\DrawRSI;
use DrawOHLC\DrawImage\DrawVolume;
use Nette\Utils\Image;
use DrawOHLC\DrawImage\DrawOhlcList;
use DrawOHLC\DrawImage\DrawSingleValue;
use DrawOHLC\DrawImage\DrawBorder;

class BasicColorSchema extends AbstractColorSchema {

	protected function getWhite(int $transparency = 0):array {
		return Image::rgb(255,255,255,$transparency);

	}

	protected function getBlack(int $transparency = 0):array{
		return Image::rgb(0,0,0,$transparency);
	}

	protected function configure() {


		$this->colorArray[DrawCanvas::class]['setBgColor']=$this->getWhite();
		$this->colorArray[DrawCanvas::class]['setColor']=$this->getBlack();


		$this->colorArray[DrawBorder::class]['setBorderColor']=$this->getWhite();
		$this->colorArray[DrawBorder::class]['setColor']=Image::rgb(10,0,0);


		$this->colorArray[DrawOhlcList::class]['setWickColor']=$this->getBlack();
		$this->colorArray[DrawBgOhlcList::class]['setWickColor']=$this->getBlack();
		$this->colorArray[DrawOhlcList::class]['setColorBullish']=Image::rgb(32,255,0);

		$this->colorArray[DrawOhlcList::class]['setColorBerish']=Image::rgb(255,0,0);
		$this->colorArray[DrawOhlcList::class]['setColor']=Image::rgb(20,0,0);




		$this->colorArray[DrawSingleValue::class]['setBgColor']=Image::rgb(128,50,0);
		$this->colorArray[DrawOhlc::class]['setColor']=Image::rgb(30,0,0);
		$this->colorArray[DrawOhlc::class]['setBgColor']=Image::rgb(40,0,0);

		$this->colorArray[DrawRSI::class]['setBgColor']=Image::rgb(241, 193, 241);
		$this->colorArray[DrawRSI::class]['setColor']=Image::rgb(160,30, 160);

		$this->colorArray[DrawOhlcList::class]['setBgColor']=Image::rgb(50,0,0);
		$this->colorArray[DrawBorder::class]['setColor']=$this->getWhite();


		$this->colorArray[DrawVolume::class]['setBgColor']=Image::rgb(99,99,99);
		$this->colorArray[DrawAvgVolume::class]['setBgColor']=Image::rgb(70,255,0);
		$this->colorArray[DrawAvgVolume::class]['setColor']=$this->getWhite();
		$this->colorArray[DrawBgOhlcList::class]['setColorBerish']=Image::rgb(255,0,0);
		$this->colorArray[DrawBgOhlcList::class]['setColorBullish']=Image::rgb(80,0,255);

		$this->colorArray[DrawBgOhlcList::class]['setBgColor']=Image::rgb(255,0,255,10);;
		$this->colorArray[DrawBgOhlcList::class]['setColor']=$this->getWhite();

		$this->colorArray[DrawBorder::class]['setBgColor']=$this->getBlack();
		$this->colorArray[DrawSingleValue::class]['setColor']=Image::rgb(33,24,0);
		$this->colorArray[DrawBgOhlcList::class]['setColorTimeline']=Image::rgb(99,99,99);
		$this->colorArray[DrawMACD::class]['setBgColor']=Image::rgb(80,80,180);

		$this->colorArray[DrawMACD::class]['setColor']= Image::rgb(196,0,0);
		$this->colorArray[DrawMACD::class]['setFastColor']=Image::rgb(255,0,0);
		$this->colorArray[DrawMACD::class]['setSlowColor']=Image::rgb(128,0,255);
		$this->colorArray[DrawMACD::class]['setHistogramColor']=Image::rgb(0,255,0);
		$this->colorArray[DrawMACD::class]['setWickColor']=Image::rgb(90,90,90);
		/**
		 *
		$this->colorBerish  =Image::rgb(255,0,0);
		$this->colorBullish =Image::rgb(0,255,0);


		 */
		parent::configure();
	}
}