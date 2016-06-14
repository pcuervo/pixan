<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('organics_revslider_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_revslider_theme_setup' );
	function organics_revslider_theme_setup() {
		if (organics_exists_revslider()) {
			add_filter( 'organics_filter_list_sliders',					'organics_revslider_list_sliders' );
			if (is_admin()) {
				add_filter( 'organics_filter_importer_options',			'organics_revslider_importer_set_options' );
				add_action( 'organics_action_importer_params',			'organics_revslider_importer_show_params', 10, 1 );
				add_action( 'organics_action_importer_clear_tables',	'organics_revslider_importer_clear_tables', 10, 1 );
				add_action( 'organics_action_importer_import',			'organics_revslider_importer_import', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'organics_filter_importer_required_plugins',	'organics_revslider_importer_required_plugins', 10, 2 );
			add_filter( 'organics_filter_required_plugins',				'organics_revslider_required_plugins' );
		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'organics_exists_revslider' ) ) {
	function organics_exists_revslider() {
		return function_exists('rev_slider_shortcode');
		//return class_exists('RevSliderFront');
		//return is_plugin_active('revslider/revslider.php');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'organics_revslider_required_plugins' ) ) {
	//add_filter('organics_filter_required_plugins',	'organics_revslider_required_plugins');
	function organics_revslider_required_plugins($list=array()) {
		$list[] = array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
					'source'	=> organics_get_file_dir('plugins/install/revslider.zip'),
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check RevSlider in the required plugins
if ( !function_exists( 'organics_revslider_importer_required_plugins' ) ) {
	//add_filter( 'organics_filter_importer_required_plugins',	'organics_revslider_importer_required_plugins', 10, 2 );
	function organics_revslider_importer_required_plugins($not_installed='', $importer=null) {
		if ($importer && in_array('revslider', $importer->options['required_plugins']) && !organics_exists_revslider() )
			$not_installed .= '<br>Revolution Slider';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'organics_revslider_importer_set_options' ) ) {
	//add_filter( 'organics_filter_importer_options',	'organics_revslider_importer_set_options', 10, 1 );
	function organics_revslider_importer_set_options($options=array()) {
		if (is_array($options)) {
			$options['folder_with_revsliders'] = 'demo/revslider';			// Name of the folder with Revolution slider data
			$options['import_sliders'] = true;								// Import Revolution Sliders
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'organics_revslider_importer_show_params' ) ) {
	//add_action( 'organics_action_importer_params',	'organics_revslider_importer_show_params', 10, 1 );
	function organics_revslider_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('revslider', $importer->options['required_plugins']) ? 'checked="checked"' : ''; ?> value="1" name="importer_revslider" id="importer_revslider" /> <label for="importer_revslider"><?php esc_html_e('Import Revolution Sliders', 'organics'); ?></label><br>
		<?php
	}
}

// Clear tables
if ( !function_exists( 'organics_revslider_importer_clear_tables' ) ) {
	//add_action( 'organics_action_importer_clear_tables',	'organics_revslider_importer_clear_tables', 10, 1 );
	function organics_revslider_importer_clear_tables($importer) {
		if (isset($_POST['importer_revslider'])) {
			global $wpdb;
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_sliders");
			if ( is_wp_error( $res ) ) esc_html_e( 'Failed truncate table "revslider_sliders".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_slides");
			if ( is_wp_error( $res ) ) esc_html_e( 'Failed truncate table "revslider_slides".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_static_slides");
			if ( is_wp_error( $res ) ) esc_html_e( 'Failed truncate table "revslider_static_slides".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
		}
	}
}

// Import posts
if ( !function_exists( 'organics_revslider_importer_import' ) ) {
	//add_action( 'organics_action_importer_import',	'organics_revslider_importer_import', 10, 1 );
	function organics_revslider_importer_import($importer) {
		if (isset($_POST['importer_revslider'])) {
			if (file_exists(WP_PLUGIN_DIR.'/revslider/revslider.php')) {
				require_once WP_PLUGIN_DIR.'/revslider/revslider.php';
				$dir = organics_get_folder_dir($importer->options['folder_with_revsliders']);
				if ( is_dir($dir) ) {
					$hdir = @opendir( $dir );
					if ( $hdir ) {
						echo '<script>'
							. 'document.getElementById("import_progress_status").innerHTML = "' . esc_html__('Import Revolution sliders ...', 'organics') .'";'
							. '</script>';
						echo '<br><b>'.esc_html__('Import Revolution sliders ...', 'organics').'</b><br>'; flush();
						$slider = new RevSlider();
						$counter = 0;
						while (($file = readdir( $hdir ) ) !== false ) {
							$counter++;
							if ($counter <= $importer->last_slider) continue;
							$pi = pathinfo( ($dir) . '/' . ($file) );
							if ( substr($file, 0, 1) == '.' || is_dir( ($dir) . '/' . ($file) ) || $pi['extension']!='zip' )
								continue;
if ($importer->options['debug']) printf(esc_html__('Slider "%s":', 'organics'), $file);
							if (!is_array($_FILES)) $_FILES = array();
							$_FILES["import_file"] = array("tmp_name" => ($dir) . '/' . ($file));
							$response = $slider->importSliderFromPost();
							if ($response["success"] == false) { 
if ($importer->options['debug']) echo ' '.esc_html__('import error:', 'organics').'<br>'.organics_debug_dump_var($response);
							} else {
if ($importer->options['debug']) echo ' '.esc_html__('imported', 'organics').'<br>';
							}
							flush();
							break;
						}
						@closedir( $hdir );
						// Write last slider into log
						organics_fpc($importer->import_log, $file ? '0|100|'.intval($counter) : '');
						$importer->last_slider = $file ? $counter : 0;
					}
				}
			} else {
if ($importer->options['debug']) { printf(esc_html__('Can not locate Revo plugin: %s', 'organics'), WP_PLUGIN_DIR.'/revslider/revslider.php<br>'); flush(); }
			}
		}
	}
}


// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'organics_revslider_list_sliders' ) ) {
	//add_filter( 'organics_filter_list_sliders',					'organics_revslider_list_sliders' );
	function organics_revslider_list_sliders($list=array()) {
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'organics');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'organics_get_list_revo_sliders' ) ) {
	function organics_get_list_revo_sliders($prepend_inherit=false) {
		global $ORGANICS_GLOBALS;
		if (isset($ORGANICS_GLOBALS['list_revo_sliders']))
			$list = $ORGANICS_GLOBALS['list_revo_sliders'];
		else {
			$list = array();
			if (organics_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$ORGANICS_GLOBALS['list_revo_sliders'] = $list = apply_filters('organics_filter_list_revo_sliders', $list);
		}
		return $prepend_inherit ? organics_array_merge(array('inherit' => esc_html__("Inherit", 'organics')), $list) : $list;
	}
}
?>