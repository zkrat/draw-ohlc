<?php

namespace DrawOHLC\Collection;


class DataCollectionException extends \Exception{
	const KEY_DUPLICATION=1;
	const KEY_IS_NOT_SCALAR=2;
	const COLLECTION_EMPTY = 3;
	const CLASS_NOT_FOUND=404;

}