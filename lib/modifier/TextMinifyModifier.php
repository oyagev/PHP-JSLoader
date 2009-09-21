<?php
require_once 'TextModifier.php';
require_once '../minify/jsmin-1.1.1.php';

class TextMinifyModifier extends TextModifier{
	
	function modify($input='',$opts=array()){
		
		$input = JSMin::minify($input);
		return $input;
	}
	
}
?>