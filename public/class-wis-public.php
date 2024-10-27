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

		add_shortcode('wis_slider', array($this, 'wis_shortcode'));

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wis-front.css', array(), $this->version, 'all' );		

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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wis-front', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'wis-front', 'pic_ajax_object', array(
        	'ajax_url' => admin_url( 'admin-ajax.php' ),
        	'nonce'    => wp_create_nonce( 'wis_ajax_nonce' ),
    		) 
		);

	}
	private function wis_public_init(){
		

	}
	public function wis_shortcode($atts) {
        $options = get_option('custom_image_slider_options');
        $timer = isset($options['slider_timer']) ? (int)$options['slider_timer'] * 1000 : 3000;

        ob_start();
		
        ?>
        <div class="wordpress-image-slider" data-timer="<?php echo esc_attr($timer); ?>">
            <?php foreach ($options['slides'] as $slide) : ?>
                <div class="slide">
                    <img src="<?php echo esc_url($slide['url']); ?>" alt="<?php echo esc_attr($slide['title']); ?>">
                    <div class="caption">
                        <h2><?php echo esc_html($slide['title']); ?></h2>
                        <p><?php echo esc_html($slide['description']); ?></p>
                        <?php if ($slide['cta_url'] && $slide['cta_text']) : ?>
                            <a href="<?php echo esc_url($slide['cta_url']); ?>" class="cta-button"><?php echo esc_html($slide['cta_text']); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.custom-image-slider');
            const slides = slider.querySelectorAll('.slide');
            const timer = slider.dataset.timer || 3000;
            let index = 0;

            function showNextSlide() {
                slides[index].classList.remove('active');
                index = (index + 1) % slides.length;
                slides[index].classList.add('active');
            }

            setInterval(showNextSlide, timer);
        });
        </script>
        <style>
        .wordpress-image-slider { position: relative; }
        .wordpress-image-slider .slide { display: none; position: absolute; width: 100%; }
        .wordpress-image-slider .slide.active { display: block; }
        .wordpress-image-slider .caption { background: rgba(0, 0, 0, 0.5); padding: 10px; color: #fff; }
        </style>
        <?php
        return ob_get_clean();
    }

}
