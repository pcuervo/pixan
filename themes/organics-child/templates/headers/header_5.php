<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_template_header_5_theme_setup' ) ) {
	add_action( 'organics_action_before_init_theme', 'organics_template_header_5_theme_setup', 1 );
	function organics_template_header_5_theme_setup() {
        organics_add_template(array(
			'layout' => 'header_5',
			'mode'   => 'header',
			'title'  => esc_html__('Header 5', 'organics'),
			'icon'   => organics_get_file_url('templates/headers/images/5.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'organics_template_header_5_output' ) ) {
	function organics_template_header_5_output($post_options, $post_data) {
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

		<header class="top_panel_wrap top_panel_style_5 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_5 top_panel_position_<?php echo esc_attr(organics_get_custom_option('top_panel_position')); ?>">

			<?php if (organics_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info', 'cart', 'login', 'currency');
						require_once organics_get_file_dir('templates/headers/_parts/top-panel-top.php');
						?>
					</div>
				</div>
			<?php } ?>

			<div class="top_panel_middle" <?php echo trim($header_css); ?>>
				<div class="content_wrap">
					<div class="contact_logo">
						<?php require_once organics_get_file_dir('templates/headers/_parts/logo.php'); ?>
					</div>
                </div>
                <div class="main-menu_wrapper">
                    <div class="content_wrap">
                        <div class="menu_main_wrap clearfix">
                            <?php
                            if (organics_get_custom_option('show_search')=='yes')
                                echo trim(organics_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
                            ?>
                            <a href="#" class="menu_main_responsive_button icon-menu"></a>
                            <nav class="menu_main_nav_area">

								<!-- img logo header fijo -->
								<a href="<?php echo site_url('/'); ?>" class="[ logo-header-fijo ]">
									<img src="/pixan/wp-content/uploads/2016/07/logo-small.png" alt="">
								</a>

                                <?php
                                if (empty($ORGANICS_GLOBALS['menu_main'])) $ORGANICS_GLOBALS['menu_main'] = organics_get_nav_menu('menu_main');
                                if (empty($ORGANICS_GLOBALS['menu_main'])) $ORGANICS_GLOBALS['menu_main'] = organics_get_nav_menu();
                                echo ($ORGANICS_GLOBALS['menu_main']);
                                ?>

								<!-- User en header fijo -->

								<div class="top_panel_top_user_area">
								    <?php

								    if (in_array('search', $top_panel_top_components) && organics_get_custom_option('show_search')=='yes') {
								        ?>
								        <div class="top_panel_top_search"><?php echo organics_sc_search(array('state'=>'closed')); ?></div>
								    <?php
								    }

										$menu_user = organics_get_nav_menu('menu_user');
										if (empty($menu_user)) {
											?>
											<ul id="menu_user" class="menu_user_nav">
											<?php
										} else {
											$menu = organics_substr($menu_user, 0, organics_strlen($menu_user)-5);
											$pos = organics_strpos($menu, '<ul');
											if ($pos!==false) $menu = organics_substr($menu, 0, $pos+3) . ' class="menu_user_nav"' . organics_substr($menu, $pos+3);
											echo str_replace('class=""', '', $menu);
										}

								        if (in_array('language', $top_panel_top_components) && organics_get_custom_option('show_languages')=='yes' && function_exists('icl_get_languages')) {
								            $languages = icl_get_languages('skip_missing=1');
								            if (!empty($languages) && is_array($languages)) {
								                $lang_list = '';
								                $lang_active = '';
								                foreach ($languages as $lang) {
								                    $lang_title = esc_attr($lang['translated_name']);	//esc_attr($lang['native_name']);
								                    if ($lang['active']) {
								                        $lang_active = $lang_title;
								                    }
								                    $lang_list .= "\n"
								                        .'<li><a rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url(apply_filters('WPML_filter_link', $lang['url'], $lang)) . '">'
								                        .'<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang_title) . '" title="' . esc_attr($lang_title) . '" />'
								                        . ($lang_title)
								                        .'</a></li>';
								                }
								                ?>
								                <li class="menu_user_language">
												<a href="#"><span><?php echo trim($lang_active); ?></span></a>
												<ul><?php echo trim($lang_list); ?></ul>
								                </li>
								            <?php
								            }
								        }

								        if (in_array('bookmarks', $top_panel_top_components) && organics_get_custom_option('show_bookmarks')=='yes') {
								            // Load core messages
								            organics_enqueue_messages();
								            ?>
										<li class="menu_user_bookmarks"><a href="#" class="bookmarks_show icon-star" title="<?php esc_attr_e('Show bookmarks', 'organics'); ?>"><?php esc_html_e('Bookmarks', 'organics'); ?></a>
								                <?php
								                $list = organics_get_value_gpc('organics_bookmarks', '');
								                if (!empty($list)) $list = json_decode($list, true);
								                ?>
								                <ul class="bookmarks_list">
												<li><a href="#" class="bookmarks_add icon-star-empty" title="<?php esc_attr_e('Add the current page into bookmarks', 'organics'); ?>"><?php esc_html_e('Add bookmark', 'organics'); ?></a></li>
								                    <?php
								                    if (!empty($list) && is_array($list)) {
								                        foreach ($list as $bm) {
														echo '<li><a href="'.esc_url($bm['url']).'" class="bookmarks_item">'.($bm['title']).'<span class="bookmarks_delete icon-cancel" title="'.esc_attr__('Delete this bookmark', 'organics').'"></span></a></li>';
								                        }
								                    }
								                    ?>
								                </ul>
								            </li>
								        <?php
								        }

								        if (in_array('login', $top_panel_top_components) && organics_get_custom_option('show_login')=='yes') {
								            if ( !is_user_logged_in() ) {
								                // Load core messages
								                organics_enqueue_messages();
								                // Load Popup engine
								                organics_enqueue_popup();
										// Anyone can register ?
										if ( (int) get_option('users_can_register') > 0) {
								                ?>
								                <li class="menu_user_register"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil8"><?php esc_html_e('Register', 'organics'); ?></a><?php
												if (organics_get_theme_option('show_login')=='yes') {
													require_once organics_get_file_dir('templates/headers/_parts/register.php');
												}?></li>
										<?php } ?>
								                <li class="menu_user_login"><a href="<?php echo site_url('/my-account/'); ?>" class="popup_link popup_login_link icon-user189"><?php esc_html_e('Login', 'organics'); ?></a><?php
								                    if (organics_get_theme_option('show_login')=='yes') {
													require_once organics_get_file_dir('templates/headers/_parts/login.php');
								                    }?></li>
								            <?php
								            } else {
								                $current_user = wp_get_current_user();
								                ?>
								                <li class="menu_user_controls">
								                    <a href="<?php echo site_url('/my-account/'); ?>"><?php
								                        $user_avatar = '';
								                        if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 16*min(2, max(1, organics_get_theme_option("retina_ready"))));
								                        if ($user_avatar) {
														?><span class="user_avatar"><?php echo trim($user_avatar); ?></span><?php
													}?></a>
								                </li>
											<!-- <li class="menu_user_logout [ no-margin-left ]"><a href="<?php echo wp_logout_url(home_url()); ?>" class="icon icon-logout"><?php esc_html_e('Salir', 'organics'); ?></a></li> -->
								            <?php
								            }
								        }

								        if (in_array('socials', $top_panel_top_components) && organics_get_custom_option('show_socials')=='yes') {
								            ?>
								            <div class="top_panel_top_socials">
								                <?php echo organics_do_shortcode('[trx_socials size="tiny"][/trx_socials]'); ?>
								            </div>
								        <?php
								        }

								        ?>

								    </ul>

								</div>

                            </nav>
                            <?php
                            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce() && (organics_is_woocommerce_page() && organics_get_custom_option('show_cart')=='shop' || organics_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
                                ?>
                                <div class="menu_main_cart top_panel_icon">
                                    <?php require_once organics_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
                                </div>
                                <?php
                            }
                            ?>
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
	$header_mobile['header_5'] = array(
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