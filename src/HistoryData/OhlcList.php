<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 12.10.18
 * Time: 10:18
 */

namespace DrawOHLC\HistoryData;



use DrawOHLC\Collection\DataCollection;
use DrawOHLC\Helper\ArrayHelper;
use Nette\Utils\DateTime;

class OhlcList extends DataCollection {


	protected $minLow;

	protected $maxHigh=0;

	protected $minVolume;

	protected $maxVolume=0;

	protected $position=1;


	protected $multipicator=1;


	/**
	 * @var array
	 */
	protected $highArray=[];

	/**
	 * @var array
	 */
	protected $openArray=[];

	/**
	 * @var array
	 */
	protected $closeArray=[];

	/**
	 * @var array
	 */
	protected $lowArray=[];

	protected $dataByTimestamp=[];

	/**
	 * @var Ohlc
	 */
	protected $firstOhlc;
	/**
	 * @var Ohlc
	 */
	protected $lastOhlc;

	/**
	 * @var array
	 */
	private $insertArray=[];

	/**
	 * @var array
	 */
	private $updateArray=[];
	/**
	 * @var array
	 */
	private $volumeArray=[];
	/**
	 * @var int
	 */
	protected $periodLength;

	/**
	 * OhlcList constructor.
	 *
	 * @param int $periodLength
	 */
	protected function __construct(int $periodLength) {
		$this->periodLength =$periodLength;
	}

	/**
	 * @param int $periodLength
	 *
	 * @return OhlcList
	 */
	public static function create(int $periodLength):OhlcList{
		$class = new static($periodLength);
		return $class;
	}

	public function addOhlc(Ohlc $ohlc){
		$this->data[$this->position]                                 =$ohlc;
		$this->highArray[$this->position]                            =$ohlc->getHigh();
		$this->openArray[$this->position]                            =$ohlc->getOpen();
		$this->closeArray[$this->position]                           =$ohlc->getClose();
		$this->lowArray[$this->position]                             =$ohlc->getLow();
		$this->volumeArray[$this->position]                             =$ohlc->getVolume();
		$this->dataByTimestamp[$ohlc->getDatetime()->getTimestamp()] =$ohlc;
		$ohlc->setPosition($this->position);

		if (is_null($this->firstOhlc))
			$this->firstOhlc= $ohlc;

		$this->lastOhlc= $ohlc;
		$this->position++;

		if (is_null($this->minLow))
			$this->minLow=$ohlc->getLow();

		if (is_null($this->minVolume))
			$this->minVolume= $ohlc->getVolume();

		$this->minVolume=min($this->minVolume,$ohlc->getVolume());
		$this->maxVolume=max($this->maxVolume,$ohlc->getVolume());
		$this->minLow= min($this->minLow,$ohlc->getLow());
		$this->maxHigh= max($this->maxHigh,$ohlc->getHigh());
	}

	/**
	 * @param DateTime $dateTime
	 *
	 * @return bool
	 */
	public function hasOhlcByDatetime(DateTime $dateTime ):bool{
		return isset($this->dataByTimestamp[$dateTime->getTimestamp()]);
	}

	/**
	 * @param DateTime $dateTime
	 *
	 * @return Ohlc
	 */
	public function getOhlcByDatetime(DateTime $dateTime ):Ohlc{
		return $this->dataByTimestamp[$dateTime->getTimestamp()];
	}

	/**
	 * @return mixed
	 */
	public function getMinLow() {
		return $this->minLow;
	}

	/**
	 * @return mixed
	 */
	public function getMaxHigh() {
		return $this->maxHigh;
	}



	public function hasOhlcByPostion( $position ):bool {
		return isset($this->data[$position]);
	}

	public function getOhlcByPostion( $position ):Ohlc {
		return $this->data[$position];
	}

	/**
	 * @return mixed
	 */
	public function getMinVolume() {
		return $this->minVolume;
	}

	/**
	 * @return int
	 */
	public function getMaxVolume(): int {
		return $this->maxVolume;
	}

	/**
	 * @return int
	 */
	public function getPosition(): int {
		return $this->position;
	}

	/**
	 * @return array
	 */
	public function getHighArray($multiplicator=1): array {
		return $this->multipicatorArray($this->highArray,$multiplicator);;
	}

	/**
	 * @return array
	 */
	public function getOpenArray($multiplicator=1): array {
		return $this->multipicatorArray($this->openArray,$multiplicator);
	}

	/**
	 * @return array
	 */
	public function getCloseArray($multiplicator=1): array {
		return $this->multipicatorArray($this->closeArray,$multiplicator);;
	}

	/**
	 * @return array
	 */
	public function getLowArray($multiplicator=1): array {
		return $this->multipicatorArray($this->lowArray,$multiplicator);
	}
	private function multipicatorArray($array,$multiplicator){
		return self::multipicator($array,$multiplicator);
	}

	public static function multipicator($array,$multiplicator){
			if ($multiplicator!==1){
				$array=ArrayHelper::multipicator($array,$multiplicator);
			}
			return $array;
	}

	public function deleteLastOhlc() {
		$this->position--;
		unset($this->data[$this->position],$this->highArray[$this->position],$this->openArray[$this->position], $this->closeArray[$this->position], $this->lowArray[$this->position]);
	}

	/**
	 * @return Ohlc
	 */
	public function getFirstOhlc():Ohlc {
		return $this->firstOhlc;
	}

	/**
	 * @return Ohlc
	 */
	public function getLastOhlc():Ohlc {
		return $this->lastOhlc;
	}



	/**
	 * @return bool
	 */
	public function hasFirstOhlc():bool {
		return $this->firstOhlc instanceof Ohlc;
	}

	/**
	 * @return bool
	 */
	public function hasLastOhlc():bool {
		return $this->lastOhlc instanceof Ohlc;
	}


	public function countData( OhlcList $cacheOhlcList=null ) {
		foreach ($this->data as $ohlc){
			/**
			 * @var Ohlc $ohlc
			 */
			// TODO: less request


			if ( $cacheOhlcList instanceof OhlcList && $cacheOhlcList->hasOhlcByDatetime($ohlc->getDatetime())){
				$ohlcChache=$cacheOhlcList->getOhlcByDatetime($ohlc->getDatetime());
				if (!$ohlcChache->isSame($ohlc)){
					$this->updateArray[]=$ohlc->getArray();
				}
			}else{
				$this->insertArray[]=$ohlc->getArray();
			}

		}
	}

	/**
	 * @return array
	 */
	public function getInsertArray(): array {
		return $this->insertArray;
	}

	/**
	 * @return array
	 */
	public function getUpdateArray(): array {
		return $this->updateArray;
	}

	public function getVolumeArray() {
		return $this->volumeArray;
	}

	/**
	 * @return int
	 */
	public function getPeriodLength(): int {
		return $this->periodLength;
	}

	public function getMultipicator():float {
		return $this->multipicator;
	}

	public function setMultipicator(float $multipicator) {
		$this->multipicator=$multipicator;
	}

}