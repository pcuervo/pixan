<?php
/**
 * Admin panel settings for Area de Entrega Pixan.
 *
 * This class will create menu items in admin panel, as well as initial setup
 * of post types and all required elements...
 *
 * @since 1.0.0
 */

class Area_Entrega_Pixan_Settings {

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 * @return null or Area_Entrega_Pixan_Settings instance
	 */
	public static function get() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 */
	private function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_and_localize_admin_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_area_entrega' ) );
		add_action( 'init', array( $this, 'register_custom_post_types' ), 5 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 5, 1  );
	}

	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_post_types() {
		$this->register_post_type_area_entrega();
	}

	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_taxonomies() {
		$this->register_taxonomy_maestros();
		$this->register_taxonomy_modulos();
		$this->register_taxonomy_lecciones();
	}

	/**
	 * Register all meta boxes needed for custom post types.
	 */
	public function add_meta_boxes_area_entrega() {
		add_meta_box( 'meta-box-area_entrega', 'Dias y horario de entrega', array( $this, 'meta_box_horario' ), 'area-entrega');
		add_meta_box( 'meta-box-id', 'Defina en el mapa el area de la zona', array( $this, 'meta_box_area_entrega' ), 'area-entrega' );
	}

	/**
	 * Save metaboxes
	 */
	public function save_meta_boxes( $post_id ) {
		$this->save_meta_boxes_area_entrega( $post_id );
	}

	/**
	 * Add javascript and style files
	 */
	public function enqueue_and_localize_admin_scripts(){
		if( 'area-entrega' == get_post_type() )
		{
			wp_enqueue_style( 'admin_styles', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/css/map_styles.css' );
			//wp_enqueue_script( 'geo-map-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyABZ4eSBYBsLi5WQ7WdXZpivNq6n4wQZPA&libraries=drawing');
			wp_enqueue_script( 'geo-map-gmaps', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/gmaps/gmaps.js', array('map-admin-orders-api' ));	
			wp_enqueue_script( 'geo-map-script', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/mapa.js', array('map-admin-orders-api', 'geo-map-gmaps', 'jquery'));	
		}
	}


	/******************************************
	* CUSTOM POST TYPES
	******************************************/

	/**
	 * Register the post type "Área de Entrega"
	 */
	private function register_post_type_area_entrega() {
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
	}// register_post_type_area_entrega


	/******************************************
	* META BOX CALLBACKS
	******************************************/

	/**
	* Display day and time meta_boxes for post type "AreaEntrega"
	* @param obj $post
	**/
	public function meta_box_horario( $post ){
		$dia1 = get_post_meta($post->ID, '_dia1', true);
		$dia2 = get_post_meta($post->ID, '_dia2', true);
		$dia3 = get_post_meta($post->ID, '_dia3', true);
		$dia4 = get_post_meta($post->ID, '_dia4', true);
		$dia5 = get_post_meta($post->ID, '_dia5', true);
		$dia6 = get_post_meta($post->ID, '_dia6', true);
		$dia7 = get_post_meta($post->ID, '_dia7', true);
		$hora = get_post_meta($post->ID, '_hora', true);
		
		$d1 = ''; $d2 = ''; $d3 = ''; $d4 = ''; $d5 = ''; $d6 = ''; $d7 = '';
		wp_nonce_field(__FILE__, '_dia1_nonce');
		wp_nonce_field(__FILE__, '_dia2_nonce');
		wp_nonce_field(__FILE__, '_dia3_nonce');
		wp_nonce_field(__FILE__, '_dia4_nonce');
		wp_nonce_field(__FILE__, '_dia5_nonce');
		wp_nonce_field(__FILE__, '_dia6_nonce');
		wp_nonce_field(__FILE__, '_dia7_nonce');
		wp_nonce_field(__FILE__, '_hora_nonce');

		if($dia1 == 'Lunes') { $d1 = 'checked="checked"'; }
		if($dia2 == 'Martes') { $d2 = 'checked="checked"'; }
		if($dia3 == 'Miercoles') { $d3 = 'checked="checked"'; }
		if($dia4 == 'Jueves') { $d4 = 'checked="checked"'; }
		if($dia5 == 'Viernes') { $d5 = 'checked="checked"'; }
		if($dia6 == 'Sabado') { $d6 = 'checked="checked"'; }
		if($dia7 == 'Domingo') { $d7 = 'checked="checked"'; }

		echo 'Lunes <input type="checkbox" id="_dia1" name="_dia1" value="Lunes" '.$d1.' />';
		echo 'Martes <input type="checkbox" id="_dia2" name="_dia2" value="Martes" '.$d2.' />';
		echo 'Miercoles <input type="checkbox" id="_dia3" name="_dia3" value="Miercoles" '.$d3.' />';
		echo 'Jueves <input type="checkbox" id="_dia4" name="_dia4" value="Jueves" '.$d4.' />';
		echo 'Viernes <input type="checkbox" id="_dia5" name="_dia5" value="Viernes" '.$d5.' />';
		echo 'Sabado <input type="checkbox" id="_dia6" name="_dia6" value="Sabado" '.$d6.' />';
		echo 'Domingo <input type="checkbox" id="_dia7" name="_dia7" value="Domingo" '.$d7.' />';
		echo '<br />';
		echo '<input style="width:45%;"" type="text" id="hora" name="_hora" value="'.$hora.'" placeholder="Rango de horario de entrega (De 1pm a 4pm)" />';
	}// meta_box_info_maestro

	/**
	* Display map meta_boxes for post type "AreaEntrega"
	* @param obj $post
	**/
	public function meta_box_area_entrega( $post ){
		$coordenadas = get_post_meta($post->ID, '_coordenadas', true);
		wp_nonce_field(__FILE__, '_coordenadas_nonce');

		echo '<div class="wrap" id="divMap" style="width: 1005; text-align: center;>"
				 <h3><small>Defina un poligono cerrado marcando los puntos que delimitan la zona</small> <a href="#" id="clearPolygon">Resetear Mapa<a/></h3>
				<div id="gmap_geo" class="gmaps"></div>
			</div>';
		echo '<textarea style="width:100%; display:none;" type="text" id="coordenadas" name="_coordenadas" placeholder="Coordenadas de la zona">'.$coordenadas.'</textarea>';
	}// meta_box_info_maestro


	/******************************************
	* SAVE META BOXES
	******************************************/

	/**
	* Save the metaboxes for post type "Área de Entrega"
	**/
	private function save_meta_boxes_area_entrega( $post_id ){
		if ( isset($_POST['_dia1']) and check_admin_referer(__FILE__, '_dia1_nonce') ){
			update_post_meta($post_id, '_dia1', $_POST['_dia1']);
		}
		else {
			delete_post_meta($post_id, '_dia1');	
		}
		if ( isset($_POST['_dia2']) and check_admin_referer(__FILE__, '_dia2_nonce') ){
			update_post_meta($post_id, '_dia2', $_POST['_dia2']);
		}
		else {
			delete_post_meta($post_id, '_dia2');	
		}
		if ( isset($_POST['_dia3']) and check_admin_referer(__FILE__, '_dia3_nonce') ){
			update_post_meta($post_id, '_dia3', $_POST['_dia3']);
		}
		else {
			delete_post_meta($post_id, '_dia3');	
		}
		if ( isset($_POST['_dia4']) and check_admin_referer(__FILE__, '_dia4_nonce') ){
			update_post_meta($post_id, '_dia4', $_POST['_dia4']);
		}
		else {
			delete_post_meta($post_id, '_dia4');	
		}
		if ( isset($_POST['_dia5']) and check_admin_referer(__FILE__, '_dia5_nonce') ){
			update_post_meta($post_id, '_dia5', $_POST['_dia5']);
		}
		else {
			delete_post_meta($post_id, '_dia5');	
		}
		if ( isset($_POST['_dia6']) and check_admin_referer(__FILE__, '_dia6_nonce') ){
			update_post_meta($post_id, '_dia6', $_POST['_dia6']);
		}
		else {
			delete_post_meta($post_id, '_dia6');	
		}
		if ( isset($_POST['_dia7']) and check_admin_referer(__FILE__, '_dia7_nonce') ){
			update_post_meta($post_id, '_dia7', $_POST['_dia7']);
		}
		else {
			delete_post_meta($post_id, '_dia7');	
		}
		if ( isset($_POST['_hora']) and check_admin_referer(__FILE__, '_hora_nonce') ){
			update_post_meta($post_id, '_hora', $_POST['_hora']);
		}
		if ( isset($_POST['_coordenadas']) and check_admin_referer(__FILE__, '_coordenadas_nonce') ){
			update_post_meta($post_id, '_coordenadas', $_POST['_coordenadas']);
		}
	}// save_meta_boxes_area_entrega

}// Area_Entrega_Pixan_Settings