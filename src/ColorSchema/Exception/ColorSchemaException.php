<?php

namespace DrawOHLC\ColorSchema\Exception;

class ColorSchemaException extends \Exception {

	const MSG_METHOD_COLOR_NOT_EXISTS='Method color  %s of class %s not exists';
	const METHOD_COLOR_NOT_EXISTS=2;

	const MSG_COLOR_SCHEMA_NOT_SET='Color schema not set';
	const COLOR_SCHEMA_NOT_SET=4;

	const MSG_MISSING_PARENT_CONFIGURATION='Missing parent::configure();';
	const MISSING_PARENT_CONFIGURATION=6;
}