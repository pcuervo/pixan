<?php

// CUSTOM METABOXES //////////////////////////////////////////////////////////////////

function meta_box_unidad_medida(){
	add_meta_box( 'meta-box-unidad_medida', 'Información Adicional', 'show_metabox_unidad_medida', 'product');
}

function show_metabox_unidad_medida($post){
	
	$unit = get_post_meta($post->ID, 'unidadmedida', true);
	$temperatura = get_post_meta($post->ID, 'temperatura', true);

	$s1 = ''; $s2 = ''; $s3 = ''; $s4 = '';
	$ss1 = ''; $ss2 = '';
	wp_nonce_field(__FILE__, '_area_entrega_nonce');

	if($unit == 'Unidad') { $s1 = 'checked="checked"'; }
	if($unit == 'Kilos') { $s2 = 'checked="checked"'; }
	if($unit == 'Gramos') { $s3 = 'checked="checked"'; }			
	
	if($temperatura == 'Congelado') { $ss1 = 'checked="checked"'; }
	if($temperatura == 'Fresco') { $ss2 = 'checked="checked"'; }

	
	//echo '<div style="width:40%; text-align:left; float:left;">';
	echo '<h4>Unidad de Medida:</h4>';
    echo '<input type="radio" name="unidadmedida" id="unidad" value="Unidad" '.$s1.' /><label for="unidad">Unidad</label>';
	echo '<input type="radio" name="unidadmedida" id="kilos" value="Kilos" '.$s2.' /><label for="kilos">Kilos</label>';
	echo '<input type="radio" name="unidadmedida" id="gramos" value="Gramos" '.$s3.' /><label for="gramo">Gramos</label>';
	//echo '</div>';

	echo '<br />';

	//echo '<div style="width:40%; text-align:right; float:right;">';
	echo '<h4>Temperatura:</h4>';
    echo '<input type="radio" name="temperatura" value="Congelado" '.$ss1.' /><label for="unidad">Congelado</label>';
	echo '<input type="radio" name="temperatura" value="Fresco" '.$ss2.' /><label for="kilos">Fresco</label>';
	//echo '</div>';
}

add_action('save_post', function($post_id){

	if ( isset($_POST['unidadmedida']) and check_admin_referer(__FILE__, '_unidad_medida_nonce') ){
		update_post_meta($post_id, 'unidadmedida', $_POST['unidadmedida']);
	}
	if ( isset($_POST['temperatura']) and check_admin_referer(__FILE__, '_unidad_medida_nonce') ){
		update_post_meta($post_id, 'temperatura', $_POST['temperatura']);
	}

});





?>