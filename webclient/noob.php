<?php
require("includes/start.php");
$noob = $connection->sendRequest('get_noob',array('token'=>$token,'noob_id'=>$_GET['noob']))->result;
if (empty($noob['name']))
{
	echo $thispage->starting('Feut missing','user');
	echo errormsg("Geen feut geselecteerd");
	echo $thispage->ending();
}
else
{
echo $thispage->starting('Punten van '.$noob['name'],'user');
	?>
	<img class="img-responsive img-circle noob-face" src="<?=$noob['img_url']?>" width="150">
	<a class="btn btn-danger" href="register/<?=$noob['id']?>">
		<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
	</a>

	<h2><?=$noob['total']?> minpunten totaal</h2>
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<?php
	$points = $connection->sendRequest('get_points',array('token'=>$token,'noob_id'=>$noob['id']))->result;
	foreach ($points as $p)
	{
		$file_output = 'Geen';
		if (!empty($p['reason_file']))
		{
			$extension = strtolower(pathinfo($p['reason_file'])['extension']);
		}
		?>
		<div class="panel panel-primary">
			<div class="panel-heading" role="tab" id="heading<?=$p['id']?>">
				<h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$p['id']?>" aria-expanded="false" aria-controls="collapse<?=$p['id']?>">
					<?=$p['reason_text'];?> (<?=$p['amount']?>)
					</a>
				</h4>
			</div>
			<div id="collapse<?=$p['id']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?=$p['id']?>">
				<div class="panel-body">
				<p>Verdient op <?=date('d-m-Y H:i',$p['create_time'])?></p>
				<img class="img-responsive noob-img" src="<?=$p['reason_file']?>">
				</div>
			</div>
		</div>
		<?php
	}
	echo $thispage->ending();
}
?>