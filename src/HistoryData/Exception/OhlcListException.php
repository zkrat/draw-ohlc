<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 19.03.19
 * Time: 16:49
 */
namespace DrawOHLC\HistoryData\Exception;

class OhlcListException extends \Exception {

	const MESSAGE_METHOD_NOT_IMPLEMENTED='Method not implemented';
	const METHOD_NOT_IMPLEMENTED=10;
	const MESSAGE_FILE_NOT_FOUND = 'File not found';
	const FILE_NOT_FOUND =20;

}