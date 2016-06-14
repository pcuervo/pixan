<?php
$video_bg_show  = organics_get_custom_option('show_video_bg')=='yes' && (organics_get_custom_option('video_bg_youtube_code')!='' || organics_get_custom_option('video_bg_url')!='');
if ($video_bg_show) {
	$youtube = organics_get_custom_option('video_bg_youtube_code');
	$video   = organics_get_custom_option('video_bg_url');
	$overlay = organics_get_custom_option('video_bg_overlay')=='yes';
	if (!empty($youtube)) {
		?>
		<div class="video_bg<?php echo ($overlay ? ' video_bg_overlay' : ''); ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
		<?php
	} else if (!empty($video)) {
		$info = pathinfo($video);
		$ext = !empty($info['extension']) ? $info['extension'] : 'src';
		?>
		<div class="video_bg<?php echo esc_attr($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
		<?php
	}
}