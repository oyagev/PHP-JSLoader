<?php
require_once '../lib/JSLoader.php';

$jsloader = JSLoader::getInstance();
$jsloader->setJavascriptFolder('../js');
$jsloader->setJavascriptFolderURL('https://www.oren.loc/JSLoader/js');

$jsloader->add('./scripts/prototype.js');
$jsloader->add('./scripts/scriptaculous.js');

$jsloader->putScriptTag();
?>