<?php
// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Theme init
if (!function_exists('organics_importer_theme_setup')) {
	add_action( 'organics_action_after_init_theme', 'organics_importer_theme_setup' );		// Fire this action after load theme options
	function organics_importer_theme_setup() {
		if (is_admin() && current_user_can('import') && organics_get_theme_option('admin_dummy_data')=='yes') {
			new organics_dummy_data_importer();
		}
	}
}

class organics_dummy_data_importer {

	// Theme specific settings
	var $options = array(
		'debug'					=> true,						// Enable debug output
		'enable_importer'		=> true,						// Show Importer section
		'enable_exporter'		=> true,						// Show Exporter section
		'data_type'				=> 'vc',						// Default dummy data type
		'file_with_content'		=> array(
			'no_vc'				=> 'demo/dummy_data.xml',		// Name of the file with demo content without VC wrappers
			'vc'				=> 'demo/dummy_data_vc.xml'		// Name of the file with demo content for Visual Composer
			),
		'file_with_mods'		=> 'demo/theme_mods.txt',		// Name of the file with theme mods
		'file_with_options'		=> 'demo/theme_options.txt',	// Name of the file with theme options
		'file_with_widgets'		=> 'demo/widgets.txt',			// Name of the file with widgets data
		'domain_dev'			=> '',							// Domain on developer's server. 								MUST BE SET IN THEME!
		'domain_demo'			=> '',							// Domain on demo-server.										MUST BE SET IN THEME!
		'uploads_folder'		=> 'imports',					// Folder with images on demo server
		'upload_attachments'	=> true,						// Upload attachments images
		'import_posts'			=> true,						// Import posts
		'import_tm'				=> true,						// Import Theme Mods
		'import_to'				=> true,						// Import Theme Options
		'import_widgets'		=> true,						// Import widgets
		'overwrite_content'		=> true,						// Overwrite existing content
		'taxonomies'			=> array(),						// List of required taxonomies: 'post_type' => 'taxonomy', ...	MUST BE SET OR CHANGED IN THEME!
		'required_plugins'		=> array(						// Required plugins slugs. 										MUST BE SET OR CHANGED IN THEME!
			'organics_utils'
		),
		'additional_options'	=> array(						// Additional options slugs (for export plugins settings).		MUST BE SET OR CHANGED IN THEME!
			// WP
			'blogname',
			'blogdescription',
			'posts_per_page',
			'show_on_front',
			'page_on_front',
			'page_for_posts'
		)
	);

	var $error    = '';				// Error message
	var $success  = '';				// Success message
	var $result   = 0;				// Import posts percent (if break inside)
	
	var $last_slider = 0;			// Last imported slider number. 															MUST BE SET OR CHANGED IN THEME!

	var $nonce = '';
	var $export_mods = '';
	var $export_options = '';
	var $export_templates = '';
	var $export_widgets = '';
	var $uploads_url = '';
	var $uploads_dir = '';
	var $import_log = '';
	var $import_last_id = 0;

	//-----------------------------------------------------------------------------------
	// Constuctor
	//-----------------------------------------------------------------------------------
	function __construct() {
	    $this->options = apply_filters('organics_filter_importer_options', $this->options);
		$this->nonce = wp_create_nonce(get_admin_url());
		$uploads_info = wp_upload_dir();
		$this->uploads_dir = $uploads_info['basedir'];
		$this->uploads_url = $uploads_info['baseurl'];
		if ($this->options['debug']) define('IMPORT_DEBUG', true);
		$this->import_log = organics_get_file_dir('core/core.importer/importer.log');
		$log = explode('|', organics_fgc($this->import_log));
		$this->import_last_id = (int) $log[0];
		$this->result = empty($log[1]) ? 0 : (int) $log[1];
		$this->last_slider = empty($log[2]) ? '' : $log[2];
		add_action('admin_menu', array($this, 'admin_menu_item'));
	}

	//-----------------------------------------------------------------------------------
	// Admin Interface
	//-----------------------------------------------------------------------------------
	function admin_menu_item() {
		if ( current_user_can( 'manage_options' ) ) {
			// Add in admin menu 'Theme Options'
			organics_admin_add_menu_item('submenu', array(
				'parent' => 'organics_options',
				'page_title' => esc_html__('Install Dummy Data', 'organics'),
				'menu_title' => esc_html__('Install Dummy Data', 'organics'),
				'capability' => 'manage_options',
				'menu_slug'  => 'trx_importer',
				'callback'   => array($this, 'build_page'),
				'icon'		 => ''
				)
			);
		}
	}
	
	
	//-----------------------------------------------------------------------------------
	// Build the Main Page
	//-----------------------------------------------------------------------------------
	function build_page() {
		
		$after_importer = false;

		do {
			if ( isset($_POST['importer_action']) ) {
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], get_admin_url() ) ) {
					$this->error = esc_html__('Incorrect WP-nonce data! Operation canceled!', 'organics');
					break;
				}
				if ($this->check_required_plugins()) {
					$this->options['overwrite_content']	= $_POST['importer_action']=='overwrite';
					$this->options['data_type'] 		= $_POST['data_type']=='vc' ? 'vc' : 'no_vc';
					$this->options['upload_attachments']= isset($_POST['importer_upload']);
					$this->options['import_posts']		= isset($_POST['importer_posts']);
					$this->options['import_tm']			= isset($_POST['importer_tm']);
					$this->options['import_to']			= isset($_POST['importer_to']);
					$this->options['import_widgets']	= isset($_POST['importer_widgets']);
					$this->import_last_id = (int) $_POST['last_id'];
					?>
					<div class="trx_importer_log">
						<?php
						$this->importer();
						$after_importer = true;
						?>
						<script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('.trx_importer_log').remove();
								<?php if ($this->import_last_id > 0 || (!empty($this->last_slider) && isset($_POST['importer_revslider']))) { ?>
								setTimeout(function() {
									jQuery('#trx_importer_continue').trigger('click');
								}, 3000);
								<?php } ?>
							});
						</script>
					</div>
					<?php
				}
			} else if ( isset($_POST['exporter_action']) ) {
				if ( !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], get_admin_url() ) ) {
					$this->error = esc_html__('Incorrect WP-nonce data! Operation canceled!', 'organics');
					break;
				}
				$this->exporter();
			}
		} while (false);
		?>
		<div class="trx_importer">
			<div class="trx_importer_result">
				<?php if (!empty($this->error)) { ?>
				<p>&nbsp;</p>
				<div class="error">
					<p><?php echo ($this->error); ?></p>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
				<?php if (!empty($this->success)) { ?>
				<p>&nbsp;</p>
				<div class="updated">
					<p><?php echo ($this->success); ?></p>
				</div>
				<p>&nbsp;</p>
				<?php } ?>
			</div>
	
			<?php if (empty($this->success) && $this->options['enable_importer']) { ?>
				<div class="trx_importer_section"<?php echo ($after_importer ? ' style="display:none;"' : ''); ?>>
					<h2 class="trx_title"><?php _e('Organics Importer', 'organics'); ?></h2>
					<p><b><?php _e('Attention! Important info:', 'organics'); ?></b></p>
					<ol>
						<li><?php esc_html_e('Data import can take a long time (sometimes more than 10 minutes) - please wait until the end of the procedure, do not navigate away from the page.', 'organics'); ?></li>
						<li><?php esc_html_e('Web-servers set the time limit for the execution of php-scripts. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically!', 'organics'); ?></li>
						<li><?php esc_html_e('We recommend that you select the first option to import (with the replacement of existing content) - so you get a complete copy of our demo site', 'organics'); ?></li>
						<li><?php esc_html_e('We also encourage you to leave the enabled check box "Upload attachments" - to download the demo version of the images', 'organics'); ?></li>
					</ol>
	
					<form id="trx_importer_form" action="#" method="post">
	
						<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />
						<input type="hidden" value="0" name="last_id" />
	
						<p>
						<input type="radio" <?php echo ($this->options['overwrite_content'] ? 'checked="checked"' : ''); ?> value="overwrite" name="importer_action" id="importer_action_over" /><label for="importer_action_over"><?php esc_html_e('Overwrite existing content', 'organics'); ?></label><br>
						<?php esc_html_e('In this case <b>all existing content will be erased</b>! But you get full copy of the our demo site <b>(recommended)</b>.', 'organics'); ?>
						</p>
	
						<p>
						<input type="radio" <?php echo !$this->options['overwrite_content'] ? 'checked="checked"' : ''; ?> value="append" name="importer_action" id="importer_action_append" /><label for="importer_action_append"><?php esc_html_e('Append to existing content', 'organics'); ?></label><br>
						<?php esc_html_e('In this case demo data append to the existing content! Warning! You do not have exact copy of the demo site.', 'organics'); ?>
						</p>
	
						<p><b><?php esc_html_e('Select the data to import:', 'organics'); ?></b></p>
						<p>
						<?php
						$checked = 'checked="checked"';
						if (!empty($this->options['file_with_content']['vc']) && file_exists(organics_get_file_dir($this->options['file_with_content']['vc']))) {
							?>
							<input type="radio" <?php echo ($this->options['data_type']=='vc' ? $checked : ''); ?> value="vc" name="data_type" id="data_type_vc" /><label for="data_type_vc"><?php esc_html_e('Import data for edit in the Visual Composer', 'organics'); ?></label><br>
							<?php
							if ($this->options['data_type']=='vc') $checked = '';
						}
						if (!empty($this->options['file_with_content']['no_vc']) && file_exists(organics_get_file_dir($this->options['file_with_content']['no_vc']))) {
							?>
							<input type="radio" <?php echo ($this->options['data_type']=='no_vc' || $checked ? $checked : ''); ?> value="no_vc" name="data_type" id="data_type_no_vc" /><label for="data_type_no_vc"><?php esc_html_e('Import data without Visual Composer wrappers', 'organics'); ?></label>
							<?php
						}
						?>
						</p>
						<p>
						<input type="checkbox" <?php echo ($this->options['import_posts'] ? 'checked="checked"' : ''); ?> value="1" name="importer_posts" id="importer_posts" /> <label for="importer_posts"><?php esc_html_e('Import posts', 'organics'); ?></label><br>
						<input type="checkbox" <?php echo ($this->options['upload_attachments'] ? 'checked="checked"' : ''); ?> value="1" name="importer_upload" id="importer_upload" /> <label for="importer_upload"><?php esc_html_e('Upload attachments', 'organics'); ?></label>
						</p>
						<p>
						<input type="checkbox" <?php echo ($this->options['import_tm'] ? 'checked="checked"' : ''); ?> value="1" name="importer_tm" id="importer_tm" /> <label for="importer_tm"><?php esc_html_e('Import Theme Mods', 'organics'); ?></label><br>
						<input type="checkbox" <?php echo ($this->options['import_to'] ? 'checked="checked"' : ''); ?> value="1" name="importer_to" id="importer_to" /> <label for="importer_to"><?php esc_html_e('Import Theme Options', 'organics'); ?></label><br>
						<input type="checkbox" <?php echo ($this->options['import_widgets'] ? 'checked="checked"' : ''); ?> value="1" name="importer_widgets" id="importer_widgets" /> <label for="importer_widgets"><?php esc_html_e('Import Widgets', 'organics'); ?></label><br><br>

						<?php do_action('organics_action_importer_params', $this); ?>

						</p>
						<div class="trx_buttons">
							<?php if ($this->import_last_id > 0 || (!empty($this->last_slider) && isset($_POST['importer_revslider']))) { ?>
								<h4 class="trx_importer_complete"><?php sprintf(esc_html__('Import posts completed by %s', 'organics'), $this->result.'%'); ?></h4>
								<input type="submit" value="<?php printf(esc_html__('Continue import (from ID=%s)', 'organics'), $this->import_last_id); ?>" onClick="this.form.last_id.value='<?php echo esc_attr($this->import_last_id); ?>'" id="trx_importer_continue">
								<input type="submit" value="<?php esc_html_e('Start import again', 'organics'); ?>">
							<?php } else { ?>
								<input type="submit" value="<?php esc_html_e('Start import', 'organics'); ?>">
							<?php } ?>
						</div>
					</form>
				</div>
			<?php } ?>

			<?php if (empty($this->success) && $this->options['enable_exporter']) { ?>
				<div class="trx_exporter_section"<?php echo ($after_importer ? ' style="display:none;"' : ''); ?>>
					<h2 class="trx_title"><?php esc_html_e('Organics Exporter', 'organics'); ?></h2>
					<form id="trx_exporter_form" action="#" method="post">
	
						<input type="hidden" value="<?php echo esc_attr($this->nonce); ?>" name="nonce" />
						<input type="hidden" value="all" name="exporter_action" />
	
						<div class="trx_buttons">
							<?php if ($this->export_options!='') { ?>

								<table border="0" cellpadding="6">
								<tr>
									<th align="left"><?php esc_html_e('Theme Mods', 'organics'); ?></th>
									<td><?php organics_fpc(organics_get_file_dir('core/core.importer/export/theme_mods.txt'), $this->export_mods); ?>
										<a download="theme_mods.txt" href="<?php echo esc_url(organics_get_file_url('core/core.importer/export/theme_mods.txt')); ?>"><?php esc_html_e('Download', 'organics'); ?></a>
									</td>
								</tr>
								<tr>
									<th align="left"><?php esc_html_e('Theme Options', 'organics'); ?></th>
									<td><?php organics_fpc(organics_get_file_dir('core/core.importer/export/theme_options.txt'), $this->export_options); ?>
										<a download="theme_options.txt" href="<?php echo esc_url(organics_get_file_url('core/core.importer/export/theme_options.txt')); ?>"><?php esc_html_e('Download', 'organics'); ?></a>
									</td>
								</tr>
								<tr>
									<th align="left"><?php esc_html_e('Templates Options', 'organics'); ?></th>
									<td><?php organics_fpc(organics_get_file_dir('core/core.importer/export/templates_options.txt'), $this->export_templates); ?>
										<a download="templates_options.txt" href="<?php echo esc_url(organics_get_file_url('core/core.importer/export/templates_options.txt')); ?>"><?php esc_html_e('Download', 'organics'); ?></a>
									</td>
								</tr>
								<tr>
									<th align="left"><?php esc_html_e('Widgets', 'organics'); ?></th>
									<td><?php organics_fpc(organics_get_file_dir('core/core.importer/export/widgets.txt'), $this->export_widgets); ?>
										<a download="widgets.txt" href="<?php echo esc_url(organics_get_file_url('core/core.importer/export/widgets.txt')); ?>"><?php esc_html_e('Download', 'organics'); ?></a>
									</td>
								</tr>
								
								<?php do_action('organics_action_importer_export_fields', $this); ?>

								</table>

							<?php } else { ?>

								<input type="submit" value="<?php esc_html_e('Export Theme Options', 'organics'); ?>">

							<?php } ?>
						</div>
	
					</form>
				</div>
			<?php } ?>
		</div>
		<?php
	}
	
	
	//-----------------------------------------------------------------------------------
	// Export dummy data
	//-----------------------------------------------------------------------------------
	function exporter() {
		global $wpdb;
		$suppress = $wpdb->suppress_errors();

		// Export theme mods
		$this->export_mods = serialize($this->prepare_domains(get_theme_mods()));

		// Export theme, templates and categories options and VC templates
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE 'organics_options%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(organics_unserialize($row->option_value));
			}
		}
		// Export additional options
		if (is_array($this->options['additional_options']) && count($this->options['additional_options']) > 0) {
			foreach ($this->options['additional_options'] as $opt) {
				$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE '" . esc_sql($opt) . "'" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$options[$row->option_name] = organics_unserialize($row->option_value);
					}
				}
			}
		}
		$this->export_options = serialize($this->prepare_domains($options));

		// Export templates options
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name LIKE 'organics_options_template_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(organics_unserialize($row->option_value));
			}
		}
		$this->export_templates = serialize($this->prepare_domains($options));

		// Export widgets
		$rows = $wpdb->get_results( "SELECT option_name, option_value FROM " . esc_sql($wpdb->options) . " WHERE option_name = 'sidebars_widgets' OR option_name LIKE 'widget_%'" );
		$options = array();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $row) {
				$options[$row->option_name] = $this->prepare_uploads(organics_unserialize($row->option_value));
			}
		}
		$this->export_widgets = serialize($this->prepare_domains($options));

		// Export Theme specific post types
		do_action('organics_action_importer_export', $this);

		$wpdb->suppress_errors( $suppress );
	}
	
	
	//-----------------------------------------------------------------------------------
	// Import dummy data
	//-----------------------------------------------------------------------------------
	function importer() {
		?>
		<p>&nbsp;</p>
		<div class="error">
			<h4><?php echo esc_html__('Import progress:', 'organics') . ' <span id="import_progress_value">' . (!empty($this->last_slider) && isset($_POST['importer_revslider']) ? 99 : $this->result) . '</span>%'; ?></h4>
			<p><?php echo esc_html__('Status:', 'organics'); ?> <span id="import_progress_status"></span></p>
			<p><?php echo esc_html__('Data import can take a long time (sometimes more than 10 minutes)!', 'organics')
				. '<br>' . esc_html__('Please wait until the end of the procedure, do not navigate away from the page!', 'organics'); ?></p>
		</div>
		<p>&nbsp;</p>
		<?php
		flush();
		// Import posts, pages, menu items, etc.
		$result = 100;
		if ($this->options['import_posts'] && (empty($this->last_slider) || !isset($_POST['importer_revslider']))) {
			// Load WP Importer class
			if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true); // we are loading importers
			if ( !class_exists('WP_Import') ) {
				require organics_get_file_dir('core/core.importer/wordpress-importer.php');
			}
			if ( class_exists( 'WP_Import' ) ) {
				$result = $this->import_posts();
				if ($result >= 100) {
					do_action('organics_action_importer_after_import_posts', $this);
				} else {
					$log = explode('|', organics_fgc($this->import_log));
					$this->import_last_id = (int) $log[0];
				}
			}
		}

		// Import Theme Mods
		if ($result>=100 && $this->options['import_tm'] && (empty($this->last_slider) || !isset($_POST['importer_revslider']))) {
			$this->import_theme_mods();
		}

		// Import Theme Options
		if ($result>=100 && $this->options['import_to'] && (empty($this->last_slider) || !isset($_POST['importer_revslider']))) {
			organics_options_reset();
			$this->import_theme_options();
		}

		// Import Widgets
		if ($result>=100 && $this->options['import_widgets'] && (empty($this->last_slider) || !isset($_POST['importer_revslider'])))
			$this->import_widgets();

		// Import Theme specific posts
		if ($result>=100)
			do_action('organics_action_importer_import', $this);

		// Setup Front page and Blog page
		if ($result>=100 && $this->options['import_posts'] && (empty($this->last_slider) || !isset($_POST['importer_revslider']))) {
			// Flush rules after install
			flush_rewrite_rules();
		}

		// Finally redirect to success page
		if ($result >= 100 && (empty($this->last_slider) || !isset($_POST['importer_revslider']))) {
			$this->success = esc_html__('Congratulations! Import demo data finished successfull!', 'organics');
		} else {
			$this->error = '<h4>' . sprintf(esc_html__('Import progress: %s.', 'organics'), max(99, $result).'%') . '</h4>'
				. esc_html__('Due to the expiration of the time limit for the execution of scripts on your server, the import process is interrupted!', 'organics')
				. '<br>' . esc_html__('After 3 seconds, the import will continue automatically!', 'organics');
			$this->result = $result;
		}
	}
	
	//==========================================================================================
	// Utilities
	//==========================================================================================

	// Check for required plugings
	function check_required_plugins() {
		$not_installed = '';
		if (in_array('organics_utils', $this->options['required_plugins']) && !defined('TRX_UTILS_VERSION') )
			$not_installed .= '<br>Organics Utilities';
		$not_installed = apply_filters('organics_filter_importer_required_plugins', $not_installed, $this);
		if ($not_installed) {
			$this->error = '<b>'.esc_html__('Attention! For correct installation of the demo data, you must install and activate the following plugins: ', 'organics').'</b>'.($not_installed);
			$this->options['enable_importer'] = false;
			$this->options['enable_exporter'] = false;
			return false;
		}
		return true;
	}


	// Import XML file with posts data
	function import_posts() {
		if (empty($this->options['file_with_content'][$this->options['data_type']])) return;
		echo ($this->import_last_id == 0 
			? '<h3>'.esc_html__('Start Import', 'organics').'</h3>'
			: '<h3>'.sprintf(esc_html__('Continue Import from ID=%d', 'organics'), $this->import_last_id).'</h3>');
		echo '<b>' . esc_html__('Import Posts (pages, menus, attachments, etc) ...', 'organics').'</b><br>';
		flush();
		$theme_xml = organics_get_file_dir($this->options['file_with_content'][$this->options['data_type']]);
		$importer = new WP_Import();
		$importer->fetch_attachments = $this->options['upload_attachments'];
		$importer->overwrite = $this->options['overwrite_content'];
		$importer->debug = $this->options['debug'];
		$importer->uploads_folder = $this->options['uploads_folder'];
		$importer->demo_url = 'http://' . $this->options['domain_demo'] . '/';
		$importer->start_from_id = $this->import_last_id;
		$importer->import_log = $this->import_log;
		if ($this->import_last_id == 0) $this->clear_tables();
		$this->prepare_taxonomies();
		if (!$this->options['debug']) ob_start();
		$result = $importer->import($theme_xml);
		if (!$this->options['debug']) ob_end_clean();
		if ($result>=100) organics_fpc($this->import_log, '');
		return $result;
	}
	
	
	// Delete all data from tables
	function clear_tables() {
		global $wpdb;
		if ($this->options['overwrite_content']) {
			echo '<br><b>'.esc_html__('Clear tables ...', 'organics').'</b><br>';
			if ($this->options['import_posts']) {
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->comments));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "comments".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->commentmeta));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "commentmeta".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->postmeta));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "postmeta".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->posts));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "posts".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->terms));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "terms".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_relationships));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "term_relationships".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
				$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->term_taxonomy));
				if ( is_wp_error( $res ) ) echo esc_html__( 'Failed truncate table "term_taxonomy".', 'organics' ) . ' ' . ($res->get_error_message()) . '<br />';
			}
			flush();
			do_action('organics_action_importer_clear_tables', $this);
		}
	}

	
	// Prepare additional taxes
	function prepare_taxonomies() {
		if (!function_exists('organics_require_data')) return;
		if (isset($this->options['taxonomies']) && is_array($this->options['taxonomies']) && count($this->options['taxonomies']) > 0) {
			foreach ($this->options['taxonomies'] as $type=>$tax) {
				organics_require_data( 'taxonomy', $tax, array(
					'post_type'			=> array( $type ),
					'hierarchical'		=> false,
					'query_var'			=> $tax,
					'rewrite'			=> true,
					'public'			=> false,
					'show_ui'			=> false,
					'show_admin_column'	=> false,
					'_builtin'			=> false
					)
				);
			}
		}
	}


	// Import theme mods
	function import_theme_mods() {
		if (empty($this->options['file_with_mods'])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . esc_html__('Import Theme Mods ...', 'organics') .'";'
			. '</script>';
		echo '<br><b>'.esc_html__('Import Theme Mods ...', 'organics').'</b><br>';
		flush();
		$txt = organics_fgc(organics_get_file_dir($this->options['file_with_mods']));
		$data = organics_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
			}
			$theme = get_option( 'stylesheet' );
			update_option( "theme_mods_$theme", $data );
		}
	}


	// Import theme options
	function import_theme_options() {
		if (empty($this->options['file_with_options'])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . esc_html__('Import Theme Options ...', 'organics') .'";'
			. '</script>';
		echo '<br><b>'.esc_html__('Import Theme Options ...', 'organics').'</b><br>';
		flush();
		$txt = organics_fgc(organics_get_file_dir($this->options['file_with_options']));
		$data = organics_unserialize($txt);
		// Replace upload url in options
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				if (is_array($v) && count($v) > 0) {
					foreach ($v as $k1=>$v1) {
						$v[$k1] = $this->replace_uploads($v1);
					}
				} else
					$v = $this->replace_uploads($v);
				if ($k == 'mega_main_menu_options' && isset($v['last_modified']))
					$v['last_modified'] = time()+30;
				update_option( $k, $v );
			}
		}
		organics_load_main_options();
	}


	// Import widgets
	function import_widgets() {
		if (empty($this->options['file_with_widgets'])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . esc_html__('Import Widgets ...', 'organics') .'";'
			. '</script>';
		echo '<br><b>'.esc_html__('Import Widgets ...', 'organics').'</b><br>';
		flush();
		$txt = organics_fgc(organics_get_file_dir($this->options['file_with_widgets']));
		$data = organics_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			foreach ($data as $k=>$v) {
				update_option( $k, $this->replace_uploads($v) );
			}
		}
	}


	// Import any SQL dump
	function import_dump($slug, $title) {
		if (empty($this->options['file_with_'.$slug])) return;
		echo '<script>'
			. 'document.getElementById("import_progress_status").innerHTML = "' . sprintf(esc_html__('Import %s ...', 'organics'), $title) .'";'
			. '</script>';
		echo '<br><b>'. sprintf(esc_html__('Import %s ...', 'organics'), $title) . '</b><br>';
		flush();
		$txt = organics_fgc(organics_get_file_dir($this->options['file_with_'.$slug]));
		$data = organics_unserialize($txt);
		if (is_array($data) && count($data) > 0) {
			global $wpdb;
			foreach ($data as $table=>$rows) {
				// Clear table, if it is not 'users' or 'usermeta'
				if ($this->options['overwrite_content'] && !in_array($table, array('users', 'usermeta')))
					$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix . $table));
				$values = $fields = '';
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$f = '';
						$v = '';
						if (is_array($row) && count($row) > 0) {
							foreach ($row as $field => $value) {
								$f .= ($f ? ',' : '') . "'" . esc_sql($field) . "'";
								$v .= ($v ? ',' : '') . "'" . esc_sql($value) . "'";
							}
						}
						if ($fields == '') $fields = '(' . $f . ')';
						$values .= ($values ? ',' : '') . '(' . $v . ')';
						// If query length exceed 64K - run query, because MySQL not accept long query string
						// If current table 'users' or 'usermeta' - run queries row by row, because we append data
						if (organics_strlen($values) > 64000 || in_array($table, array('users', 'usermeta'))) {
							// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
							$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
							$wpdb->query($q);
							$values = $fields = '';
					}
				}
				}
				if (!empty($values)) {
				// Attention! All items in the variable $values escaped on the loop above - esc_sql($value)
				$q = "INSERT INTO ".esc_sql($wpdb->prefix . $table)." VALUES {$values}";
				$wpdb->query($q);
			}
		}
	}
	}

	
	// Replace uploads dir to new url
	function replace_uploads($str) {
		return organics_replace_uploads_url($str, $this->options['uploads_folder']);
	}

	
	// Replace uploads dir to imports then export data
	function prepare_uploads($str) {
		if ($this->options['uploads_folder']=='uploads') return $str;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_uploads($v);
			}
		} else if (is_string($str)) {
			$str = str_replace('/uploads/', "/{$this->options['uploads_folder']}/", $str);
		}
		return $str;
	}
	
	// Replace dev domain to demo domain then export data
	function prepare_domains($str) {
		if ($this->options['domain_dev']==$this->options['domain_demo']) return $str;
		if (is_array($str) && count($str) > 0) {
			foreach ($str as $k=>$v) {
				$str[$k] = $this->prepare_domains($v);
			}
		} else if (is_string($str)) {
			$str = str_replace($this->options['domain_dev'], $this->options['domain_demo'], $str);
		}
		return $str;
	}
}
?>