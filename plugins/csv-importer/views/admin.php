<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 * @link    http://www.AitThemes.com/
 * @since   1.0.0
 */

$import = AitImport::get_instance();

?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php 
	// save encoding
	if(isset($_POST["encoding"])) {
		update_option( 'ait_import_plugin_encoding', $_POST["encoding"] );
		echo '<div class="updated"><p>'.__('Settings saved').'.</p></div>';
	}
	// import posts from uploaded file
	if(isset($_FILES["posts_csv"]) && isset($_POST["type"])) {
		if ($_FILES["posts_csv"]["error"] > 0) {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'.</p></div>';
		} else {
			$import->import_csv($_POST["type"],$_FILES["posts_csv"]['tmp_name'],$_POST["duplicate"]);
		}
		
	}
	// import categories from uploaded file
	if(isset($_FILES["categories_csv"]) && isset($_POST["type"])) {
		if ($_FILES["categories_csv"]["error"] > 0) {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'.</p></div>';
		} else {
			$import->import_terms_csv($_POST["type"],$_FILES["categories_csv"]['tmp_name'],$_POST["duplicate"]);
		}
	}

	if(isset($_FILES["posts_csv_precios"]) && isset($_POST["type"])) {
		$ext = explode('.', $_FILES["posts_csv_precios"]['name']);
		if ($_FILES["posts_csv_precios"]["error"] > 0 || $ext[count($ext)-1] != 'csv') {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'. Por favor intente de nuevo con un archivo correcto.</p></div>';
		} else {
			$import->import_csv_precios($_POST["type"],$_FILES["posts_csv_precios"]['tmp_name']);
		}
		
	}
	?>

	<div class="import-settings metabox-holder">
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span><?php _e('Import settings'); ?></span></h3>

			<div class="inside">

				<?php $saved_encoding = get_option( 'ait_import_plugin_encoding', '25' ); ?>

				<form action="admin.php?page=ait-import" method="post">
					<label for="import-encoding"><?php _e('Encoding of imported CSV files: '); ?></label>
					<select name="encoding" id="import-encoding">
					<?php foreach (mb_list_encodings() as $key => $value) {
						if($key == intval($saved_encoding)) {
							echo "<option selected='selected' value='$key'>$value</option>";
						} else {
							echo "<option value='$key'>$value</option>";
						}
					} ?>
					</select>
					<input type="submit" value="<?php _e('Save settings'); ?>" class="save button">
				</form>

			</div>

		</div>
	</div>

	<div class=" metabox-holder">
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span><?php _e('Ajuste de precios'); ?></span></h3>

			<div class="inside">

				<!-- AJUSTE DE PRECIOS MASIVOS -->
				<div >
					<form  action="<?php echo AIT_IMPORT_PLUGIN_URL . 'download.php'; ?>" method="post">
						
						<h3>Actualización de precios masivos</h3>
						
						<table style="display: none;">
							<tr>
								<th><?php _e('Attribute'); ?></th>
								<th><?php _e('Column name in CSV file'); ?></th>
								<th><?php _e('Notice'); ?></th>
							</tr>
							
							<!-- AGREGADOS EN HARDCODE PARA OBTENERE EL FORMATO REQUEIRDO POR EL CLIENTE -->
							<tr>
								<td><input type="checkbox" name="MID" checked="checked"> MID </td>
								<td>MID</td>
								<td>Valor numerico</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="PRECIO" checked="checked"> PRECIO </td>
								<td>PRECIO</td>
								<td>Valor numerico</td>
							</tr>
							

						</table>

						<input type="hidden" name="ait-import-post-type" value="<?php echo $type->id; ?>">
						<input type="hidden" name="ait-import-is-ait-type" value="yes">

						<input type="submit" value="<?php _e('Descargar CSV de ejemplo para ajustar precios'); ?>" class="download button">
					
					</form>

					<form action="admin.php?page=ait-import" method="post" enctype="multipart/form-data">
						
						<h4>Actualizar precios</h4>
						<div style="display:none;">
						Delimiter: <select name="delim" id="delim">
	                                        <option value=",">,</option>
	                                        <option value=";">;</option>
	                     </select><br>

						<input type="hidden" name="type" value="<?php echo $type->id; ?>">

						<input type="radio" name="duplicate" value="1" checked="checked"> <?php _e("Rename item's name (slug) if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="2"> <?php _e("Update old item's data if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="3"> <?php _e("Ignore item if item with name (slug) already exists"); ?> <br>
						</div>
						
						<input type="file" name="posts_csv_precios">
						<input type="submit" value="Actualizar precios" class="upload button-primary">
						
					
					</form>
				</div>
				<!-- AJUSTES DE INVENTARIO -->
				<div style="border: 1px solid; padding: 10px;">
					<form  action="<?php echo AIT_IMPORT_PLUGIN_URL . 'download.php'; ?>" method="post">
						
						<h3>Ajustes de inventario</h3>
						
						<table style="display: none;">
							<tr>
								<th><?php _e('Attribute'); ?></th>
								<th><?php _e('Column name in CSV file'); ?></th>
								<th><?php _e('Notice'); ?></th>
							</tr>
							
							<!-- AGREGADOS EN HARDCODE PARA OBTENERE EL FORMATO REQUEIRDO POR EL CLIENTE -->
							<tr>
								<td><input type="checkbox" name="MID" checked="checked"> MID </td>
								<td>MID</td>
								<td>Valor numerico</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="UPC" checked="checked"> UPC </td>
								<td>UPC</td>
								<td>Corresponde al valor Sku</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="TALLA" checked="checked"> Talla del Producto </td>
								<td>TALLA</td>
								<td>El nombre de la talla contenido en esta columna ya debe haberse creado en la pagina como un atributo de producto.</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="CANTIDAD" checked="checked"> Cantidad </td>
								<td>CANTIDAD</td>
								<td>Representa la cantidad del producto en la talla especificada.</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="COMENTARIOS" checked="checked"> Comentarios </td>
								<td>COMENTARIOS</td>
								<td>Notas respecto si es un ajuste por mercancia dañanda, alta por devolución, etc.</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="DESTACADO" checked="checked"> Destacado </td>
								<td>DESTACADO</td>
								<td>Marcar producto como destacado <small>(Admite "SI", "si", "YES", "yes" o dejar vacio para no marcar como destacado)</small>. Ej: <strong>SI</strong></td>
							</tr>

						</table>

						<input type="hidden" name="ait-import-post-type" value="<?php echo $type->id; ?>">
						<input type="hidden" name="ait-import-is-ait-type" value="yes">

						<input type="submit" value="<?php _e('Descargar CSV de ejemplo para ajuste de inventario'); ?>" class="download button">
					
					</form>

					<form action="admin.php?page=ait-import" method="post" enctype="multipart/form-data">
						
						<h4>Ajustar inventario</h4>
						<div style="display:none;">
						Delimiter: <select name="delim" id="delim">
	                                        <option value=",">,</option>
	                                        <option value=";">;</option>
	                     </select><br>

						<input type="hidden" name="type" value="<?php echo $type->id; ?>">

						<input type="radio" name="duplicate" value="1" checked="checked"> <?php _e("Rename item's name (slug) if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="2"> <?php _e("Update old item's data if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="3"> <?php _e("Ignore item if item with name (slug) already exists"); ?> <br>
						</div>
						
						<?php if(empty( $bulks )) { ?>
							<input type="file" name="posts_csv_ajuste">
							<input type="submit" value="Iniciar ajuste de inventario" class="upload button-primary">
						<?php }	else { ?>
							<div class="">
								<p style="background-color: pink;">
									Debe enviar los productos pendientes al SIL antes de realizar un ajuste de inventario.
								</p>
							</div>
						<?php } ?>
					
					</form>
				</div>
			</div>

		</div>
	</div>

	<?php
	foreach ($import->post_types as $type) { ?>
	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span><?php echo $type->name; ?></span></h3>

			<div class="inside">
			
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'download.php'; ?>" method="post">
					
					<h4><?php _e('Sample CSV description'); ?></h4>
					
					<table>
						<tr>
							<th><?php _e('Attribute'); ?></th>
							<th><?php _e('Column name in CSV file'); ?></th>
							<th><?php _e('Notice'); ?></th>
						</tr>
	
						<?php foreach ($type->default_options as $o_name => $option) { ?>
						<tr>
							<td><input type="checkbox" name="<?php echo $o_name; ?>" checked="checked"> <?php echo $option['label']; ?></td>
							<td><?php echo $o_name; ?></td>
							<td><?php echo $option['notice']; ?></td>
						</tr>
						<?php } ?>
						<?php if(isset($type->meta_options)) { foreach ($type->meta_options as $o_name => $option) { ?>
						<tr>
							<td><input type="checkbox" name="<?php echo $o_name; ?>" checked="checked"> <?php echo $option['label']; ?></td>
							<td><?php echo $o_name; ?></td>
							<td><?php 
								if($option['type'] == 'radio' || $option['type'] == 'select') {
									_e('Available values: ');
									$count_opt = count($option['default']);
									$i = 0;
									foreach ($option['default'] as $key => $value) {
										echo '<b>' . $key . '</b> (' . $value['label'] . ')';
										$i++;
										if($i < $count_opt) echo ',';
									}
								}
							?></td>
						</tr>
						<?php } } ?>
						<?php if(isset($type->taxonomies)) { foreach ($type->taxonomies as $key => $tax) { ?>
						<tr>
							<td><input type="checkbox" name="tax-<?php echo $key; ?>" checked="checked"> <?php echo $tax->name; ?></td>
							<td><?php echo 'tax-'.$key; ?></td>
							<td><?php _e('Comma separated list of categories names (slugs) (e.g. cat1,cat2)'); ?></td>
						</tr>
						<?php } } ?>
					</table>

					<input type="hidden" name="ait-import-post-type" value="<?php echo $type->id; ?>">
					<input type="hidden" name="ait-import-is-ait-type" value="yes">

					<input type="submit" value="<?php _e('Download sample CSV'); ?>" class="download button">
				
				</form>

				<form action="admin.php?page=ait-import" method="post" enctype="multipart/form-data">
					
					<h4><?php _e('Import from file'); ?></h4>
					
					Delimiter: <select name="delim" id="delim">
                                        <option value=",">,</option>
                                        <option value=";">;</option>
                     </select><br>

					<input type="hidden" name="type" value="<?php echo $type->id; ?>">

					<input type="radio" name="duplicate" value="1" checked="checked"> <?php _e("Rename item's name (slug) if item with name (slug) already exists"); ?> <br>
					<input type="radio" name="duplicate" value="2"> <?php _e("Update old item's data if item with name (slug) already exists"); ?> <br>
					<input type="radio" name="duplicate" value="3"> <?php _e("Ignore item if item with name (slug) already exists"); ?> <br>

					<input type="file" name="posts_csv">
					<input type="submit" value="<?php _e('Import from CSV'); ?>" class="upload button-primary">
				
				</form>

				<?php if(isset($type->taxonomies)) { foreach ($type->taxonomies as $key => $tax) { ?>
					
					<h3><?php echo $tax->name; ?></h3>
					
					<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'download.php'; ?>" method="post">

						<h4><?php _e('Sample CSV description'); ?></h4>

						<table>
							<tr>
								<th><?php _e('Attribute'); ?></th>
								<th><?php _e('Column name in CSV file'); ?></th>
								<th><?php _e('Notice'); ?></th>
							</tr>

							<?php foreach ($tax->default_options as $o_name => $option) { ?>
							<tr>
								<td><input type="checkbox" name="<?php echo $o_name; ?>" checked="checked"> <?php echo $option['label']; ?></td>
								<td><?php echo $o_name; ?></td>
								<td><?php echo $option['notice']; ?></td>
							</tr>
							<?php } ?>
							<?php if(isset($tax->meta_options)) { foreach ($tax->meta_options as $o_name => $option) { ?>
							<tr>
								<td><input type="checkbox" name="<?php echo $o_name; ?>" checked="checked"> <?php echo $option; ?></td>
								<td><?php echo $o_name; ?></td>
								<td></td>
							</tr>
							<?php } } ?>
						</table>

						<input type="hidden" name="ait-import-post-type" value="<?php echo $tax->id; ?>">

						<input type="submit" value="<?php _e('Download sample CSV'); ?>" class="download button">

					</form>

					<form action="admin.php?page=ait-import" method="post" enctype="multipart/form-data">

						<h4><?php _e('Import from file'); ?></h4>
						
					Delimiter: <select name="delim" id="delim">
                                        <option value=",">,</option>
                                        <option value=";">;</option>
                     </select><br>
						
						<input type="hidden" name="type" value="<?php echo $tax->id; ?>">

						<input type="radio" name="duplicate" value="1" checked="checked"> <?php _e("Rename category's name (slug) if category with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="2"> <?php _e("Update old category's data if category with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="3"> <?php _e("Ignore category if category with name (slug) already exists"); ?> <br>

						<input type="file" name="categories_csv">
						<input type="submit" value="<?php _e('Import categories from CSV'); ?>" class="upload button-primary">
					
					</form>

				<?php } } ?>

			</div>

		</div>

	</div>
	<?php } ?>
</div>