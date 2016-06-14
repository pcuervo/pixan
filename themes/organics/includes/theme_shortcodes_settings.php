<?php
/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'organics_shortcodes2_settings_theme_setup' ) ) {
//	if ( organics_vc_is_frontend() )
    if ((isset($_GET['vc_editable']) && $_GET['vc_editable'] == 'true') || (isset($_GET['vc_action']) && $_GET['vc_action'] == 'vc_inline'))
        add_action('organics_action_before_init_theme', 'organics_shortcodes2_settings_theme_setup', 21);
    else
        add_action('organics_action_after_init_theme', 'organics_shortcodes2_settings_theme_setup', 11);
    function organics_shortcodes2_settings_theme_setup()
    {
        if (organics_shortcodes_is_used()) {
            global $ORGANICS_GLOBALS;

            // Shortcodes list
            //------------------------------------------------------------------
            $ORGANICS_GLOBALS['shortcodes']['trx_image'] = array(
                    "title" => __("Image", "organics"),
                    "desc" => __("Insert image into your post (page)", "organics"),
                    "decorate" => false,
                    "container" => false,
                    "params" => array(
                        "url" => array(
                            "title" => __("URL for image file", "organics"),
                            "desc" => __("Select or upload image or write URL from other site", "organics"),
                            "readonly" => false,
                            "value" => "",
                            "type" => "media",
                            "before" => array(
                                'sizes' => true        // If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
                            )
                        ),
                        "title" => array(
                            "title" => __("Title", "organics"),
                            "desc" => __("Image title (if need)", "organics"),
                            "value" => "",
                            "type" => "text"
                        ),
                        "icon" => array(
                            "title" => __("Icon before title", 'organics'),
                            "desc" => __('Select icon for the title from Fontello icons set', 'organics'),
                            "value" => "",
                            "type" => "icons",
                            "options" => $ORGANICS_GLOBALS['sc_params']['icons']
                        ),
                        "align" => array(
                            "title" => __("Float image", "organics"),
                            "desc" => __("Float image to left or right side", "organics"),
                            "value" => "",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => $ORGANICS_GLOBALS['sc_params']['float']
                        ),
                        "shape" => array(
                            "title" => __("Image Shape", "organics"),
                            "desc" => __("Shape of the image: square (rectangle) or round", "organics"),
                            "value" => "square",
                            "type" => "checklist",
                            "dir" => "horizontal",
                            "options" => array(
                                "square" => __('Square', 'organics')
                            )
                        ),
                        "link" => array(
                            "title" => __("Link", "organics"),
                            "desc" => __("The link URL from the image", "organics"),
                            "value" => "",
                            "type" => "text"
                        ),
                        "width" => organics_shortcodes_width(),
                        "height" => organics_shortcodes_height(),
                        "top" => $ORGANICS_GLOBALS['sc_params']['top'],
                        "bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
                        "left" => $ORGANICS_GLOBALS['sc_params']['left'],
                        "right" => $ORGANICS_GLOBALS['sc_params']['right'],
                        "id" => $ORGANICS_GLOBALS['sc_params']['id'],
                        "class" => $ORGANICS_GLOBALS['sc_params']['class'],
                        "animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
                        "css" => $ORGANICS_GLOBALS['sc_params']['css']
                    )
            );


            // Button
            $ORGANICS_GLOBALS['shortcodes']['trx_button'] = array(
                "title" => __("Button", "organics"),
                "desc" => __("Button with link", "organics"),
                "decorate" => false,
                "container" => true,
                "params" => array(
                    "_content_" => array(
                        "title" => __("Caption", "organics"),
                        "desc" => __("Button caption", "organics"),
                        "value" => "",
                        "type" => "text"
                    ),
                    "type" => array(
                        "title" => __("Button's shape", "organics"),
                        "desc" => __("Select button's shape", "organics"),
                        "value" => "round",
                        "size" => "medium",
                        "options" => array(
                            'square' => __('Square', 'organics'),
                            'round' => __('Round', 'organics')
                        ),
                        "type" => "switch"
                    ),
                    "style" => array(
                        "title" => __("Button's style", "organics"),
                        "desc" => __("Select button's style", "organics"),
                        "value" => "default",
                        "dir" => "horizontal",
                        "options" => array(
                            'filled' => __('Filled', 'organics'),
                            'border' => __('Border', 'organics')
                        ),
                        "type" => "checklist"
                    ),
                    "scheme" => array(
                        "title" => __("Button's color scheme", "organics"),
                        "desc" => __("Select button's color scheme", "organics"),
                        "value" => "original",
                        "dir" => "horizontal",
                        "options" => array(
                            'original' => __('Original', 'organics'),
                            'dark' => __('Dark', 'organics'),
                            'orange' => __('Orange', 'organics'),
                            'crimson' => __('Crimson', 'organics')
                        ),
                        "type" => "checklist"
                    ),
                    "size" => array(
                        "title" => __("Button's size", "organics"),
                        "desc" => __("Select button's size", "organics"),
                        "value" => "small",
                        "dir" => "horizontal",
                        "options" => array(
                            'small' => __('Small', 'organics'),
                            'medium' => __('Medium', 'organics'),
                            'large' => __('Large', 'organics')
                        ),
                        "type" => "checklist"
                    ),
                    "icon" => array(
                        "title" => __("Button's icon",  'organics'),
                        "desc" => __('Select icon for the title from Fontello icons set',  'organics'),
                        "value" => "",
                        "type" => "icons",
                        "options" => $ORGANICS_GLOBALS['sc_params']['icons']
                    ),
                    "color" => array(
                        "title" => __("Button's text color", "organics"),
                        "desc" => __("Any color for button's caption", "organics"),
                        "std" => "",
                        "value" => "",
                        "type" => "color"
                    ),
                    "bg_color" => array(
                        "title" => __("Button's backcolor", "organics"),
                        "desc" => __("Any color for button's background", "organics"),
                        "value" => "",
                        "type" => "color"
                    ),
                    "align" => array(
                        "title" => __("Button's alignment", "organics"),
                        "desc" => __("Align button to left, center or right", "organics"),
                        "value" => "none",
                        "type" => "checklist",
                        "dir" => "horizontal",
                        "options" => $ORGANICS_GLOBALS['sc_params']['align']
                    ),
                    "link" => array(
                        "title" => __("Link URL", "organics"),
                        "desc" => __("URL for link on button click", "organics"),
                        "divider" => true,
                        "value" => "",
                        "type" => "text"
                    ),
                    "target" => array(
                        "title" => __("Link target", "organics"),
                        "desc" => __("Target for link on button click", "organics"),
                        "dependency" => array(
                            'link' => array('not_empty')
                        ),
                        "value" => "",
                        "type" => "text"
                    ),
                    "popup" => array(
                        "title" => __("Open link in popup", "organics"),
                        "desc" => __("Open link target in popup window", "organics"),
                        "dependency" => array(
                            'link' => array('not_empty')
                        ),
                        "value" => "no",
                        "type" => "switch",
                        "options" => $ORGANICS_GLOBALS['sc_params']['yes_no']
                    ),
                    "rel" => array(
                        "title" => __("Rel attribute", "organics"),
                        "desc" => __("Rel attribute for button's link (if need)", "organics"),
                        "dependency" => array(
                            'link' => array('not_empty')
                        ),
                        "value" => "",
                        "type" => "text"
                    ),
                    "width" => organics_shortcodes_width(),
                    "height" => organics_shortcodes_height(),
                    "top" => $ORGANICS_GLOBALS['sc_params']['top'],
                    "bottom" => $ORGANICS_GLOBALS['sc_params']['bottom'],
                    "left" => $ORGANICS_GLOBALS['sc_params']['left'],
                    "right" => $ORGANICS_GLOBALS['sc_params']['right'],
                    "id" => $ORGANICS_GLOBALS['sc_params']['id'],
                    "class" => $ORGANICS_GLOBALS['sc_params']['class'],
                    "animation" => $ORGANICS_GLOBALS['sc_params']['animation'],
                    "css" => $ORGANICS_GLOBALS['sc_params']['css']
                )
            );


            // Axiomthemes - Recent Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_recent_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Recent Products", "organics"),
                "desc" => esc_html__("WooCommerce shortcode: show recent products", "organics"),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", "organics"),
                        "desc" => esc_html__("How many products showed", "organics"),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", "organics"),
                        "desc" => esc_html__("How many columns per row use for products output", "organics"),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'organics'),
                            "title" => esc_html__('Title', 'organics')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );


            // Axiomthemes - Featured Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_featured_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Featured Products", "organics"),
                "desc" => esc_html__("WooCommerce shortcode: show featured products", "organics"),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", "organics"),
                        "desc" => esc_html__("How many products showed", "organics"),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", "organics"),
                        "desc" => esc_html__("How many columns per row use for products output", "organics"),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'organics'),
                            "title" => esc_html__('Title', 'organics')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );

            // Axiomthemes - Best Selling Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_best_selling_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Best Selling Products", "organics"),
                "desc" => esc_html__("WooCommerce shortcode: show best selling products", "organics"),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", "organics"),
                        "desc" => esc_html__("How many products showed", "organics"),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", "organics"),
                        "desc" => esc_html__("How many columns per row use for products output", "organics"),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                )
            );

            // Axiomthemes - Sale Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_sale_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Sale Products", "organics"),
                "desc" => esc_html__("WooCommerce shortcode: show sale products", "organics"),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", "organics"),
                        "desc" => esc_html__("How many products showed", "organics"),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", "organics"),
                        "desc" => esc_html__("How many columns per row use for products output", "organics"),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'organics'),
                            "title" => esc_html__('Title', 'organics')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );

            // Axiomthemes - Top Rated Products
            $ORGANICS_GLOBALS['shortcodes']["trx_axiomthemes_top_rated_products"] = array(
                "title" => esc_html__("Axiomthemes Slider Top Rated Products", "organics"),
                "desc" => esc_html__("WooCommerce shortcode: show top rated products", "organics"),
                "decorate" => false,
                "container" => false,
                "params" => array(
                    "posts_per_page" => array(
                        "title" => esc_html__("Number", "organics"),
                        "desc" => esc_html__("How many products showed", "organics"),
                        "value" => 4,
                        "min" => 1,
                        "type" => "spinner"
                    ),
                    "columns" => array(
                        "title" => esc_html__("Columns", "organics"),
                        "desc" => esc_html__("How many columns per row use for products output", "organics"),
                        "value" => 4,
                        "min" => 2,
                        "max" => 4,
                        "type" => "spinner"
                    ),
                    "orderby" => array(
                        "title" => esc_html__("Order by", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "date",
                        "type" => "select",
                        "options" => array(
                            "date" => __('Date', 'organics'),
                            "title" => esc_html__('Title', 'organics')
                        )
                    ),
                    "order" => array(
                        "title" => esc_html__("Order", "organics"),
                        "desc" => esc_html__("Sorting order for products output", "organics"),
                        "value" => "desc",
                        "type" => "switch",
                        "size" => "big",
                        "options" => $ORGANICS_GLOBALS['sc_params']['ordering']
                    )
                )
            );
        }
    }
}
?>