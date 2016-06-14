<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_form_custom_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_form_custom_theme_setup', 1 );
	function organics_template_form_custom_theme_setup() {
		organics_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'organics')
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_form_custom_output' ) ) {
	function organics_template_form_custom_output($post_options, $post_data) {
		global $ORGANICS_GLOBALS;
		?>
		<form <?php echo ($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $ORGANICS_GLOBALS['ajax_url']); ?>">
			<?php echo trim($post_options['content']); ?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>