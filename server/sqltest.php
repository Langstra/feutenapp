<?php
require('includes/start.php');
$user = q("SELECT id FROM board_members WHERE username=? AND password=?",array('Rien','lol'));
if (n($user)>0)
{
	echo 'oeps';
}
else
{
	echo 'its something';
}
?>