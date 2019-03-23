<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 22/08/2017
 * Time: 07:34
 */
namespace DrawOHLC\Helper;



class ArrayHelper {

	public  static function convert(&$array,$revert=null) {
		$array =self::flat($array,$revert);
	}


	private  static function flat($array ,$revert=null) {
		if (!is_array($array)) {
			return FALSE;
		}
		$result = array();
		if (is_null($revert) || $revert>0){
			foreach ($array as $key => $value) {
				if (is_array($value) && (is_null($revert) || $revert>0)) {

					$result = array_merge($result, self::flat($value,$revert-1));
				}
				else {
					$result[$key] = $value;
				}
			}
		}else{
			$result = $array;
		}
		return $result;
	}

	public static function arrayMerge($var,$includKeys=true):array {
		$arrayList =func_get_args();
		$returnArray=[];
		foreach ($arrayList as $rows){
			if($includKeys){
				foreach ($rows as $id => $array){
					$returnArray[$id]=$array;
				}
			}else{
				$returnArray = array_merge($returnArray,$rows);
			}
		}
		return $returnArray;
	}

	public static function multipicator( array $array, float $multiplicator=1,$floor=false ):array {
		if ($multiplicator!==1){
			foreach ($array as $key=>  &$value){
				if(is_numeric($value)){
					if($floor)
						$value=floor($value*$multiplicator);
					else
						$value=floor($value)*$multiplicator;
				}

			}

		}
		return $array;
	}
}