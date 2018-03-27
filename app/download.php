<?php 
if (isset($_GET['app_name'])) {
	$app_name = $_GET['app_name'];
	$pump_id  = $_GET['pump_id'];
	$version  = $_GET['version'];
	$filename = $app_name.'_'.$pump_id.'_'.$version.'.apk';

	header('Content-Type: application/download');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header("Content-Length: " . filesize($filename));

	$fp = fopen($filename, "r");
	fpassthru($fp);
	fclose($fp);
}

?>