<?php
/*
Plugin Name: TablePress Extension: DataTables TableTools
Plugin URI: https://tablepress.org/extensions/datatables-tabletools/
Description: Custom Extension for TablePress to add the DataTables TableTools functionality
Version: 1.2
Author: Tobias Bäthge
Author URI: https://tobias.baethge.com/
*/

/*
 * Register necessary Plugin Filters.
 */
add_filter( 'tablepress_shortcode_table_default_shortcode_atts', 'tablepress_add_shortcode_parameters_tabletools' );
add_filter( 'tablepress_table_js_options', 'tablepress_add_tabletools_js_options', 10, 3 );
add_filter( 'tablepress_datatables_command', 'tablepress_add_tabletools_js_command', 10, 5 );
if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'tablepress_enqueue_tabletools_css' );
}

/**
 * Add "datatables_tabletools" as a valid parameter to the [table /] Shortcode.
 *
 * @since 1.0
 *
 * @param array $default_atts Default attributes for the TablePress [table /] Shortcode.
 * @return array Extended attributes for the Shortcode.
 */
function tablepress_add_shortcode_parameters_tabletools( $default_atts ) {
	$default_atts['datatables_tabletools'] = false;
	return $default_atts;
}

/**
 * Pass "datatables_tabletools" from Shortcode parameters to JavaScript arguments.
 *
 * @since 1.0
 *
 * @param array  $js_options    Current JS options.
 * @param string $table_id      Table ID.
 * @param array $render_options Render Options.
 * @return array Modified JS options.
 */
function tablepress_add_tabletools_js_options( $js_options, $table_id, $render_options ) {
	$js_options['datatables_tabletools'] = $render_options['datatables_tabletools'];

	// Register the JS.
	if ( $js_options['datatables_tabletools'] ) {
		$js_tabletools_url = plugins_url( 'js/TableTools.min.js', __FILE__ );
		wp_enqueue_script( 'tablepress-tabletools', $js_tabletools_url, array( 'tablepress-datatables' ), '2.2.4-dev', true );
	}

	return $js_options;
}

/**
 * Evaluate "datatables_tabletools" parameter and add corresponding JavaScript code, if needed.
 *
 * @since 1.0
 *
 * @param string $command    DataTables command.
 * @param string $html_id    HTML ID of the table.
 * @param array  $parameters DataTables parameters.
 * @param string $table_id   Table ID.
 * @param array  $js_options DataTables JS options.
 * @return string Modified DataTables command.
 */
function tablepress_add_tabletools_js_command( $command, $html_id, $parameters, $table_id, $js_options ) {
	if ( ! $js_options['datatables_tabletools'] ) {
		return $command;
	}

	$table_wrapper = "{$html_id}_wrapper";
	$html_id = str_replace( '-', '_', $html_id );
	$table_name = "DT_{$html_id}";
	$tabletools_name = "DTTT_{$html_id}";
	$command = substr( $command, 0, -1 ); // Remove ; at the end.
	$swf_path = plugins_url( 'swf/copy_csv_xls_pdf.swf', __FILE__ );

	// With text (some CSS needs to commented out!):
	//$tabletools_options = '{ "sSwfPath": "' . $swf_path . '", "aButtons": [ "copy", "print", { "sExtends": "collection", "sButtonText": "Save as", "aButtons": [ "csv", "xls", "pdf" ] } ] }';

	// with images:
	$tabletools_options = '{ "sSwfPath": "' . $swf_path . '", "aButtons": [ { "sExtends": "copy", "sButtonText": "" }, { "sExtends": "csv", "sButtonText": "" }, { "sExtends": "xls", "sButtonText": "" }, { "sExtends": "pdf", "sButtonText": "" }, { "sExtends": "print", "sButtonText": "" } ] }';

	$command = "var {$table_name} = {$command}, {$tabletools_name} = new TableTools({$table_name}, {$tabletools_options}); $('#{$table_wrapper}').before({$tabletools_name}.dom.container);";
	return $command;
}

/**
 * Enqueue CSS files.
 *
 * @since 1.0.
 */
function tablepress_enqueue_tabletools_css() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	$tabletools_css_url = plugins_url( "css/TableTools{$suffix}.css", __FILE__ );
	wp_enqueue_style( 'tablepress-tabletools-css', $tabletools_css_url, array(), '2.1.5' );
}
