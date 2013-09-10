<?php

// Add the Meta Box
function add_cubetech_image_carousel_meta_box() {
	add_meta_box(
		'cubetech_image_carousel_meta_box', // $id
		'Details des Inhaltes', // $title 
		'show_cubetech_image_carousel_meta_box', // $callback
		'cubetech_imgcarousel', // $page
		'normal', // $context
		'high'); // $priority
}
add_action('add_meta_boxes', 'add_cubetech_image_carousel_meta_box');

// Field Array
$prefix = 'cubetech_image_carousel_';

$args = array( 'posts_per_page' => -1, 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'post', 'order' => 'ASC', 'orderby' => 'title' ); 
$postlist = get_posts( $args );

$args = array( 'posts_per_page' => -1, 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'page', 'order' => 'ASC', 'orderby' => 'title' ); 
$pagelist = get_posts( $args );

$options = array();
array_push($options, array('label' => 'Keine interne Verlinkung', 'value' => 'nope'));
array_push($options, array('label' => '', 'value' => false));

array_push($options, array('label' => '----- Beiträge -----', 'value' => false));
foreach($postlist as $p) {
	array_push($options, array('label' => $p->post_title, 'value' => $p->ID));
}

array_push($options, array('label' => '', 'value' => false));
array_push($options, array('label' => '----- Seiten -----', 'value' => false));
foreach($pagelist as $p) {
	array_push($options, array('label' => $p->post_title, 'value' => $p->ID));
}

$cubetech_image_carousel_meta_fields = array(
	array(
		'label'=> 'Verlinkung intern',
		'desc'	=> 'Interne Seiten und Beiträge',
		'id'	=> $prefix.'links',
		'type'	=> 'select',
		'options' => $options,
	),
	array(
		'label'=> 'Verlinkung extern',
		'desc'	=> 'Externe Verlinkung (mit http://) – wird vor interner Verlinkung priorisiert wenn ausgefüllt',
		'id'	=> $prefix.'externallink',
		'type'	=> 'text'
	),
	array(  
	    'label'  => 'Bild',  
	    'desc'  => 'Bild im Slider',  
	    'id'    => $prefix.'image',  
	    'type'  => 'image'  
	) 
);

// The Callback
function show_cubetech_image_carousel_meta_box() {
global $cubetech_image_carousel_meta_fields, $post;
// Use nonce for verification
echo '<input type="hidden" name="cubetech_image_carousel_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	
	// Begin the field table and loop
	echo '<table class="form-table">';
	foreach ($cubetech_image_carousel_meta_fields as $field) {
		// get value of this field if it exists for this post
		$meta = get_post_meta($post->ID, $field['id'], true);
		// begin a table row with
		echo '<tr>
				<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
				<td>';
				switch($field['type']) {
					// text
					case 'text':
						echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// textarea
					case 'textarea':
						echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// select
					case 'select':
						echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
						foreach ($field['options'] as $option) {

							if($meta == $option['value'] && $option['value'] != '') {
								$selected = ' selected="selected"';
							} elseif ($option['value'] == 'nope') {
								$selected = ' selected="selected"';
							} else {
								$selected = '';
							}
							echo '<option' . $selected . ' value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						echo '</select><br /><span class="description">'.$field['desc'].'</span>';
					break;
					// image
					case 'image':
						if ($meta) {
							$image = wp_get_attachment_image_src($meta, 'medium');
							$image = '<img src="' . $image[0] . '" class="cubetech-preview-image" alt="' . $field['id'] . '" style="max-height: 100px;" /><br />';
						} else {
							$image = '<img class="cubetech-preview-image" alt="" style="max-height: 100px;" /><br />';
						}
						echo '
						<input name="' . $field['id'] . '" type="hidden" class="cubetech-upload-image" value="' . $meta . '" />
						' . $image . '
						<input class="cubetech-upload-image-button button" type="button" value="Bild auswählen" />
						<small> <a href="#" class="cubetech-clear-image-button">Bild entfernen</a></small>
						<br clear="all" /><span class="description" style="display: inline-block; margin-top: 5px;">' . $field['desc'] . '</span>';
					break;

				} //end switch
		echo '</td></tr>';
	} // end foreach
	echo '</table>'; // end table
}

// Save the Data
function save_cubetech_image_carousel_meta($post_id) {
    global $cubetech_image_carousel_meta_fields;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['cubetech_image_carousel_meta_box_nonce'], basename(__FILE__))) 
		return $post_id;
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}
	
	// loop through fields and save the data
	foreach ($cubetech_image_carousel_meta_fields as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	} // end foreach
}
add_action('save_post', 'save_cubetech_image_carousel_meta');  