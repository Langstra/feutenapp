<?php
include('includes/start.php');
echo $thispage->starting('Minpunt registreren','user');
?>
<div class="row">
<?php
if (isset($_POST['register']) && isset($_POST['noob']))
{
	if (isset($_FILES['reason_file']['tmp_name']))
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://frisenfeutig.nl/server/files.php?token=".$token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
		$args['file'] = new CurlFile($_FILES['reason_file']['tmp_name'],$_FILES['reason_file']['type'],$_FILES['reason_file']['name']);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
		$file_location = curl_exec($ch);
	}
	else
	{
		$file_location = "";
	}

	try
	{
		$result = $connection->sendRequest('add_points', array('noob_ids'=>$_POST['noob'],'reason_text'=>$_POST['reason_text'],'reason_file'=>$file_location,'amount'=>$_POST['amount'],'token'=>$token));
		if ($result->result)
		{
			echo successmsg("Minpunt geregistreerd");
		}
		else
		{
			echo errormsg("Minpunt niet geregistreerd, fout opgetreden");
		}
	}
	catch (Exception $ex)
	{
		var_dump($ex);
	}
}
else
{
	?>
	<form enctype="multipart/form-data" action="register" method="post">
	<?php
	try
	{
		$noobs = $connection->sendRequest('get_noobs', array('token'=>$token));
		$noob_id = (isset($_GET['noob'])?$_GET['noob']:-1);
		foreach($noobs->result as $n)
		{
		?>
			<label class="checkbox-inline">
				<input type="checkbox" name="noob[]" value="<?=$n['id']?>" <?=($n['id']==$noob_id?'checked':'')?>>
				<img class="img-responsive img-circle" src="<?=$n['img_url']?>" width="150">
			</label>
	<?php
		}
	}
	catch (Exception $ex)
	{
		var_dump($ex);
	}
	?>
		<div class="form-group">
			<label for="reason">Reden</label>
			<textarea id="reason" class="form-control" name="reason_text" rows="4"></textarea>
		</div>
		<div class="form-group">
			<label for="reason_file">Bijlage</label>
			<input type="file" id="reason_file" name="reason_file">
		</div>
		<div class="form-group">
			<label for="amount">Aantal minpunten</label>
			<input type="number" id="amount" class="form-control" name="amount">
		</div>
		<input type="submit" class="btn btn-primary" name="register" value="Minpunten, feut!">
	</form>
	<?php
}
?>
</div>
<?php
echo $thispage->ending();
?>