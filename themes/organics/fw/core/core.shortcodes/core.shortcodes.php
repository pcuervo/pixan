<?php
/**
 * AxiomThemes Framework: shortcodes manipulations
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('organics_sc_theme_setup')) {
	add_action( 'organics_action_init_theme', 'organics_sc_theme_setup', 1 );
	function organics_sc_theme_setup() {
		// Add sc stylesheets
		add_action('organics_action_add_styles', 'organics_sc_add_styles', 1);
	}
}

if (!function_exists('organics_sc_theme_setup2')) {
	add_action( 'organics_action_before_init_theme', 'organics_sc_theme_setup2' );
	function organics_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'organics_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('organics_sc_prepare_content')) organics_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('organics_shortcode_output', 'organics_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'organics_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'organics_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'organics_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes styles
if ( !function_exists( 'organics_sc_add_styles' ) ) {
	//add_action('organics_action_add_styles', 'organics_sc_add_styles', 1);
	function organics_sc_add_styles() {
		// Shortcodes
		organics_enqueue_style( 'organics-shortcodes-style',	organics_get_file_url('core/core.shortcodes/shortcodes.css'), array(), null );
	}
}


// Add shortcodes init scripts
if ( !function_exists( 'organics_sc_add_scripts' ) ) {
	//add_filter('organics_shortcode_output', 'organics_sc_add_scripts', 10, 4);
	function organics_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $ORGANICS_GLOBALS;
		
		if (empty($ORGANICS_GLOBALS['shortcodes_scripts_added'])) {
			$ORGANICS_GLOBALS['shortcodes_scripts_added'] = true;
			//organics_enqueue_style( 'organics-shortcodes-style', organics_get_file_url('core/core.shortcodes/shortcodes.css'), array(), null );
			organics_enqueue_script( 'organics-shortcodes-script', organics_get_file_url('core/core.shortcodes/shortcodes.js'), array('jquery'), null, true );
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('organics_sc_prepare_content')) {
	function organics_sc_prepare_content() {
		if (function_exists('organics_sc_clear_around')) {
			$filters = array(
				array('axiomthemes', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
            if (function_exists('organics_exists_woocommerce') && organics_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'organics_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('organics_sc_excerpt_shortcodes')) {
	function organics_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('organics_sc_clear_around')) {
	function organics_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// Organics shortcodes load scripts
if (!function_exists('organics_sc_load_scripts')) {
	function organics_sc_load_scripts() {
		organics_enqueue_script( 'organics-shortcodes-script', organics_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		organics_enqueue_script( 'organics-selection-script',  organics_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// Organics shortcodes prepare scripts
if (!function_exists('organics_sc_prepare_scripts')) {
	function organics_sc_prepare_scripts() {
		global $ORGANICS_GLOBALS;
		if (!isset($ORGANICS_GLOBALS['shortcodes_prepared'])) {
			$ORGANICS_GLOBALS['shortcodes_prepared'] = true;
			$json_parse_func = 'eval';	// 'JSON.parse'
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					try {
						ORGANICS_GLOBALS['shortcodes'] = <?php echo trim($json_parse_func); ?>(<?php echo json_encode( organics_array_prepare_to_json($ORGANICS_GLOBALS['shortcodes']) ); ?>);
					} catch (e) {}
					ORGANICS_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? (!empty($ORGANICS_GLOBALS['to_colorpicker']) ? $ORGANICS_GLOBALS['to_colorpicker'] : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('organics_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','organics_sc_selector_add_in_toolbar', 11);
	function organics_sc_selector_add_in_toolbar(){

		if ( !organics_options_is_used() ) return;

		organics_sc_load_scripts();
		organics_sc_prepare_scripts();

		global $ORGANICS_GLOBALS;

		$shortcodes = $ORGANICS_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'organics').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo ($shortcodes_list);
	}
}

// Organics shortcodes builder settings
require_once organics_get_file_dir('core/core.shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once organics_get_file_dir('core/core.shortcodes/shortcodes_vc.php');
}

// Organics shortcodes implementation
require_once organics_get_file_dir('core/core.shortcodes/shortcodes.php');
?>