<div id="popup_registration" class="popup_wrap popup_registration bg_tint_light">
	<a href="#" class="popup_close"></a>
	<div class="form_wrap">
		<form name="registration_form" method="post" class="popup_form registration_form">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr(home_url()); ?>"/>
			<div class="form_left">
				<div class="popup_form_field login_field iconed_field icon-user"><input type="text" id="registration_username" name="registration_username"  value="" placeholder="<?php esc_attr_e('Nombre de usuario (ingresar)', 'organics'); ?>"></div>
				<div class="popup_form_field email_field iconed_field icon-mail-1"><input type="text" id="registration_email" name="registration_email" value="" placeholder="<?php esc_attr_e('Email', 'organics'); ?>"></div>
				<div class="popup_form_field agree_field">
					<input type="checkbox" value="agree" id="registration_agree" name="registration_agree">
					<label for="registration_agree"><?php esc_html_e('Estoy de acuerdo con los', 'organics'); ?></label> <a href="#"><?php esc_html_e('Términos y condiciones', 'organics'); ?></a>
				</div>
				<div class="popup_form_field submit_field"><input type="submit" class="submit_button" value="<?php esc_attr_e('Ingresar', 'organics'); ?>"></div>
			</div>
			<div class="form_right">
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="password" id="registration_pwd"  name="registration_pwd"  value="" placeholder="<?php esc_attr_e('Contraseña', 'organics'); ?>"></div>
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="password" id="registration_pwd2" name="registration_pwd2" value="" placeholder="<?php esc_attr_e('Confirmar contraseña', 'organics'); ?>"></div>
				<div class="popup_form_field description_field"><?php esc_html_e('Mínimo 6 caracteres', 'organics'); ?></div>
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="date" id="_fecha_nacimiento" name="_fecha_nacimiento" value="" placeholder="<?php esc_attr_e('Fecha de Nacimineto', 'organics'); ?>"></div>
				<div class="popup_form_field description_field"><?php esc_html_e('Fecha de nacimiento', 'organics'); ?></div>
			</div>
		</form>
		<div class="result message_block"></div>
	</div>	<!-- /.registration_wrap -->
</div>		<!-- /.user-popUp -->
