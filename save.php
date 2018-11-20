<?php
require_once "rb.php";
function post( $key, $default = null ) {
	if ( isset( $_REQUEST[$key] ) && $_REQUEST[$key] != null )
	{
		return $_REQUEST[$key];
	}
	else
	{
		return $default;
	}
}

R::setup( 'mysql:host=127.0.0.1;dbname=bible',
	'root', '' );
$verses = post( 'verses' );


if ( post( "last" ) ) :
	print_r( json_encode( R::getRow( 'select chapter,book from `bible3` ORDER by id desc' ), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
endif;

if ( post( 'save' ) ) :
	$bible            = R::dispense( 'bible3' );
	$bible->book      = post( 'book' );
	$bible->chapter   = post( 'chapter' );
	$bible->verse     = post( "verse" );
	$bible->verse_num = post( "verse_id" );
	echo R::store( $bible );
endif;


?>

