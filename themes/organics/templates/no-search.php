<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_no_search_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_no_search_theme_setup', 1 );
	function organics_template_no_search_theme_setup() {
		organics_add_template(array(
			'layout' => 'no-search',
			'mode'   => 'internal',
			'title'  => esc_html__('No search results found', 'organics'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'organics_template_no_search_output' ) ) {
	function organics_template_no_search_output($post_options, $post_data) {
        global $ORGANICS_GLOBALS;
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php echo sprintf(esc_html__('Busqueda: %s', 'organics'), get_search_query()); ?></h2>
				<p><?php esc_html_e( 'Disculpa, por el momento no contamos con este producto.', 'organics' ); ?></p>
				<p><?php echo wp_kses( sprintf(__('Checa nuestros productos existentes en <a href="../categoria-producto/canastas/programar-canasta-recurrente/">Crea tu canasta</a><a class="hidden" href="%s">%s</a>', 'organics'), home_url(), get_bloginfo()), $ORGANICS_GLOBALS['allowed_tags'] ); ?>
				<?php echo trim(organics_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>