<?php


namespace DrawOHLC\ColorSchema;


use DrawOHLC\DrawImage\AbstractDrawCanvas;

class AbstractColorSchema {

	protected $colorArray=[];

	public function getBgColor(AbstractDrawCanvas $class){
		$className = get_class($class);
		dumpe($className,get_class_methods($className));
		if (isset($this->colorArray[$className]['BgColor'])){

		}

	}

}