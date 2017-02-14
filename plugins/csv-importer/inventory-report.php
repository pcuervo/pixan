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

if(file_exists($wp_root . 'wp-config.php')) {
	require_once($wp_root . "wp-config.php");
}


global $wpdb;

$layout = array(
					'NOMBRE',
					'SKU',
					'UNIDAD',
					'TEMPERATURA',
					'CANTIDAD'
										
					);

$data = array();
// fix for microsoft office
$data[0] = array('sep=;');
$data[1] = $layout;
$orden_number = 0;
$i = 0;
$n = 2;
$desde = '';
$hasta = '';
$canttotal = 0;
$subtotal = 0;

	
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=inventory-report.csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	$products = $wpdb->get_results("select (select meta_value from px_postmeta pm where pm.post_id = p.id and pm.meta_key = '_stock') as stock, p.* from px_posts p where p.post_type = 'product' and p.post_status = 'publish' and (select pmm.meta_value from px_postmeta pmm where pmm.meta_key = '_manage_stock' and pmm.post_id = p.id) = 'yes'");

	foreach ($products as $product) {
				
		$meta = get_post_meta($product->ID);
		

		$data[$n] =  array(
						utf8_decode(html_entity_decode($product->post_title)),
						isset($meta['_sku'][0]) ? $meta['_sku'][0] : '-',
						isset($meta['unidadmedida'][0]) ? $meta['unidadmedida'][0] : '-',
						isset($meta['temperatura'][0]) ? $meta['temperatura'][0] : '-',
						round($product->stock, 2)
						);
			
		$n++;
	
	}
		

	function outputCSV($data) {
		$outstream = fopen("php://output", 'w');
		function __outputCSV(&$vals, $key, $filehandler) {
			fputcsv($filehandler, $vals, ';', '"');
		}
		array_walk($data, '__outputCSV', $outstream);
		fclose($outstream);
	}

	outputCSV($data);

