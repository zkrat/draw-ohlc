<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 12.02.19
 * Time: 10:16
 */

namespace DrawOHLC\MovingAverage;



use Traversable;

class DrawMovingAvarage implements  \IteratorAggregate {
	/**
	 * @var
	 */
	protected $color;
	/**
	 * @var int
	 */
	protected $thick;
	/**
	 * @var IMovingAverage
	 */
	protected $ma;

	private function __construct(IMovingAverage $ma,$color,$thick=1) {
		$this->ma=$ma;
		$this->color=$color;
		$this->thick=$thick;

	}

	public static function create(IMovingAverage $ma,$color,$thick=1):DrawMovingAvarage{
		return new static( $ma,$color,$thick);
	}


	/**
	 * Retrieve an external iterator
	 * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 * @since 5.0.0
	 */
	public function getIterator(){
		return $this->ma->getIterator();
	}

	/**
	 * @return mixed
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @return int
	 */
	public function getThick(): int {
		return $this->thick;
	}

}