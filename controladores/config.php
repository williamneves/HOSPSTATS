<?php

define("DB",'pgsql');	// mysql ou pgsql
define("DB_NAME","db1");
define("DB_USER","PACIENTE");
define("DB_HOST","138.117.36.146");
define("DB_PASS","PAC");

if(!@($conexao=pg_connect ("host=".DB_HOST." dbname=".DB_NAME." port=5432 user=".DB_USER." password=".DB_PASS)))
{
	return "Não foi possível estabelecer uma conexão com o banco de dados.";
}

function senha($string)
{
	return hash_hmac('sha256',$string, 'hsc');
}

function consultaBanco($sql)
{
	return pg_query($sql);
}

?>