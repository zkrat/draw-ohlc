<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 27.02.19
 * Time: 17:30
 */

namespace DrawOHLC\DrawImage;


abstract class AbstractDrawIndicator extends AbstractDrawCanvas {


	/**
	 * @var DrawOhlcList
	 */
	protected  $drawOhlcList;

	/**
	 * @var int
	 */
	protected $length;

	protected function __construct(int $height=30,DrawOhlcList $drawOhlcList,int $length=null) {
		$offset=3;
		$this->fixedHeight=true;
		$this->length=$length;

		if($drawOhlcList instanceof DrawBgOhlcList){
			$drawOhlcList->extraSize(0,$height+$offset);
			$this->drawOhlcList=$drawOhlcList->getDrawOhlcList();
		}else{
			$this->drawOhlcList=$drawOhlcList;
		}
		$drawOhlcList->createSpaceBottom($height+$offset);
		$drawOhlcList->addDrawCanvas($this);


		$this->setOffset(0,$drawOhlcList->getHeight());

		$this->height=$height;
		$this->width=$drawOhlcList->getWidth();
		$this->loadIndicator();

	}


	abstract protected function loadIndicator();


}