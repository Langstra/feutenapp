<?php
require('includes/start.php');

if (isset($token))
{
	echo $thispage->starting('Vereniging registreren','user','no_user');
	echo errormsg("Je bent al ingelogd bij een vereniging, feut. Zoek het lekker uit.");
	echo $thispage->ending();
	exit;
}
if (isset($_POST['register_association']))
{
	try
	{
		$result = $connection->sendRequest('register_association',array('association_name' => $_POST['association_name'],'username'=>$_POST['username'],'password'=>$_POST['password']));
		if ($result->result)
		{
			$_SESSION['token'] = $result->result['token'];
			header("Location: home");
		}
		else
		{
			echo $thispage->starting('Vereniging registreren','','no_user');
			echo errormsg("Er is iets fout gegaan bij het registreren, probeer opnieuw.");
			echo $thispage->ending();
		}
	}
	catch (Exception $ex)
	{
		print_r($ex);
	}
}
else
{
	echo $thispage->starting('Vereniging registreren','','no_user');
	?>
	<p>
	Registreer jouw vereniging bij 'Fris en feutig' en maak direct een account aan voor jezelf. Daarna kun je met jouw account inloggen en je medebestuurders toevoegen.
	</p>
	<form action="register_association" method="post">
		<div class="form-group">
			<label for="association_name">Verenigingsnaam</label>
			<input type="text" id="association_name" class="form-control" name="association_name">
		</div>
		<div class="form-group">
			<label for="username">Persoonlijke gebruikersnaam</label>
			<input type="text" id="username" class="form-control" name="username">
		</div>
		<div class="form-group">
			<label for="password">Persoonlijk wachtwoord</label>
			<input type="password" id="password" class="form-control" name="password">
		</div>
		<input type="submit" class="btn btn-primary" name="register_association" value="Vereniging registreren">
	</form>
	<?php
}