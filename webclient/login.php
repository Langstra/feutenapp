<?php
include('includes/start.php');

if (isset($token))
{
	echo $thispage->starting('Log in','user','no_user');
	echo errormsg("Je bent al ingelogd feut. Zoek het lekker uit.");
	echo $thispage->ending();
	exit;
}
if (isset($_POST['login']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	if (isset($_POST['remember']))
	{
		$remember = $_POST['remember'];
	}
	else
	{
		$remember = false;
	}
	$checked = '';
	if ($remember == 1)
	{
		$checked = 'checked';
	}
	$form = '<form action="login" method="post">
		<div class="form-group">
			<label for="username">Gebruikersnaam</label>
			<input type="text" class="form-control" name="username" placeholder="Gebruikersnaam" value="'.$username.'" /></td>
		</div>
		<div class="form-group">
			<label for="password">Wachtwoord</label>
			<input type="password" class="form-control" name="password" placeholder="Wachtwoord" />
		</div>
		<div class="checkbox">
			<label>
				<input type="checkbox" name="remember" value="1" '.$checked.'> Blijf ingelogd
			</label>
		</div>
		<input type="submit" class="btn btn-primary" value="Log in" name="login" />
	</form>
	';
	if (empty($username))
	{
		echo $thispage->starting('Log in','','no_user');
		echo errormsg('Vul je gebruikersnaam in feut.');
		echo $form;
		echo $thispage->ending();
		exit;
	}
	if (empty($password))
	{
		echo $thispage->starting('Log in','','no_user');
		echo errormsg('Vul je wachtwoord in feut.');
		echo $form;
		echo $thispage->ending();
		exit;
	}
	
	$request = $connection->sendRequest('authenticate', array('username'=>$_POST['username'],'password'=>$_POST['password']));
	if (!$request->result)
	{
		echo $thispage->starting('Log in','','no_user');
		echo errormsg('Dat is niet jouw login feut.');
		echo $form;
		echo $thispage->ending();
		exit;
	}
	else
	{
		$_SESSION['token'] = $request->result['token'];
		if ($remember == 1)
		{
			setcookie('feutentoken',$request->result['token'],time()+60*60*24*28);
		}
		header('Location: home');
		exit;
	}
}
else
{
	echo $thispage->starting('Log in','','no_user');
?>
<form action="login" method="post">
	<div class="form-group">
		<label for="username">Gebruikersnaam</label>
		<input type="text" class="form-control" name="username" placeholder="Gebruikersnaam" /></td>
	</div>
	<div class="form-group">
		<label for="password">Wachtwoord</label>
		<input type="password" class="form-control" name="password" placeholder="Wachtwoord" />
	</div>
	<div class="checkbox">
		<label>
			<input type="checkbox" name="remember" value="1"> Blijf ingelogd
		</label>
	</div>
	<input type="submit" class="btn btn-primary" value="Log in" name="login" />
</form>
<?php
	echo $thispage->ending();
}
?>