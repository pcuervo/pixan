<?php

function show_metabox_punto_recoleccion($post){
	

	$punto = get_post_meta($post->ID, 'ubicacion_punto', true);
	$nombre_responsable = get_post_meta($post->ID, 'nombre_responsable', true);
	$apellido_responsable = get_post_meta($post->ID, 'apellido_responsable', true);
	$telefono_responsable = get_post_meta($post->ID, 'telefono_responsable', true);
	$entre = get_post_meta($post->ID, 'entre_punto', true);
	$latitud_punto = get_post_meta($post->ID, 'latitud_punto', true);
	$longitud_punto = get_post_meta($post->ID, 'longitud_punto', true);

	wp_nonce_field(__FILE__, '_ubicacion_punto_nonce');
	wp_nonce_field(__FILE__, '_latitud_punto_nonce');
	wp_nonce_field(__FILE__, '_longitud_punto_nonce');
	wp_nonce_field(__FILE__, '_nombre_responsable_nonce');
	wp_nonce_field(__FILE__, '_apellido_responsable_nonce');
	wp_nonce_field(__FILE__, '_telefono_responsable_nonce');
	wp_nonce_field(__FILE__, '_entre_punto_nonce');

	echo "<label for='ubicacion_punto' class=''>Ingresa la dirección: </label><br><br>";
	echo "<input type='text' class='widefat' id='ubicacion_punto' name='ubicacion_punto' value='$punto'/>";
	echo "<input type='hidden' class='widefat' id='latitud_punto' name='latitud_punto' value='$latitud_punto'/>";
	echo "<input type='hidden' class='widefat' id='longitud_punto' name='longitud_punto' value='$longitud_punto'/>";

	echo '<br><br><div class="iframe-cont">';
		if ($latitud_punto != '') {
			echo '<iframe width="100%" height="170" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q='.$latitud_punto.','.$longitud_punto.'&hl=es;z=14&amp;output=embed"></iframe>';
		}
	echo '</div><br>';

	echo "<label for='nombre_responsable' class=''>Nombre del responsable: </label>";
	echo "<input type='text' class='widefat' placeholder='Nombre del responsable' id='nombre_responsable' name='nombre_responsable' value='$nombre_responsable'/><br><br>";

	echo "<label for='apellido_responsable' class=''>Apellido del responsable: </label>";
	echo "<input type='text' class='widefat' placeholder='apellido del responsable' id='apellido_responsable' name='apellido_responsable' value='$apellido_responsable'/><br><br>";

	echo "<label for='telefono_responsable' class=''>Teléfono del responsable: </label>";
	echo "<input type='text' class='widefat' id='telefono_responsable' name='telefono_responsable' value='$telefono_responsable'/><br><br>";

	echo "<label for='entre_punto' class=''>Entre calles: </label>";
	echo "<input type='text' class='widefat' id='entre_punto' name='entre_punto' value='$entre'/><br><br>";


}
add_action('add_meta_boxes', function(){
	global $post;

	add_meta_box( 'meta-box-punto-recoleccion', 'Punto de recolección', 'show_metabox_punto_recoleccion', 'puntos-recoleccion');

});

/**
* Save the metaboxes for post type "Puntos de Recolección"
* */
add_action( 'save_post', function ( $post_id ){

	if ( isset($_POST['ubicacion_punto']) and check_admin_referer(__FILE__, '_ubicacion_punto_nonce') ){
		update_post_meta($post_id, 'ubicacion_punto', $_POST['ubicacion_punto']);
	}
	if ( isset($_POST['latitud_punto']) and check_admin_referer(__FILE__, '_latitud_punto_nonce') ){
		update_post_meta($post_id, 'latitud_punto', $_POST['latitud_punto']);
	}
	if ( isset($_POST['longitud_punto']) and check_admin_referer(__FILE__, '_longitud_punto_nonce') ){
		update_post_meta($post_id, 'longitud_punto', $_POST['longitud_punto']);
	}
	if ( isset($_POST['nombre_responsable']) and check_admin_referer(__FILE__, '_nombre_responsable_nonce') ){
		update_post_meta($post_id, 'nombre_responsable', $_POST['nombre_responsable']);
	}
	if ( isset($_POST['apellido_responsable']) and check_admin_referer(__FILE__, '_apellido_responsable_nonce') ){
		update_post_meta($post_id, 'apellido_responsable', $_POST['apellido_responsable']);
	}
	if ( isset($_POST['telefono_responsable']) and check_admin_referer(__FILE__, '_telefono_responsable_nonce') ){
		update_post_meta($post_id, 'telefono_responsable', $_POST['telefono_responsable']);
	}
	if ( isset($_POST['entre_punto']) and check_admin_referer(__FILE__, '_entre_punto_nonce') ){
		update_post_meta($post_id, 'entre_punto', $_POST['entre_punto']);
	}
	
});// save_meta_boxes_puntos_recoleccion

?>