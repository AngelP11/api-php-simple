<?php

class conexion {

	private $server;
	private $user;
	private $password;
	private $database;
	private $port;
	private $conn;


	function __construct() {
		$datos = $this->getData();

		foreach($datos as $key => $value) {
			$this->server = $value["server"];
			$this->user = $value["user"];
			$this->password = $value["password"];
			$this->database = $value["database"];
			$this->port = $value["port"];
		}

		$this->conn = new mysqli($this->server, $this->user, $this->password, $this->database, $this->port);

		if ($this->conn->connect_errno) {
			echo "No se pudo conectar con la base de datos";
			die();
		}
	}


	private function getData() {
		$direccion = dirname(__FILE__);
		$data = file_get_contents($direccion . "/" . "config");

		return json_decode($data, true);
	}


	private function convertUtf8($array) {
		array_walk_recursive($array, function(&$item, $key) {
			if (!mb_detect_encoding($item, "utf-8", true)) {
				$item = utf8_encode($item);
			}
		});

		return $array;
	}


	public function executeQuery($query) {
		$results = $this->conn->query($query);
		$resultsArray = array();

		foreach($results as $result) {
			$resultsArray[] = $result;
		}

		return $this->convertUtf8($resultsArray);
	}


	public function nonQuery($query) {
		$results = $this->conn->query($query);

		return $this->conn->affected_rows;
	}


	public function nonQueryId($query) {
		$results = $this->conn->query($query);
		$rows = $this->conn->affected_rows;

		if ($rows >= 1) {
			return $this->conn->insert_id;
		} else {
			return 0;
		}
	}


	protected function encrypt($str) {
		return md5($str);
	}

}