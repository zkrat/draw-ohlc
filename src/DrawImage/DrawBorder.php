<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 17.08.18
 * Time: 22:42
 */

namespace DrawOHLC\DrawImage;


use Nette\Utils\Image;

class DrawBorder extends AbstractDrawCanvas {

	private $margin;

	private $border;

	private $padding;

	/**
	 * @var array
	 */
	private $borderColor;

	private function __construct($margin,$border,$padding=2) {
		$this->margin =$margin;
		$this->border =$border;
		$this->padding=$padding;
	}

	private function getFullBorder(){
		return $this->margin +$this->border +$this->padding;
	}

	public static function create(AbstractDrawCanvas $parentDrawCanvas,$margin=2,$border=2,$padding=2) {
		$class = new static($margin,$border,$padding);
		$parentDrawCanvas->addDrawCanvas($class);
		$class->setBorderOffset();
		return $class;

	}

	public function setBorderOffset(){
		$this->setOffset($this->getFullBorder(),$this->getFullBorder());
		$this->width = $this->getParent()->getWidth()-$this->getFullBorder()*2;
		$this->height = $this->getParent()->getHeight()-$this->getFullBorder()*2;
	}


	public function draw(){
		$x1 = $this->offsetX;
		$y1 = $this->offsetY;
		$x2 = $x1+$this->width;
		$y2 = $y1+$this->height;

		for ($border=0;$border<$this->border;$border++){
			$this->getImage()->rectangle($x1+$border-$this->padding,$y1+$border-$this->padding,$x2-$border+$this->padding,$y2-$border+$this->padding,$this->borderColor);
		}
		parent::draw();
	}


	/**
	 * @param array $bgColor
	 */
	public function setBgColor( array $bgColor ): AbstractDrawCanvas {
		$this->bgColor = $bgColor;
		return $this;
	}

	/**
	 * @param array $borderColor
	 */
	public function setBorderColor( array $borderColor ): AbstractDrawCanvas {
		$this->borderColor = $borderColor;
		return $this;
	}



}