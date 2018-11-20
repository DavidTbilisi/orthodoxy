<?php $ch = curl_init();
function post( $key, $default ) {
	if ( isset( $_REQUEST[$key] ) && $_REQUEST[$key] != null )
	{
		return $_REQUEST[$key];
	}
	else
	{
		return $default;
	}
}

$p                = $_REQUEST;
//print_r($p);
$bible            = new stdClass();
$bible->v         = post( "version", '4' );
$bible->w         = post( "book", '100' );
$bible->t         = post( "chapter", '0' );
$bible->m         = post( "verse", '0' );
$bible->ennaa1    = post( "lang1", 'geo' );
$bible->ennaa2    = post( "lang2", 'geo' );
$bible->versia1   = post( "version1", 'm' );
$bible->versia2   = post( "version2", 'm' );
$bible->searcht   = post( "search", '' );
$bible->page      = post( "page", '0' );
$bible->innerlang = post( "innerLang", 'geo' );

$param = '';
foreach ( $bible as $key => $value ):
	$param .= $key . '=' . $value . '&';
endforeach;


// set url
curl_setopt( $ch, CURLOPT_URL, "http://holybible.ge/breadphp.php" );
curl_setopt( $ch, CURLOPT_POST, 1 );
curl_setopt( $ch, CURLOPT_POSTFIELDS,$param);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
// $output contains the output string
$output = curl_exec( $ch );

print_r( $output );
?>