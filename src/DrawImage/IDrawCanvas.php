<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 18.08.18
 * Time: 14:01
 */

namespace DrawOHLC\DrawImage;


use Nette\Utils\Image;

interface IDrawCanvas {


	public function addDrawCanvas(IDrawCanvas $drawCanvas);

	public function draw();

	public function setParent(IDrawCanvas $drawCanvas  );

	/**
	 * @return array
	 */
	public function getData(): array ;

	/**
	 * @return mixed
	 */
	public function getWidth();

	/**
	 * @return mixed
	 */
	public function getHeight() ;

	/**
	 * @return mixed
	 */
	public function getBgColor() ;


	/**
	 * @return bool
	 */
	public function hasParent(): bool ;

	/**
	 * @return IDrawCanvas
	 */
	public function getParent(): IDrawCanvas ;

	/**
	 * @return Image
	 */
	public function getImage(): Image ;

	/**
	 * @return int
	 */
	public function getOffsetX(): int;

	/**
	 * @return int
	 */
	public function getOffsetY(): int;

	/**
	 * @return int
	 */
	public function getAbsolutOffsetX(): int;

	/**
	 * @return int
	 */
	public function getAbsolutOffsetY(): int;





}