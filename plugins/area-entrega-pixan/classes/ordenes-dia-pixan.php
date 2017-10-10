<?php
/**
 * Checkouts settings for Area de Entrega Pixan.
 *
 * This class will create menu items in woocomerce checkout page, as well as initial setup
 * of post types and all required elements...
 *
 * @since 1.0.0
 */

class Ordenes_Dia_Pixan {

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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_and_localize_admin_scripts' ) );
		add_action( 'wp_dashboard_setup', array( $this , 'add_dashboard_widgets_map' ) );
		add_action( 'admin_menu', array( $this , 'my_plugin_menu' ) );
		add_action ('wp_head' , array( $this , 'show_map_to_delivery'));
		add_action( 'rest_api_init', function () {
			register_rest_route( 'ordenes_dia_delivery/v1', 'mapa-ruta-pedidos', array(
				'methods' => 'GET',
				'callback' => array( $this , 'show_map_orders'),
			) );
		} );
	}


	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_post_types() {
		//$this->register_post_type_area_entrega_checkout();
	}

	public function show_map_to_delivery() {
		echo get_site_url();
		if(true) {
			$this->show_map_orders();
		}
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
	public function enqueue_and_localize_admin_scripts(){
		if( is_admin() ) {
			wp_enqueue_script( 'jquery-ui-datepicker', array('jquery-ui' ) );
			wp_enqueue_style( 'map_checkout_styles', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/css/map_styles.css' );
			wp_enqueue_script( 'map-admin-orders-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAi4ZbPiWVLU21gxMQa-GE2MznGK1OlpTc');
			wp_enqueue_script( 'admin-orders-gmaps', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/gmaps/gmaps.js', array('map-admin-orders-api' ));	
			wp_enqueue_script( 'admin-orders-map', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/mapa_admin_orders.js', array('map-admin-orders-api', 'admin-orders-gmaps', 'jquery'));
			wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
  			wp_enqueue_style( 'jquery-ui' ); 
		}
	}

	/**
	* Display map meta_boxes for post type "AreaEntrega"
	* 
	**/

	/** Step 1. */
	public function my_plugin_menu() {
		add_menu_page( 'Pedidos en Mapa', 'Pedidos Geolocalizados', 'manage_options', 'pedidos-geolocalizados-mapa', array( $this , 'show_map_orders' ), '', 2 );
	}
	
	public function show_admin_map_widget() {
		if(isset($_POST['fecha_ruta']) && $_POST['fecha_ruta'] != '') {
			$dayofweek = date('N', strtotime($_POST['fecha_ruta']));
			$fech = $_POST['fecha_ruta'];
			$area = $_POST['area_entrega'];
		}
		else {
			$fech = date('Y-m-d');
			$dayofweek = date('N');
			$area = "";
		}

		$id_statuses = array();
		$default_statuses = wc_get_order_statuses();

		$checkcompleted = '';
		$checkprocessing = '';
		$checkshipped = '';
		$checkinvoiced = '';
		$checkrefunded = '';
		$checkonhold = '';
		$checkcancelled = '';
		$checkpending = '';
		$checkfailed = '';

		if(isset($_POST['check-completed']) && $_POST['check-completed'] != '') { $id_statuses[] = $_POST['check-completed']; $checkcompleted = 'checked="checked"'; }
		if(isset($_POST['check-processing']) && $_POST['check-processing'] != '') { $id_statuses[] = $_POST['check-processing']; $checkprocessing = 'checked="checked"'; }
		if(isset($_POST['check-shipped']) && $_POST['check-shipped'] != '') { $id_statuses[] = $_POST['check-shipped']; $checkshipped = 'checked="checked"'; }
		if(isset($_POST['check-invoiced']) && $_POST['check-invoiced'] != '') { $id_statuses[] = $_POST['check-invoiced']; $checkinvoiced = 'checked="checked"'; }
		if(isset($_POST['check-refunded']) && $_POST['check-refunded'] != '') { $id_statuses[] = $_POST['check-refunded']; $checkrefunded = 'checked="checked"'; }
		if(isset($_POST['check-on-hold']) && $_POST['check-on-hold'] != '') { $id_statuses[] = $_POST['check-on-hold']; $checkonhold = 'checked="checked"'; }
		if(isset($_POST['check-cancelled']) && $_POST['check-cancelled'] != '') { $id_statuses[] = $_POST['check-cancelled']; $checkcancelled = 'checked="checked"'; }
		if(isset($_POST['check-pending']) && $_POST['check-pending'] != '') { $id_statuses[] = $_POST['check-pending']; $checkpending = 'checked="checked"'; }
		if(isset($_POST['check-failed']) && $_POST['check-failed'] != '') { $id_statuses[] = $_POST['check-failed']; $checkfailed = 'checked="checked"'; }
		
		if(empty($id_statuses)) {
			$id_statuses[] = 'wc-completed'; $checkcompleted = 'checked="checked"';
			$id_statuses[] = 'wc-processing'; $checkprocessing = 'checked="checked"';
			$id_statuses[] = 'wc-invoiced'; $checkinvoiced = 'checked="checked"';
		}
		

		
		$id_areas = array();
		//echo '==='.$dayofweek.'===';
		$areas_entregas = get_posts( array(
		    'numberposts' => -1,
		    'post_type'   => 'area-entrega',
		    'meta_key'	  => '_dia'.$dayofweek
		) );
		//var_dump($areas_entregas);
		for($i = 0; $i < count($areas_entregas); $i++)
		{
			$id_areas[] = $areas_entregas[$i]->ID;
			//$meta = get_post_meta($areas_entregas[$i]->ID);
			//var_dump($meta);
			
		}
		//var_dump($id_areas);
		
		//Select area-entrega post type
	    $query_args = array(
			'post_type'      => 'area-entrega',
			'orderby'        => 'date',
			'post_status'	 => 'publish',
			'no_found_rows'  => true,
			'cache_results'  => false,
		);

	    $posts = new WP_Query( $query_args );
	    echo '<form action="index.php" method="post">';
	    echo '<h4>Status de las ordenes: <br>';
	    echo '<table style="width: 100%; margin-bottom:10px;">';
	    echo '<tbody>';
	    echo '<tr>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-processing" value="wc-processing" '.$checkprocessing.'>En Proceso </label></td>'; 	    
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-invoiced" value="wc-invoiced" '.$checkinvoiced.'>Facturadas </label></td>'; 
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-completed" value="wc-completed" '.$checkcompleted.'>Completadas </label></td>'; 
	    echo '</tr>';
	    echo '<tr>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-failed" value="wc-failed" '.$checkfailed.'>Fallidas </label></td>'; 
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-on-hold" value="wc-on-hold" '.$checkonhold.'>En espera </label></td>'; 
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-cancelled" value="wc-cancelled" '.$checkcancelled.'>Canceladas </label></td>'; 
	    echo '</tr>';
	    echo '<tr>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-pending" value="wc-pending" '.$checkpending.'>Pendiente de Pago </label></td>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-refunded" value="wc-refunded" '.$checkrefunded.'>Reembolsadas </label></td>'; 
	    echo '<td style="width:33%;"></td>'; 
	    echo '</tr>';
	    echo '</tbody></table>';
		
	    echo '<table style="width: 100%;">';
	    echo '<thead><tr>';
	    echo '<td style="width: 50%;">Area de entrega </td>';
	    echo '<td style="width: 50%;">Fecha para entregas </td>';
	    echo '</tr></thead>';
	    echo '<tbody>';

	    echo '<td><select style="width:100%;" id="area_entrega" name="area_entrega" class="input-text" >';
	 		echo '<option></option>';
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$sel = '';
				if($area == $posts->post->ID) { $sel = 'selected'; }
				echo '<option value="'.$posts->post->ID.'" id="ae_'.$posts->post->ID.'" '.$sel.'>'.get_the_title().'</option>';
			}
		}

		echo '</select></td>';

		echo '<td><input style="width:100%;" type="date" name="fecha_ruta" value="'.$fech.'" /></td></tr>';
		echo '<tr><td colspan="2" style="text-align:center;"><input type="submit" value="ACTUALIZAR MAPA" /></td></tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</form>';

		echo '<input style="width:45%;" type="button" id="gmap_admin_orders_start" class="btn blue" value="Iniciar Ruta"/>
				<input style="width:45%;" type="button" id="btnImprimir" disabled="disabled" class="btn blue" value="Imprimir"/>
				<div id="divImprimir"><div id="gmap_admin_orders" class="gmaps">
				</div><br>';
		if($area != '') {
			if(in_array($area, $id_areas)) {
				unset($id_areas);
				$id_areas[] = $area;	
			}
			else {
				unset($id_areas);
				$id_areas[] = 0;
			}
			
		}	
		$customer_orders = get_posts( array(
		    'numberposts' => -1,
		    'meta_key'    => '_billing_area_entrega',
		    'meta_value'  => $id_areas,
		    'post_type'   => wc_get_order_types(),
		    'post_status' => $id_statuses,
		) );

		/*
		echo '<select style="display:none;" id="listado_ordenes">';
		echo '<option></option>';
		for($i = 0; $i < count($customer_orders); $i++)
		{
			$meta = get_post_meta($customer_orders[$i]->ID);
			var_dump($meta);
			echo '<option class="orderMap" id="o_'.$customer_orders[$i]->ID.'" data-lat="'.$meta['_billing_lat'][0].'" data-long="'.$meta['_billing_long'][0].'" data-dir="'.$meta['_billing_formated_address'][0].'" data-num="'.$customer_orders[$i]->ID.'">'.$customer_orders[$i]->ID.' '.$customer_orders[$i]->post_title.'</option>';
		}
		echo '</select>';
		*/
		if(count($customer_orders) >= 35) {
			echo '<div class="error"><h3>Para el dia de hoy existen <strong>'.count($customer_orders).'</strong> pedidos/guacales por entregar.</h3></div>';
		}

		if(count($customer_orders) > 0) {
			for($i = 0; $i < count($customer_orders); $i++)
			{
				//DESCARTAR PEDIDOS MENORES A UN DIA
				if($customer_orders[$i]->post_date < date('Y-m-d',strtotime("-1 days")) || $fech > date('Y-m-d')) {
					$meta = get_post_meta($customer_orders[$i]->ID);
					
					isset($meta['_unidadmedida_orden'][0]) ? $uni = $meta['_unidadmedida_orden'][0] : $uni = '';
					isset($meta['_temperaturas_orden'][0]) ? $tem = $meta['_temperaturas_orden'][0] : $tem = '';

					echo '<div><input type="checkbox" class="orderMap" id="o_'.$customer_orders[$i]->ID.'" data-lat="'.$meta['_billing_lat'][0].'" data-long="'.$meta['_billing_long'][0].'" data-dir="'.$meta['_billing_formated_address'][0].'" data-num="'.$customer_orders[$i]->ID.'" checked="checked" data-info="'.$customer_orders[$i]->ID.' '.$customer_orders[$i]->post_title.'"> <strong>'.$default_statuses[$customer_orders[$i]->post_status].'</strong> '.$customer_orders[$i]->ID.' '.$customer_orders[$i]->post_title.' <br> Nombre: '.$meta['_billing_first_name'][0].' '.$meta['_billing_last_name'][0].'<br> Telefono: '.$meta['_billing_phone'][0].'<br>'.$meta['_billing_formated_address'][0].'<br>Total: $'.$meta['_order_total'][0].'<br>Temperaturas: '.$tem.'<br>Unidades: '.$uni.' </div><br/>';
				}

			}
			echo '<ol id="gmap_admin_orders_instructions"></ol></div>';
		}
		else {
			echo '<div style="border-left: #ffb900 solid 5px; padding: 0px 5px;"><p><strong>No se encontro ningun pedido para la combinación zona y fecha de entrega seleccionadas.</strong></p></div>';
		}
	}

	public function show_map_orders() {
		//var_dump($_POST);
		if(isset($_POST['fecha_ruta']) && $_POST['fecha_ruta'] != '') {
			$dayofweek = date('N', strtotime($_POST['fecha_ruta']));
			$fech = $_POST['fecha_ruta'];
			$area = $_POST['area_entrega'];
		}
		else {
			$fech = date('Y-m-d');
			$dayofweek = date('N');
			$area = "";
		}

		$default_statuses = wc_get_order_statuses();
		$id_statuses = array();

		$checkcompleted = '';
		$checkprocessing = '';
		$checkshipped = '';
		$checkinvoiced = '';
		$checkrefunded = '';
		$checkonhold = '';
		$checkcancelled = '';
		$checkpending = '';
		$checkfailed = '';

		if(isset($_POST['check-completed']) && $_POST['check-completed'] != '') { $id_statuses[] = $_POST['check-completed']; $checkcompleted = 'checked="checked"'; }
		if(isset($_POST['check-processing']) && $_POST['check-processing'] != '') { $id_statuses[] = $_POST['check-processing']; $checkprocessing = 'checked="checked"'; }
		if(isset($_POST['check-shipped']) && $_POST['check-shipped'] != '') { $id_statuses[] = $_POST['check-shipped']; $checkshipped = 'checked="checked"'; }
		if(isset($_POST['check-invoiced']) && $_POST['check-invoiced'] != '') { $id_statuses[] = $_POST['check-invoiced']; $checkinvoiced = 'checked="checked"'; }
		if(isset($_POST['check-refunded']) && $_POST['check-refunded'] != '') { $id_statuses[] = $_POST['check-refunded']; $checkrefunded = 'checked="checked"'; }
		if(isset($_POST['check-on-hold']) && $_POST['check-on-hold'] != '') { $id_statuses[] = $_POST['check-on-hold']; $checkonhold = 'checked="checked"'; }
		if(isset($_POST['check-cancelled']) && $_POST['check-cancelled'] != '') { $id_statuses[] = $_POST['check-cancelled']; $checkcancelled = 'checked="checked"'; }
		if(isset($_POST['check-pending']) && $_POST['check-pending'] != '') { $id_statuses[] = $_POST['check-pending']; $checkpending = 'checked="checked"'; }
		if(isset($_POST['check-failed']) && $_POST['check-failed'] != '') { $id_statuses[] = $_POST['check-failed']; $checkfailed = 'checked="checked"'; }

		if(empty($id_statuses)) {
			$id_statuses[] = 'wc-completed'; $checkcompleted = 'checked="checked"';
			$id_statuses[] = 'wc-processing'; $checkprocessing = 'checked="checked"';
			$id_statuses[] = 'wc-invoiced'; $checkinvoiced = 'checked="checked"';
		}

		$id_areas = array();
		//echo '==='.$dayofweek.'===';
		$areas_entregas = get_posts( array(
		    'numberposts' => -1,
		    'post_type'   => 'area-entrega',
		    'meta_key'	  => '_dia'.$dayofweek
		) );
		//var_dump($areas_entregas);
		for($i = 0; $i < count($areas_entregas); $i++)
		{
			$id_areas[] = $areas_entregas[$i]->ID;
			//$meta = get_post_meta($areas_entregas[$i]->ID);
			//var_dump($meta);
			
		}
		//var_dump($id_areas);
		//Select area-entrega post type
	    $query_args = array(
			'post_type'      => 'area-entrega',
			'orderby'        => 'date',
			'post_status'	 => 'publish',
			'no_found_rows'  => true,
			'cache_results'  => false,
		);

	    $posts = new WP_Query( $query_args );
	    echo '<form action="admin.php?page=pedidos-geolocalizados-mapa" method="post">';
	    echo '<h4>Status de las ordenes: <br>';
	    echo '<table style="width: 100%; margin-bottom:10px;">';
	    echo '<tbody>';
	    echo '<tr>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-processing" value="wc-processing" '.$checkprocessing.'>En Proceso </label></td>'; 	    
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-invoiced" value="wc-invoiced" '.$checkinvoiced.'>Facturadas </label></td>'; 
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-completed" value="wc-completed" '.$checkcompleted.'>Completadas </label></td>'; 
	    echo '</tr>';
	    echo '<tr>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-failed" value="wc-failed" '.$checkfailed.'>Fallidas </label></td>'; 
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-on-hold" value="wc-on-hold" '.$checkonhold.'>En espera </label></td>'; 
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-cancelled" value="wc-cancelled" '.$checkcancelled.'>Canceladas </label></td>'; 
	    echo '</tr>';
	    echo '<tr>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-pending" value="wc-pending" '.$checkpending.'>Pendiente de Pago </label></td>';
	    echo '<td style="width:33%;"><label><input type="checkbox" name="check-refunded" value="wc-refunded" '.$checkrefunded.'>Reembolsadas </label></td>'; 
	    echo '<td style="width:33%;"></td>'; 
	    echo '</tr>';
	    echo '</tbody></table>';
		
	    echo '<table style="width: 100%;">';
	    echo '<tbody>';
	    echo '<table style="width: 100%;">';
	    echo '<thead><tr>';
	    echo '<td style="width: 50%;">Area de entrega </td>';
	    echo '<td style="width: 50%;">Fecha para entregas </td>';
	    echo '</tr></thead>';
	    echo '<tbody>';

	    echo '<td><select style="width:100%;" id="area_entrega" name="area_entrega" class="input-text" >';
	 		echo '<option></option>';
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$sel = '';
				if($area == $posts->post->ID) { $sel = 'selected'; }
				echo '<option value="'.$posts->post->ID.'" id="ae_'.$posts->post->ID.'" '.$sel.'>'.get_the_title().'</option>';
			}
		}

		echo '</select></td>';

		echo '<td><input style="width:100%;" type="date" name="fecha_ruta" value="'.$fech.'" /></td></tr>';
		echo '<tr><td colspan="2" style="text-align:center;"><input type="submit" value="ACTUALIZAR MAPA" /></td></tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</form>';

		echo '<input style="width:45%;" type="button" id="gmap_admin_orders_start" class="btn blue" value="Iniciar Ruta"/>
						<input style="width:45%;" type="button" id="btnImprimir" disabled="disabled" class="btn blue" value="Imprimir"/>';
		echo '<div id="divImprimir"><div id="gmap_admin_orders" class="gmaps"></div>';

		if($area != '') {
			if(in_array($area, $id_areas)) {
				unset($id_areas);
				$id_areas[] = $area;	
			}
			else {
				unset($id_areas);
				$id_areas[] = 0;
			}
			
		}

		$customer_orders = get_posts( array(
		    'numberposts' => -1,
		    'meta_key'    => '_billing_area_entrega',
		    'meta_value'  => $id_areas,
		    'post_type'   => wc_get_order_types(),
		    'post_status' => $id_statuses,
		) );

		/*
		echo '<select style="display:none;" id="listado_ordenes">';
		echo '<option></option>';
		for($i = 0; $i < count($customer_orders); $i++)
		{
			$meta = get_post_meta($customer_orders[$i]->ID);
			var_dump($meta);
			echo '<option class="orderMap" id="o_'.$customer_orders[$i]->ID.'" data-lat="'.$meta['_billing_lat'][0].'" data-long="'.$meta['_billing_long'][0].'" data-dir="'.$meta['_billing_formated_address'][0].'" data-num="'.$customer_orders[$i]->ID.'">'.$customer_orders[$i]->ID.' '.$customer_orders[$i]->post_title.'</option>';
		}
		echo '</select>';
		*/
		if(count($customer_orders) > 0) {
			for($i = 0; $i < count($customer_orders); $i++)
			{
				//DESCARTAR PEDIDOS MENORES A UN DIA
				if($customer_orders[$i]->post_date < date('Y-m-d',strtotime("-1 days")) || $fech > date('Y-m-d')) {
					$meta = get_post_meta($customer_orders[$i]->ID);
					
					isset($meta['_unidadmedida_orden'][0]) ? $uni = $meta['_unidadmedida_orden'][0] : $uni = '';
					isset($meta['_temperaturas_orden'][0]) ? $tem = $meta['_temperaturas_orden'][0] : $tem = '';

					echo '<div><input type="checkbox" class="orderMap" id="o_'.$customer_orders[$i]->ID.'" data-lat="'.$meta['_billing_lat'][0].'" data-long="'.$meta['_billing_long'][0].'" data-dir="'.$meta['_billing_formated_address'][0].'" data-num="'.$customer_orders[$i]->ID.'" checked="checked" data-info="'.$customer_orders[$i]->ID.' '.$customer_orders[$i]->post_title.'"> <strong>'.$default_statuses[$customer_orders[$i]->post_status].'</strong> '.$customer_orders[$i]->ID.' '.$customer_orders[$i]->post_title.'<br> Nombre: '.$meta['_billing_first_name'][0].' '.$meta['_billing_last_name'][0].'<br> Telefono: '.$meta['_billing_phone'][0].'<br>'.$meta['_billing_formated_address'][0].'<br>Total: $'.$meta['_order_total'][0].'<br>Temperaturas: '.$tem.'<br>Unidades: '.$uni.' </div><br/>';
				}

			}
			echo '<ol id="gmap_admin_orders_instructions"></ol></div>';
		}
		else {
			echo '<div style="border-left: #ffb900 solid 5px; padding: 0px 5px;"><p><strong>No se encontro ningun pedido para la combinación zona y fecha de entrega seleccionadas.</strong></p></div>';
		}

				
	}

	public function add_dashboard_widgets_map() {
		wp_add_dashboard_widget( 'dashboard_widget_map', 'Ordenes del Dia', array( $this , 'show_admin_map_widget' ) );
	}


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
	

}// Area_Entrega_Checkout_Pixan_Settings