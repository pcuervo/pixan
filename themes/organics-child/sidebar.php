<?php
/**
 * The Sidebar containing the main widget areas.
 */

$sidebar_show   = organics_get_custom_option('show_sidebar_main');
$sidebar_scheme = organics_get_custom_option('sidebar_main_scheme');
$sidebar_name   = organics_get_custom_option('sidebar_main');

if (!organics_param_is_off($sidebar_show) && is_active_sidebar($sidebar_name)) {
	?>
	<div class="sidebar widget_area scheme_<?php echo esc_attr($sidebar_scheme); ?>" role="complementary">
		<div class="sidebar_inner widget_area_inner">
			<?php if ( ! is_user_logged_in() ){ ?>
				<a href="<?php echo site_url('/my-account/'); ?>" class="[ button-login ] sc_button sc_button_round sc_button_style_filled sc_button_scheme_original sc_button_size_small">Ingresa / Registrate</a>
			<?php } ?>

			<?php if ( is_page(array('cart', 'checkout')) ){ ?>
				<a href="<?php echo site_url('/categoria-producto/productos/'); ?>" class="[ width---100 box-sizing-border-box ][ button-login ] sc_button sc_button_round sc_button_style_filled sc_button_scheme_original sc_button_size_small">Agregar productos</a><br>
			<?php } ?>

			<?php
			ob_start();
			do_action( 'before_sidebar' );
			if (($reviews_markup = organics_storage_get('reviews_markup')) != '') {
				echo '<aside class="column-1_1 widget widget_reviews">' . trim($reviews_markup) . '</aside>';
			}
			organics_storage_set('current_sidebar', 'main');
			if ( !dynamic_sidebar($sidebar_name) ) {
				// Put here html if user no set widgets in sidebar
			}
			do_action( 'after_sidebar' );
			$out = ob_get_contents();
			ob_end_clean();
			echo trim(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
			?>
		</div>
	</div> <!-- /.sidebar -->
	<?php
}
?>