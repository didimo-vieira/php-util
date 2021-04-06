<?php
/*
Abstract class to represents any generic object.
Using this class you don't need to implement gets or sets. 
You can use properties as attributes or with gets and sets.
Recomended for simple objects.
Author: Dídimo Vieira de Araújo Junior
*/
abstract class Entity {
	//Stores the properties names and values.
	protected $properties;
	
	//Returns the property value.
	public function __get($propertyName){
		if (array_key_exists($propertyName, $this->properties)){
			return $this->properties[$propertyName];
		} else {
			throw new PropertyNotExistsException(get_class($this)."::".$propertyName);
		}
	}
	
	//Validates and sets the new value for the property.
	public function __set($propertyName, $newValue){
		if (array_key_exists($propertyName, $this->properties)){
			if ($this->validate($propertyName, $newValue)){
				$this->properties[$propertyName] = $newValue;
			} else {
				throw new InvalidPropertyValueException(get_class($this)."::".$propertyName);
			}
		} else {
			throw new PropertyNotExistsException(get_class($this)."::".$propertyName);
		}
	}
	
	//Allows to implement a validation funcion for new property values.
	//Must return true when $newValue is ok for $propertyName
	protected abstract function validate($propertyName, $newValue);
	
	//Releases resources.
	public function __destruct(){
		foreach ($this->properties as $propertyName => $propertyValue){
			unset($this->properties[$propertyName]);
		}
		unset($this->properties);
	}
	
	public function __clone(){
		$this->properties = clone $this->properties;
	}

	//Implements generic gets and sets methods
	public function __call($name, $arguments){
		$first3 = substr($name, 0, 3);
		$property = substr($name, 3);
		switch ($first3){
			case 'get'://if starts with 'get'
				if (array_key_exists($property, $this->properties)){//verify property name
					return $this->properties[$property];
				} elseif (array_key_exists(lcfirst($property), $this->properties)){//verify property name with lower case on first char
					return $this->properties[lcfirst($property)];
				} else {
					throw new MethodNotExistsException(get_class($this)."::".$name);
				}
				break;
			case 'set'://if starts with set
				if (array_key_exists($property, $this->properties)){//verify property name
					$this->properties[$property] = $arguments[0];
				} elseif (array_key_exists(lcfirst($property), $this->properties)){//verify property name with lower case on first char
					$this->properties[lcfirst($property)] = $arguments[0];
				} else {
					throw new MethodNotExistsException(get_class($this)."::".$name);
				}
				break;
			default:
				$this->$name($arguments);
				break;
		}

	}
}

//Exception classes 
class MethodNotExistsException extends Exception {
	public function __construct($methodName){
		parent::__construct("Method '$methodName' not exists.");
	}
}
class PropertyNotExistsException extends Exception {
	public function __construct($propertyName){
		parent::__construct("Property '$propertyName' not exists.");
	}
}
class InvalidPropertyValueException extends Exception {
	public function __construct($propertyName){
		parent::__construct("Invalid Property '$propertyName' value.");
	}
}
?>