<?php
require('includes/start.php');
echo $thispage->starting('Wachtwoord wijzigen','user');

if (isset($_POST['edit_password']))
{
	try
	{
		$result = $connection->sendRequest('edit_password',array('token' => $token,'current_password'=>$_POST['current_password'],'new_password'=>$_POST['new_password']));
		if ($result->result)
		{
			echo successmsg('Wachtwoord gewijzigd');
		}
		else
		{
			echo errormsg("Er is iets fout gegaan bij het wijzigen, probeer opnieuw.");
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
	<form action="edit_password" method="post">
		<div class="form-group">
			<label for="current_password">Huidig wachtwoord</label>
			<input type="password" id="current_password" class="form-control" name="current_password">
		</div>
		<div class="form-group">
			<label for="new_password">Nieuw wachtwoord</label>
			<input type="password" id="new_password" class="form-control" name="new_password">
		</div>
		<input type="submit" class="btn btn-primary" name="edit_password" value="Wachtwoord wijzigen">
	</form>
	<?php
}
echo $thispage->ending();
?>