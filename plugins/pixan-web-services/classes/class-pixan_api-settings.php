<?php

class PIXAN_API_Settings {

	private static $instance = null;

	/**
	 * Get singleton instance of class
	 *
	 * @return null|WPSWS_Settings
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
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		//add_action( 'wp_ajax_wpw_save_settings', array( $this, 'save_settings' ) );
	}

	/**
	 * Add menu pages
	 */
	public function add_menu_pages() {
		add_menu_page( 'Overview', 'Web Services', 'manage_options', 'wpw', array( $this, 'screen_main' ) );
	}

	/**
	 * The main screen
	 */
	public function screen_main() {
		?>



<?php if( !defined('PIXAN_API_AUTH_KEY') ): ?>

	<h2>Debes definir 'PIXAN_API_AUTH_KEY' desde wp-config.php</h2>


	Ej: define('PIXAN_API_AUTH_KEY', '62c7T5ljHphf83abXs0o2zDDO687P6DF');

<?php else: ?>
		

<h2>Login</h2>
Url: <code><?php bloginfo('url'); ?>/api/login</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/login" method="post" target="_blank">



<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>

<label for="type">type [post]</label>
<select name="type" id="type">
	<option value="site">site</option>
	<option value="facebook">facebook</option>
</select><br/>


<label for="user_login">user_login [post]</label>
<input type="text" name="user_login" id="user_login" value=""/><br/>

<label for="user_password">user_password [post] - optional</label>
<input type="text" name="user_password" id="user_password" value=""/><br/>


<label for="facebook_uid">facebook_uid [post] - optional</label>
<input type="text" name="facebook_uid" id="facebook_uid" value=""/><br/>


<input type="submit" value="Enviar"/>



</form>


<h2>Register</h2>
Url: <code><?php bloginfo('url'); ?>/api/register</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/register" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>


<label for="type">type [post]</label>
<select name="type" id="type">
	<option value="site">site</option>
	<option value="facebook">facebook</option>
</select><br/>


<label for="user_email">user_email [post]</label>
<input type="text" name="user_email" id="user_email" value=""/><br/>

<label for="username">username [post] opcional</label>
<input type="text" name="username" id="username" value=""/><br/>



<label for="user_password">user_password [post] - optional</label>
<input type="text" name="user_password" id="user_password" value=""/><br/>


<label for="facebook_uid">facebook_uid [post] - optional</label>
<input type="text" name="facebook_uid" id="facebook_uid" value=""/><br/>

<label for="first_name">first_name [post] </label>
<input type="text" name="first_name" id="first_name" value=""/><br/>

<label for="_fecha_nacimiento">_fecha_nacimiento [post] (dd/mm/aaaa) </label>
<input type="text" name="_fecha_nacimiento" id="_fecha_nacimiento" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>




<h2>Add list</h2>
Url: <code><?php bloginfo('url'); ?>/api/add_list</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/add_list" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	

<label for="user_id">user_id [post] - optional</label>
<input type="text" name="user_id" id="user_id" value=""/><br/>


<label for="list_name">list_name [post] - optional</label>
<input type="text" name="list_name" id="list_name" value=""/><br/>

<label for="list_recurrence">list_recurrence [post]</label>
<select name="list_recurrence" id="list_recurrence">
	<option value="8">8</option>
	<option value="15">15</option>
	<option value="30">30</option>
</select><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>Get all lists</h2>
Url: <code><?php bloginfo('url'); ?>/api/get_all_lists</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/get_all_lists" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	

<label for="user_id">user_id [post] - optional</label>
<input type="text" name="user_id" id="user_id" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>

<h2>delete_list</h2>
Url: <code><?php bloginfo('url'); ?>/api/delete_list</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/delete_list" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	

<label for="user_id">list_id [post] - optional</label>
<input type="text" name="list_id" id="list_id" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>view_list</h2>
Url: <code><?php bloginfo('url'); ?>/api/view_list</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/view_list" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	

<label for="list_id">list_id [post] - optional</label>
<input type="text" name="list_id" id="list_id" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>



<h2>add_product_to_list</h2>
Url: <code><?php bloginfo('url'); ?>/api/add_product_to_list</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/add_product_to_list" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	

<label for="list_id">list_id [post] - optional</label>
<input type="text" name="list_id" id="list_id" value=""/><br/>

<label for="product_id">product_id [post] - optional</label>
<input type="text" name="product_id" id="product_id" value=""/><br/>

<label for="cant">cant [post] - optional</label>
<input type="text" name="cant" id="cant" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>update_product_from_a_list</h2>
Url: <code><?php bloginfo('url'); ?>/api/update_product_from_a_list</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/update_product_from_a_list" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	

<label for="list_id">list_id [post] - optional</label>
<input type="text" name="list_id" id="list_id" value=""/><br/>

<label for="product_id">product_id [post] - optional</label>
<input type="text" name="product_id" id="product_id" value=""/><br/>

<label for="cant">cant [post] - optional</label>
<input type="text" name="cant" id="cant" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>



<h2>Reset password</h2>
Url: <code><?php bloginfo('url'); ?>/api/change_password</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/change_password" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>

<label for="user_email">user_email [post]</label>
<input type="text" name="user_email" id="user_email" value=""/><br/>

<label for="old_password">old_password [post]</label>
<input type="text" name="old_password" id="old_password" value=""/><br/>

<label for="new_password">new_password [post] </label>
<input type="text" name="new_password" id="new_password" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>Lost password</h2>
Url: <code><?php bloginfo('url'); ?>/api/lost_password</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/lost_password" method="post" target="_blank">

<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>

<label for="user_email">user_email [post]</label>
<input type="text" name="user_email" id="user_email" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<h2>zonas entrega</h2>
Url: <code><?php bloginfo('url'); ?>/api/zonas_entrega</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/zonas_entrega" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	


<input type="submit" value="Enviar"/>

</form>



<h2>update user meta</h2>
Url: <code><?php bloginfo('url'); ?>/api/update_user_meta</code><br/><br/>
<form action="<?php bloginfo('url'); ?>/api/update_user_meta" method="post" target="_blank">
	
<label for="auth_key">auth_key [post]</label>
<input type="text" name="auth_key" id="auth_key" value="<?php echo PIXAN_API_AUTH_KEY; ?>"/><br/>	


<label for="user_id">user_id [post]</label>
<input type="text" name="user_id" id="user_id" value=""/><br/>

<label for="user_meta">user_meta [post]</label>
<input type="text" name="user_meta" id="user_meta" value=""/><br/>

<label for="meta_value">meta_value [post]</label>
<input type="text" name="meta_value" id="meta_value" value=""/><br/>

<input type="submit" value="Enviar"/>

</form>


<script type="text/javascript">
	$ = jQuery.noConflict();
	$('.js-add-product').click( function(e){
		e.preventDefault();
		$current_index = $('.product:last').data('id');
		$('.product:first').clone().appendTo('.products');
		$lastProduct = $('.product:last');
		$lastProduct.data('id', ($current_index+1));
		$lastProduct.find('input').val('');
		$lastProduct.find('input:first').attr('name', 'line_items['+($current_index+1)+'][id]');
		$lastProduct.find('input:last').attr('name', 'line_items['+($current_index+1)+'][quantity]');
	})

	$('#method_id').change(function(){
		console.log('changing');
	})
	
</script>




<?php endif; ?>



	<?php
	}

}