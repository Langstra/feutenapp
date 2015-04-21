<?php
include('includes/start.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<pre>
<?php
$params = array('password'=>'hamerfeut','username'=>'rienheuver');
echo test($params);
function test($params)
{
	try
	{
		$salt = 'frisenfruitig36';
		$cryptpass = crypt($params['password'],$salt);
		$id = q("SELECT id FROM board_members WHERE username=? AND password=?",array($params['username'],$cryptpass));
		if ($id)
		{
			$token = bin2hex(openssl_random_pseudo_bytes(16));
			q("INSERT INTO auth_keys (key) VALUES (?)",array($token));
			q("UPDATE board_members SET (auth_keys_id) VALUES (?)",array(last()));
			return $token;
		}
		else
		{
			return 'wtf';
		}
	}
	catch (PDOException $ex)
	{
		return 'wtf';
	}
}
?>
</pre>