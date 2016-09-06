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
 * Post Type Import Class
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 */
class AitImportType {
	
	public $id;

	public $name;

	public $default_options;

	public $meta_options;

	public $taxonomies;

	private $id_dir;

	private $ait;

	private $fw_post_types_path;

	/**
	 * Exclude these options from import
	 * 
	 * @var array
	 */
	private $exclude_ait_options = array(
		"ait-dir-item" => array(
			'showStreetview',
			'streetViewLatitude',
			'streetViewLongitude',
			'streetViewHeading',
			'streetViewPitch',
			'streetViewZoom'
		)
	);

	public function __construct($id, $ait = false, $id_dir = '') {
		
		$this->default_options = array(
			'post_name' => array(
				'label' => __("Name / Identifier / Slug"),
				'notice' => __("Name (slug) for identifing item")
			),
			'post_title' => array(
				'label' => __("Title"),
				'notice' => ''
			),
			'post_status' => array(
				'label' => __("Status"),
				'notice' => __("Available values: <b>draft</b>, <b>publish</b>, <b>pending</b>, <b>future</b>, <b>private</b>. Default value is <b>draft</b>")
			),
			'post_content' => array(
				'label' => __("Content"),
				'notice' => ''
			),
			'post_excerpt' => array(
				'label' => __("Excerpt"),
				'notice' => ''
			),
			'post_author' => array(
				'label' => __("Author username"),
				'notice' => __("Default author is currently logged user")
			),
			'post_parent' => array(
				'label' => __("Parent"),
				'notice' => __("Name (slug) of parent item if post type support it")
			),
			'post_date' => array(
				'label' => __("Date"),
				'notice' => __("Date in format: <b>Y-m-d H:i:s</b> (e.g. <b>2001-03-10 17:16:18</b>). Default insert current date and time.")
			),
			'post_image' => array(
				'label' => __("Featured Image"),
				'notice' => __("Slug (name) of media file")
			),
			'comment_status' => array(
				'label' => __("Comment status"),
				'notice' => __("Available values: <b>closed</b>, <b>open</b>")
			),
			'ping_status' => array(
				'label' => __("Ping status"),
				'notice' => __("Available values: <b>closed</b>, <b>open</b>")
			),
		);

		$this->id = $id;
		$this->ait = $ait;
		$this->id_dir = $id_dir;

		$this->name = (isset($GLOBALS['wp_post_types'][$id]->labels->name)) ? $GLOBALS['wp_post_types'][$id]->labels->name : $id;

		$this->fw_post_types_path = (defined('AIT_FRAMEWORK_DIR')) ? AIT_FRAMEWORK_DIR . '/CustomTypes/' : false;
		$this->meta_options = $this->get_meta_options();

		$taxonomies = get_object_taxonomies($id);
		foreach ($taxonomies as $tax) {
			// exclude post_format
			if($tax != 'post_format') {
				$this->taxonomies[$tax] = new AitImportTaxonomy($tax);
			}
		}

	}

	public function get_meta_options() {
		if($this->ait){
			// special type
			if($this->id_dir == 'dir-item-tour-offer'){
				$path = $this->fw_post_types_path .'dir-item-tour/' . $this->id_dir . '.neon';
			} else {
				$path = $this->fw_post_types_path . $this->id_dir . '/' . $this->id_dir . '.neon';
			}
			$config = NNeon::decode(file_get_contents($path));
			// check local settings
			$config_theme_file = THEME_DIR ."/conf/_".$this->id.".neon";
			if(file_exists($config_theme_file)) {
				$config_theme = NNeon::decode(file_get_contents($config_theme_file));
				$config = array_merge($config,$config_theme);
			}
			foreach ($config as $cfKey => $cf) {
				// remove section params
				if($cf == 'section' || $cf == 'section-title') { unset($config[$cfKey]); }
				// remove disabled
				if(isset($cf['type']) && $cf['type'] == 'disabled') { unset($config[$cfKey]); }
				// remove special options
				if(isset($this->exclude_ait_options[$this->id]) && in_array($cfKey, $this->exclude_ait_options[$this->id])) { unset($config[$cfKey]); }
			}
			var_dump($config);
			return $config;
		} else {
			$config = array(
			    
			    "unidadmedida" => array(
			         "label" => "Unidad de Medida",
			         "type" => "text",
			         "notice" => "Valores aceptados: (Kilos, Gramos, Litros, Manojo, Cesta)"
			         
			    ),
			    "temperatura" => array(
			         "label" => "Temperatura",
			         "type" => "text",
			         "notice" => "Valores aceptados: (Fresco, Congelado)"
			         
			    ),
			    "_price" => array(
			         "label" => "Precio",
			         "type" => "text",
			         "notice" => ""
			         
			    )
			);
			return $config;
		}
	}
}