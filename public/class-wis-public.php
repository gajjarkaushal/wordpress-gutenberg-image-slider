<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://gajjarkaushal.com
 * @since      1.0.0
 *
 * @package    Wordpress_Image_Slider
 * @subpackage Wordpress_Image_Slider/public
 * @author     Kaushal Gajjar <gajjarkaushal1@gmail.com>
 */
class Wordpress_Image_Slider_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode('image_gallery', array($this, 'wis_slider_options'));

	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'sass/wis-front.css', array(), $this->version, 'all' );		

	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wis-front.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'wis-front', 'pic_ajax_object', array(
        	'ajax_url' => admin_url( 'admin-ajax.php' ),
        	'nonce'    => wp_create_nonce( 'wis_ajax_nonce' ),
    		) 
		);

	}
	public function wis_slider_options($atts) {
		$id = intval($atts['id']);
		if(empty($id)) return;

        $options = get_post_meta($id,'wis_slider_options',true);

		if(empty($options)) return; 

        $timer = isset($options['slider_timer']) ? (int)$options['slider_timer'] * 1000 : 3000;

        ob_start();
		
        ?>
		<!-- slider.php -->
		<!-- slider.php -->
		<div class="banner">
			<div class="slider">
				<div class="slides">
					<?php foreach ($options['slides'] as $slide) : ?>
						<div class="slide" style="background-image: url('<?php echo esc_url($slide['url']); ?>');">
							<div class="banner-content">
								<h2 class="banner-header"><?php echo esc_html($slide['title']); ?></h2>
								<hr />
								<p class="banner-description"><?php echo esc_html($slide['description']); ?></p>
								<?php if ($slide['cta_url'] && $slide['cta_text']) : ?>
									<a href="<?php echo esc_url($slide['cta_url']); ?>" class="cta-button"><?php echo esc_html($slide['cta_text']); ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<button class="prev">&#10094;</button>
				<button class="next">&#10095;</button>
			</div>
		</div>

        <?php
        return ob_get_clean();
    }

}
