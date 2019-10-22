<?php


namespace DrawOHLC\ColorSchema;


use Nette\Utils\Image;

class BasicColorSchema extends AbstractColorSchema {

	protected function configure() {
		$this->colorArray['DrawOHLC\DrawImage\DrawBorder']['setBorderColor']=Image::rgb(255,255,255);
		$this->colorArray['DrawOHLC\DrawImage\DrawBorder']['setColor']=Image::rgb(10,0,0);

		$this->colorArray['DrawOHLC\DrawImage\DrawOhlcList']['setColorBerish']=Image::rgb(255,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawOhlcList']['setColor']=Image::rgb(20,0,0);

		$this->colorArray['DrawOHLC\DrawImage\DrawOhlc']['setColor']=Image::rgb(30,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawOhlc']['setBgColor']=Image::rgb(40,0,0);

		$this->colorArray['DrawOHLC\DrawImage\DrawRSI']['setBgColor']=Image::rgb(241, 193, 241);
		$this->colorArray['DrawOHLC\DrawImage\DrawRSI']['setColor']=Image::rgb(160,30, 160);

		$this->colorArray['DrawOHLC\DrawImage\DrawOhlcList']['setBgColor']=Image::rgb(50,0,0);
		$this->colorArray['Models\Trading\Analyse\Draw\DrawRsiDivergenceFinderList']['setBgColor']=Image::rgb(0,0,0);

		$this->colorArray['DrawOHLC\DrawImage\DrawBorder']['setColor']=Image::rgb(255,255,255);

		$this->colorArray['Models\Trading\Analyse\Draw\DrawRsiDivergenceFinderList']['setColor']=Image::rgb(255,255,255);
		$this->colorArray['DrawOHLC\DrawImage\DrawVolume']['setBgColor']=Image::rgb(60,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawAvgVolume']['setBgColor']=Image::rgb(70,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawAvgVolume']['setColor']=Image::rgb(255,255,255);
		$this->colorArray['DrawOHLC\DrawImage\DrawBgOhlcList']['setColorBerish']=Image::rgb(255,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawBgOhlcList']['setColorBullish']=Image::rgb(80,0,255);
		$this->colorArray['DrawOHLC\DrawImage\DrawBgOhlcList']['setWickColor']=Image::rgb(255,255,255);
		$this->colorArray['DrawOHLC\DrawImage\DrawBgOhlcList']['setBgColor']=Image::rgb(190,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawBgOhlcList']['setColor']=Image::rgb(255,255,255);
		$this->colorArray['DrawOHLC\DrawImage\DrawCanvas']['setBgColor']=Image::rgb(0,0,0);
		$this->colorArray['DrawOHLC\DrawImage\DrawCanvas']['setColor']=Image::rgb(255,255,255);
		parent::configure();
	}
}