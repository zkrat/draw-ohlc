<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 22.10.18
 * Time: 8:24
 */

namespace DrawOHLC\MovingAverage;


use DrawOHLC\Collection\DataCollection;
use DrawOHLC\Helper\ArrayHelper;
use DrawOHLC\HistoryData\Ohlc;
use DrawOHLC\HistoryData\OhlcList;



abstract class AbstractSingleValue extends DataCollection implements IMovingAverage {

	const UNCOUNTABLE=true;

	protected $length;


	protected $ohlcList;

	protected $minValue;

	protected $maxValue;

	protected $preProcessValues=true;


	protected $postProcessValues=true;

	const FUNCTION_NAME='_functionName';
	const SUB_CLASS_NAME='ClassName';


	protected function __construct(OhlcList $ohlcList,int $length) {
		$this->length = $length;
		$this->ohlcList=$ohlcList;
		$this->load();
	}

	private function load() {
		if ($this->preProcessValues===TRUE)
			$array =$this->getPreValues();
		else
			$array =$this->ohlcList->getCloseArray();
		$arrayValues =call_user_func($this::FUNCTION_NAME,$array,$this->length);
		if(is_array($arrayValues) && $this->postProcessValues===TRUE)
			$arrayValues = $this->getPostValues($arrayValues);

		foreach ($this->ohlcList as $ohlc){
			/**
			 * @var Ohlc $ohlc
			 */
			if($ohlc->hasNextOhlc()){
				$position=$ohlc->getPosition();
				$key=$position;
				if(isset($arrayValues[$key]) && $arrayValues[$key]!=0){
					$value =  $arrayValues[$key] ;
					$subClass= call_user_func($this::SUB_CLASS_NAME.'::create',$value,$ohlc,$this);
				}else{
					$subClass=UncountableSingleValueOhlc::createUncountable($ohlc,$this);
				}
				$this->data[$key]=$subClass;
			}
		}
	}


	public function addMovingAverageOhlc(AbstractSingleValueOhlc $movingAverageOhlc){
		$this->data[$this->position]=$movingAverageOhlc;
	}

	public function hasMovingAverageOhlc($position):bool{
		return isset($this->data[$position]);
	}

	public function getMovingAverageOhlc($position):AbstractSingleValueOhlc{
		return $this->data[$position];
	}


	public static function create(OhlcList $ohlcList,int $length=14) {
		$class = new static($ohlcList,$length);
		return $class;
	}

	/**
	 * @return mixed
	 */
	public function getLength() {
		return $this->length;
	}

	protected function getPreValues() {
		return $this->ohlcList->getCloseArray($this->ohlcList->getMultipicator());
	}


	protected function getPostValues( $arrayValues ) {
		$multiplicator=1/$this->ohlcList->getMultipicator();
		if($multiplicator!==1)
			$arrayValues = ArrayHelper::multipicator($arrayValues,$multiplicator);
		return $arrayValues;
	}


	public function hasPosition($position):bool{
		return isset($this->data[$position]);
	}

	public function getPosition($position):AbstractSingleValueOhlc{
		return $this->data[$position];
	}

	abstract public function getLabel() ;

	/**
	 * @return OhlcList
	 */
	public function getOhlcList(): OhlcList {
		return $this->ohlcList;
	}

	public function getSubClassName(){
		return $this::SUB_CLASS_NAME;
	}

}