<?php

define("DB",'pgsql');	// mysql ou pgsql
define("DB_NAME","db1");
define("DB_USER","PACIENTE");
define("DB_HOST","138.117.36.146");
define("DB_PASS","PAC");

// Medida preventiva caso autoload abaixo não funcione
include_once ("Conexao.class.php");

function __autoload($className){
	 if(file_exists($className.".class.php")){
		  include($className.".class.php");
	 }
}

// Funções de Banco de Dados
$Conexao = Conexao::getInstance();

function senha($string)
{
	return hash_hmac('sha256',$string, 'hsc');
}


?>