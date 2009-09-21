<?php
require_once '../interface/ISingleton.php';
require_once '../interface/IModifier.php';

class JSLoader implements ISingleton{
	
	static private $instance=NULL;
	
	protected 
		$files=array(),
		$scriptsFolder = 'js/',
		$urlScriptFolder = '/js/',
		$modifier = NULL
		;
		
	
	
	/**
	 * @return JSLoader
	 */
	static function getInstance(){
		if (!self::$instance){
			$c = __CLASS__;
			self::$instance = new $c ();
		}
		return self::$instance;
	}
	
	
	protected function __construct(){
		$this->setJavascriptFolder('js/');
		$this->setJavascriptFolderURL('/js/');
	}
	
	/**
	 * @param $folder - absolute destination folder for generated javascript files. 
	 * Don't use trailing slashes. 
	 * Folder must be writeable for PHP.
	 * Example: $jsloader->setJavascriptFolder('/home/user/public_html/my_app/js');
	 */
	function setJavascriptFolder($folder){
		$this->scriptsFolder = $folder . '/';
	}
	
	/**
	 * @param $folder - URL of generated javascript folder
	 * Don't use trailing slashes. 
	 * Example: $jsloader->setJavascriptFolderURL('http://www.my-website.com/js');
	 */
	function setJavascriptFolderURL($url){
		$this->urlScriptFolder = $url . '/';
	}
	
	/**
	 * @param IModifier $modifier - any text modifier instance
	 */
	function setModifier(IModifier $modifier){
		$this->modifier = $modifier;
	}
	
	/**
	 * @param $filename - Absolute path to a real javascript file
	 */
	function add($filename){
		
		if (file_exists($filename)){
			$fileChecksum = crc32(file_get_contents($filename));
			$this->files[$fileChecksum] = $filename;	
		}
		
	}
	
	function getLink(){
		$filename = $this->createUnitedFile();
		if ($filename){
			return $this->urlScriptFolder . $filename;
		}
		return false;
			
	}
	
	function putScriptTag(){
		if ($link = $this->getLink()){
			echo '<script type="text/javascript" src="' . $link . '"></script>';
		}
	}
	
	protected function getUnitedFileString(){
		$string='';
		foreach ($this->files as $checksum=>$filename){
			$string.=file_get_contents($filename) . "\n";
		}
		return $string;
	}
	
	/**
	 * 
	 * @return int united checksum
	 * United checksum is the sum of all files checksum
	 */
	protected function getUnitedChecksum(){
		return array_sum(array_keys($this->files)); 
	}
	
	/**
	 * @return string FileName
	 * 
	 */
	protected function createUnitedFile(){
		if (empty($this->files)) return false;
		
		$unitedFileName = $this->getUnitedChecksum() . '.js';
		$fullFileName = $this->scriptsFolder . $unitedFileName ;
		if (file_exists($fullFileName)) return $unitedFileName;
		
		if ($this->modifier){
			$unitedString = $modifier->modify( $this->getUnitedFileString() );
		}else{
			$unitedString = $this->getUnitedFileString();
		}
		
		
		file_put_contents( $fullFileName, $unitedString);
		return $unitedFileName;
	}
	
	
}
?>