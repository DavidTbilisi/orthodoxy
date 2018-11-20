<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/8/2018
 * Time: 3:06 PM
 */
require_once 'rb.php';
require_once 'Helpers.php';
R::setup('mysql:host=127.0.0.1;dbname=bible',
	'root', '');

function toJson($res) {
	header('content-type:application/json');
	print_r(json_encode($res, JSON_UNESCAPED_UNICODE));
}
if (Helpers::req('word')) {
//print_r($_POST);
	$res = R::getAll("select * from `bible1` where `verse` regexp ?", ["{$_POST['word']}"]);
	toJson($res);
}

if (Helpers::req('book')) {
	$books = R::getAll("select `name` from `books` ORDER BY 'order' ASC ");
	toJson($books);
}


if (Helpers::req('json')){
	$bible = R::getAll("select * from `bible2`");
	print Helpers::json($bible);
	file_put_contents('bible.json',Helpers::json($bible));
}