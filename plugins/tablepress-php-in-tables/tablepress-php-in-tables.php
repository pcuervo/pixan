<?php
/*
Plugin Name: TablePress Extension: PHP in tables
Plugin URI: https://tablepress.org/extensions/php-in-tables/
Description: Custom Extension for TablePress to allow and execute PHP code in table cells
Version: 1.0
Author: Tobias Bäthge
Author URI: https://tobias.baethge.com/
*/

add_filter( 'tablepress_cell_content', 'tablepress_execute_php_in_cells', 10, 4 );

/**
 * Evaluate PHP code in table cells on the output-buffered cell content.
 *
 * @since 1.0.0
 *
 * @param string $cell_content  Cell content.
 * @param string $table_id      Table ID.
 * @param int    $row_number    Row number.
 * @param int    $column_number Column number.
 * @return string Modified cell content.
 */
function tablepress_execute_php_in_cells( $cell_content, $table_id, $row_number, $column_number ) {
	ob_start();
	eval( '?>' . $cell_content );
	return ob_get_clean();
}
