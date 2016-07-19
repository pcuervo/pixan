<?php
	$all_options = organics_get_global('header_mobile');
	$top_panel_style = organics_get_custom_option('top_panel_style');
	$header_options = $all_options[$top_panel_style];

	$contact_address_1=trim(organics_get_custom_option('contact_address_1'));
	$contact_address_2=trim(organics_get_custom_option('contact_address_2'));
	$contact_phone=trim(organics_get_custom_option('contact_phone'));
	$contact_email=trim(organics_get_custom_option('contact_email'));
?>
	<div class="header_mobile">
		<div class="content_wrap">
			<?php
            include organics_get_file_dir('templates/headers/_parts/logo.php');
            ?>
            <div class="menu_button icon-menu"></div>
            <?php
            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce() && (organics_is_woocommerce_page() && organics_get_custom_option('show_cart')=='shop' || organics_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) {
                ?>
                <div class="menu_main_cart top_panel_icon">
                    <?php include organics_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
                </div>
            <?php
            }
			?>
		</div>

		<div class="side_wrap">
			<!-- <div class="close"><?php esc_html_e('Close', 'organics'); ?></div> -->
            <div class="close" style="font-size: 40px;">×</div>


			<div class="panel_top">
                <nav class="menu_main_nav_area">
                    <?php
                    $menu_main = organics_get_nav_menu('menu_main');
                    if (empty($menu_main)) $menu_main = organics_get_nav_menu();
                    echo trim($menu_main);
                    ?>
                </nav>
				<?php echo trim(organics_sc_search(array()));
//                if (organics_get_custom_option('show_login')=='yes') {
//                    if ( is_user_logged_in() ) { ?>
<!--                        <div class="login"><a href="--><?php //echo wp_logout_url(); ?><!--">--><?php //echo esc_html_e('Logout', 'organics'); ?><!--</a></div>-->
<!--                    --><?php
//                    }
//                    else { ?>
<!--                        <div class="login"><a href="--><?php //echo home_url('/'); ?><!--wp-login.php">--><?php //echo esc_html_e('Login', 'organics'); ?><!--</a></div>-->
<!--                    --><?php
//                    }
//                }



                if (organics_get_custom_option('show_login')=='yes') {
                    if ( is_user_logged_in() ) {
                        ?>
                        <div class="login"><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="popup_link"><?php echo esc_html_e('Logout', 'organics'); ?></a></div>
                    <?php
                    }
                    else {
                        // Load core messages
                        organics_enqueue_messages();
                        // Load Popup engine
                        organics_enqueue_popup();
                        ?>
                        <div class="login"><a href="#popup_login" class="popup_link popup_login_link icon-user"><?php esc_html_e('Login', 'organics'); ?></a></div>
                        <?php
                        // Anyone can register ?
                        if ( (int) get_option('users_can_register') > 0) {
                            ?>
                            <div class="login"><a href="#popup_registration" class="popup_link popup_register_link icon-pencil"><?php esc_html_e('Register', 'organics'); ?></a></div>
                        <?php
                        }
                    }
                }











                ?>
			</div>


			<div class="panel_middle">
                <?php
                if (!empty($contact_address_1) || !empty($contact_address_2)) {
                    ?><div class="contact_field contact_address">
                    <span class="contact_icon icon-home"></span>
                    <span class="contact_label contact_address_1"><?php echo force_balance_tags($contact_address_1); ?></span>
                    <span class="contact_address_2"><?php echo force_balance_tags($contact_address_2); ?></span>
                    </div><?php
                }

                if (!empty($contact_phone) || !empty($contact_email)) {
                    ?><div class="contact_field contact_phone">
                    <span class="contact_icon icon-phone"></span>
                    <span class="contact_label contact_phone"><?php echo force_balance_tags($contact_phone); ?></span>
                    <span class="contact_email"><?php echo force_balance_tags($contact_email); ?></span>
                    </div><?php
                }

                require_once organics_get_file_dir('templates/headers/_parts/top-panel-top.php');
                ?>
			</div>


			<div class="panel_bottom">
                <?php
                if (organics_get_custom_option('show_socials')=='yes') {
                    ?>
                    <div class="contact_socials" style="display: none;">
                        <?php echo trim(organics_sc_socials(array('size'=>'tiny'))); ?>
                    </div>
                    <div class="[ text-center ]">
                            <a class="[ margin-right--small ]" href="#">
                                <img style="width: 30px;" src="http://localhost:8888/pixan/wp-content/uploads/2016/06/facebook-pixan1.png" alt="">
                            </a>
                            <a class="[ margin-right--small ]" href="#">
                                <img style="width: 30px;" src="http://localhost:8888/pixan/wp-content/uploads/2016/06/instagram-pixan1.png" alt="">
                            </a>
                            <a class="[ margin-right--small ]" href="#">
                                <img style="width: 30px;" src="http://localhost:8888/pixan/wp-content/uploads/2016/06/twitter-pixan1.png" alt="">
                            </a>
                    </div>

                <?php
                }
                ?>
			</div>


		</div>
        <?php
        // Load core messages
        organics_enqueue_messages();
        // Load Popup engine
        organics_enqueue_popup();

        if (organics_get_theme_option('show_login')=='yes') {
            include organics_get_file_dir('templates/headers/_parts/login.php');
        }
        if ( (int) get_option('users_can_register') > 0) {
            if (organics_get_theme_option('show_login')=='yes') {
                include organics_get_file_dir('templates/headers/_parts/register.php');
            }
        } ?>
		<div class="mask"></div>
	</div>

<?php
    if ( is_user_logged_in() ) {
        ?>
        <script>jQuery('html').addClass('bar');</script>
    <?php
    }
?>