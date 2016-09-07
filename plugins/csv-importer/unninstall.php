<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 * @link    http://www.AitThemes.com/
 * @version  1.0.0
 * @since    1.0.0
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}