<?php

/*
* Plugin name: Mapa
* Description: mostrar un mapa en el inicio
* Version: 1.0
*/
	//AGREGAR EL POSTTYPE area-entrega
	//locate_template( array( 'px_area_entrega_post_type.php' ), TRUE, TRUE );
	//add_action('admin_init', 'show_map');
	function show_map() 
	{
		echo '<div class="wrap" id="divMap" style="width: 1005; text-align: center;>"
				 <h3><small>Defina un poligono cerrado marcando los puntos que delimitan la zona</small> <a href="#" onclick="resetMap()" id="clearPolygon">Resetear Mapa<a/></h3>
				<div id="gmap_geo" class="gmaps"></div>
			</div>';
	}


	add_action('admin_head', 'load_css');
	function load_css() 
	{
		echo '
			
			<style type="text/css">
				/***
				Google Maps
				***/
				h3 {
					font-color:red;
				}
				.gmaps {
				  height: 300px;
				  width: 100%;
				  /* important!  bootstrap sets max-width on img to 100% which conflicts with google map canvas*/
				}
				.gmaps img {
				  max-width: none;
				}

				#gmap_static div {
				  background-repeat: no-repeat;
				  background-position: 50% 50%;
				  height: 100%;
				  display: block;
				  height: 300px;
				}

				#gmap_routes_instructions {
				  margin-top: 10px;
				  margin-bottom: 0px;
				}
			</style>';
	}

	function register_meta_boxes() {
	    add_meta_box( 'meta-box-id', 'Defina en el mapa el area de la zona', 'show_map', 'area-entrega' );
	    //wp_add_dashboard_widget( 'admin-map-box', __( 'Mapa Box' ), 'show_map' );

	}
	add_action( 'add_meta_boxes', 'register_meta_boxes' );

	add_action('admin_head', 'load_js');
	function load_js() 
	{
		//wp_enqueue_script( 'geo-map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=drawing');
		wp_enqueue_script( 'geo-map-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyABZ4eSBYBsLi5WQ7WdXZpivNq6n4wQZPA&libraries=drawing');
		wp_enqueue_script( 'geo-map-gmaps', plugin_dir_url( __FILE__ ) . 'inc/gmaps/gmaps.js', array('geo-map-api' ));	
		wp_enqueue_script( 'geo-map-script', plugin_dir_url( __FILE__ ) . 'inc/mapa.js', array('geo-map-api', 'geo-map-gmaps', 'jquery'));	
	}

	function example_add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'example_dashboard_widget',         // Widget slug.
			'Defina en el mapa el area de la zona',         // Title.
			'show_map' // Display function.
	    );	
	}
	add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

	function px_area_entrega_PostType() {
	
	register_post_type( 'area-entrega',
		array(
			'labels' => array(
				'name'			=> 'Área de Entrega',
				'singular_name' => 'area',
				'add_new'		=> 'Agregar area',
				'add_new_item'	=> 'Nueva area',
				'edit_item'		=> 'Editar area',
				'new_item'		=> 'Nuevo area',
				'not_found'		=> 'No se encontraron areas de pago',
				'not_found_in_trash' => 'No se encontraron areas en la papelera',
				'menu_name'		=> 'Áreas de Entrega',
			),
			'description' => 'Manejo de areas de entrega para delivery',
			'public' => true,
			'show_in_nav_menus' => true,
			'supports' => array('title',),
			'show_ui' => true,
			'show_in_menu' => true,
			
			'menu_position' => 3,
			'has_archive' => false,
			'query_var' => 'area-entrega',
			'rewrite' => array('slug' => 'area'),
			'capability_type' => 'post',
			'map_meta_cap' => true
		)
	);
	flush_rewrite_rules(false);
	
}
add_action( 'init', 'px_area_entrega_PostType');

// Metabox area de entrega
function meta_box_area_entrega(){
	add_meta_box( 'meta-box-area_entrega', 'Dias y horario de entrega', 'show_metabox_area_entrega', 'area-entrega');
}

function show_metabox_area_entrega($post){
	
	$dia1 = get_post_meta($post->ID, 'dia1', true);
	$dia2 = get_post_meta($post->ID, 'dia2', true);
	$dia3 = get_post_meta($post->ID, 'dia3', true);
	$dia4 = get_post_meta($post->ID, 'dia4', true);
	$dia5 = get_post_meta($post->ID, 'dia5', true);
	$dia6 = get_post_meta($post->ID, 'dia6', true);
	$dia7 = get_post_meta($post->ID, 'dia7', true);
	$hora = get_post_meta($post->ID, 'hora', true);
	$coordenadas = get_post_meta($post->ID, 'coordenadas', true);
	$d1 = ''; $d2 = ''; $d3 = ''; $d4 = ''; $d5 = ''; $d6 = ''; $d7 = '';
	wp_nonce_field(__FILE__, '_area_entrega_nonce');
	if($dia1 == 'Lunes') { $d1 = 'checked="checked"'; }
	if($dia2 == 'Martes') { $d2 = 'checked="checked"'; }
	if($dia3 == 'Miercoles') { $d3 = 'checked="checked"'; }
	if($dia4 == 'Jueves') { $d4 = 'checked="checked"'; }
	if($dia5 == 'Viernes') { $d5 = 'checked="checked"'; }
	if($dia6 == 'Sabado') { $d6 = 'checked="checked"'; }
	if($dia7 == 'Domingo') { $d7 = 'checked="checked"'; }

	echo 'Lunes <input type="checkbox" id="dia1" name="dia1" value="Lunes" '.$d1.' />';
	echo 'Martes <input type="checkbox" id="dia2" name="dia2" value="Martes" '.$d2.' />';
	echo 'Miercoles <input type="checkbox" id="dia3" name="dia3" value="Miercoles" '.$d3.' />';
	echo 'Jueves <input type="checkbox" id="dia4" name="dia4" value="Jueves" '.$d4.' />';
	echo 'Viernes <input type="checkbox" id="dia5" name="dia5" value="Viernes" '.$d5.' />';
	echo 'Sabado <input type="checkbox" id="dia6" name="dia6" value="Sabado" '.$d6.' />';
	echo 'Domingo <input type="checkbox" id="dia7" name="dia7" value="Domingo" '.$d7.' />';
	echo '<br />';
	echo '<input style="width:45%;"" type="text" id="hora" name="hora" value="'.$hora.'" placeholder="Rango de horario de entrega (De 1pm a 4pm)" />';

	echo '<textarea style="width:100%; display:none;" type="text" id="coordenadas" name="coordenadas" placeholder="Coordenadas de la zona">'.$coordenadas.'</textarea>';

}

add_action('save_post', function($post_id){

	if ( isset($_POST['dia1']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia1', $_POST['dia1']);
	}
	if ( isset($_POST['dia2']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia2', $_POST['dia2']);
	}
	if ( isset($_POST['dia3']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia3', $_POST['dia3']);
	}
	if ( isset($_POST['dia4']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia4', $_POST['dia4']);
	}
	if ( isset($_POST['dia5']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia5', $_POST['dia5']);
	}
	if ( isset($_POST['dia6']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia6', $_POST['dia6']);
	}
	if ( isset($_POST['dia7']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'dia7', $_POST['dia7']);
	}
	if ( isset($_POST['hora']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'hora', $_POST['hora']);
	}
	if ( isset($_POST['coordenadas']) and check_admin_referer(__FILE__, '_area_entrega_nonce') ){
		update_post_meta($post_id, 'coordenadas', $_POST['coordenadas']);
	}

});
add_action('add_meta_boxes', 'meta_box_area_entrega');

?>