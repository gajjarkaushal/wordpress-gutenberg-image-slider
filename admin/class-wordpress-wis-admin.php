<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://koderise.com
 * @since      1.0.0
 *
 * @package    Wordpress_Image_Slider
 * @subpackage Wordpress_Image_Slider/admin
 * @author     Kaushal Gajjar <gajjarkaushal1@gmail.com>
 */
class Wordpress_Image_Slider_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_menu', array($this, 'wis_register_settings_page'));
		add_action('admin_init', array($this, 'wis_wordpress_image_slider_settings_init'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wordpress_Image_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wordpress_Image_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wis.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wordpress_Image_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wordpress_Image_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wis.js', array( 'jquery' ), $this->version, false );

	}
	public function wis_register_settings_page(){
		add_menu_page(
            'Image Slider Settings',
            'Image Slider',
            'manage_options',
            'custom-image-slider',
            array($this, 'wsl_settings_page_html'),
            'dashicons-images-alt2'
        );
	}
	public function wsl_settings_page_html() {
		include(WIS_PATH.'admin/partials/wordpress-image-slider-display.php');
    }
	public function wis_wordpress_image_slider_settings_init(){
		register_setting('custom_image_slider_settings', 'custom_image_slider_options', array($this,'wis_save_slider_data'));
	}
	public function wis_save_slider_data($input) {
	   // Sanitize the slider timer
	   $input['slider_timer'] = absint($input['slider_timer']);

	   // Sanitize slides
	   if (isset($input['slides']) && is_array($input['slides'])) {
		   foreach ($input['slides'] as &$slide) {
			   $slide['url'] = esc_url_raw($slide['url']);
			   $slide['title'] = sanitize_text_field($slide['title']);
			   $slide['description'] = sanitize_textarea_field($slide['description']);
			   $slide['cta_text'] = sanitize_text_field($slide['cta_text']);
			   $slide['cta_url'] = esc_url_raw($slide['cta_url']);
		   }
	   }
   
	   return $input;
    }

}
