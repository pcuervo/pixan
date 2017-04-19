<?php

$wp_root = dirname(__FILE__) .'/../../../';
if(file_exists($wp_root . 'wp-load.php')) {
	require_once($wp_root . "wp-load.php");
} else if(file_exists($wp_root . 'wp-config.php')) {
	require_once($wp_root . "wp-config.php");
} else {
	exit;
}

if ( !current_user_can('manage_options') ) {
	exit(0);
}

if(!isset($_POST['ait-import-post-type'])) exit(0);
$type = $_POST['ait-import-post-type'];

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=sample-csv.csv");
header("Pragma: no-cache");
header("Expires: 0");

function outputCSV($data) {
	$outstream = fopen("php://output", 'w');
	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals, ';', '"');
	}
	array_walk($data, '__outputCSV', $outstream);
	fclose($outstream);
}

$output = $_POST;
unset($output['ait-import-post-type']);
unset($output['ait-import-is-ait-type']);

$data = array();
// fix for microsoft office
$data[0] = array('sep=;');
$data[1] = array_keys($output);

outputCSV($data);