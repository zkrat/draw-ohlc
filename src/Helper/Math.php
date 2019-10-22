<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 06/11/2017
 * Time: 19:00
 */

namespace DrawOHLC\Helper;




class Math {

	public static function fib($n){
		return round(pow((sqrt(5)+1)/2, $n) / sqrt(5));
	}

	public static function getDecimal($decimal){
		return (int) pow(10,$decimal);
	}


	public static function countDecimals($number){
		$str= substr(floatval($number),strpos(strval($number),'.')+1);
		return strlen($str);
	}

	public static function getDecimalsInNumber(float $number){
		return strlen($number) - (strpos(strval($number),'.')+1);
	}

		/**
	 * Round Down
	 * @param $num
	 * @param int $decimal
	 *
	 * @return float
	 */

	public static function floor($num,$decimal=0){
		if ($num==0)
			return 0;

		$number=self::getDecimal($decimal);
		return round(floor($num*$number)/$number,$decimal);
	}


	/**
	 *
	 * Round up
	 *
	 * @param $num
	 *
	 * @return float
	 */
	public static function floorHalf($num){
		if ($num==0)
			return 0;

		$numRound=round($num*2)/2;
		if ($numRound<$num)
			return  $numRound;
		else
			return self::floor($num);
	}

	/**
	 *
	 *  Round up
	 *
	 * @param $num
	 * @param int $decimal
	 *
	 * @return float
	 */
	public static function ceil($num,$decimal=0){
		if ($num==0)
			return 0;

		$number=self::getDecimal($decimal);
		return round(ceil($num*$number)/$number,$decimal);
	}

	/**
	 * @param $num
	 * @param int $decimal
	 *
	 * @return float
	 */

	public static function roundUp( $num, $decimal = 0 ) {
		return self::ceil($num,$decimal);
	}

	/**
	 * @param $num
	 * @param int $decimal
	 *
	 * @return float
	 */
	public static function roundDown( $num, $decimal = 0 ) {
		return self::floor($num,$decimal);
	}

	/**
	 *
	 * Round up
	 *
	 * @param $num
	 *
	 * @return float
	 */
	public static function ceilHalf($num){
		if ($num==0)
			return 0;

		$numRound=round($num*2)/2;
		if ($numRound>$num)
			return  $numRound;
		else
			return self::ceil($num);
	}

	public static function diff( $askPrice, $bidsPrice ,$decimals) {
		$number=self::getDecimal($decimals);
		$max = intval($askPrice*$number);
		$min = intval($bidsPrice*$number);
		return ($max-$min)/$number;
	}

	private static function floatToInt(&$first, &$second ,&$decimal){
		$decimals=max(Math::getDecimalsInNumber($first),Math::getDecimalsInNumber($second));
		$decimal= Math::getDecimal($decimals);
		$first=intval($first*$decimal);
		$second =intval($second*$decimal);
	}


	public static function subtraction( $first, $second ):float {
		$dec=null;
		Math::floatToInt($first,$second,$dec);
		return floatval(($first-$second)/$dec);

	}

	public static function division( $first, $second ):float{
		$dec=null;
		Math::floatToInt($first,$second,$dec);
		return floatval(($first/$second)/$dec);
	}

	public static function multiplication( $first, $second ):float{
		$dec=null;
		Math::floatToInt($first,$second,$dec);
		return floatval(($first*$second)/($dec*$dec));

	}
	public static function addition( $first, $second ):float{
		$dec=null;
		Math::floatToInt($first,$second,$dec);
		return floatval(($first+$second)/$dec);
	}

	public static function numberOfDecimals($value)
	{
		if ((int)$value == $value)
		{
			return 0;
		}
		else if (! is_numeric($value))
		{
			// throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
			return false;
		}

		$retun= strlen($value) - strrpos($value, '.') - 1;
		if ($retun>3 && $retun<9)
			return 8;
		else
			return $retun;
	}
}