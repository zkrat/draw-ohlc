<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 14.02.19
 * Time: 17:54
 */

namespace DrawOHLC\DrawImage;


use Nette\Utils\Image;
use Traversable;

class AbstractDrawCanvas implements \Countable, \IteratorAggregate{

	protected $data=[];

	/**
	 * @var bool
	 */
	protected $fixedWidth=false;

	protected $fontSize=8;

	protected $fontPath;

	/**
	 * @var array
	 */
	protected $color;
	/**
	 * @var bool
	 */
	protected $fixedHeight=false;

	protected $width;

	protected $height;

	protected $bgColor;

	/**
	 * @var Image
	 */
	protected $image;

	/**
	 * @var array
	 */
	protected $spaceBottom=[];

	/**
	 * @var int
	 */
	protected $absolutOffsetX=0;

	/**
	 * @var int
	 */
	protected $absolutOffsetY=0;

	/**
	 * @var int
	 */
	protected $offsetX=0;

	/**
	 * @var int
	 */
	protected $offsetY=0;

	/**
	 * @var IDrawCanvas
	 */
	protected $parent;


	public function addDrawCanvas(AbstractDrawCanvas $drawCanvas){
		$drawCanvas->setParent($this);
		$this->data[]=$drawCanvas;

	}


	public function draw(){
		foreach ($this->data as $drawCanvas){
			/**
			 * @var AbstractDrawCanvas $drawCanvas
			 */
			$drawCanvas->draw();
		}
	}

	public function setParent(AbstractDrawCanvas $drawCanvas  ){
		$this->parent=$drawCanvas;
		if(is_null($this->width))
			$this->width=$drawCanvas->getWidth();
		if(is_null($this->height))
			$this->height=$drawCanvas->getHeight();
		$this->absolutOffsetY=$drawCanvas->getAbsolutOffsetY();
		$this->absolutOffsetX=$drawCanvas->getAbsolutOffsetX();
		$this->setFontPath($drawCanvas->getFontPath());
		$this->setFontSize($drawCanvas->getFontSize());
		$this->image=$this->getImage();
	}

	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}

	/**
	 * @return mixed
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return mixed
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return mixed
	 */
	public function getBgColor() {
		return $this->bgColor;
	}


	/**
	 * @return bool
	 */
	public function hasParent(): bool {
		return $this->parent instanceof  AbstractDrawCanvas;
	}

	/**
	 * @return AbstractDrawCanvas
	 */
	public function getParent(): AbstractDrawCanvas {
		return $this->parent;
	}

	/**
	 * @return array
	 */
	public function getSpaceBottom(): array {
		return $this->spaceBottom;
	}

	public function getSpaceBottomSum(): int {
		return array_sum($this->spaceBottom);
	}

	protected function setOffset($offsetX,$offsetY){
		$this->offsetX = $offsetX;
		$this->offsetY = $offsetY;
		$this->image   = $this->parent->getImage();
		$this->absolutOffsetX = $this->offsetX + $this->parent->getAbsolutOffsetX();
		$this->absolutOffsetY = $this->offsetY + $this->parent->getAbsolutOffsetY();
	}

	/**
	 * @return bool
	 */
	public function hasImage(): bool {
		return $this->image instanceof  Image;
	}

	/**
	 * @return Image
	 */
	public function getImage(): Image {
		if ($this->hasImage())
			return $this->image;
		else
			return $this->getParent()->getImage();
	}

	/**
	 * @return int
	 */
	public function getOffsetX(): int {
		return $this->offsetX;
	}

	/**
	 * @return int
	 */
	public function getOffsetY(): int {
		return $this->offsetY;
	}

	/**
	 * @return int
	 */
	public function getAbsolutOffsetX(): int {
		return intval($this->absolutOffsetX);
	}

	/**
	 * @return int
	 */
	public function getAbsolutOffsetY(): int {
		return intval($this->absolutOffsetY);
	}

	public function getX1():int{
		return $this->absolutOffsetX;
	}

	public function getX2():int{
		return $this->absolutOffsetX + $this->width;
	}

	public function getY1():int{
		return $this->absolutOffsetY;
	}

	public function getY2():int{
		return $this->absolutOffsetY+$this->height;
	}


	/**
	 * Retrieve an external iterator
	 * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator() {
		return new \RecursiveArrayIterator($this->data);
	}


	public function createSpaceBottom( int $height ) {
		foreach ($this->data as $drawCanvas){
			/**
			 * @var AbstractDrawCanvas $drawCanvas
			 */
			if(!$drawCanvas->isFixedHeight())
				$drawCanvas->createSpaceBottom( $height );
		}
		$this->height-=$height;

	}


	/**
	 * Count elements of an object
	 * @link https://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 * @since 5.1.0
	 */
	public function count() {
		return count($this->data);
	}

	/**
	 * @return bool
	 */
	public function isFixedWidth(): bool {
		return $this->fixedWidth;
	}

	/**
	 * @return bool
	 */
	public function isFixedHeight(): bool {
		return $this->fixedHeight;
	}

	public function getClassName(){
		return get_class($this);
	}

	/**
	 * @param int $fontSize
	 *
	 * @return AbstractDrawCanvas
	 */
	public function setFontSize( int $fontSize ): AbstractDrawCanvas {
		$this->fontSize = $fontSize;
		return $this;
	}

	/**
	 * @param mixed $fontPath
	 *
	 * @return AbstractDrawCanvas
	 */
	public function setFontPath( $fontPath ) {
		$this->fontPath = $fontPath;

		return $this;
	}

	/**
	 * @param array $color
	 *
	 * @return AbstractDrawCanvas
	 */
	public function setColor( array $color ): AbstractDrawCanvas {
		$this->color = $color;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getFontSize(): int {
		return $this->fontSize;
	}

	/**
	 * @return mixed
	 */
	public function getFontPath() {
		return $this->fontPath;
	}

	/**
	 * @return array
	 */
	public function getColor(): array {
		return $this->color;
	}




}