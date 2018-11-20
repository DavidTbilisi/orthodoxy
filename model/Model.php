<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/20/2018
 * Time: 3:21 PM
 */



class Model {
	protected  $table;
	public function __construct() {
		$this->table  = strtolower(get_called_class());
	}

}