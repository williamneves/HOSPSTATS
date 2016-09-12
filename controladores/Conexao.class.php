<?php

class Conexao extends PDO {

	private static $instancia;

	public function Conexao1($dsn, $username = "", $password = "") {
		// O construtro abaixo � o do PDO
		parent::__construct($dsn, $username, $password, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
		));
	}

	public static function getInstance() {
		// Se o a instancia n�o existe eu fa�o uma
		if(!isset( self::$instancia )){
			try {
				self::$instancia = new Conexao(DB.":host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
			} catch ( PDOException $e ) {
				$e->getMessage();  
			}
		}
		// Se j� existe instancia na mem�ria eu retorno ela
		return self::$instancia;
	}
}
?>