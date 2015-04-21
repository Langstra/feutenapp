<?php

function errormsg($msg)
{
	$display = '<div class="alert alert-danger">'.$msg.'</div>';
	return $display;
}

function successmsg($msg)
{
	$display = '<div class="alert alert-success">'.$msg.'</div>';
	return $display;
}

function infomessage($msg)
{
	$display = '<div class="alert alert-info">'.$msg.'</div>';
	return $display;
}

function warningmessage($msg)
{
	$display = '<div class="alert alert-warning">'.$msg.'</div>';
	return $display;
}

?>