<?php
require('Entity.php');
/*
Represents a template content.
A template is a text file with replaceable parameters in following format: <delimiter_character><parameter_label>:<parameter_name><delimiter_character>
By default @par:<parameter_name>@
Each found parameter becomes an object attribute that you can set its value by set method or direct attribution.
Author: Dídimo Vieira de Araújo Junior
*/
class Template extends Entity {
	private static $templateList = array();
	private $content;
	private $delimiter;
	private $label;
	
	//Defines an array of $templateName=>$FullFilePath.
	public static function setTemplateList($templateList){
		if (is_array($templateList)){
			self::$templateList = $templateList;
		} else {
			throw new Exception("Invalid Template List");
		}
	}
	//Returns an array containing all templates's names.
	public static function getTemplateNameList(){
		return array_keys(self::$templateList);
	}

	//Returns a new template object specified by name.
	public static function getTemplateInstance($templateName, $delimiterCharacter = '@', $parameterLabel = 'par'){
		$instance = new Template($templateName, $delimiterCharacter, $parameterLabel);
		return $instance;
	}
	
	//Constructs an object loading a template file
	private function __construct($templateName, $delimiterCharacter, $parameterLabel){
		$this->delimiter = $delimiterCharacter;
        $this->label = $parameterLabel;	
		$this->properties = array();
		if (array_key_exists($templateName, self::$templateList)){
		    //load template
			$filename = self::$templateList[$templateName];
			$handle = fopen($filename, "r");
			$this->content = fread($handle, filesize($filename));
			fclose($handle);
			//identify parameters
			$mark  = $this->delimiter . $this->label . ":";
			$pos1 = strpos($this->content, $mark);
			while ($pos1 !== false){ //enquanto encontrar parâmetros
			    //carregar os parametros encontrados na lista
				$pos2 = strpos($this->content, $this->delimiter, $pos1+strlen($mark));
				$parL = $pos2 - $pos1 - strlen($mark); 
				$para = substr($this->content, $pos1+strlen($mark), $parL);
				$this->properties[$para] = "";
				$pos1 = strpos($this->content, $mark, $pos2+1);
			}	
		} else { //blank template if template name was not found in template list
			$this->content = "";			
		}
	}

	//Returns the list of parameters
	public function getParameterList(){
		return array_keys($this->properties);
	}
	//Returns the parameter label
	public function getParameterLabel(){
		return $this->label;
	}
	//Returns delimiter character
	public function getDelimiterCharacter(){
		return $this->delimiter;
	}
	
	//Builds the content, replacing parameters by its values, and returns as string.
	public function writeResponse(){
		$response = $this->content;
		reset($this->properties); 
		foreach($this->properties as $key => $val){
			$response = str_replace("{$this->delimiter}{$this->label}:$key{$this->delimiter}", $val, $response);
		}
		return $response;
	}

	//validates the property values ​​at the time of assignment (all values are ok).
	protected function validate($propertyName, $newValue){
		return true;
	}
}
?>