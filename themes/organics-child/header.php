<?php
// Theme init - don't remove next row! Load custom options
organics_core_init_theme();
$theme_skin = organics_esc(organics_get_custom_option('theme_skin'));
$body_scheme = organics_get_custom_option('body_scheme');
if (empty($body_scheme)  || organics_is_inherit_option($body_scheme)) $body_scheme = 'original';
$blog_style = organics_get_custom_option(is_singular() && !organics_get_global('blog_streampage') ? 'single_style' : 'blog_style');
$body_style  = organics_get_custom_option('body_style');
$article_style = organics_get_custom_option('article_style');
$top_panel_style = organics_get_custom_option('top_panel_style');
$top_panel_position = organics_get_custom_option('top_panel_position');
$top_panel_scheme = organics_get_custom_option('top_panel_scheme');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr('scheme_'.$body_scheme); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1<?php echo (organics_get_theme_option('responsive_layouts') == 'yes' ? ', maximum-scale=1' : ''); ?>">
    <meta name="format-detection" content="telephone=no">
	<!-- SEO -->
	<meta name="keywords" content="fruta, verdura, orgánico, sustentable, canastas, productos, calidad">
    <meta name="description" content="Pixan es un espacio incluyente, que da empleo y capacitación a la gente local y con capacidades diferentes, generando productos de alta calidad.">
	<!-- Facebook, Twitter metas -->
	<meta property="og:title" content="Pixan Sustentable">
	<meta name="og:description" content="Pixan es un espacio incluyente, que da empleo y capacitación a la gente local y con capacidades diferentes, generando productos de alta calidad." />
	<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/images/pixan-share.png" alt="logo redondo sobre caja">
	<meta property="og:image:width" content="210" />
	<meta property="og:image:height" content="110" />
	<meta property="fb:app_id" content="1750075545245803" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@pixan" />
	<meta name="twitter:title" content="Pixan sustentable" />
	<meta name="twitter:description" content="Pixan es un espacio incluyente, que da empleo y capacitación a la gente local y con capacidades diferentes, generando productos de alta calidad." />
	<meta name="twitter:image" content="<?php echo get_stylesheet_directory_uri(); ?>/images/pixan-share.png" alt="logo redondo sobre caja" />
	<!-- Compatibility -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">
	<?php
	if (floatval(get_bloginfo('version')) < 4.1) {
		?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}
	?>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
	if ( !function_exists('has_site_icon') || !has_site_icon() ) {
		$favicon = organics_get_custom_option('favicon');
		if (!$favicon) {
			if ( file_exists(organics_get_file_dir('skins/'.($theme_skin).'/images/favicon.ico')) )
				$favicon = organics_get_file_url('skins/'.($theme_skin).'/images/favicon.ico');
			if ( !$favicon && file_exists(organics_get_file_dir('favicon.ico')) )
				$favicon = organics_get_file_url('favicon.ico');
		}
		if ($favicon) {
			?><link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" /><?php
		}
	}
	wp_head();
	?>
</head>
<body <?php body_class();?>>
	<?php echo force_balance_tags(organics_get_custom_option('gtm_code')); ?>
	<?php do_action( 'before' ); ?>
	<?php
	if (organics_get_custom_option('menu_toc_home')=='yes')
		echo trim(organics_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'organics'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'organics'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => home_url())
			));
	if (organics_get_custom_option('menu_toc_top')=='yes')
		echo trim(organics_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'organics'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'organics'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			));
	?>
	<?php if ( !organics_param_is_off(organics_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>
	<?php // require_once organics_get_file_dir('sidebar_outer.php'); ?>
	<?php
		$class = $style = '';
		if ($body_style=='boxed' || organics_get_custom_option('bg_image_load')=='always') {
			if (($img = (int) organics_get_custom_option('bg_image', 0)) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = (int) organics_get_custom_option('bg_pattern', 0)) > 0)
				$class = 'bg_pattern_'.($img);
            else if (organics_get_custom_option('bg_custom')=='yes') {
                if ( (($img = organics_get_custom_option('bg_image_custom')) != '') && (($img_bg_color = organics_get_custom_option('bg_color', '')) != '') )
                    $style = 'background: url(' . esc_url($img) . ') ' . str_replace('_', ' ', organics_get_custom_option('bg_image_custom_position')) . ' no-repeat;' . 'background-color: ' . ($img_bg_color) . ';';
            }
            else if (($img = organics_get_custom_option('bg_color', '')) != '')
				$style = 'background-color: '.($img).';';
			else if (organics_get_custom_option('bg_custom')=='yes') {
				if (($img = organics_get_custom_option('bg_image_custom')) != '')
					$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', organics_get_custom_option('bg_image_custom_position')) . ' no-repeat;';
				else if (($img = organics_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = organics_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = organics_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = organics_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
		}
	?>
	<div class="body_wrap<?php echo ($class ? ' '.esc_attr($class) : ''); ?>"<?php echo ($style ? ' style="'.esc_attr($style).'"' : ''); ?>>
		<?php
		require_once organics_get_file_dir('templates/headers/_parts/video_bg.php');
		?>
		<div class="page_wrap">
			<?php
			if (in_array($top_panel_position, array('above', 'over'))) {
				organics_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			require_once organics_get_file_dir('templates/headers/_parts/header-mobile.php');
			require_once organics_get_file_dir('templates/headers/_parts/slider.php');
			if ($top_panel_position == 'below') {
				organics_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			require_once organics_get_file_dir('templates/headers/_parts/breadcrumbs.php');
			?>
			<div class="page_content_wrap page_paddings_<?php echo esc_attr(organics_get_custom_option('body_paddings')); ?>">
				<?php
				if ($body_style!='fullscreen') organics_open_wrapper('<div class="content_wrap">');
				organics_open_wrapper('<div class="content">');
				?>