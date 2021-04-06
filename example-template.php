<?php
require('Template.php');

Template::setTemplateList(array('test'=>'example-template.html'));
$o = Template::getTemplateInstance('test');
$o->title = 'My Page\'s Title';
$o->heading = 'Hello World!';
$o->content = 'Hi! This is an example of how to use Template class.';
echo $o->writeResponse();
?>