<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_plain_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_plain_theme_setup', 1 );
	function organics_template_plain_theme_setup() {
		organics_add_template(array(
			'layout' => 'plain',
			'template' => 'plain',
			'need_terms' => true,
			'mode'   => 'blogger',
			'title'  => esc_html__('Blogger layout: Plain', 'organics')
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_plain_output' ) ) {
	function organics_template_plain_output($post_options, $post_data) {
		?>
		<div class="post_item sc_blogger_item sc_plain_item<?php echo ($post_options['number'] == $post_options['posts_on_page'] && !organics_param_is_on($post_options['loadmore']) ? ' sc_blogger_item_last' : ''); ?>" style="background-image: url(<?php echo (wp_get_attachment_url( get_post_thumbnail_id($page->ID) )); ?>);">

            <div class="sc_plain_item_inner">
                <?php
                if (!empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_links)) {
                    ?>
                    <div class="post_category">
					<span class="post_category_label"><?php esc_html_e('in', 'organics'); ?></span> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?>
                    </div>
                    <?php
                }

                if (!isset($post_options['links']) || $post_options['links']) {
                    ?><h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_title']); ?></a></h4><?php
                } else {
                    ?><h4 class="post_title"><?php echo ($post_data['post_title']); ?></h4><?php
                }

                if (!$post_data['post_protected'] && $post_options['info']) {
                    $info_parts = array('counters'=>true, 'terms'=>false, 'author' => false);
				require organics_get_file_dir('templates/_parts/post-info.php');
                }
                ?>
            </div>
		</div>		<!-- /.post_item -->

		<?php
	}
}
?>