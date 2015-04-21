<?php
include('includes/start.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
<pre>
<?php
$connection = Tivoka\Client::connect("http://frisenfeutig.nl");
try
{
	$request = $connection->sendRequest('add_points', array('noob_id'=>'5','reason_text'=>'Hoi','reason_file'=>'','amount'=>1,'token'=>'6121718da393bcc04eaec3d5538fa7e4'));
	print_r($request->result);
	echo '<br />';
	print_r($request->getRequest(Tivoka\Tivoka::SPEC_2_0));
}
catch (Exception $ex)
{
	print_r($ex);
}
?>
</pre>