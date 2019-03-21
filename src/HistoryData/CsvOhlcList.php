<?php
/**
 * Created by PhpStorm.
 * User: zkrat
 * Date: 19.03.19
 * Time: 16:48
 */

namespace DrawOHLC\HistoryData;


use DrawOHLC\HistoryData\Exception\OhlcListException;
use Nette\Utils\DateTime;

class CsvOhlcList extends OhlcList {

	/**
	 * @param int $periodLength
	 *
	 * @return OhlcList
	 * @throws OhlcListException
	 * @deprecated
	 */
	public static function create( int $periodLength ): OhlcList {
		throw new OhlcListException(OhlcListException::MESSAGE_METHOD_NOT_IMPLEMENTED,OhlcListException::METHOD_NOT_IMPLEMENTED);
	}

	public static function createFromFile($fileName){
		if (!file_exists($fileName))
			throw new OhlcListException(OhlcListException::MESSAGE_FILE_NOT_FOUND,OhlcListException::FILE_NOT_FOUND);
		$class = new static(0);
		$array =self::readCSV($fileName);
		$class->load($array);
		return $class;
	}

	private function load($array){
		foreach ($array as $row){
			$dateString = $row[0];
			$time=strtotime($dateString);
			$open = $row[1];
			$high = $row[2];
			$low = $row[3];
			$close = $row[4];
			$volume = $row[6];
			if(is_numeric($time)){
				$dateTime=DateTime::from($time);
				if(isset($prevDateTime) && $this->periodLength==0){
					/**
					 * @var DateTime $prevDateTime
					 */
					$this->periodLength = $dateTime->getTimestamp() - $prevDateTime->getTimestamp();
				}

				Ohlc::create($dateTime,$open,$high,$low,$close,$volume,$this);
				$prevDateTime=$dateTime;
			}
		}
	}

	private static function readCSV($csvFile):array{
		$line_of_text=[];
		$file_handle = fopen($csvFile, 'r');
		while (!feof($file_handle) ) {
			$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		return $line_of_text;
	}

}