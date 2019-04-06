<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 06.04.19
 * Time: 20:27
 */

namespace DrawOHLC\Helper;


class OhlcLengthHelper {

	const MINUTE=60;
	const HOUR=3600;
	const DAY=86400;
	const WEEK=604800;

	public static function getTimeFrame(int $seconds ) {
		if ($seconds<OhlcLengthHelper::MINUTE)
			return $seconds.'s';
		elseif ($seconds<OhlcLengthHelper::HOUR){
			return round($seconds/OhlcLengthHelper::MINUTE).'m';
		}
		elseif ($seconds<OhlcLengthHelper::DAY){
			return round($seconds/OhlcLengthHelper::HOUR).'H';
		}
		elseif ($seconds<OhlcLengthHelper::WEEK){
			return round($seconds/OhlcLengthHelper::DAY).'D';
		}

		return '~'.$seconds.'s';
	}

}