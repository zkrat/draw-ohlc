<?php


namespace DrawOHLC\ColorSchema;


use DrawOHLC\ColorSchema\Exception\ColorSchemaException;
use DrawOHLC\DrawImage\AbstractDrawCanvas;

abstract class AbstractColorSchema {

	protected $colorArray=[];

	private $strict=false;

	private $debug=false;

	private $configureSetup=FALSE;

	/**
	 * @return bool
	 */
	public function isDebug(): bool {
		return $this->debug;
	}

	/**
	 * @param bool $debug
	 */
	public function setDebug( bool $debug ): void {
		$this->debug = $debug;
	}




	public function setStrict(){
		$this->strict=true;
	}

	protected function __construct() {
	}

	public static function create():AbstractColorSchema{
		$class= new static();
		$class->configure();
		if ($class->configureSetup==FALSE){
			throw new ColorSchemaException(ColorSchemaException::MSG_MISSING_PARENT_CONFIGURATION,ColorSchemaException::MISSING_PARENT_CONFIGURATION);
		}
		return  $class;
	}

	public function setColor(AbstractDrawCanvas $class){
		$className = get_class($class);
		$classMethods =$this->getColorMethods($className);
		foreach ($classMethods as $classMethod){
			$getClassMethod=str_replace('set','get',$classMethod);

			if (!method_exists($class,$getClassMethod)  ||(method_exists($class,$getClassMethod)  && is_null($class->$getClassMethod())) )
			if (isset($this->colorArray[$className][$classMethod])){
				$class->$classMethod($this->colorArray[$className][$classMethod]);
			}elseif($this->strict===TRUE){
				if ($this->debug){
					echo "<pre>\$this->colorArray['$className']['$classMethod']</pre><br>";
				}
				if(!method_exists($class,$getClassMethod)){
					$msg=sprintf(ColorSchemaException::MSG_METHOD_COLOR_NOT_EXISTS,$getClassMethod,$className);
				}else{
					$msg=sprintf(ColorSchemaException::MSG_METHOD_COLOR_NOT_EXISTS,$classMethod,$className);
				}

				throw new ColorSchemaException($msg,ColorSchemaException::METHOD_COLOR_NOT_EXISTS);
			}
		}


	}

	public function getColorMethods(string $className){
		$classArray=get_class_methods($className);

		$colorList=[];
		foreach ($classArray as $classMethod){
			$lclassMethod=strtolower($classMethod);

			if(strpos($lclassMethod,'colorschema')===FALSE && strpos($lclassMethod,'color')!==FALSE && strpos($lclassMethod,'set')!==FALSE){
				$colorList[]=$classMethod;
			}
		}
		return $colorList;
	}

	protected function configure(){
		$this->configureSetup=TRUE;
	}

}