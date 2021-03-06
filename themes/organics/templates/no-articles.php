<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_no_articles_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_no_articles_theme_setup', 1 );
	function organics_template_no_articles_theme_setup() {
		organics_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'organics'),
			'w'		 => null,
			'h'		 => null
		));
	}
}

// Template output
if ( !function_exists( 'organics_template_no_articles_output' ) ) {
	function organics_template_no_articles_output($post_options, $post_data) {
        global $ORGANICS_GLOBALS;
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'organics'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'organics' ); ?></p>
				<p><?php echo wp_kses( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'organics'), home_url(), get_bloginfo()), $ORGANICS_GLOBALS['allowed_tags'] ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'organics'); ?></p>
				<?php echo trim(organics_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>