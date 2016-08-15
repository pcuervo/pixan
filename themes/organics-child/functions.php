<?php

function px_woocommerce_functions() {
	// https://developer.wordpress.org/reference/functions/locate_template/
	// locate_template( $nombres_de_plantilla, $cargar, $requerir_una_vez ) 
	// Con esta función se carga un archivo y se sobreescribe para el child y el parent theme.
	locate_template( array( 'px_woocommerce_functions.php' ), TRUE, TRUE );
	locate_template( array( 'px_woocommerce_hooks.php' ), TRUE, TRUE );
}
add_action( 'after_setup_theme', 'px_woocommerce_functions' );


?>