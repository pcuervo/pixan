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
	
	
	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span>Existencias de Inventario</span></h3>

			<div class="inside">
				<h4><?php _e('Puede generar un listado para imprimir o descargar un archivo CSV'); ?></h4>
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'inventory-report.php'; ?>" method="post">
					<input type="submit" value="<?php _e('Descargar archivo CSV'); ?>" class="download button">
				</form>
				<form action="admin.php?page=operation-reports" method="post">
					<input type="submit" value="<?php _e('Listado para Imprimir'); ?>" class="download button">
					<input type="hidden" name="detalle" >
				</form>

			</div>
			
		</div>

	</div>

	<?php
		global $wpdb;

		if(isset($_POST["detalle"])) {
			$productos = $wpdb->get_results("select (select meta_value from px_postmeta pm where pm.post_id = p.id and pm.meta_key = '_stock') as stock, p.* from px_posts p where p.post_type = 'product' and p.post_status = 'publish' and (select pmm.meta_value from px_postmeta pmm where pmm.meta_key = '_manage_stock' and pmm.post_id = p.id) = 'yes'");

			if(!empty( $productos )) {
				echo '<div class="import-custom-type metabox-holder">';		
				echo '<div class="import-options postbox">';
				echo '<h3 class="hndle"><span>DETALLE DE EXISTENCIA DE PRODUCTOS</span><a href="#" class="button btnImprimir" style="float:right;">IMPRIMIR</a></h3>';
				echo '<div class="inside">';
				echo '<table style="width:100%;" id="tablaExistencias" border="1" cellpadding="1">';
				echo '<thead>
							<th width="40%" style="text-align:left;">Nombre</th>
							<th width="10%" style="text-align:left;">SKU</th>
							<th width="20%" style="text-align:left;">Unidad</th>
							<th width="20%" style="text-align:left;">Temperatura</th>
							<th width="10%" style="text-align:center;">Cantidad</th>
						</thead>';
				echo '<tbody>';
				foreach ($productos as $detail) {
					
					$meta = get_post_meta($detail->ID);
					
					$stilo = '';
					if($detail->stock > 0) { $stilo = 'background-color:white'; }
					if($detail->stock <= 0) { $stilo = 'background-color:pink'; }
					
					echo '<tr style="'.$stilo.'">';
					echo '<td>'.$detail->post_title.'</td>';
					echo '<td>'; if(isset($meta['_sku'][0])){ echo $meta['_sku'][0]; } else { echo '-'; } echo '</td>';
					echo '<td>'; if(isset($meta['unidadmedida'][0])){ echo $meta['unidadmedida'][0]; } else { echo '-'; } echo '</td>';
					echo '<td>'; if(isset($meta['temperatura'][0])){ echo $meta['temperatura'][0]; } else { echo '-'; } echo '</td>';
					echo '<td style="text-align:center;">'.round($detail->stock, 2).'</td>';
					echo '</tr>';
				}
				echo '</tbody></table></div></div></div>';
			}
			else {
				echo '<div class="error"><p>No se encontro ningun registro de productos que maneje stock . <br /></div>';
			}

		}

	?>
	
</div>