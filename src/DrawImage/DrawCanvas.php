<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 18.08.18
 * Time: 13:29
 */

namespace DrawOHLC\DrawImage;



use DrawOHLC\ColorSchema\AbstractColorSchema;
use DrawOHLC\DrawImage\Exception\DrawCanvasException;
use Nette\Utils\Image;
use Tracy\Debugger;

class DrawCanvas extends AbstractDrawCanvas{



	const TRANSPARENT_COLOR='transparent';

	private $showGeneratedTime=FALSE;


	private function __construct($width,$height) {

		$this->width=$width;
		$this->height=$height;
	}


	public static function createSub(AbstractDrawCanvas $parentCanvas, $width=null, $height=null, $offsetX=0,$offsetY=0) {


		if(is_null($width))
			$width = $parentCanvas->getWidth();

		if(is_null($height))
			$height = $parentCanvas->getHeight();

		$class = new static($width,$height);

		$parentCanvas->addDrawCanvas($class);
		$class->setOffset($offsetX,$offsetY);
		return $class;
	}


	public static function createCanvas( $width,$height ) {
		$class= new static($width,$height);
		$class->initImage();
		return $class;
	}

	private function initImage(){
		Debugger::timer('get_image');
		$this->image = Image::fromBlank($this->width,$this->height, $this->bgColor);
	}

	public function draw() {
		$colorSchema=$this->hasColorSchema() ? $this->getColorSchema() : null;
		if($colorSchema instanceof AbstractColorSchema)
			$colorSchema->setColor($this);


		$this->getImage()->filledRectangle($this->getX1(),$this->getY1(),$this->getX2(),$this->getY2(),$this->getBgColor());


		parent::draw();
	}


	public function drawImage($toFile=null){

		if(is_null($this->fontPath))
			throw new DrawCanvasException(DrawCanvasException::MESSAGE_FONT_NOT_SET,DrawCanvasException::FONT_NOT_SET);

		$this->draw();

		if($this->showGeneratedTime){
			$time=round(Debugger::timer('get_image')/0.001);
			$maxSize=max($this->fontSize,8);
			$this->getImage()->ttfText(min($maxSize,8),0,$this->getX1()+2,$this->getY2()-2,$this->color,$this->fontPath, 'generated in:'.$time . 'ms');
		}
		if(is_null($toFile)){
			//dumpe($this,__METHOD__);
			$this->getImage()->send(Image::PNG,80);
			die;
		}
		else
			$this->getImage()->save($toFile,Image::PNG,80);
	}

	/**
	 * @return DrawCanvas
	 */
	public function showGeneratedTime( ): DrawCanvas {
		$this->showGeneratedTime = TRUE;
		return $this;
	}

	/**
	 * @return DrawCanvas
	 */
	public function hideGeneratedTime( ): DrawCanvas {
		$this->showGeneratedTime = FALSE;
		return $this;
	}

}