<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://koderise.com
 * @since      1.0.0
 *
 * @package    Wordpress_Image_Slider
 * @subpackage Wordpress_Image_Slider/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1><?php esc_html_e('WordPress Image Slider Settings', 'wis'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('custom_image_slider_settings');
        $options = get_option('custom_image_slider_options');
        ?>
        <label for="slider_timer"><?php _e('Slide Timer (seconds)','wis'); ?>:</label>
        <input type="number" name="custom_image_slider_options[slider_timer]" value="<?php echo esc_attr($options['slider_timer'] ?? 3); ?>" min="1">
        
        <h2><?php esc_html_e('Slides', 'wis'); ?></h2>
        <div id="slider_images">
            <?php if (!empty($options['slides'])) : ?>
                <?php foreach ($options['slides'] as $index => $slide) : ?>
                    <div class="slider-image" draggable="true" data-timestamp="<?php echo $index; ?>" style>
                        <input type="hidden" name="custom_image_slider_options[slides][<?php echo $index; ?>][url]" value="<?php echo esc_url($slide['url']); ?>">
                        <img src="<?php echo esc_url($slide['url']); ?>" width="100">
                        <input type="text" name="custom_image_slider_options[slides][<?php echo $index; ?>][title]" placeholder="Title" value="<?php echo esc_attr($slide['title'] ?? ''); ?>">
                        <textarea name="custom_image_slider_options[slides][<?php echo $index; ?>][description]" rows="4" cols="50"><?php echo esc_attr($slide['description'] ?? ''); ?></textarea>
                        <input type="text" name="custom_image_slider_options[slides][<?php echo $index; ?>][cta_text]" placeholder="CTA Button Name" value="<?php echo esc_attr($slide['cta_text'] ?? ''); ?>">
                        <input type="url" name="custom_image_slider_options[slides][<?php echo $index; ?>][cta_url]" placeholder="CTA Button URL" value="<?php echo esc_url($slide['cta_url'] ?? ''); ?>">
                        <button type="button" class="remove-image">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="add_image">Add Image</button>
        <?php submit_button(); ?>
    </form>
</div>