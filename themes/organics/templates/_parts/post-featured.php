<?php
					if (isset($post_data['post_video']) and $post_data['post_video']) {
						echo trim(organics_get_video_frame($post_data['post_video'], $post_data['post_video_image'] ? $post_data['post_video_image'] : $post_data['post_thumb']));
					} else if (isset($post_data['post_audio']) and $post_data['post_audio']) {
						if (organics_get_custom_option('substitute_audio')=='no' || !organics_in_shortcode_blogger(true))
							echo trim(organics_get_audio_frame($post_data['post_audio'], $post_data['post_audio_image'] ? $post_data['post_audio_image'] : $post_data['post_thumb_url']));
						else
							echo trim($post_data['post_audio']);
					} else if ($post_data['post_thumb'] && ($post_data['post_format']!='gallery' || !$post_data['post_gallery'] || organics_get_custom_option('gallery_instead_image')=='no')) {
						
						if ( !isset($post_data['post_attachment']) ) {
							$post_data['post_attachment'] = '';
						}
						
						?>
						<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
						<?php
						if ($post_data['post_format']=='link' && $post_data['post_url']!='')
							echo '<a class="hover_icon icon-eye-light" href="'.esc_url($post_data['post_url']).'"'.($post_data['post_url_target'] ? ' target="'.esc_attr($post_data['post_url_target']).'"' : '').'>'.($post_data['post_thumb']).'</a>';
						else if ($post_data['post_link']!='')
							echo '<a class="hover_icon icon-eye-light" href="'.esc_url($post_data['post_link']).'">'.($post_data['post_thumb']).'</a>';
						else
							echo trim($post_data['post_thumb']); 
						?>
						</div>
						<?php
					} else if ($post_data['post_gallery']) {
						organics_enqueue_slider();
						echo trim($post_data['post_gallery']);
					}
?>