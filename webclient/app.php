<?php
require('includes/start.php');
echo $thispage->starting('De app','user');
?>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-body">
				<h2>Android</h2>
				<p>
					<ol>
						<li>Navigeer naar Settings->Security (Instellingen->Beveiliging)</li>
						<li>Zet 'Unknown sources' (Onbekende bronnen) aan</li>
						<li><a href="http://frisenfeutig.nl/app/fris_en_feutig.apk" class="btn btn-primary">Download de app op je telefoon</a></li>
						<li>Open 'android.apk' op je telefoon en installeer de app!</li>
						<li>Deel uit je minpunten</li>
					</ol>
				</p>
				<p><?=errormsg("De app is nog niet af, heb eens geduld feut.")?></p>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-body">
				<h2>iOS</h2>
				<p><?=errormsg("De app is nog niet af, heb eens geduld feut.")?></p>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-primary">
			<div class="panel-body">
				<h2>Windows Phone</h2>
				<p><?=errormsg("De app is nog niet af, heb eens geduld feut.")?></p>
			</div>
		</div>
	</div>
</div>
<?php
echo $thispage->ending();
?>