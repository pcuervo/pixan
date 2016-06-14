<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_form_2_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_form_2_theme_setup', 1 );
	function organics_template_form_2_theme_setup() {
		organics_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'organics')
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_form_2_output' ) ) {
	function organics_template_form_2_output($post_options, $post_data) {
		global $ORGANICS_GLOBALS;
		$address_1 = organics_get_theme_option('contact_address_1');
		$address_2 = organics_get_theme_option('contact_address_2');
		$phone = organics_get_theme_option('contact_phone');
		$fax = organics_get_theme_option('contact_fax');
		$email = organics_get_theme_option('contact_email');
		$open_hours = organics_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_address column-1_3">
				<div class="sc_form_address_field">
					<span class="sc_form_address_icon"></span>
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'organics'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_icon"></span>
                    <span class="sc_form_address_label"><?php esc_html_e('Phone number', 'organics'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
                </div>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_icon"></span>
                    <span class="sc_form_address_label"><?php esc_html_e('Have any questions', 'organics'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($email); ?></span>
                </div>
                <?php if ($open_hours) {?>
                <div class="sc_form_address_field">
                    <span class="sc_form_address_icon"></span>
                    <span class="sc_form_address_label"><?php esc_html_e('We are open', 'organics'); ?></span>
                    <span class="sc_form_address_data"><?php echo trim($open_hours); ?></span>
                </div>
                <?php }
                else ''?>
				<?php echo do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]'); ?>
			</div><div class="sc_form_fields column-2_3">
				<form <?php echo ($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $ORGANICS_GLOBALS['ajax_url']); ?>">
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'organics'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'organics'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'organics'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'organics'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php _e('Message', 'organics'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'organics'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'organics'); ?></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>