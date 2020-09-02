<?php

/* Using OCI */

class DB {

	private $conn;
	private $stid;
	private $user;
	private $pwd;
	private $dsn;

	public function __construct($dsn='', $user='', $pwd=''){		

		$this->dsn = ($dsn) ? $dsn : AppConfig::DB_HOST;
		$this->user = ($user) ? $user : AppConfig::DB_USER;
		$this->pwd = ($pwd) ? $pwd : AppConfig::DB_PASS;

	}

	public function DBConnect(){		

		// $conn = oci_pconnect($this->user, $this->pwd, $this->dsn);
		// if (!$conn) {
		// 	$e = oci_error();
		// 	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		// }

		// $this->conn = $conn;

	}

	public function DBQuery($sql){
		
		return null;
		if(!$this->conn){
			$this->DBConnect();
		}

		// Prepare the statement
		$stid = oci_parse($this->conn, $sql);
		if (!$stid) {
			$e = oci_error($conn);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		// Perform the logic of the query
		$r = oci_execute($stid);
		if (!$r) {
			$e = oci_error($stid);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		return $this->stid = $stid;


	}

	public function DBFetchRow(){

		if(!$this->conn){
			return null;
		}

		return oci_fetch_array($this->stid, OCI_ASSOC+OCI_RETURN_NULLS);

	}

	public function DBClose(){
		if($this->stid){
			oci_free_statement($this->stid);
		}
		// oci_close($this->conn);
	}

}