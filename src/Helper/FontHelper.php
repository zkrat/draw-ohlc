<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 20.03.19
 * Time: 22:03
 */

namespace DrawOHLC\Helper;


class FontHelper {


	public static function getFontBoxSize($size, $angle, $fontfile, $text):array {
		$type_space=imagettfbbox($size, $angle, $fontfile, $text);
		$width = abs($type_space[4] - $type_space[0]) + 10;
		$height = abs($type_space[5] - $type_space[1]) + 10;
		return [$width,$height];
	}

}