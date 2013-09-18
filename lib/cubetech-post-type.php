<?php

function cubetech_image_carousel_create_post_type() {
	register_post_type('cubetech_imgcarousel',
		array(
			'labels' => array(
				'name' => __('Bildkarussell'),
				'singular_name' => __('Bild'),
				'add_new' => __('Bild hinzufügen'),
				'add_new_item' => __('Neues Bild hinzufügen'),
				'edit_item' => __('Bild bearbeiten'),
				'new_item' => __('Neues Bild'),
				'view_item' => __('Bild betrachten'),
				'search_items' => __('Bild durchsuchen'),
				'not_found' => __('Keine Bilder gefunden.'),
				'not_found_in_trash' => __('Keine Bilder gefunden.')
			),
			'capability_type' => 'post',
			'public' => true,
			'has_archive' => false,
			'rewrite' => array('slug' => 'image-carousel', 'with_front' => false),
			'show_ui' => true,
			'menu_position' => '20',
			'menu_icon' => null,
			'hierarchical' => true,
			'supports' => array('title', 'editor', 'thumbnail')
		)
	);
	flush_rewrite_rules();
}

add_action('init', 'cubetech_image_carousel_create_post_type');

?>
