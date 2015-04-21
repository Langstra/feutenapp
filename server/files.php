<?php
require('includes/start.php');

if (n(q("SELECT id FROM auth_tokens WHERE token=?",array($_GET['token'])))>0)
{
	$name = $_FILES['file']['name'];
	$tmp_name = $_FILES['file']['tmp_name'];
	$pathinfo = pathinfo($name);
	$extension = strtolower($pathinfo['extension']);
	$move_to = 'files/'.time().'.'.$extension;
	if($extension != "png" && $extension != "jpg")
	{
		echo 'wrong extension error';
	}
	else
	{
		if(move_uploaded_file($tmp_name, $move_to)) {
			echo 'http://'.$_SERVER['SERVER_NAME'].'/'.$move_to;
		}
		else
		{
			echo 'saving file error';
		}
	}
}
?>