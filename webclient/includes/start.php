<?php

error_reporting(-1);
ini_set('display_errors', 'On');

session_start();
require("includes/functions.php");
require("includes/jsonrpc.php");
date_default_timezone_set('Europe/Amsterdam');

if (isset($_GET['logout']))
{
	session_destroy();
	unset($token);
	setcookie('feutentoken','',time()-1);
	header("Location: index.php");
}

if (isset($_COOKIE['feutentoken']))
{
	$_SESSION['token'] = $_COOKIE['feutentoken'];
}

if (isset($_SESSION['token']))
{
	$token = $_SESSION['token'];
}

include("includes/page.class.php");
$connection = Tivoka\Client::connect("http://frisenfeutig.nl/server/");
if (isset($token))
{
	$user_association = $connection->sendRequest('get_user_association', array('token'=>$token))->result;
}
else
{
	$user_association = NULL;
}
$thispage = new page($user_association);
?>