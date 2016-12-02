<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class PIXAN_API_Web_services {

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 *
	 * @return null|PIXAN_WS_Webservice_get_posts
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
	 * Setup hooks
	 */
	private function hooks() {
		//add_action( 'pixan_api_webservice_get_posts', array( $this, 'get_posts' ) );

		add_action( 'pixan_api_webservice_login', array( $this, 'login' ) );
		add_action( 'pixan_api_webservice_register', array( $this, 'register' ) );
		
		
		// LISTAS
		
		add_action( 'pixan_api_webservice_get_all_lists', array( $this, 'get_all_lists' ) );
		add_action( 'pixan_api_webservice_add_list', array( $this, 'add_list' ) );
		add_action( 'pixan_api_webservice_delete_list', array( $this, 'delete_list' ) );
		add_action( 'pixan_api_webservice_update_product_from_a_list', array( $this, 'update_product_from_a_list' ) );
		add_action( 'pixan_api_webservice_view_list', array( $this, 'view_list' ) );
		add_action( 'pixan_api_webservice_add_product_to_list', array( $this, 'add_product_to_list' ) );
		
		
		add_action( 'pixan_api_webservice_lost_password', array( $this, 'lost_password' ) );
		add_action( 'pixan_api_webservice_change_password', array( $this, 'change_password' ) );
		

	}
	
	
	public function get_all_lists(){
		$list_manager = new Product_List_Settings;
		
		$result = $list_manager->get_list( $_POST['user_id'] );
		
		
		PIXAN_API_Output::get()->output( true, 200, '', $result	);
	}
	
	public function add_list(){
		
		
		$list_manager = new Product_List_Settings;
		
		$list_name 			= $_POST['list_name'];
		$list_recurrence 	= $_POST['list_recurrence'];
		$user_id			= $_POST['user_id'];
			
		$result = $list_manager->add_list( $list_name, $list_recurrence, $user_id );
		
		if( $result == $list_name ){
			PIXAN_API_Output::get()->output( false, 200, 'La lista ya existe.'	);
		}else if( $result === 0 ){
			PIXAN_API_Output::get()->output( false, 500, 'Ocurrio un error al crear la lista.'	);
		}else{
			PIXAN_API_Output::get()->output( true	);
		}
		
		
	}
	
	public function delete_list(){
		
		$list_manager = new Product_List_Settings;
		
		$result = $list_manager->delete_list( $_POST['list_id'] );
		
		
		PIXAN_API_Output::get()->output( true, 200, '', $result	);
	}

	
	
	
	public function update_product_from_a_list(){
		$list_id 			= $_POST['list_id'];
		$product_id		 	= $_POST['product_id'];
		$cant				= $_POST['cant'];
		
		$list_manager = new Product_List_Settings;
		
		$result = $list_manager->update_product_from_a_list( $list_id, $product_id, $cant );
		
		PIXAN_API_Output::get()->output( true, 200, '', $result	);
		
	}
	
	public function view_list(){
		$list_manager = new Product_List_Settings;
		
		$result = $list_manager->get_list_detail( $_POST['list_id'] );
		
		if( !empty( $result ) ){
			
				
			for( $i = 0; $i < count( $result ); $i++ ){
				
				
				$result[$i]->product = new WC_Product( $result[$i]->product_id );
			}
			
		}
		
		
		
		
		PIXAN_API_Output::get()->output( true, 200, '', $result	);
	}	
	
	public function add_product_to_list(){
		
		
		$list_id 			= $_POST['list_id'];
		$product_id		 	= $_POST['product_id'];
		$cant				= $_POST['cant'];
		
		$list_manager = new Product_List_Settings;
		
		$result = $list_manager->add_product_to_a_list( $list_id, $product_id, $cant );
		
		if( $result ){
			PIXAN_API_Output::get()->output( true, 200, '', $result	);
		}else{
			PIXAN_API_Output::get()->output( false, 500, '', $result );
		}
	}



	


	public function login(){


		


		switch ($_POST['type']) {
			case 'site':

				PIXAN_API_Catch_Request::get()->check_params(array('user_login', 'type'));

				$user_login = $_POST['user_login'];

				PIXAN_API_Catch_Request::get()->check_params(array('user_password'));

				$user_password = $_POST['user_password'];

				$user = get_user_by( 'email', $user_login );

				if( $user !== false ){
					$user_login = $user->user_login;
				}

				$creds = array();
				$creds['user_login'] = $user_login;
				$creds['user_password'] = $user_password;
				
				$user = wp_signon( $creds, false );
				if ( is_wp_error($user) ){
					PIXAN_API_Output::get()->output( false, 401, $user->get_error_code() );
				}

				
				break;
			
			case 'facebook':

				error_log('Entra');

				PIXAN_API_Catch_Request::get()->check_params(array('facebook_uid'));

				$facebook_uid = $_POST['facebook_uid'];

				if( isset( $_POST['user_login'] ) && !empty( $_POST['user_login'] )){
					$user_email = $_POST['user_login'];
				}else{
					$user_email = $facebook_uid . "@facebook.com";
				}



				if ( username_exists( $user_email ) || email_exists($user_email) ) {

					$user = get_user_by( 'email', $user_email );

					if( $user === false || get_user_meta($user->ID, '_wc_social_login_facebook_uid', true) !== $facebook_uid ){

						PIXAN_API_Output::get()->output( false, 409, "User already exists or FB id dont match." );

					}else{
						PIXAN_API_Output::get()->output( true, 200, '', array(
							'user' => $this->getUserInfo( $user )
						) );

					}
					
				}else{
					error_log('Se queda aqui');
				}
				

				$user_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

				
				$userdata = array(
				    'user_login'  =>  $user_email,
				    'user_email'  =>  $user_email,
				    'role' 	      =>  'customer',
				    'user_pass'   =>  $user_password,
				);

				$user_id = wp_insert_user( $userdata ) ;



				if ( is_wp_error($user_id) ){
					
					PIXAN_API_Output::get()->output( false, 401, $user_id->get_error_code() );
				}

				$user = get_user_by( 'ID', $user_id );

				add_user_meta( $user_id, '_wc_social_login_facebook_uid', $facebook_uid);

				break;

						default:

				PIXAN_API_Output::get()->output( false, 400, "Error in param 'type'" );

				break;


		}

	


		PIXAN_API_Output::get()->output( true, 200, '', array( 'user' => $this->getUserInfo( $user ) )	);
		
	}


	public function getUserInfo( $user ){


		$customer_orders = get_posts( array(
		    'numberposts' => -1,
		    'meta_key'    => '_customer_user',
		    'meta_value'  => $user->ID,
		    'post_type'   => wc_get_order_types(),
		    'post_status' => array_keys( wc_get_order_statuses() ),
		) );


		$last_order = null;
		$total_spent = 0;

		foreach( $customer_orders as $order ){

			$order = new WC_Order( $order->ID );


			if( $last_order == null ){
				$last_order = $order;
			}

			$total_spent+= $order->get_total();


		}


		$user_info = array(
			'id'=>$user->ID,
			'username' => $user->user_login,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'email' => $user->user_email,
			'roles' => $user->roles,
			'avatar_url' => get_avatar_url($user->ID),
			"created_at" => $user->user_registered,
			
			"orders_count" =>  count( $customer_orders ) ,
			"total_spent" => $total_spent,
			
		);

		if( $last_order !== null ){
			$user_info['last_order_date'] = $last_order->order_date;
			$user_info['last_order_id'] = $last_order->id;
			$user_info['billing_address'] = $last_order->get_address('billing');
			$user_info['shipping_address'] = $last_order->get_address('shipping');
		}

		return $user_info;

	}



	public function register(){

		
		switch ($_POST['type']) {
			case 'site':

				PIXAN_API_Catch_Request::get()->check_params(array('user_email', 'type'));
				PIXAN_API_Catch_Request::get()->check_params(array('user_password'));

				$user_email = $_POST['user_email'];

				if ( username_exists( $user_email ) || email_exists($user_email) ) {
					PIXAN_API_Output::get()->output( false, 409, "User already exists." );
				}

				$user_password = $_POST['user_password'];


				$userdata = array(
				    'user_login'  =>  $user_email,
				    'user_email'  =>  $user_email,
				    'role' 	      =>  'customer',
				    'user_pass'   =>  $user_password,
				);

				$user_id = wp_insert_user( $userdata ) ;

				if ( is_wp_error($user) ){
					PIXAN_API_Output::get()->output( false, 401, $user->get_error_code() );
				}
				
				break;
			
			case 'facebook':

				PIXAN_API_Catch_Request::get()->check_params(array('facebook_uid'));

				$facebook_uid = $_POST['facebook_uid'];

				if( isset( $_POST['user_email'] ) && !empty( $_POST['user_email'] )){
					$user_email = $_POST['user_email'];
				}else{
					$user_email = $facebook_uid . "@facebook.com";
				}



				if ( username_exists( $user_email ) || email_exists($user_email) ) {

					$user = get_user_by( 'email', $user_email );

					if( $user === false || get_user_meta($user->ID, '_wc_social_login_facebook_uid', true) !== $facebook_uid ){

						PIXAN_API_Output::get()->output( false, 409, "User already exists or FB id dont match." );

					}else{
						PIXAN_API_Output::get()->output( true, 200, '', array(
							'user' => $this->getUserInfo( $user )
						) );

					}
					
				}
				

				$user_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

				
				$userdata = array(
				    'user_login'  =>  $user_email,
				    'user_email'  =>  $user_email,
				    'role' 	      =>  'customer',
				    'user_pass'   =>  $user_password,
				);

				$user_id = wp_insert_user( $userdata ) ;



				if ( is_wp_error($user_id) ){
					PIXAN_API_Output::get()->output( false, 401, $user_id->get_error_code() );
				}

				add_user_meta( $user_id, '_wc_social_login_facebook_uid', $facebook_uid);

				break;

			
			default:

				PIXAN_API_Output::get()->output( false, 400, "Error in param 'type'" );

				break;


		}


		$user = get_user_by( 'id', $user_id );

		if( $user ){

			PIXAN_API_Output::get()->output( true, 200, '', array( 'user' => $this->getUserInfo( $user ) ) );


		}else{

			PIXAN_API_Output::get()->output( false, 500, "Server Error" );

		}




	}
	
	
	public function change_password(){


		PIXAN_API_Catch_Request::get()->check_params(array('user_email'));
		PIXAN_API_Catch_Request::get()->check_params(array('old_password'));
		PIXAN_API_Catch_Request::get()->check_params(array('new_password'));

		$user_email = $_POST['user_email'];
		$user_old_password = $_POST['old_password'];
		$user_new_password = $_POST['new_password'];

		$user = get_user_by( 'email', $user_email );
		if( ! $user ){
			PIXAN_API_Output::get()->output( false, 401, 'Invalid email' );
		}

		$creds = array();
		$creds['user_login'] = $user->user_login;
		$creds['user_password'] = $user_old_password;
		$user = get_user_by( 'email', $user_email );

		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) ){
			PIXAN_API_Output::get()->output( false, 401, $user->get_error_code() );
		}

		wp_set_password( $user_new_password, $user->ID );

		PIXAN_API_Output::get()->output( true, 200, '' );

	}
	
	
	
	public function lost_password(){

		PIXAN_API_Catch_Request::get()->check_params(array('user_email'));

		$user_login = $_POST['user_email'];

		global $wpdb, $wp_hasher;

			$errors = new WP_Error();




			if ( empty( $_POST['user_email'] ) ) {
				$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
			} elseif ( strpos( $_POST['user_email'], '@' ) ) {
				$user_data = get_user_by( 'email', trim( $_POST['user_email'] ) );
				if ( empty( $user_data ) ){

					$user_data = get_user_by( 'login', trim( $_POST['user_email'] ) );

					if ( empty( $user_data ) ){
						$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));	
					}

				}
					
			} else {
				$login = trim($_POST['user_email']);
				$user_data = get_user_by('login', $login);
			}

			/**
			 * Fires before errors are returned from a password reset request.
			 *
			 * @since 2.1.0
			 * @since 4.4.0 Added the `$errors` parameter.
			 *
			 * @param WP_Error $errors A WP_Error object containing any errors generated
			 *                         by using invalid credentials.
			 */
			do_action( 'lostpassword_post', $errors );

			if ( $errors->get_error_code() ){
				PIXAN_API_Output::get()->output( false, 401, $errors );
			}

			if ( !$user_data ) {
				$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));

				PIXAN_API_Output::get()->output( false, 401, $errors );
			}

			// Redefining user_login ensures we return the right case in the email.
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
			$key = $this->get_password_reset_key( $user_data );

			if ( is_wp_error( $key ) ) {
				PIXAN_API_Output::get()->output( false, 401, $key );
			}

			$message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
			$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
			$message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n";

			if ( is_multisite() )
				$blogname = $GLOBALS['current_site']->site_name;
			else
				/*
				 * The blogname option is escaped with esc_html on the way into the database
				 * in sanitize_option we want to reverse this for the plain text arena of emails.
				 */
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

			$title = sprintf( __('[%s] Password Reset'), $blogname );

			/**
			 * Filter the subject of the password reset email.
			 *
			 * @since 2.8.0
			 * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
			 *
			 * @param string  $title      Default email title.
			 * @param string  $user_login The username for the user.
			 * @param WP_User $user_data  WP_User object.
			 */
			$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

			/**
			 * Filter the message body of the password reset mail.
			 *
			 * @since 2.8.0
			 * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
			 *
			 * @param string  $message    Default mail message.
			 * @param string  $key        The activation key.
			 * @param string  $user_login The username for the user.
			 * @param WP_User $user_data  WP_User object.
			 */
			$message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

			if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ){
				PIXAN_API_Output::get()->output( false, 401, null );
			}

			

			PIXAN_API_Output::get()->output( true, 200);

	}
	
	public function get_password_reset_key( $user ) {
		global $wpdb, $wp_hasher;
	
		/**
		 * Fires before a new password is retrieved.
		 *
		 * @since 1.5.0
		 * @deprecated 1.5.1 Misspelled. Use 'retrieve_password' hook instead.
		 *
		 * @param string $user_login The user login name.
		 */
		do_action( 'retreive_password', $user->user_login );
	
		/**
		 * Fires before a new password is retrieved.
		 *
		 * @since 1.5.1
		 *
		 * @param string $user_login The user login name.
		 */
		do_action( 'retrieve_password', $user->user_login );
	
		/**
		 * Filter whether to allow a password to be reset.
		 *
		 * @since 2.7.0
		 *
		 * @param bool true           Whether to allow the password to be reset. Default true.
		 * @param int  $user_data->ID The ID of the user attempting to reset a password.
		 */
		$allow = apply_filters( 'allow_password_reset', true, $user->ID );
	
		if ( ! $allow ) {
			return new WP_Error( 'no_password_reset', __( 'Password reset is not allowed for this user' ) );
		} elseif ( is_wp_error( $allow ) ) {
			return $allow;
		}
	
		// Generate something random for a password reset key.
		$key = wp_generate_password( 20, false );
	
		/**
		 * Fires when a password reset key is generated.
		 *
		 * @since 2.5.0
		 *
		 * @param string $user_login The username for the user.
		 * @param string $key        The generated password reset key.
		 */
		do_action( 'retrieve_password_key', $user->user_login, $key );
	
		// Now insert the key, hashed, into the DB.
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . WPINC . '/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
		$key_saved = $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
		if ( false === $key_saved ) {
			return new WP_Error( 'no_password_key_update', __( 'Could not save password reset key to database.' ) );
		}
	
		return $key;
	}




	


	/**
	 * This is the default included 'get_posts' webservice
	 * This webservice will fetch all posts of set post type
	 *
	 * @todo
	 * - All sorts of security checks
	 * - Allow custom query variables in webservice (e.g. custom sorting, posts_per_page, etc.)
	 */
	public function get_posts() {

		// Check if post type is set
		if ( ! isset( $_GET['post_type'] ) ) {
			Pixan_Web_Service::get()->throw_error( 'No post type set.' );
		}

		// Set post type
		$post_type = esc_sql( $_GET['post_type'] );

		// Global options
		$options = Pixan_Web_Service::get()->get_options();

		// Get 'get_posts' options
		$gp_options = array();
		if ( isset( $options['get_posts'] ) ) {
			$gp_options = $options['get_posts'];
		}

		// Fix scenario where there are no settings for given post type
		if ( ! isset( $gp_options[$post_type] ) ) {
			$gp_options[$post_type] = array();
		}

		// Setup options
		$pt_options = wp_parse_args( $gp_options[$post_type], $this->get_default_settings() );

		// Check if post type is enabled
		if ( 'false' == $pt_options['enabled'] ) {
			Pixan_Web_Service::get()->throw_error( 'Post Type not supported.' );
		}

		// Setup default query vars
		$default_query_arguments = array(
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
		);

		// Get query vars
		$query_vars = array();
		if ( isset( $_GET['qv'] ) ) {
			$query_vars = $_GET['qv'];
		}

		// Merge query vars
		$query_vars = wp_parse_args( $query_vars, $default_query_arguments );

		// Set post type
		$query_vars['post_type'] = $post_type;

		// Get posts
		$posts = get_posts( $query_vars );

		// Post data to show - this will be manageble at some point
		$show_post_data_fields = array( 'ID', 'post_title', 'post_content', 'post_date' );

		// Post meta data to show - this will be manageble at some point
		$show_post_meta_data_fields = array( 'ssm_supermarkt', 'ssm_adres' );

		// Data array
		$return_data = array();

		// Loop through posts
		foreach ( $posts as $post ) {

			$post_custom = get_post_custom( $post->ID );

			$data = array();

			// Add regular post fields data array
			foreach ( $pt_options['fields'] as $show_post_data_field ) {

				$post_field_value = $post->$show_post_data_field;

				// Fetch thumbnail
				if ( 'thumbnail' == $show_post_data_field ) {
					$post_field_value = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
				}

				// Set post field value
				$data[ $show_post_data_field ] = $post_field_value;
			}

			// Add post meta fields to data array
			foreach ( $pt_options['custom'] as $show_post_meta_data_field ) {

				$meta_field_value = get_post_meta( $post->ID, $show_post_meta_data_field, true );

				if ( $meta_field_value != '' ) {
					$data[ $show_post_meta_data_field ] = $meta_field_value;
				}

			}

			$return_data[] = $data;

		}

		PIXAN_API_Output::get()->output( $return_data );
	}

}