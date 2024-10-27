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
<div class="wis-wrap">
    <?php
    global $post;
    // settings_fields('custom_image_slider_settings');
    $options = get_post_meta($post->ID, 'wis_slider_options',true);

    ?>
    <label for="slider_timer"><?php _e('Slide Timer (seconds)','wis'); ?>:</label>
    <input type="number" name="wis_slider_options[slider_timer]" value="<?php echo esc_attr($options['slider_timer'] ?? 3); ?>" min="1">
    <hr />
    <h3 class='slide-heading'><?php esc_html_e('Slides', 'wis'); ?><button type="button" id="add_image" class="button"><?php _e('Add Image', 'wis'); ?></button></h2>
    <div id="slider_images">
        <?php if (!empty($options['slides'])) : ?>
            <?php foreach ($options['slides'] as $index => $slide) : ?>
                <div class="slider-image" draggable="true" data-timestamp="<?php echo $index; ?>" style>
                    <input type="hidden" name="wis_slider_options[slides][<?php echo $index; ?>][url]" value="<?php echo esc_url($slide['url']); ?>">
                    <img src="<?php echo esc_url($slide['url']); ?>" width="100">
                    <input type="text" name="wis_slider_options[slides][<?php echo $index; ?>][title]" placeholder="Title" value="<?php echo esc_attr($slide['title'] ?? ''); ?>">
                    <textarea name="wis_slider_options[slides][<?php echo $index; ?>][description]" rows="4" cols="50"><?php echo esc_attr($slide['description'] ?? ''); ?></textarea>
                    <input type="text" name="wis_slider_options[slides][<?php echo $index; ?>][cta_text]" placeholder="CTA Button Name" value="<?php echo esc_attr($slide['cta_text'] ?? ''); ?>">
                    <input type="url" name="wis_slider_options[slides][<?php echo $index; ?>][cta_url]" placeholder="CTA Button URL" value="<?php echo esc_url($slide['cta_url'] ?? ''); ?>">
                    <button type="button" class="remove-image button"><?php _e('Remove','wis'); ?></button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <?php wp_nonce_field('wis_nonce_action', 'wis_settings_nonce'); ?>
</div>