<?php
require("includes/start.php");
echo $thispage->starting('Statistieken','user');

// Data gathering
$noobs = $connection->sendRequest('get_noobs', array('token'=>$token));
$days = $connection->sendRequest('get_point_days',array('token'=>$token));
$noobs_array;
if ($noobs->result)
{
	$biggest_noob = $smallest_noob = $noobs->result[0];
	foreach ($noobs->result as $n)
	{
		if ($n['total']>$biggest_noob['total'])
		{
			$biggest_noob = $n;
		}
		if ($n['total']<$smallest_noob['total'])
		{
			$smallest_noob = $n;
		}
		$points = $connection->sendRequest('get_points',array('token'=>$token,'noob_id'=>$n['id']));
		$noobs_array[$n['id']] = $points->result;
	}
	?>
	<div class="row">
		<div class="col-md-6">
			<h2>Grootste feut</h2>
			<h4><a href="noob/<?=$biggest_noob['id']?>"><?=$biggest_noob['name']?> (<?=$biggest_noob['total']?> minpunten)</a></h4>
			<img class="img-responsive img-circle noob-face" src="<?=$biggest_noob['img_url']?>" width="150">
		</div>
		<div class="col-md-6">
			<h2>Minst gefeute feut</h2>
			<h4><a href="noob/<?=$smallest_noob['id']?>"><?=$smallest_noob['name']?> (<?=$smallest_noob['total']?> minpunten)</a></h4>
			<img class="img-responsive img-circle noob-face" src="<?=$smallest_noob['img_url']?>" width="150">
		</div>
	</div>
	<div class="row">
	<!-- Pie chart of points -->
		<div id="donutchart" class="col-md-6" style="height: 400px;"></div>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			function drawChart()
			{
				var data = google.visualization.arrayToDataTable([
					['Feut', 'Aantal minpunten'],
					<?php
					foreach ($noobs->result as $n)
					{
						if ($n['total'] >= 0)
						{
							echo "['".$n['name']."',	".$n['total']."],
							";
						}
					}
					?>
				]);

				var options = {
					title: 'Verdeling onder het kb',
					pieHole: 0.4,
				};

				var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
				chart.draw(data, options);
			}
		</script>

		<!-- Timeline of points -->
		<div id="chart_div" class="col-md-6" style="height: 400px;"></div>
		<script type="text/javascript">
			google.load('visualization', '1', {packages: ['corechart', 'line']});
			google.setOnLoadCallback(drawBackgroundColor);

			function drawBackgroundColor()
			{
				var data = new google.visualization.DataTable();
				data.addColumn('date', 'Dag');
				<?php
				foreach ($noobs->result as $n)
				{
					echo "data.addColumn('number', '".$n['name']."');
					";
				}
				?>

				data.addRows([
				<?php
				$output = '';
				$noob_totals = array();
				foreach ($days->result as $d)
				{
					$day = explode('-',$d['day']);
					$row = '[new Date('.$day[0].','.$day[1].','.$day[2].'),';
					foreach ($noobs->result as $n)
					{
						if (array_key_exists($n['id'],$noob_totals))
						{
							$amount = $noob_totals[$n['id']];
						}
						else
						{
							$amount = 0;
						}
						foreach ($noobs_array[$n['id']] as $point)
						{
							if (date('Y-m-d',$point['create_time']) == $d['day'])
							{
								$amount += $point['amount'];
							}
						}
						$noob_totals[$n['id']] = $amount;
						$row .= $amount.',';
					}
					$output .= substr($row,0,strlen($row)-1).'],
					';
				}
				echo substr($output,0,strlen($output)-1);
				?>
				]);

				var options = {
					hAxis: {
						title: 'Tijd'
					},
					vAxis: {
						title: 'Minpunten'
					}
				};

				var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			}
		</script>
	</div>
<?php
}
else
{
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-body">
					<h2>Feutloos</h2>
					<p><?=errormsg("Jullie hebben nog geen feutjes, feut. Laat staan dat je minpunten hebt uitgedeeld. Minpunten voor jou!")?></p>
				</div>
			</div>
		</div>
	</div>
	<?php
}
echo $thispage->ending();
?>