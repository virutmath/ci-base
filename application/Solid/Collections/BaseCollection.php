<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 6/17/2016
 * Time: 8:42 AM
 */

namespace Solid\Collections;


class BaseCollection
{
	//define and allowable property of collection
	protected $listProperties;
	/**
	 * @var string $name
	 */
	/**
	 * @property string $publicName
	 */
	
	/**
	 * BaseCollection constructor.
	 * @param $modelRecord
	 */

	public function __construct($modelRecord)
	{
		foreach($this->listProperties as $property){
			$camel_case_property = camel_case($property);
			if(property_exists($modelRecord,$property) && property_exists($this,$camel_case_property)) {
				$this->{$camel_case_property} = $modelRecord->{$property};
			}
		}
		if (property_exists($modelRecord, 'name')) {
			$this->publicName = $this->name;
		}
	}
	
	public function __get($name)
	{
		// TODO: Implement __get() method.
		$method = 'get'.ucfirst(camel_case($name));
		if(is_callable([$this, $method])){
			return $this->$method();
		}else{
			return $this->{$name};
		}
	}
}