<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_form_1_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_form_1_theme_setup', 1 );
	function organics_template_form_1_theme_setup() {
		organics_add_template(array(
			'layout' => 'form_1',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 1', 'organics')
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_form_1_output' ) ) {
	function organics_template_form_1_output($post_options, $post_data) {
		global $ORGANICS_GLOBALS;
		?>
		<form <?php echo ($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $ORGANICS_GLOBALS['ajax_url']); ?>">
			<div class="sc_form_info">
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'organics'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'organics'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'organics'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'organics'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'organics'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'organics'); ?>"></div>
			</div>
			<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'organics'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'organics'); ?>"></textarea></div>
			<div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'organics'); ?></button></div>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>