<?php
require('includes/start.php');
echo $thispage->starting('Home','user');
?>
<div class="row">
<?php
try
{
	$noobs = $connection->sendRequest('get_noobs', array('token'=>$token));
	if (count($noobs->result) < 1)
	{
		?>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-body">
					<h2>Feutloos</h2>
					<p><?=errormsg("Jullie hebben nog geen feutjes, feut.")?></p>
				</div>
			</div>
		</div>
		<?php
	}
	foreach ($noobs->result as $n)
	{
		?>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-body">
					<h2><a href="noob/<?=$n['id']?>"><?=$n['name']?></a></h2>
					<a href="noob/<?=$n['id']?>"><img class="img-responsive img-circle noob-face" src="<?=$n['img_url']?>" width="150"></a>
					<div class="pull-left">
						<h4>Minpunten:</h4>
						<h2><?=$n['total']?></h2>
					</div>
					<a class="btn btn-danger pull-right" href="register/<?=$n['id']?>">
						<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}
catch (Exception $ex)
{
	var_dump($ex);
}
?>
</div>
<?php
echo $thispage->ending();
?>