<?php
require_once('Entity.php');

class Book extends Entity {
    //this is what you need to do
	public function __construct(){
		$this->properties = array(
			"title" => "",
			"author" => "",
            "year" => ""
		);
	}

    //implement rules to sets if aplicable
	protected function validate($propertyName, $newValue){
		return true;
    }

    //optionals
    public function __toString(){
        return "{$this->title} ({$this->year}, {$this->author})";
    }
}

$o = new Book();//you can use like attributes
$o->title = "My Pseudo Book";
$o->author = "Dídimo Vieira";
$o->year = 2021;
echo $o;
echo "<br>";

$o->setTitle("PHP Entity");//you can use like gets and sets
$o->setAuthor("Junior Araújo");
$o->setYear(2010);
echo $o;
echo "<br>";
echo implode(' / ', array($o->getTitle(), $o->getAuthor(), $o->getYear()));
?>