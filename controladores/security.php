<?php
session_start();
if(!isset($_SESSION['Dados']))
{
	header("Location: index.php");
}
?>