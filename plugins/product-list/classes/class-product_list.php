<?php
/**
 * Admin panel settings for Area de Entrega Pixan.
 *
 * This class will create menu items in admin panel, as well as initial setup
 * of post types and all required elements...
 *
 * @since 1.0.0
 */

class Product_List_Settings {

	private static $instance = null;
	public static $endpoint = 'product-list';

	/**
	 * Get singleton instance of class
	 * @return null or Product_List_Settings instance
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
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Hooks
	 */
	private function hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_and_localize_scripts' ) );
		//add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes_area_entrega' ) );
		//add_action( 'init', array( $this, 'register_custom_post_types' ), 5 );
		//add_action( 'save_post', array( $this, 'save_meta_boxes' ), 5, 1  );
		add_action('woocommerce_after_cart_table', array( $this, 'add_list_to_list_button'));
		add_action('woocommerce_after_add_to_cart_button', array( $this, 'add_product_to_list_button'));
		//
		//add_action(    'woocommerce_after_subcategory',				array( $this, 'add_product_to_list_button') );
		//add_action(    'woocommerce_after_shop_loop_item',			array( $this, 'add_product_to_list_button') );
		//
		//add_action('woocommerce_after_my_account', array( $this, 'show_user_lists'));
		add_action( 'wp_ajax_add_products_to_a_list', array( $this, 'add_products_to_a_list') );
		add_action( 'wp_ajax_nopriv_add_products_to_a_list', array( $this, 'add_products_to_a_list') );

		// Insering your new tab/page into the My Account page.
		add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
		add_action( 'woocommerce_account_' . self::$endpoint .  '_endpoint', array( $this,'show_user_lists') );

		//ACTION TO SEND MAIL REMINDERS OF RECURRENT LIST's
		add_action( 'my_cron_event',  array( $this, 'send_mail_reminders') );
	}




	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_post_types() {
		//$this->register_post_type_area_entrega();
	}

	/**
	 * Register all custom post types needed for "Administrador de Cursos"
	 */
	public function register_custom_taxonomies() {

	}

	/**
	 * Register all meta boxes needed for custom post types.
	 */
	public function add_meta_boxes_area_entrega() {

	}

	/**
	 * Save metaboxes
	 */
	public function save_meta_boxes( $post_id ) {

	}

	/**
	 * Add javascript and style files
	 */
	public function enqueue_and_localize_scripts(){

		//wp_enqueue_style( 'admin_styles', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/css/map_styles.css' );
		//wp_enqueue_script( 'geo-map-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyABZ4eSBYBsLi5WQ7WdXZpivNq6n4wQZPA&libraries=drawing');
		//wp_enqueue_script( 'geo-map-gmaps', AREA_ENTREGA_PIXAN_PLUGIN_URL . 'inc/js/gmaps/gmaps.js', array('map-admin-lists-api' ));
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.0/jquery-ui.js', array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui', array('jquery' ) );

		wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css', true);
		//wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
  		wp_enqueue_script( 'jquery-ui-dialog', array('jquery-ui' ) );
  		wp_enqueue_style( 'jquery-ui-style' );
		wp_enqueue_script( 'product-list', PRODUCT_LIST_URL . 'inc/js/product-list.js', array('jquery-ui'));


	}


	/******************************************
	* CUSTOM POST TYPES
	******************************************/


	/******************************************
	* META BOX CALLBACKS
	******************************************/
	/**
	 * Insert the new endpoint into the My Account menu.
	 *
	 * @param array $items
	 * @return array
	 */
	public function new_menu_items( $items ) {
		// Remove the address menu item.
		$address = $items['edit-address'];
		unset( $items['edit-address'] );
		// Insert your custom endpoint.
		$items[ self::$endpoint ] = __( 'Mis Listas', 'woocommerce' );
		// Insert back the address item.
		$items['edit-address'] = $address;
		return $items;
	}

	/**
	 * Endpoint HTML content.
	 */
	public function endpoint_content() {
		//wc_get_template( 'myaccount/navigation.php' );
	?>

		<div class="woocommerce-MyAccount-content">

			<p>Contenido de prueba. Aqui se mostrara el detalle de productos de la lista.</p>

		</div>

		<?php
	}

	function add_list_to_list_button() {
		if(is_numeric(get_current_user_id()) && get_current_user_id() != 0) {
			echo '<a href="#" class="button alt addToList">Agregar este pedido a mi Lista</a>';
			echo '<input type="hidden" id="rutaPlugin" name="rutaPlugin" value="'.PRODUCT_LIST_URL.'" />';
			echo '<input type="hidden" id="rutaAjax" name="rutaAjax" value="'.admin_url('admin-ajax.php').'" />';
			$this->show_dialog();
		}
	}

	function show_dialog() {
		echo '<div style="display:none;" id="dialog" class="add_to_list_dialog" title="Seleccionar Lista">
				<div id="dialogLoader" style="display:none;"><img src="'.PRODUCT_LIST_URL.'inc/img/loader.gif" alt="Cargando..." /></div>
				<div id="dialogMsj"></div>
				<div id="dialogDefaultText">
			    <p>Selecciona la lista en la que deseas guardar los articulos.</p>';
			$listas = $this->get_list(get_current_user_id());
			if (count($listas[0]) > 0) {
				echo '<select id="add_product_list" name="add_product_list" >';
				foreach ( $listas as $list )
				{
					echo '<option value="'.$list->id.'">'.$list->nombre.'</option>';
				}
				echo '</select>';
			}
			else {
				echo '<span style="color:pink;">Aún no tienes ninguna lista, pero no te preocupes crearemos una por ti cuando hagas click en <strong>Continuar</strong>.</span>';
				echo '<input type="hidden" id="add_product_list" name="add_product_list" value="0" />';
			}
		echo '</div></div>';
	}

	public function send_mail_reminders() {
		$listas = $this->get_all_lists();
		if (count($listas[0]) > 0) {
			foreach ( $listas as $list )
			{
				$ahora = explode( ' ', current_time( 'mysql' ));
				$ahora = date('d/m/Y', strtotime($ahora[0]));
				$ultimo_recordatorio = explode( ' ', $list->fecha);
				$ultimo_recordatorio = date('d/m/Y', strtotime($ultimo_recordatorio[0]));
				$dif = $this->calcular_cant_dias_entre_fechas($ultimo_recordatorio, $ahora);

				if($dif >= $list->recurrencia) {
					$this->send_mail($list->id, $list->user_id);
				}
			}
		}
	}

	public function send_mail($idlista, $idusuario) {
		global $wpdb;
		$ud = get_userdata( $idusuario );
		echo 'Enviando Mail a '.$ud->first_name.' '.$ud->last_name.' -> '.$ud->user_email.'<br />';

		$subject = 'Pixan - Recordatorio de tu lista';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		//$headers = 'From: Pixan <' . $ud->user_email . '>' . "\r\n";
		$message = '<html><body>';
		$message .= $this->show_list_detail_to_email($idlista);
		$message .= '</body></html>';

		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

		//SEND EMAIL CONFIRMATION
		$resp = wp_mail( $ud->user_email, $subject, $message, $headers );


		$wpdb->update(
			$wpdb->prefix . 'product_list',
			array( 'fecha' => current_time( 'mysql' )),
			array( 'id' => $idlista ),
			array( '%s' ),
			array( '%d' )
		);
	}

	public function update_list_detail($idlista) {
		global $wpdb;

		$detalle = $this->get_list_detail($idlista);

		if (count($detalle[0]) > 0) {
			foreach ( $detalle as $det )
			{
				$wpdb->update(
					$wpdb->prefix . 'product_list_detail',
					array( 'cantidad' => $_GET['cant_'.$det->product_id] ),
					array( 'product_list_id' => $idlista, 'product_id' => $det->product_id ),
					array( '%d' ),
					array( '%d', '%d' )
				);
			}
		}

		$this->show_list_detail($idlista);
	}

	//CALCULA LA CANTIDAD DE DIAS ENTRE 2 FECHAS CON FORMATO dd/mm/aaaa
	public function calcular_cant_dias_entre_fechas($fechaL,$fechaS)
	{
		$dia1 = substr($fechaL, 0, 2);
		$mes1 = substr($fechaL, 3, 2);
		$anno1 = substr($fechaL, 6, 4);

		$dia2 = substr($fechaS, 0, 2);
		$mes2 = substr($fechaS, 3, 2);
		$anno2 = substr($fechaS, 6, 4);

		$timestamp1 = mktime(0,0,0,$mes1,$dia1,$anno1);
		$timestamp2 = mktime(0,0,0,$mes2,$dia2,$anno2);

		$segundos_diferencia = $timestamp1 - $timestamp2;

		$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

		$dias_diferencia = abs($dias_diferencia);

		$dias_diferencia = floor($dias_diferencia);

		if ($timestamp1 > $timestamp2)
		{
			$dias_diferencia = $dias_diferencia*-1;
		}

		return $dias_diferencia;
	}

	//CALCULA LA DIFERENCIA ENTRE 2 FECHAS EN CUALQUIER FORMATO RETORNANDO EN FORMATO TIMESTAMP
	public function compara_fechas($fecha1,$fecha2)
	{
	    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
		{
			list($dia1,$mes1,$año1)=explode("/",$fecha1);
		}

		if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
	    {
			list($dia1,$mes1,$año1)=explode("-",$fecha1);
	    }
		if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
	    {
			list($dia2,$mes2,$año2)=explode("/",$fecha2);
	    }
		if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
	    {
			list($dia2,$mes2,$año2)=explode("-",$fecha2);
		}

        $dif1 = mktime(0,0,0,$mes1,$dia1,$año1);
		$dif2 = mktime(0,0,0, $mes2,$dia2,$año2);
		$dif = $dif1 - $dif2;
        return ($dif);
	}

	function add_product_to_list_button() {
		if(is_numeric(get_current_user_id()) && get_current_user_id() != 0) {
			echo '<a href="#" class="button alt addToList">Agregar a mi Lista</a>';
			echo '<div class="clearfix"></div>';
			echo '<input type="hidden" id="rutaPlugin" name="rutaPlugin" value="'.PRODUCT_LIST_URL.'" />';
			echo '<input type="hidden" id="rutaAjax" name="rutaAjax" value="'.admin_url('admin-ajax.php').'" />';
			$this->show_dialog();
		}
	}

	function show_user_lists() {

		if( isset($_GET['lista_nombre']) && isset($_GET['recurrencia']) ) {
			$nombrenueva = $this->add_list( $_GET['lista_nombre'], $_GET['recurrencia'] );
			$this->print_user_list($nombrenueva);

		}
		else if( isset($_GET['eliminar']) ) {
			$this->delete_list( $_GET['eliminar']);
			$this->print_user_list('');

		}
		else if( isset($_GET['eliminar_detalle']) ) {
			$this->delete_list_detail( $_GET['eliminar_detalle'], $_GET['list_id']);
			$this->show_list_detail($_GET['list_id']);
		}
		else if( isset($_GET['loadCart']) ){
			$this->add_product_list_to_cart($_GET['loadCart']);
		}
		else if( isset($_GET['detalle']) ){
			$this->show_list_detail($_GET['detalle']);
		}
		else if( isset($_GET['actualizar']) ){
			$this->update_list_detail($_GET['actualizar']);
		}
		//TESTING CRON DELETE THIS ELSE IF SENTENCE
		else if ( isset($_GET['cron']) ){
			$this->send_mail_reminders();
		}
		//END TESTING CRON DELETE THIS ELSE IF SENTENCE
		else {
			$this->print_user_list('');

		}

	}

	public function print_user_list($cod) {

		if ($cod != '' && !is_numeric($cod)) {
			echo '<div class="woocommerce-Message woocommerce-Message--info woocommerce-error" >';
			echo 'Ya tienes una lista con el nombre <strong>'.$cod.'</strong> ingresa un nombre distinto.';
			echo '</div>';
		}
		echo '<h4>Mis listas</h4>';
		echo '<table class="woocommerce-MyAccount-lists shop_table shop_table_responsive my_account_lists account-lists-table">
				<thead>
					<tr>
							<th class="list-name"><span class="nobr">Nombre</span></th>
							<th class="list-date" ><span class="nobr">Productos</span></th>
							<th class="list-status"><span class="nobr">Recurrencia</span></th>
							<th class="list-actions"><span class="nobr">&nbsp;</span></th>
					</tr>
				</thead>

				<tbody>';

				$listas = $this->get_list(get_current_user_id());
				if (count($listas[0]) > 0) {
					foreach ( $listas as $list )
					{
						echo '<tr class="list" data-listid="'.$list->id.'">
							<td class="list-name" data-title="Pedido">
								<a href="#">'.$list->nombre.'</a>
							</td>
							<td style="text-align: center;" data-title="Cantidad">
								<span>'.$this->count_products( $list->id ).'</span>
							</td>
							<td data-title="Recurrencia">
								Cada <span>'.$list->recurrencia.'</span> dias.
							</td>
							<td class="list-actions" data-title="&nbsp;">
								<a href="'.SITEURL.'my-account/product-list/?detalle='.$list->id.'" class="button view">Ver</a>
								<a href="'.SITEURL.'my-account/product-list/?eliminar='.$list->id.'" title="Eliminar" class="button view red">X</a>
							</td>
						</tr>';
					}
				}
				else {
					echo '<tr class="list" style="background-color: pink; text-align:center;"><td colspan="4">Aún no tienes ninguna lista.</td></tr>';
				}

				echo '<tr class="list">
						<form id="formAddLista" action="'.SITEURL.'my-account/product-list/" type="post" >
							<td class="list-name" colspan="2">
								<input id="lista_nombre" name="lista_nombre" />
							</td>
							<td class="list-total" data-title="Total">
								<select id="recurrencia" name="recurrencia" >
									<option></option>
									<option value="8">Semanal</option>
									<option value="15">Quincenal</option>
									<option value="30">Mensual</option>
								</select>
							</td>
							<td class="list-actions" data-title="&nbsp;">
								<button type="submit" class="button view">Crear</button>
							</td>
						</form>
					</tr>
					</tbody>
				</table>';
	}

	public function show_list_detail( $list_id ) {
		$_pf = new WC_Product_Factory();
		$detalle = $this->get_list_detail($list_id);
		$urladdtocart = '';
		echo '<h3>'.$this->get_list_name($list_id).'</h3>';
		echo '<table class="shop_table shop_table_responsive cart" cellspacing="0">
			<thead>
				<tr>
					<th class="">&nbsp;</th>
					<th class="">&nbsp;</th>
					<th class="list-name">Producto</th>
					<th class="">Precio</th>
					<th class="">Cantidad</th>
				</tr>
			</thead>
			<tbody>';

		echo '<form id="formUpdateList" action="'.SITEURL.'my-account/product-list/" type="post" >';
			if (count($detalle[0]) > 0) {
				$prod_ids = '';
				$cant_ids = '';
				foreach ( $detalle as $det )
				{
					$_product = $_pf->get_product($det->product_id);
					$urladdtocart .= '&quantity['.$det->product_id.']='.$det->cantidad;

					$prod_ids .= $det->product_id.',';
					$cant_ids .= $det->cantidad.',';
					$stock_msj = '';
					$stock = $_product->get_stock_quantity();
					if(isset($stock) && $stock == 0) {
						$stock_msj = '<strong style="color: red;"><small>Agotado: No podra agregarse al carrito.<small></strong>';
					}

					echo '<tr class="productOnList" data-p_id="'.$det->product_id.'">';
						echo '<td><a href='.SITEURL.'my-account/product-list/?eliminar_detalle='.$det->product_id.'&list_id='.$det->product_list_id.' class="remove" title="Eliminar de mi Lista" >X</a></td>';
						echo '<td>'.$_product->get_image().'</td>';
						echo '<td>'.$_product->get_title().' <br>'.$stock_msj.'</td>';
						echo '<td>'.WC()->cart->get_product_price( $_product ).'</td>';
						echo '<td><input size="1" style="text-align: center;" id="cant_'.$det->product_id.'" name="cant_'.$det->product_id.'" value="'.$det->cantidad.'" /></td>';
					echo '</tr>';
				}
				$prod_ids = substr($prod_ids, 0, -1);
				$cant_ids = substr($cant_ids, 0, -1);
			}
			else {
				echo '<td colspan="5">Esta lista esta vacia.</td>';
			}

			echo '</tbody>';
		echo '</table>';

		echo '<input type="hidden" id="actualizar" name="actualizar" value="'.$list_id.'" />';
		echo '<button style="float:right;" type="submit" class="button view">Actualizar Cantidades</button>';
		//echo '<a href="'.SITEURL.'my-account/product-list/?actualizar='.$list_id.'&prods='.$prod_ids.'&cants='.$cant_ids.'" class="[ float-right ] button alt">Actualizar Cantidades</a>';
		echo '</form>';
		echo '<a  href="'.SITEURL.'my-account/product-list/?loadCart='.$list_id.'" class="[ float-right ] button alt">Agregar los articulos de esta lista a mi carrito</a>';

	}

	//FORMAT DETAIL LIST TO EMAIL HTML TEMPLATE
	public function show_list_detail_to_email( $list_id ) {
		global $current_user;
      	get_currentuserinfo();
		$_pf = new WC_Product_Factory();
		$detalle = $this->get_list_detail($list_id);
		$msj = '';
		$msj .= '<a href="'.SITEURL.'" style="display: block; margin-bottom:30px;">';
			$msj .= '<img style="width:100%;" src="'.SITEURL.'wp-content/themes/organics-child/images/header.png" alt="logo pixan"/>';
		$msj .= '</a>';
		$msj .= '<p style="margin-bottom: 20px;font-size: 16px;line-height: 30px;color: #222222;">Hola '.$current_user->user_firstname .' '.$current_user->user_lastname.', ya esta lista tu canasta <strong>'.$this->get_list_name($list_id).'</strong> de productos Pixan, sólo haz click en <a style="color: #1E4B24;" href="'.SITEURL.'my-account/product-list/">ver mi lista</a> para finalizar tu compra.</p>';
		$msj .= '<h3 style="margin-bottom:30px; text-align: center; background-color: #80B500; color: #fff; padding: 10px; font-size: 22px; letter-spacing: 2px; font-weight:500;">'.$this->get_list_name($list_id).'</h3>';
		$msj .= '<table style="border-bottom: 2px solid #80B500; width: 100%; text-align: left;" class="shop_table shop_table_responsive cart" cellspacing="0">
			<thead>
				<tr style"margin:20px 0">
					<th class="">&nbsp;</th>
					<th style="font-size: 16px; padding-right: 15px; color:#1E4B24; text-align:center;" class="list-name">Producto</th>
					<th style="font-size: 16px; padding-right: 15px; color:#1E4B24; text-align:center;" class="">Precio</th>
					<th style="font-size: 16px; padding-right: 15px; color:#1E4B24; text-align:center;" class="">Cantidad</th>
					<th style="font-size: 16px; padding-right: 15px; color:#1E4B24; text-align:center;" class="">Unidad de medida</th>
				</tr>
			</thead>
			<tbody>';


			if (count($detalle[0]) > 0) {

				foreach ( $detalle as $det )
				{
					$stock = $_product->get_stock_quantity();
					if(isset($stock) && $stock == 0) {
						$stock_msj = '<strong style="color: red;"><small>Agotado: No podra agregarse al carrito.<small></strong>';
					}
					$tipo_unidad = get_post_meta($det->product_id, 'unidadmedida', true);
					$_product = $_pf->get_product($det->product_id);
					$msj .= '<tr style="padding-right: 15px; class="productOnList" data-p_id="'.$det->product_id.'">';
						$msj .= '<td style="padding-right: 15px; padding-bottom: 15px; padding-top: 15px;">'.$_product->get_image().'</td>';
						$msj .= '<td style="font-size:16px; color: #1E4B24; padding-right: 15px;text-align:center;">'.$_product->get_title().' <br>'.$stock_msj.'</td>';
						$msj .= '<td style="font-size: 16px; padding-right: 15px; color: #222222;text-align:center;">'.WC()->cart->get_product_price( $_product ).'</td>';
						$msj .= '<td style="font-size: 16px; padding-right: 15px; color: #222222;text-align:center;">'.$det->cantidad.'</td>';
						$msj .= '<td style="font-size: 16px; padding-right: 15px; color: #222222;text-align:center;">'.$tipo_unidad.'</td>';
					$msj .= '</tr>';
				}
			}
			else {
				$msj .= '<td colspan="5" style="color:pink;">Esta lista esta vacia.</td>';
			}

			$msj .= '</tbody>';
		$msj .= '</table>';
		$msj .= '<div style="text-align: center; margin-bottom:50px">';
			$msj .= '<a href="'.SITEURL.'my-account/product-list" style="background-color: #80B500; cursor: pointer; color: #fff; font-weight:400; font-size:14px; letter-spacing:1px; text-decoration: none; padding: 6px 20px; line-height: 28px; text-transform: uppercase; border-radius: 5px;" class="button alt">Ver mi lista</a>';
		$msj .= '</div>';
		$msj .= '<div style="text-align: center;">';
			$msj .= '<p style="font-size:16px; color: #222222;">Si deseas modificar o eliminar el recordatorio de tu lista da clic <a style="color: #1E4B24;" href="'.SITEURL.'my-account/product-list/">aquí</a></p>';
			$msj .= '<h4>';
				$msj .= '<a style="font-size:16px; color:#80b500; text-decoration: none;" href="'.SITEURL.'">Visita Pixan Sustentable</a>';
			$msj .= '</h4>';
			$msj .= '<a href="'.SITEURL.'">';
				$msj .= '<img style="width: 80px;" src="'.SITEURL.'wp-content/themes/organics-child/images/logo-small.png" class="Logo redondo pixan">';
			$msj .= '</a>';
		$msj .= '</div>';
		//echo $msj;
		return $msj;
	}

	/******************************************
	* SAVE META BOXES
	******************************************/

	/**
	* Save the metaboxes for post type "Lista de Productos"
	**/

	/**
	* Add listo to user account
	* @param int $user_id, $nombre, $recurrencia
	* @return Integer
	*/
	public function add_list( $nombre, $recurrencia ) {
		global $wpdb;
		$existe_nombre = $wpdb->get_results(
			"SELECT nombre FROM " . $wpdb->prefix . "product_list WHERE nombre = '" . $nombre."' AND user_id = ".get_current_user_id()
			);
		if( empty( $existe_nombre ) ) {
			if(isset($nombre) && isset($recurrencia) ) {
				$list_data = array(
					'user_id'		=> get_current_user_id(),
					'nombre'		=> $nombre,
					'recurrencia' 	=> $recurrencia,
					'fecha'			=> current_time( 'mysql' ),
				);
				$wpdb->insert(
					$wpdb->prefix . 'product_list',
					$list_data,
					array( '%d', '%s', '%d', '%s' )
				);
				//wp_redirect( 'my-account' );
				return $wpdb->insert_id;
			}
			else {
				return 0;
			}
		}
		else {
			return $nombre;
		}
	}

	public function count_products( $list_id ) {
		global $wpdb;
		$total_products = $wpdb->get_results(
			"SELECT count(product_list_id) as TOT FROM " . $wpdb->prefix . "product_list_detail WHERE product_list_id = " . $list_id
			);
		if( empty( $total_products ) ) return 0;

		return $total_products[0]->TOT;
	}

	/******************************************
	* GETTERS
	******************************************/

	/**
	 * Return all Product Lists
	 * @return Vimeo $lib
	 */
	public static function get_list($user_id){
		global $wpdb;
		$list_results = $wpdb->get_results(
			"SELECT * FROM " . $wpdb->prefix . "product_list WHERE user_id = " . $user_id
			);
		if( empty( $list_results ) ) return 0;

		return $list_results;
	}

	public static function get_all_lists(){
		global $wpdb;
		$list_results = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "product_list ");
		if( empty( $list_results ) ) return 0;

		return $list_results;
	}

	public static function get_list_name($id){
		global $wpdb;
		$nombre = $wpdb->get_results(
			"SELECT nombre FROM " . $wpdb->prefix . "product_list WHERE id = " . $id
			);
		if( empty( $nombre ) ) return 'Sin nombre';

		return $nombre[0]->nombre;
	}

	public static function get_list_detail($list_id){
		global $wpdb;
		$list_results = $wpdb->get_results(
			"SELECT * FROM " . $wpdb->prefix . "product_list_detail WHERE product_list_id = " . $list_id
			);
		if( empty( $list_results ) ) return 0;

		return $list_results;
	}

	public function delete_list( $list_id ) {
		global $wpdb;

		$where = array(
			'id'	=> $list_id
		);
		return $wpdb->delete(
			$wpdb->prefix . 'product_list',
			$where,
			array( '%d' )
		);
	}

	public function delete_list_detail( $product_id, $list_id ) {
		global $wpdb;

		$where = array(
			'product_id'	=> $product_id,
			'product_list_id'	=> $list_id
		);
		return $wpdb->delete(
			$wpdb->prefix . 'product_list_detail',
			$where,
			array( '%d' )
		);
	}

	public function add_products_to_a_list() {
		global $wpdb;
		$resp = "OK";
		$list_id = $_POST['list_id'];
		if ( $list_id == 0 ) {
			$list_id = $this->add_list( "Mi Lista", 15 );
		}

		for($i = 0; $i<count($_POST['ids']); $i++) {
			$list_data = array(
				'product_list_id'	=> $list_id,
				'product_id'		=> $_POST['ids'][$i],
				'cantidad' 			=> $_POST['cant'][$i]
			);
			$wpdb->insert(
				$wpdb->prefix . 'product_list_detail',
				$list_data,
				array( '%d', '%d', '%d' )
			);
			//wp_redirect( 'my-account' );
			if($wpdb->insert_id == FALSE) { $resp = "ERROR".$wpdb->insert_id; };
		}
		echo $resp;
		wp_die();
	}

	public function add_product_list_to_cart( $list_id ) {
		$detalle = $this->get_list_detail($list_id);
		if (count($detalle[0]) > 0) {

			foreach ( $detalle as $det )
			{
				WC()->cart->add_to_cart( $det->product_id, $det->cantidad );
			}
		}
		echo '<div class="woocommerce-Message woocommerce-Message--info woocommerce-success" >';
		echo 'Hemos agregado los productos de tu lista a tu carrito.';
		echo '</div>';
		echo '<a href="'.SITEURL.'cart" class="[ float-right ] button alt">Ver Carrito</a>';
		//header("Location: ".SITEURL."cart");
	}

}// Product_List_Settings

