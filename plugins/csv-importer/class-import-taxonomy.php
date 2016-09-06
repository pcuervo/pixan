<?php
/**
 * AIT Import Plugin
 *
 * @package   AitImport
 * @author    AitThemes.com <info@ait-themes.com>
 * @copyright 2013 AitThemes
 * @link      http://www.AitThemes.com/
 */

/**
 * Taxonomy Import Class
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 */
class AitImportTaxonomy {
	
	public $id;

	public $name;

	public $default_options;

	public $meta_options;

	public $storage_type;

	private $options_list;

	public function __construct($id) {
		
		$this->id = $id;
		$this->name = (isset($GLOBALS['wp_taxonomies'][$id]->labels->name)) ? $GLOBALS['wp_taxonomies'][$id]->labels->name : $id;

		$this->default_options = array(
			'slug' => array(
				'label' => __('Name / Identifier / Slug'),
				'notice' => __('Name (slug) for identifing category')
			),
			'title' => array(
				'label' => __('Title'),
				'notice' => ''
			),
			'description' => array(
				'label' => __('Description'),
				'notice' => ''
			),
			'parent' => array(
				'label' => __('Parent category'),
				'notice' => __('Parent category name (slug)')
			)
		);

		$this->options_list = array(
			'directory' => array(
				'ait-dir-item-category' => array(
					'excerpt' => __('Excerpt'),
					'icon' => __('Icon'),
					'marker' => __('Marker')
				)
			)
		);

		if(defined('THEME_CODE_NAME') && isset($this->options_list[THEME_CODE_NAME][$id])) {
			$this->meta_options = $this->options_list[THEME_CODE_NAME][$id];
		}

		if(defined('THEME_CODE_NAME') && THEME_CODE_NAME == 'directory') {
			$storage_type = 2;
		} else {
			$storage_type = 1;
		}

	}
	
}