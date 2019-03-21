<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 28.01.19
 * Time: 13:41
 */
namespace Models\HistoryData\Statistics;

class NormalDistribution {

	private $values=[];
	private $valuesCounter=[];

	public function addValue($value){
		$this->values[]=$value;

		if(isset($this->valuesCounter[$value]))
			$this->valuesCounter[$value]++;
		else
			$this->valuesCounter[$value]=1;
	}

	/**
	 * @return array
	 */
	public function getCounterValues(): array {
		arsort($this->valuesCounter);
		return $this->valuesCounter;
	}

	public function getPercentValues(): array {
		$sum=array_sum($this->valuesCounter);
		$percent=[];
		foreach ($this->valuesCounter as $price =>$count){
			$percent[$price]=round(($count/$sum)*100,2);
		}
		return $percent;
	}

}