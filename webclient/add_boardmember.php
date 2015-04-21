<?php
require('includes/start.php');
echo $thispage->starting("Bestuurder toevoegen",'user');

if (isset($_POST['add_boardmember']))
{
	try
	{
		$result = $connection->sendRequest('add_boardmember',array('token' => $token,'username'=>$_POST['username'],'password'=>$_POST['password']));
		if ($result->result)
		{
			echo successmsg('Bestuurder toegevoegd');
		}
		else
		{
			echo errormsg("Er is iets fout gegaan bij het toevoegen, probeer opnieuw.");
		}
	}
	catch (Exception $ex)
	{
		print_r($ex);
	}
}
else
{
	?>
	<form action="add_boardmember" method="post">
		<div class="form-group">
			<label for="username">Gebruikersnaam</label>
			<input type="text" id="username" class="form-control" name="username">
		</div>
		<div class="form-group">
			<label for="password">Wachtwoord</label>
			<input type="password" id="password" class="form-control" name="password">
		</div>
		<input type="submit" class="btn btn-primary" name="add_boardmember" value="Bestuurder toevoegen">
	</form>
	<?php
}

echo $thispage->ending();
?>