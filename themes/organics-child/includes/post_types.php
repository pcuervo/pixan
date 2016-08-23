<?php

	/**
	 * PUNTOS DE RECOLECCION
	 */
	$labels = array(
		'name'          => 'Puntos de Recolección',
		'singular_name' => 'Puntos de Recolección',
		'add_new'       => 'Nuevo Punto',
		'add_new_item'  => 'Nuevo Punto',
		'edit_item'     => 'Editar Punto',
		'new_item'      => 'Nuevo Punto',
		'all_items'     => 'Todos',
		'view_item'     => 'Ver Punto',
		'search_items'  => 'Buscar Punto',
		'not_found'     => 'No se encontro',
		'menu_name'     => 'Puntos de Recolección'
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'puntos-recoleccion' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
	);
	register_post_type( 'puntos-recoleccion', $args );

?>
