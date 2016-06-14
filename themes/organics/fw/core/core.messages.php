<?php
/**
 * AxiomThemes Framework: messages subsystem
 *
 * @package	axiomthemes
 * @since	axiomthemes 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('organics_messages_theme_setup')) {
	add_action( 'organics_action_before_init_theme', 'organics_messages_theme_setup' );
	function organics_messages_theme_setup() {
		// Core messages strings
		add_action('organics_action_add_scripts_inline', 'organics_messages_add_scripts_inline');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('organics_get_error_msg')) {
	function organics_get_error_msg() {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['error_msg']) ? $ORGANICS_GLOBALS['error_msg'] : '';
	}
}

if (!function_exists('organics_set_error_msg')) {
	function organics_set_error_msg($msg) {
		global $ORGANICS_GLOBALS;
		$msg2 = organics_get_error_msg();
		$ORGANICS_GLOBALS['error_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('organics_get_success_msg')) {
	function organics_get_success_msg() {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['success_msg']) ? $ORGANICS_GLOBALS['success_msg'] : '';
	}
}

if (!function_exists('organics_set_success_msg')) {
	function organics_set_success_msg($msg) {
		global $ORGANICS_GLOBALS;
		$msg2 = organics_get_success_msg();
		$ORGANICS_GLOBALS['success_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}

if (!function_exists('organics_get_notice_msg')) {
	function organics_get_notice_msg() {
		global $ORGANICS_GLOBALS;
		return !empty($ORGANICS_GLOBALS['notice_msg']) ? $ORGANICS_GLOBALS['notice_msg'] : '';
	}
}

if (!function_exists('organics_set_notice_msg')) {
	function organics_set_notice_msg($msg) {
		global $ORGANICS_GLOBALS;
		$msg2 = organics_get_notice_msg();
		$ORGANICS_GLOBALS['notice_msg'] = $msg2 . ($msg2=='' ? '' : '<br />') . ($msg);
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('organics_set_system_message')) {
	function organics_set_system_message($msg, $status='info', $hdr='') {
		update_option('organics_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('organics_get_system_message')) {
	function organics_get_system_message($del=false) {
		$msg = get_option('organics_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			organics_del_system_message();
		return $msg;
	}
}

if (!function_exists('organics_del_system_message')) {
	function organics_del_system_message() {
		delete_option('organics_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('organics_messages_add_scripts_inline')) {
	function organics_messages_add_scripts_inline() {
		global $ORGANICS_GLOBALS;
		echo '<script type="text/javascript">'
			
			. "if (typeof ORGANICS_GLOBALS == 'undefined') var ORGANICS_GLOBALS = {};"
			
			// Strings for translation
			. 'ORGANICS_GLOBALS["strings"] = {'
				. 'bookmark_add: 		"' . addslashes(esc_html__('Add the bookmark', 'organics')) . '",'
				. 'bookmark_added:		"' . addslashes(esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'organics')) . '",'
				. 'bookmark_del: 		"' . addslashes(esc_html__('Delete this bookmark', 'organics')) . '",'
				. 'bookmark_title:		"' . addslashes(esc_html__('Enter bookmark title', 'organics')) . '",'
				. 'bookmark_exists:		"' . addslashes(esc_html__('Current page already exists in the bookmarks list', 'organics')) . '",'
				. 'search_error:		"' . addslashes(esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'organics')) . '",'
				. 'email_confirm:		"' . addslashes(esc_html__('On the e-mail address %s we sent a confirmation email. Please, open it and click on the link.', 'organics')) . '",'
				. 'reviews_vote:		"' . addslashes(esc_html__('Thanks for your vote! New average rating is:', 'organics')) . '",'
				. 'reviews_error:		"' . addslashes(esc_html__('Error saving your vote! Please, try again later.', 'organics')) . '",'
				. 'error_like:			"' . addslashes(esc_html__('Error saving your like! Please, try again later.', 'organics')) . '",'
				. 'error_global:		"' . addslashes(esc_html__('Global error text', 'organics')) . '",'
				. 'name_empty:			"' . addslashes(esc_html__('The name can\'t be empty', 'organics')) . '",'
				. 'name_long:			"' . addslashes(esc_html__('Too long name', 'organics')) . '",'
				. 'email_empty:			"' . addslashes(esc_html__('Too short (or empty) email address', 'organics')) . '",'
				. 'email_long:			"' . addslashes(esc_html__('Too long email address', 'organics')) . '",'
				. 'email_not_valid:		"' . addslashes(esc_html__('Invalid email address', 'organics')) . '",'
				. 'subject_empty:		"' . addslashes(esc_html__('The subject can\'t be empty', 'organics')) . '",'
				. 'subject_long:		"' . addslashes(esc_html__('Too long subject', 'organics')) . '",'
				. 'text_empty:			"' . addslashes(esc_html__('The message text can\'t be empty', 'organics')) . '",'
				. 'text_long:			"' . addslashes(esc_html__('Too long message text', 'organics')) . '",'
				. 'send_complete:		"' . addslashes(esc_html__("Send message complete!", 'organics')) . '",'
				. 'send_error:			"' . addslashes(esc_html__('Transmit failed!', 'organics')) . '",'
				. 'login_empty:			"' . addslashes(esc_html__('The Login field can\'t be empty', 'organics')) . '",'
				. 'login_long:			"' . addslashes(esc_html__('Too long login field', 'organics')) . '",'
				. 'login_success:		"' . addslashes(esc_html__('Login success! The page will be reloaded in 3 sec.', 'organics')) . '",'
				. 'login_failed:		"' . addslashes(esc_html__('Login failed!', 'organics')) . '",'
				. 'password_empty:		"' . addslashes(esc_html__('The password can\'t be empty and shorter then 4 characters', 'organics')) . '",'
				. 'password_long:		"' . addslashes(esc_html__('Too long password', 'organics')) . '",'
				. 'password_not_equal:	"' . addslashes(esc_html__('The passwords in both fields are not equal', 'organics')) . '",'
				. 'registration_success:"' . addslashes(esc_html__('Registration success! Please log in!', 'organics')) . '",'
				. 'registration_failed:	"' . addslashes(esc_html__('Registration failed!', 'organics')) . '",'
				. 'geocode_error:		"' . addslashes(esc_html__('Geocode was not successful for the following reason:', 'wspace')) . '",'
				. 'googlemap_not_avail:	"' . addslashes(esc_html__('Google map API not available!', 'organics')) . '",'
				. 'editor_save_success:	"' . addslashes(esc_html__("Post content saved!", 'organics')) . '",'
				. 'editor_save_error:	"' . addslashes(esc_html__("Error saving post data!", 'organics')) . '",'
				. 'editor_delete_post:	"' . addslashes(esc_html__("You really want to delete the current post?", 'organics')) . '",'
				. 'editor_delete_post_header:"' . addslashes(esc_html__("Delete post", 'organics')) . '",'
				. 'editor_delete_success:	"' . addslashes(esc_html__("Post deleted!", 'organics')) . '",'
				. 'editor_delete_error:		"' . addslashes(esc_html__("Error deleting post!", 'organics')) . '",'
				. 'editor_caption_cancel:	"' . addslashes(esc_html__('Cancel', 'organics')) . '",'
				. 'editor_caption_close:	"' . addslashes(esc_html__('Close', 'organics')) . '"'
				. '};'
			
			. '</script>';
	}
}
?>