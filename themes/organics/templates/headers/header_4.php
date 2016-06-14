<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_header_4_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_header_4_theme_setup', 1 );
	function organics_template_header_4_theme_setup() {
        organics_add_template(array(
			'layout' => 'header_4',
			'mode'   => 'header',
			'title'  => esc_html__('Header 4', 'organics'),
			'icon'   => organics_get_file_url('templates/headers/images/4.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_header_4_output' ) ) {
	function organics_template_header_4_output($post_options, $post_data) {
		global $ORGANICS_GLOBALS;

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background: url('.esc_url($header_image).') repeat center top"' 
				: '';
		}
		?>
		
		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_4 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_4 top_panel_position_<?php echo esc_attr(organics_get_custom_option('top_panel_position')); ?>">
			
			<?php if (organics_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info', 'login', 'bookmarks', 'currency', 'cart');
						require_once organics_get_file_dir('templates/headers/_parts/top-panel-top.php');
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo trim($header_css); ?>>
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid"><div
						class="column-1_6 contact_logo">
							<?php require_once organics_get_file_dir('templates/headers/_parts/logo.php'); ?>
						</div><div 
						class="column-5_6 menu_main_wrap">
							<?php
                            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce() && (organics_is_woocommerce_page() && organics_get_custom_option('show_cart')=='shop' || organics_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
                                ?>
                                <div class="menu_main_cart top_panel_icon">
                                    <?php require_once organics_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
                                </div>
                            <?php
                            }
                            if (organics_get_custom_option('show_search')=='yes') echo trim(organics_sc_search(array('state'=>'closed')));
                            ?>
                            <a href="#" class="menu_main_responsive_button icon-menu"></a>
                            <nav class="menu_main_nav_area">
                                <?php
                                if (empty($ORGANICS_GLOBALS['menu_main'])) $ORGANICS_GLOBALS['menu_main'] = organics_get_nav_menu('menu_main');
                                if (empty($ORGANICS_GLOBALS['menu_main'])) $ORGANICS_GLOBALS['menu_main'] = organics_get_nav_menu();
                                echo ($ORGANICS_GLOBALS['menu_main']);
                                ?>
                            </nav>
						</div>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
	}
}

$header_mobile = organics_get_global('header_mobile');
$header_mobile['header_4'] = array(
    'open_hours' => true,
    'login' => true,
    'socials' => false,
    'bookmarks' => false,
    'contact_address' => true,
    'contact_phone_email' => true,
    'woo_cart' => true,
    'search' => true
);
organics_set_global('header_mobile', $header_mobile);
?>