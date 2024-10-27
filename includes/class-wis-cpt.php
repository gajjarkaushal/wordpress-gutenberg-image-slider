<?php

/**
 * The file that defines WordPress Image Slider Custom Post type in Wordpress
 *
 * A class definition that crete custom post type and functions used across both the
 * backend and public-facing side of the site and the admin area.
 *
 * @link       https://gajjarkaushal.com
 * @since      1.0.0
 *
 * @package    Wordpress_Image_Slider_CPT
 * @subpackage Wordpress_Image_Slider/includes
 * @author     Kaushal Gajjar <gajjarkaushal1@gmail.com>
 */

class Wordpress_Image_Slider_CPT {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        // Register the custom post type on initialization
        add_action('init', array($this, 'wis_register_post_type'));
        
        // Add meta boxes for slider settings
        add_action('add_meta_boxes', array($this, 'wis_slider_settings_meta_box'));
        
        // Save the slider options when the post is saved
        add_action('save_post', array($this, 'wis_save_post'));
        
        // Add a column for shortcodes in the admin post list
        add_filter('manage_wis-slider_posts_columns', array($this, 'wis_shortcode_columns'));
        
        // Populate the shortcode column with the appropriate data
        add_action('manage_wis-slider_posts_custom_column', array($this, 'wis_populate_shortcode_columns'), 10, 2);
    }
    /**
     * Registers the custom post type for the plugin.
     *
     * The function registers a new post type named 'wis-slider' with the WordPress
     * function register_post_type().
     *
     * @since 1.0.0
     */
    public function wis_register_post_type() {
        
        /* Define the labels for the custom post type. */
        $labels = array(
            'name'                  => _x( 'Image Sliders', 'Post type general name', 'wis' ),
            'singular_name'         => _x( 'Image Slider', 'Post type singular name', 'wis' ),
            'menu_name'             => _x( 'Image Sliders', 'Admin Menu text', 'wis' ),
            'name_admin_bar'        => _x( 'Image Slider', 'Add New on Toolbar', 'wis' ),
            'add_new'               => __( 'Add New', 'wis' ),
            'add_new_item'          => __( 'Add New Image Slider', 'wis' ),
            'new_item'              => __( 'New Image Slider', 'wis' ),
            'edit_item'             => __( 'Edit Image Slider', 'wis' ),
            'view_item'             => __( 'View Image Slider', 'wis' ),
            'all_items'             => __( 'All Image Sliders', 'wis' ),
            'search_items'          => __( 'Search Image Sliders', 'wis' ),
            'parent_item_colon'     => __( 'Parent Image Sliders:', 'wis' ),
            'not_found'             => __( 'No Image Sliders found.', 'wis' ),
            'not_found_in_trash'    => __( 'No Image Sliders found in Trash.', 'wis' ),
            'filter_items_list'     => _x( 'Filter Image Sliders list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wis' ),
            'items_list_navigation' => _x( 'Image Sliders list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wis' ),
            'items_list'            => _x( 'Image Sliders list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wis' ),
        );

        /* Define the arguments for the custom post type. */
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'wis-slider' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-slides',
            'supports'           => array( 'title', 'editor' ),
            // 'show_in_rest' => true,
        );

        /* Register the custom post type. */
        register_post_type( 'wis-slider', $args );
    }

    /**
     * Adds a meta box for the slider settings.
     *
     * The function adds a meta box named 'Slider Settings' to the 'wis-slider' post type.
     * The meta box is displayed in the normal context and has a high priority.
     *
     * The callback function is wis_slider_settings_meta_box_callback, which renders the
     * content of the meta box.
     *
     * @since 1.0.0
     */
    public function wis_slider_settings_meta_box(){
        add_meta_box(
            'wis-slider-settings',
            'Slider Settings',
            array($this,'wis_slider_settings_meta_box_callback'),
            'wis-slider',
            'normal',
            'high'
        );
    }
    /**
     * Callback function for the meta box 'Slider Settings'.
     *
     * The function renders the content of the meta box, which is displayed in the normal context and has a high priority.
     *
     * @param WP_Post $post The post object for the current post.
     *
     * @since 1.0.0
     */
    public function wis_slider_settings_meta_box_callback(){
        include(WIS_PATH.'admin/partials/wordpress-image-slider-display.php');
    }

    /**
     * Save the post data.
     *
     * The function is hooked to the 'save_post' action and is used to save the post data.
     *
     * @since 1.0.0
     *
     * @param int     $post_ID The ID of the post being saved.
     */
    public function wis_save_post($post_id){
        // Check nonce for security
        if (!isset($_POST['wis_settings_nonce']) || !wp_verify_nonce($_POST['wis_settings_nonce'], 'wis_nonce_action')) {
            return;
        }

        // Check for auto-saving
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
       

        if(isset($_POST['wis_slider_options']) && !empty($_POST['wis_slider_options'])) {
            $WISData = $this->wis_sanitize_multidimensional_array($_POST['wis_slider_options']);
            if ($WISData) {
                update_post_meta($post_id, 'wis_slider_options', $WISData);
            }    
        }
    }
    /**
     * Recursively sanitizes a multidimensional array.
     *
     * This function iterates over each element of the provided array, recursively
     * sanitizing nested arrays and individual values. It ensures that the data is 
     * safe for storage and output by sanitizing values based on their data type.
     *
     * @param array $data The multidimensional array to sanitize.
     *
     * @return array The sanitized multidimensional array.
     */
    function wis_sanitize_multidimensional_array($data) {
        // Initialize an empty array to hold sanitized data
        $sanitized_data = array();

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Recursively sanitize nested arrays
                $sanitized_data[$key] = $this->wis_sanitize_multidimensional_array($value);
            } else {
                // Sanitize different data types
                $sanitized_data[$key] = $this->wsi_sanitize_value($value);
            }
        }

        return $sanitized_data;
    }
    /**
     * Sanitizes a given value based on its data type.
     *
     * @param mixed $value The value to sanitize.
     *
     * @return mixed The sanitized value.
     */
    function wsi_sanitize_value($value) {
        if (is_string($value)) {
            // Sanitize strings
            return sanitize_text_field($value); // or use esc_html($value) for HTML context
        } elseif (is_numeric($value)) {
            // Return numbers as they are
            return $value;
        } elseif (is_bool($value)) {
            // Return booleans as they are
            return $value;
        } else {
            // Fallback for other data types
            return null; // or return a default value
        }
    }
    /**
     * Adds a new column for the shortcode in the Image Gallery CPT
     *
     * @param array $columns The columns array
     * @return array
     */
    function wis_shortcode_columns($columns) {
        $columns['shortcode'] = __('Shortcode'); // Add new column
        return $columns;
    }
    /**
     * Populates the shortcode column in the Image Gallery CPT
     *
     * @param string $column The column name
     * @param int $post_id The post ID
     */
    function wis_populate_shortcode_columns($column, $post_id) {
        if ($column === 'shortcode') {
            // Display the shortcode with the post ID
            echo '[image_gallery id=' . esc_attr($post_id) . ']';
        }
    }

} 
new Wordpress_Image_Slider_CPT();