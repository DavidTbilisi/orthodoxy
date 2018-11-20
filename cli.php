<?php

if ($argv[1] === 'model:create'){
	$class_name = ucfirst($argv[2]);
	$data= <<<Data
<?php
require_once 'Model.php';
class $class_name extends Model{
	public function __construct(){
		parent::__construct();
		// your construct things goes here
	}
}
?>
Data;


if (	file_put_contents('model/'.$class_name.'.php',$data)){
	echo 'file generated in '.'model/'.$class_name.'.php';
};
}
