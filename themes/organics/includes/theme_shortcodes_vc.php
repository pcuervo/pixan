<?php
if ( !function_exists( 'organics_shortcodes2_vc_theme_setup' ) ) {

    //if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
	//{ add_action( 'organics_action_before_init_theme', 'organics_shortcodes2_vc_theme_setup', 21 ); }
	
	if (function_exists('themerex_exists_visual_composer') && themerex_exists_visual_composer())
			add_action('organics_action_before_init_theme','organics_shortcodes2_vc_theme_setup');
    

    function organics_shortcodes2_vc_theme_setup() {

        if (organics_shortcodes_is_used()) {

            // Remove standard VC shortcodes
            //vc_remove_element("trx_image");
            //vc_remove_element("trx_button");

            global $ORGANICS_GLOBALS;

            // Image
            //-------------------------------------------------------------------------------------

            vc_map(array(
                "base" => "trx_image",
                "name" => __("Image", "organics"),
                "description" => __("Insert image", "organics"),
                "category" => __('Content', 'js_composer'),
                'icon' => 'icon_trx_image',
                "class" => "trx_sc_single trx_sc_image",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "url",
                        "heading" => __("Select image", "organics"),
                        "description" => __("Select image from library", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "",
                        "type" => "attach_image"
                    ),
                    array(
                        "param_name" => "align",
                        "heading" => __("Image alignment", "organics"),
                        "description" => __("Align image to left or right side", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['float']),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "shape",
                        "heading" => __("Image shape", "organics"),
                        "description" => __("Shape of the image: square or round", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Square', 'organics') => 'square'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "title",
                        "heading" => __("Title", "organics"),
                        "description" => __("Image's title", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "icon",
                        "heading" => __("Title's icon", "organics"),
                        "description" => __("Select icon for the title from Fontello icons set", "organics"),
                        "class" => "",
                        "value" => $ORGANICS_GLOBALS['sc_params']['icons'],
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "link",
                        "heading" => __("Link", "organics"),
                        "description" => __("The link URL from the image", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    $ORGANICS_GLOBALS['vc_params']['id'],
                    $ORGANICS_GLOBALS['vc_params']['class'],
                    $ORGANICS_GLOBALS['vc_params']['animation'],
                    $ORGANICS_GLOBALS['vc_params']['css'],
                    organics_vc_width(),
                    organics_vc_height(),
                    $ORGANICS_GLOBALS['vc_params']['margin_top'],
                    $ORGANICS_GLOBALS['vc_params']['margin_bottom'],
                    $ORGANICS_GLOBALS['vc_params']['margin_left'],
                    $ORGANICS_GLOBALS['vc_params']['margin_right']
                )
            ));









            // Axiomthemes - Recent Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_recent_products",
                "name" => esc_html__("Axiomthemes Slider Recent Products", "organics"),
                "description" => esc_html__("WooCommerce shortcode: show recent products", "organics"),
                "category" => esc_html__('WooCommerce', 'js_composer'),
                'icon' => 'icon_trx_recent_products',
                "class" => "trx_sc_single trx_sc_recent_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", "organics"),
                        "description" => esc_html__("How many products showed", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", "organics"),
                        "description" => esc_html__("How many columns per row use for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'organics') => 'date',
                            __('Title', 'organics') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Recent_Products extends ORGANICS_VC_ShortCodeSingle {}



            // WooCommerce - Featured Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_featured_products",
                "name" => esc_html__("Axiomthemes Slider Featured Products", "organics"),
                "description" => esc_html__("WooCommerce shortcode: show featured products", "organics"),
                "category" => esc_html__('WooCommerce', 'js_composer'),
                'icon' => 'icon_trx_featured_products',
                "class" => "trx_sc_single trx_sc_featured_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", "organics"),
                        "description" => esc_html__("How many products showed", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", "organics"),
                        "description" => esc_html__("How many columns per row use for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'organics') => 'date',
                            __('Title', 'organics') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Featured_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Axiomthemes - Best Selling Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_best_selling_products",
                "name" => esc_html__("Axiomthemes Slider Best Selling Products", "organics"),
                "description" => esc_html__("WooCommerce shortcode: show best selling products", "organics"),
                "category" => esc_html__('WooCommerce', 'js_composer'),
                'icon' => 'icon_trx_best_selling_products',
                "class" => "trx_sc_single trx_sc_best_selling_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", "organics"),
                        "description" => esc_html__("How many products showed", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", "organics"),
                        "description" => esc_html__("How many columns per row use for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Best_Selling_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Axiomthemes - Sale Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_sale_products",
                "name" => esc_html__("Axiomthemes Slider Sale Products", "organics"),
                "description" => esc_html__("WooCommerce shortcode: show sale products", "organics"),
                "category" => esc_html__('WooCommerce', 'js_composer'),
                'icon' => 'icon_trx_sale_products',
                "class" => "trx_sc_single trx_sc_sale_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", "organics"),
                        "description" => esc_html__("How many products showed", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", "organics"),
                        "description" => esc_html__("How many columns per row use for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'organics') => 'date',
                            __('Title', 'organics') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Sale_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Axiomthemes - Top Rated Products
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_axiomthemes_top_rated_products",
                "name" => esc_html__("Axiomthemes Slider Top Rated Products", "organics"),
                "description" => esc_html__("WooCommerce shortcode: show top rated products", "organics"),
                "category" => esc_html__('WooCommerce', 'js_composer'),
                'icon' => 'icon_trx_top_rated_products',
                "class" => "trx_sc_single trx_sc_top_rated_products",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "per_page",
                        "heading" => esc_html__("Number", "organics"),
                        "description" => esc_html__("How many products showed", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "4",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "columns",
                        "heading" => esc_html__("Columns", "organics"),
                        "description" => esc_html__("How many columns per row use for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => "1",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "orderby",
                        "heading" => esc_html__("Order by", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Date', 'organics') => 'date',
                            __('Title', 'organics') => 'title'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "order",
                        "heading" => esc_html__("Order", "organics"),
                        "description" => esc_html__("Sorting order for products output", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['ordering']),
                        "type" => "dropdown"
                    )
                )
            ) );

            class WPBakeryShortCode_Trx_AxiomThemes_Top_Rated_Products extends ORGANICS_VC_ShortCodeSingle {}



            // Button
            //-------------------------------------------------------------------------------------

            vc_map( array(
                "base" => "trx_button",
                "name" => __("Button", "organics"),
                "description" => __("Button with link", "organics"),
                "category" => __('Content', 'js_composer'),
                'icon' => 'icon_trx_button',
                "class" => "trx_sc_single trx_sc_button",
                "content_element" => true,
                "is_container" => false,
                "show_settings_on_create" => true,
                "params" => array(
                    array(
                        "param_name" => "content",
                        "heading" => __("Caption", "organics"),
                        "description" => __("Button caption", "organics"),
                        "class" => "",
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "type",
                        "heading" => __("Button's shape", "organics"),
                        "description" => __("Select button's shape", "organics"),
                        "class" => "",
                        "value" => array(
                            __('Round', 'organics') => 'round',
                            __('Square', 'organics') => 'square'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "style",
                        "heading" => __("Button's style", "organics"),
                        "description" => __("Select button's style", "organics"),
                        "class" => "",
                        "value" => array(
                            __('Filled', 'organics') => 'filled',
                            __('Border', 'organics') => 'border'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "scheme",
                        "heading" => __("Button's color scheme", "organics"),
                        "description" => __("Select button's color scheme", "organics"),
                        "class" => "",
                        "value" => array(
                            __('Original', 'organics') => 'original',
                            __('Dark', 'organics') => 'dark',
                            __('Orange', 'organics') => 'orange',
                            __('Crimson', 'organics') => 'crimson'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "size",
                        "heading" => __("Button's size", "organics"),
                        "description" => __("Select button's size", "organics"),
                        "admin_label" => true,
                        "class" => "",
                        "value" => array(
                            __('Small', 'organics') => 'small',
                            __('Medium', 'organics') => 'medium',
                            __('Large', 'organics') => 'large'
                        ),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "icon",
                        "heading" => __("Button's icon", "organics"),
                        "description" => __("Select icon for the title from Fontello icons set", "organics"),
                        "class" => "",
                        "value" => $ORGANICS_GLOBALS['sc_params']['icons'],
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "color",
                        "heading" => __("Button's text color", "organics"),
                        "description" => __("Any color for button's caption", "organics"),
                        "class" => "",
                        "value" => "",
                        "type" => "colorpicker"
                    ),
                    array(
                        "param_name" => "bg_color",
                        "heading" => __("Button's backcolor", "organics"),
                        "description" => __("Any color for button's background", "organics"),
                        "class" => "",
                        "value" => "",
                        "type" => "colorpicker"
                    ),
                    array(
                        "param_name" => "align",
                        "heading" => __("Button's alignment", "organics"),
                        "description" => __("Align button to left, center or right", "organics"),
                        "class" => "",
                        "value" => array_flip($ORGANICS_GLOBALS['sc_params']['align']),
                        "type" => "dropdown"
                    ),
                    array(
                        "param_name" => "link",
                        "heading" => __("Link URL", "organics"),
                        "description" => __("URL for the link on button click", "organics"),
                        "class" => "",
                        "group" => __('Link', 'organics'),
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "target",
                        "heading" => __("Link target", "organics"),
                        "description" => __("Target for the link on button click", "organics"),
                        "class" => "",
                        "group" => __('Link', 'organics'),
                        "value" => "",
                        "type" => "textfield"
                    ),
                    array(
                        "param_name" => "popup",
                        "heading" => __("Open link in popup", "organics"),
                        "description" => __("Open link target in popup window", "organics"),
                        "class" => "",
                        "group" => __('Link', 'organics'),
                        "value" => array(__('Open in popup', 'organics') => 'yes'),
                        "type" => "checkbox"
                    ),
                    array(
                        "param_name" => "rel",
                        "heading" => __("Rel attribute", "organics"),
                        "description" => __("Rel attribute for the button's link (if need", "organics"),
                        "class" => "",
                        "group" => __('Link', 'organics'),
                        "value" => "",
                        "type" => "textfield"
                    ),
                    $ORGANICS_GLOBALS['vc_params']['id'],
                    $ORGANICS_GLOBALS['vc_params']['class'],
                    $ORGANICS_GLOBALS['vc_params']['animation'],
                    $ORGANICS_GLOBALS['vc_params']['css'],
                    organics_vc_width(),
                    organics_vc_height(),
                    $ORGANICS_GLOBALS['vc_params']['margin_top'],
                    $ORGANICS_GLOBALS['vc_params']['margin_bottom'],
                    $ORGANICS_GLOBALS['vc_params']['margin_left'],
                    $ORGANICS_GLOBALS['vc_params']['margin_right']
                ),
                'js_view' => 'VcTrxTextView'
            ) );


        }
    }
}
?>