<?php
/**
 * Checkouts settings for Area de Entrega Pixan.
 *
 * This class will create menu items in woocomerce checkout page, as well as initial setup
 * of post types and all required elements...
 *
 * @since 1.0.0
 */

class Area_Entrega_Checkout_Pixan_Settings {

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 * @return null or Area_Entrega_Checkout_Pixan_Settings instance
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_and_localize_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_area_entrega_checkout' ) );
		add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'show_google_map_checkout' ) );
		add_filter( 'woocommerce_checkout_fields' , array( $this, 'manage_checkout_fields') );
		add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
		//add_action( 'init', array( $this, 'register_custom_post_types' ), 5 );
		//add_action( 'save_post', array( $this, 'save_meta_boxes' ), 5, 1  );
	}


	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_post_types() {
		//$this->register_post_type_area_entrega_checkout();
	}


	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_taxonomies() {
		//$this->register_taxonomy_maestros();
		//$this->register_taxonomy_modulos();
		//$this->register_taxonomy_lecciones();
	}

	/**
	 * Register all meta boxes needed for custom post types.
	 */
	public function add_meta_boxes_area_entrega_checkout() {
		//add_meta_box( 'meta-box-area-entrega-checkout', 'Escriba su dirección para validar si esta dentro de una de las areas de entrega', array( $this, 'meta_box_area_entrega_checkout' ), 'area-entrega' );
	}

	/**
	 * Save metaboxes
	 */
	public function save_meta_boxes( $post_id ) {
		$this->save_meta_boxes_area_entrega_checkout( $post_id );
	}

	/**
	 * Add javascript and style files
	 */
	public function enqueue_and_localize_scripts(){
		if( is_checkout() ) {
			wp_enqueue_style( 'map_checkout_styles', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/css/map_checkout_styles.css' );
			wp_enqueue_script( 'geo-map-api2', 'https://maps.googleapis.com/maps/api/js?libraries=geometry,places&key=AIzaSyABZ4eSBYBsLi5WQ7WdXZpivNq6n4wQZPA');
			wp_enqueue_script( 'google-function-autocomplete', AREA_ENTREGA_PIXAN_PLUGIN_URL.'inc/js/google-autocomplete.js', array('geo-map-api2'), '1.0', true );
			wp_enqueue_script( 'geo-map-gmaps2', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/gmaps/gmaps.js', array('geo-map-api2' ));	
			wp_enqueue_script( 'geo-map-script-geocoding', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/mapa_geocoding.js', array('geo-map-api2', 'geo-map-gmaps2', 'jquery'));
		}
			
	}

	/**
	* Display map meta_boxes for post type "AreaEntrega"
	* 
	**/
	public function show_google_map_checkout(){
		//$coordenadas = get_post_meta($post->ID, '_coordenadas', true);
		//wp_nonce_field(__FILE__, '_coordenadas_nonce');
		
	    //Select area-entrega post type
	    $query_args = array(
			'post_type'      => 'area-entrega',
			'orderby'        => 'date',
			'no_found_rows'  => true,
			'cache_results'  => false,
		);

	    $posts = new WP_Query( $query_args );

	 	echo '<select id="area_entrega" style="display:none;" name="area_entrega" class="input-text" >';
	 		echo '<option></option>';
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$meta = get_post_meta($posts->post->ID);
				$dias = '';
				if(isset($meta['_dia1'])) { $dias .= $meta['_dia1'][0].', '; }
				if(isset($meta['_dia2'])) { $dias .= $meta['_dia2'][0].', '; }
				if(isset($meta['_dia3'])) { $dias .= $meta['_dia3'][0].', '; }
				if(isset($meta['_dia4'])) { $dias .= $meta['_dia4'][0].', '; }
				if(isset($meta['_dia5'])) { $dias .= $meta['_dia5'][0].', '; }
				if(isset($meta['_dia6'])) { $dias .= $meta['_dia6'][0].', '; }
				if(isset($meta['_dia7'])) { $dias .= $meta['_dia7'][0].', '; }
				$dias = substr($dias, 0, -2);
				echo '<option value="'.$posts->post->ID.'" class="area_e" id="ae_'.$posts->post->ID.'" data-dias="'.$dias.'" data-hora="'.$meta['_hora'][0].', '.'" data-coor="'.$meta['_coordenadas'][0].'">'.get_the_title().'</option>';
			}
		}
		
		echo '</select><small style="color:red; display:none;" id="areaInfo">Tu dirección no esta en ninguna de nuestras zonas de entrega, por favor intentalo de nuevo luego.</small><br />';
		echo '<div id="divInfoAreaEntrega" style="display:none;" >
				<h5 id="lblNombrePunto"></h5>
				<strong>Dias de Entrega: </strong><p><small id="lblDiasEntrega"></small></p>
				<strong>Horario: </strong><p><small id="lblHorarioEntrega"></small></p>
			</div>';

		//Select puntos-recoleccion post type
		$query_args = array(
			'post_type'      => 'puntos-recoleccion',
			'orderby'        => 'date',
			'no_found_rows'  => true,
			'cache_results'  => false,
		);

	    $posts = new WP_Query( $query_args );
	 	echo '<select id="puntos_recoleccion" name="puntos_recoleccion" style="display: none;" class="input-text form-row-wide">';
	 		echo '<option></option>';
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$meta = get_post_meta($posts->post->ID);
				echo '<option value="'.$posts->post->ID.'" class="punto_r" id="pr_'.$posts->post->ID.'" data-lat="'.$meta['latitud_punto'][0].'" data-long="'.$meta['longitud_punto'][0].'" data-responsable="'.$meta['nombre_responsable'][0].' '.$meta['apellido_responsable'][0].'" data-tel="'.$meta['telefono_responsable'][0].'" data-ubicacion="'.$meta['ubicacion_punto'][0].' ('.$meta['entre_punto'][0].')">'.get_the_title().'</option>';
			}
		}
		echo '</select>';

		echo '<div id="divInfoPunto" style="display:none;">
				<h3 id="nombrePunto"></h3>
				<h4 id="responsable"></h4>
				<h5 id="telefono"></h5>
				<p id="ubicacion"></p>

			</div>';

		echo '<div class="input-group">
					<input type="text" class="form-control" id="gmap_geocoding_address" placeholder="Ingresa la dirección de envio...">
					<!--
					<span class="input-group-btn">
						<button class="btn blue" id="gmap_geocoding_btn"><i class="fa fa-search"></i></button>
					</span>
					-->
					<strong id="searchErrorText">Por favor ingresa una información mas especifica.</strong>
				</div>
			<div id="gmap_geocoding" class="gmaps">
			</div>';

	}// meta_box_info_maestro

	/**
	* Display extra inputs for checkout page
	* @param arr $fields
	**/
	public function manage_checkout_fields( $fields ) {

		$fields['billing']['billing_address_1'] = array(
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide '),
											    'clear'     => true
										     );
		$fields['billing']['billing_address_2'] = array(
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_city'] = array(
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_country'] = array(
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_state'] = array(
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_postcode'] = array(
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );


		$fields['billing']['billing_formated_address'] = array(
										        'label'     => __('Direccion Formateada', 'woocommerce'),
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );

		$fields['billing']['billing_lat'] = array(
										        'label'     => __('Latitud', 'woocommerce'),
											    'required'  => true,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_long'] = array(
										        'label'     => __('Longitud', 'woocommerce'),
											    'required'  => true,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_area_entrega'] = array(
										        'label'     => __('Area Entrega', 'woocommerce'),
											    'required'  => true,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		$fields['billing']['billing_puntos_recoleccion'] = array(
										        'label'     => __('Puntos', 'woocommerce'),
											    'required'  => false,
											    'type'		=> 'text',
											    'class'     => array('form-row-wide hide'),
											    'clear'     => true
										     );
		return $fields;

	}// set_timeframe_required
	

	/******************************************
	* CUSTOM POST TYPES
	******************************************/

	/**
	 * Register the post type "Área de Entrega"
	 */
	

	/******************************************
	* META BOX CALLBACKS
	******************************************/


	


	/******************************************
	* SAVE META BOXES
	******************************************/

	/**
	* Save the metaboxes for post type "Área de Entrega"
	**/
	private function save_meta_boxes_area_entrega_checkout( $post_id ){
		
	}// save_meta_boxes_area_entrega_checkout

	/**
	 * Update the order meta with field value
	 **/
	
	function my_custom_checkout_field_update_order_meta( $order_id ) {
		//if ($_POST['billing_area_entrega']) update_post_meta( $order_id, 'billing_area_entrega', $_POST['billing_area_entrega']);
		//if ($_POST['billing_puntos_recoleccion']) update_post_meta( $order_id, 'billing_puntos_recoleccion', $_POST['billing_puntos_recoleccion']);
		//if ($_POST['billing_lat']) update_post_meta( $order_id, 'billing_lat', $_POST['billing_lat']);
		//if ($_POST['billing_long']) update_post_meta( $order_id, 'billing_long', $_POST['billing_long']);
		//if ($_POST['billing_formated_address']) update_post_meta( $order_id, 'billing_formated_address', $_POST['billing_formated_address']);

		/*
		if ( isset($_POST['_dia1']) and check_admin_referer(__FILE__, '_dia1_nonce') ){
			update_post_meta($post_id, '_dia1', $_POST['_dia1']);
		}
		if ( isset($_POST['_dia2']) and check_admin_referer(__FILE__, '_dia2_nonce') ){
			update_post_meta($post_id, '_dia2', $_POST['_dia2']);
		}
		if ( isset($_POST['_dia3']) and check_admin_referer(__FILE__, '_dia3_nonce') ){
			update_post_meta($post_id, '_dia3', $_POST['_dia3']);
		}
		*/
	}

}// Area_Entrega_Checkout_Pixan_Settings