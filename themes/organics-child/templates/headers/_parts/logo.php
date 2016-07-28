<?php
if ( !function_exists( 'organics_show_logo' ) ) {
	function organics_show_logo() {
		global $ORGANICS_GLOBALS;
		?>
		<div class="logo">
			<a href="<?php echo esc_url(home_url()); ?>"><?php
				echo !empty($ORGANICS_GLOBALS['logo'])
					? '<img src="'.esc_url($ORGANICS_GLOBALS['logo']).'" class="logo_main" alt="logo pixan para header">'
					: '';
				echo !empty($ORGANICS_GLOBALS['logo_fixed'])
					? '<img src="'.esc_url($ORGANICS_GLOBALS['logo_fixed']).'" class="logo_fixed" alt="logo pixan para header">'
					: '';
				echo ($ORGANICS_GLOBALS['logo_text']
					? '<div class="logo_text">'.($ORGANICS_GLOBALS['logo_text']).'</div>'
					: '');
				echo ($ORGANICS_GLOBALS['logo_slogan']
					? '<br><div class="logo_slogan">' . esc_html($ORGANICS_GLOBALS['logo_slogan']) . '</div>'
					: '');
			?></a>
		</div>
	<?php
	}
}
organics_show_logo();
?>