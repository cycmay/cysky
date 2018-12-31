<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

class MySQLDatabase {
	
	private $connection;
	public $last_query;
	private $magic_quotes_active;
	private $real_escape_string_exists;
	
    function __construct() {
    	$this->open_connection();
		$this->magic_quotes_active = get_magic_quotes_gpc();
		$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" );
    }

	public function open_connection() {
		/*mysqli_connect( 
 			'localhost', /* The host to connect to 连接MySQL地址 */   
 		/*	'user',   /* The user to connect as 连接MySQL用户名 */   
 		/*	'password', /* The password to use 连接MySQL密码 */   
 		/*	'world');  /* The default database to query 连接数据库名称*/ 
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
		mysqli_query($this->connection, 'SET NAMES utf8');
		if (!$this->connection) {
			die("Database connection failed: " . mysql_error($this->connection));
		}
	}

	public function close_connection() {
		if(isset($this->connection)) {
			mysql_close($this->connection);
			unset($this->connection);
		}
	}

	public function query($sql) {
		$this->last_query = $sql;
		$result = mysqli_query($this->connection, $sql);
		$this->confirm_query($result);
		return $result;
	}
	
	public function escape_value( $value ) {
		if( $this->real_escape_string_exists ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysqli_real_escape_string($this->connection, $value);
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}
	
	// "database-neutral" methods
   public function fetch_array($result_set) {
    return mysqli_fetch_array($result_set);
   }

   public function num_rows($result_set) {
    return mysqli_num_rows($result_set);
   }
  
   public function insert_id() {
    // get the last id inserted over the current db connection
    return mysqli_insert_id($this->connection);
   }
  
   public function affected_rows() {
    return mysqli_affected_rows($this->connection);
   }

	private function confirm_query($result) {
		if (!$result) {
	    $output = "Database query failed: " . mysqli_error($this->connection) . "<br /><br />";
	    $output .= "Last SQL query: " . $this->last_query;
	    die( $output );
		}
	}
	
}

$database = new MySQLDatabase();
$db =& $database;
