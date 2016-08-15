<?php

// CUSTOM METABOXES //////////////////////////////////////////////////////////////////

// PRUEBA DE PLUGIN



function meta_box_unidad_medida(){

	add_meta_box( 'meta-box-unidad_medida', 'Unidad Medida', 'show_metabox_unidad_medida', 'unidades');
	
}

function show_metabox_unidad_medida($post){
	
	wp_nonce_field(__FILE__, '_unidad_medida_nonce');

	echo "<label for='tiempo_preparacion' class='label-paquetes'>Tiempo de preparación: </label>";
	echo "<input type='text' class='widefat' id='tiempo_preparacion' name='tiempo_preparacion' value='$tiempo_preparacion'/>";

	echo "<br><br><label for='numero_personas' class='label-paquetes'>Número de personas: </label>";
	echo "<input type='text' class='widefat' id='numero_personas' name='numero_personas' value='$numero_personas'/>";

	echo "<br><br><label for='nivel_de_preparacion' class='label-paquetes'>Nivel de preparación: </label>";
	echo "<input type='text' class='widefat' id='nivel_de_preparacion' name='nivel_de_preparacion' value='$nivel_de_preparacion'/>";

	echo "<br><br><label for='pasos_preparacion' class='label-paquetes'>Pasos para preparar: </label>";
}




?>