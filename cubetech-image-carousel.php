<?php
/**
 * Plugin Name: cubetech Image Carousel
 * Plugin URI: http://www.cubetech.ch
 * Description: cubetech Image Carousel - simple image/content carousel with featured images
 * Version: 1.0
 * Author: cubetech GmbH
 * Author URI: http://www.cubetech.ch
 */

include_once('lib/cubetech-install.php');
include_once('lib/cubetech-metabox.php');
include_once('lib/cubetech-post-type.php');
include_once('lib/cubetech-settings.php');
include_once('lib/cubetech-shortcode.php');

add_image_size( 'cubetech-image-carousel-thumb', 231, 124, true );

wp_enqueue_script('jquery');
wp_register_script('cubetech_image_carousel_js', plugins_url('assets/js/cubetech-image-carousel.js', __FILE__), 'jquery');
wp_enqueue_script('cubetech_image_carousel_js');

add_action('wp_enqueue_scripts', 'cubetech_image_carousel_add_styles');

function cubetech_image_carousel_add_styles() {
	wp_register_style('cubetech-image-carousel-css', plugins_url('assets/css/cubetech-image-carousel.css', __FILE__) );
	wp_enqueue_style('cubetech-image-carousel-css');
}

/* Add button to TinyMCE */
function cubetech_image_carousel_addbuttons() {

	if ( (! current_user_can('edit_posts') && ! current_user_can('edit_pages')) )
		return;
	
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_cubetech_image_carousel_tinymce_plugin");
		add_filter('mce_buttons', 'register_cubetech_image_carousel_button');
		add_action( 'admin_footer', 'cubetech_image_carousel_dialog' );
	}
}
 
function register_cubetech_image_carousel_button($buttons) {
   array_push($buttons, "|", "cubetech_image_carousel_button");
   return $buttons;
}
 
function add_cubetech_image_carousel_tinymce_plugin($plugin_array) {
	$plugin_array['cubetech_image_carousel'] = plugins_url('assets/js/cubetech-image-carousel-tinymce.js', __FILE__);
	return $plugin_array;
}

add_action('init', 'cubetech_image_carousel_addbuttons');

function cubetech_image_carousel_dialog() { 

	$args=array(
		'hide_empty' => false,
		'orderby' => 'name',
		'order' => 'ASC'
	);
	$taxonomies = get_terms('cubetech_image_carousel_group', $args);
	
	?>
	<style type="text/css">
		#cubetech_image_carousel_dialog { padding: 10px 30px 15px; }
	</style>
	<div style="display:none;" id="cubetech_image_carousel_dialog">
		<div>
			<p><input type="submit" class="button-primary" value="Image Carousel einfügen" onClick="tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[cubetech-image-carousel]'); tinyMCEPopup.close();" /></p>
		</div>
	</div>
	<?php
}

?>
