<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 12.10.18
 * Time: 10:19
 */

namespace DrawOHLC\HistoryData;



use Helper\DateTimeHelper;
use Nette\Utils\DateTime;

class Ohlc {

	const OPEN ='open';
	const CLOSE ='close';
	const HIGH='high';
	const LOW='low';


	/**
	 * @var DateTime
	 */
	protected $datetime;

	protected $open;

	protected $high;

	protected $low;

	protected $close;

	protected $volume;

	protected $tradesCount;

	protected $position;
	/**
	 * @var OhlcList
	 */
	protected $parent;


	/**
	 * Ohlc constructor.
	 *
	 * @param DateTime $dateTime
	 * @param $open
	 * @param $high
	 * @param $low
	 * @param $close
	 * @param $volume
	 */
	protected function __construct() {
	}

	public static function create(DateTime $dateTime, $open, $high, $low, $close,$volume,OhlcList $ohlcList=null) {
		$class           = new static();
		$class->datetime = $dateTime;
		$class->open     = $open;
		$class->high     = $high;
		$class->low      = $low;
		$class->close    =  $close;
		$class->volume   =  $volume;
		$class->parent   = $ohlcList;
		if(!is_null($ohlcList)){
			$ohlcList->addOhlc($class);
		}
		return $class;
	}

	public static function creatFromValue(DateTime $dateTime,$volue,OhlcList $ohlcList=null) {
		$class           = new static();
		$class->datetime = $dateTime;
		$class->addValue($volue);

		$class->volume   =  null;
		$class->parent   = $ohlcList;
		if(!is_null($ohlcList)){
			$ohlcList->addOhlc($class);
		}
		return $class;
	}

	public function addVolume($volume){
		if(is_null($this->volume))
			$this->volume=$volume;
		else
			$this->volume+=$volume;
	}
	public function addValue($value){
		if(is_null($this->open)){
			$this->open=$value;
			$this->high=$value;
			$this->low=$value;

		}else{
			$this->high=max($this->high,$value);
			$this->low=min($this->low,$value);
		}

		$this->close=$value;
	}


	public static function createFull(DateTime $dateTime, $open, $high, $low, $close,$volume,$tradesCount,OhlcList $ohlcList=null) {
		$class              = new static();
		$class->datetime    = $dateTime;
		$class->open        = $open;
		$class->high        = $high;
		$class->low         = $low;
		$class->close       =  $close;
		$class->volume      =  $volume;
		$class->tradesCount =$tradesCount;
		$class->parent      = $ohlcList;
		if(!is_null($ohlcList)){
			$ohlcList->addOhlc($class);
		}
		return $class;
	}




	/**
	 * @return mixed
	 */
	public function getOpen() {
		return $this->open;
	}

	/**
	 * @return mixed
	 */
	public function getHigh() {
		return $this->high;
	}

	/**
	 * @return mixed
	 */
	public function getLow() {
		return $this->low;
	}

	/**
	 * @return mixed
	 */
	public function getClose() {
		return $this->close;
	}

	public function getValue( $type ) {
		switch ($type){
			case Ohlc::HIGH:
				return $this->getHigh();
				break;
			case Ohlc::OPEN:
				return $this->getOpen();
				break;
			case Ohlc::CLOSE:
				return $this->getClose();
				break;
			case Ohlc::LOW:
				return $this->getLow();
				break;
			default:
				return $this->getClose();
		}
	}


	/**
	 * @return OhlcList
	 */
	public function getParent(): OhlcList {
		return $this->parent;
	}

	public function hasParent(): bool {
		return $this->parent instanceof OhlcList;
	}

	public function isBullish():bool {
		return $this->open<$this->close || $this->open==$this->close;
	}

	public function isBearish():bool {
		return $this->open>$this->close;
	}

	public function getObjectId(){
		return spl_object_hash($this);
	}

	/**
	 * @return DateTime
	 */
	public function getDatetime(): DateTime {
		return $this->datetime;
	}


	/**
	 * @return mixed
	 */
	public function getTradesCount() {
		return $this->tradesCount;
	}

	/**
	 * @return mixed
	 */
	public function getVolume() {
		return $this->volume;
	}

	public function setPosition(  $position ) {
		$this->position=$position;
	}

	public function hasPrevOhlc():bool {
		$position=$this->position-1;
		return $this->parent->hasOhlcByPostion($position);
	}

	public function getPrevOhlc():Ohlc {
		$position=$this->position-1;
		return $this->parent->getOhlcByPostion($position);
	}

	public function hasNextOhlc():bool {
		$position=$this->position+1;
		return $this->parent->hasOhlcByPostion($position);
	}

	public function getNextOhlc():Ohlc {
		$position=$this->position+1;
		return $this->parent->getOhlcByPostion($position);
	}

	/**
	 * @return mixed
	 */
	public function getPosition() {
		return $this->position;
	}


	public function isSame(Ohlc $ohlc){
		return $this->datetime->getTimestamp() == $ohlc->getDatetime()->getTimestamp()&&
		$this->datetime->getTimezone()->getName() == $ohlc->getDatetime()->getTimezone()->getName()&&
		$this->high==$ohlc->getHigh()&&
		$this->volume==$ohlc->getVolume()&&
		$this->open==$ohlc->getOpen()&&
		$this->low==$ohlc->getLow()&&
		$this->close==$ohlc->getClose()&&
		$this->tradesCount==$ohlc->getTradesCount();
	}

}