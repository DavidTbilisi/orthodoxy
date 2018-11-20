<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/18/2018
 * Time: 4:30 PM
 */
require_once 'rb.php';
require_once 'Helpers.php';

class Test {
	public function __construct() {
		R::setup( 'mysql:host=127.0.0.1;dbname=manytomany',
			'root', '' );
		R::debug( true );
	}


	public function getStudents() {

		$tchr                    = R::dispense( 'teachers' );
		$tchr->name              = "david";
		$stdnt                   = R::dispense( 'students' );
		$stdnt->name             = 'helen';
		$tchr->ownStudentsList[] = $stdnt;
		R::store( $tchr );
	}

}

class Db {
	private static $instance;
	private $table;
	private $result;
	private $sql;

	public function __construct( $host, $db, $user, $pass ) {
		R::setup( "mysql:host={$host};dbname={$db}",
			$user, $pass );
		R::debug( true );
	}

	public static function setup( $host, $db, $user, $pass ) {
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self( $host, $db, $user, $pass );
		}

		return self::$instance;
	}

	public function get( $sql ) {
		$this->result = R::getAll( $sql );
	}

	// CRUD

	public function all( $table, $where = 1, array $bindings = [] ) {
		$this->table  = $table;
		$this->sql    = "SELECT * FROM `{$table}` WHERE {$where}";
		$this->result = R::getAll( $this->sql, $bindings );

		return $this;
	}

	public function one( $table, $where = 1, array $bindings = [] ) {
		$this->table  = $table;
		$this->sql    = "SELECT * FROM `{$table}` WHERE {$where}";
		$this->result = R::getRow( $this->sql, $bindings );

		return $this;
	}

	public function obj() {
		return json_decode( json_encode( $this->result ), false );
	}

	public function bean() {

		if ( $this->is_assoc( $this->result ) )
		{
			$result = $this->result;
			unset( $this->result );
			$this->result[0] = $result;
		}

		return R::convertToBeans( $this->table, $this->result );
	}

	private function is_assoc( $a ) {
		foreach ( array_keys( $a ) as $key )
		{
			if ( is_int( $key ) )
			{
				return false;
			}
		}

		return true;
	}

	public function assoc() {
		return $this->result;
	}

	public function save( $table, $array ) {
		$this->table = R::dispense( $table );
		if ( $this->is_assoc( $array ) ) :
			foreach ( $array as $key => $value ):
				$this->table[$key] = $value;
			endforeach;
		else:
			throw new Exception( "მასივი უნდა იყოს ასოციაციური ['key'=>'value',]" );
		endif;
		R::store( $this->table );
	}

	public function update( $type, $array, $where ) {

		R::ext( 'update', function ( $type, $key, $value, $where )
		{
			return R::exec( "UPDATE `{$type}` SET `{$key}`= '{$value}' where {$where}" );
		} );

		foreach ( $array as $index => $value ):
			R::update( $type, $index, $value, $where );
		endforeach;

		return $this;
		/*todo: return affected rows*/
	}

	public function del( $table, $where ) {
		$to_trash = R::find( $table, $where );

		return R::trashAll( $to_trash );
	}

	public function oneToMany( $one_table, $one_id, $many_table, $many_where ) {
		$teacher            = R::load( $one_table, $one_id );
		$students           = R::find( $many_table, $many_where );
		$st                 = ucfirst( $many_table );
		$ownTable           = "own{$st}List";
		$teacher->$ownTable = $students;
		R::store( $teacher );
	}

	public function manyToMany( $first, $shared ) {
		$firstGroup  = R::find( $first[0], $first[1] );
		$sharedTag   = R::findOne( $shared[0], $shared[1] );

		$ucShared = ucfirst( $shared[0] );
		$ucShared = "shared{$ucShared}List";

		foreach ($firstGroup as $one):
		$one->$ucShared[]  = $sharedTag;
		endforeach;
		R::storeAll( $firstGroup );
	}

	private function __clone() {
		// TODO: Implement __clone() method.
	}


}


$db  = db::setup( '127.0.0.1', 'manytomany', "root", '' );
$st  = 'students';
$wd  = 'workdays';
$crs = 'courses';


$megi = R::findOne($st,'name = "მეგი"');
Helpers::dump($megi->sharedWorkdays);