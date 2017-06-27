<?php $pagename = get_query_var('pagename');
	if($pagename != 'my-account') {
?>
<style type="text/css">
	.disabled {
		color: inherit;
	    cursor: not-allowed;
	    opacity: .5;
	}
</style>
<div id="popup_registration" class="popup_wrap popup_registration bg_tint_light">
	<a href="#" class="popup_close"></a>
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/header.png" class="[ margin-bottom ]" alt="logo pixan para header">
	<div class="form_wrap">
		<form method="post" class="register registration_form">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_username"><?php _e( 'Username', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" />
				</p>

			<?php endif; ?>
			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-first">
				<label for="reg_firstname"><?php _e( 'Nombre', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" id="registration_firstname" name="registration_firstname"  value="<?php if ( ! empty( $_POST['registration_firstname'] ) ) echo esc_attr( $_POST['registration_firstname'] ); ?>" placeholder="<?php esc_attr_e('Nombre(s) ', 'organics'); ?>">
			</p>
			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-last">
				<label for="reg_lastname"><?php _e( 'Apellido', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="text" id="registration_lastname" name="registration_lastname"  value="<?php if ( ! empty( $_POST['registration_lastname'] ) ) echo esc_attr( $_POST['registration_lastname'] ); ?>" placeholder="<?php esc_attr_e('Apellido(s) ', 'organics'); ?>">
			</p>
			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
				<label for="reg_email"><?php _e( 'Email', 'woocommerce' ); ?> <span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="registration_email" id="registration_email" value="<?php if ( ! empty( $_POST['email'] ) ) echo esc_attr( $_POST['email'] ); ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_password"><?php _e( 'Password', 'woocommerce' ); ?> <span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="registration_pwd" id="registration_pwd" />
				</p>

			<?php endif; ?>

			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide [ width--99p ]">
				<label for="_fecha_nacimiento" class="popup_form_field description_field"><?php _e( 'Fecha de nacimiento', 'organics' ); ?> <span class="required">*</span></label>
				<input type="date" id="_fecha_nacimiento" name="_fecha_nacimiento" value="" placeholder="<?php esc_attr_e('mm/dd/yyyy', 'organics'); ?>">
			</p>

			<div class="[ clearfix ]"></div>

			<!-- Spam Trap -->
			<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>

			<div class="[ clearfix ]"></div>
			<p class="woocomerce-FormRow form-row [ margin-bottom--large ]">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<input type="submit" id="btnSubmitRegister" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Crear cuenta', 'woocommerce' ); ?>" />
			</p>

			<?php //do_action( 'register_form' ); ?>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
		<div class="result message_block"></div>
	</div>	<!-- /.registration_wrap -->
</div>		<!-- /.user-popUp -->
<?php } ?>