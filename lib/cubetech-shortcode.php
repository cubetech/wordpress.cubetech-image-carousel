<?php
function cubetech_image_carousel_shortcode($atts)
{
	extract(shortcode_atts(array(
		'orderby' 		=> 'menu_order',
		'order'			=> 'asc',
		'numberposts'	=> 999,
		'offset'		=> 0,
		'poststatus'	=> 'publish',
	), $atts));
	
	$return = '';	

	$return .= '<div class="cubetech-image-carousel-container">';
	$return .= '<div id="left_scroll"></div><div class="cubetech-image-carousel-inner">';
	if ( get_option('cubetech_image_carousel_show_groups') != false )
		$return .= '<h2>' . $tax->name . '</h2>';
	
	$args = array(
		'posts_per_page'  	=> 999,
		'numberposts'     	=> $numberposts,
		'offset'          	=> $offset,
		'orderby'         	=> $orderby,
		'order'           	=> $order,
		'post_type'       	=> 'cubetech_imgcarousel',
		'post_status'     	=> $poststatus,
		'suppress_filters' 	=> true,
	);
		
	$posts = get_posts($args);
	
	$return .= cubetech_image_carousel_content($posts);
	
	
	
	if ( get_option('cubetech_image_carousel_show_hr') != false )
		$return .= '<hr />';
	
	$return .= '</div><div id="right_scroll"></div></div><div id="cubetech-image-carousel-bottomline"></div>';
		
	return $return;

}

add_shortcode('cubetech-image-carousel', 'cubetech_image_carousel_shortcode');

function cubetech_image_carousel_content($posts) {

	$contentreturn = '<ul class="cubetech-image-carousel">';
	$slidercontent = '<div class="cubetech-image-carousel-content">';
	
	$i = 0;
	
	foreach ($posts as $post) {
	
		$post_meta_data = get_post_custom($post->ID);
		$terms = wp_get_post_terms($post->ID, 'cubetech_image_carousel_group');
		
		$titlelink = array('', '');
		$title = '<h3 class="cubetech-image-carousel-title">' . $post->post_title . '</h3>';
		
		$image = wp_get_attachment_image($post_meta_data['cubetech_image_carousel_image'][0]);
		$secondimage = false;
		
		$link = '';

		if(isset($post_meta_data['cubetech_image_carousel_externallink'][0]) && $post_meta_data['cubetech_image_carousel_externallink'][0] != '')
			$link = '<span class="cubetech-image-carousel-link"><a href="' . $post_meta_data['cubetech_image_carousel_externallink'][0] . '" target="_blank">' . get_option('cubetech_image_carousel_link_title') . '</a></span>';
		elseif ( $post_meta_data['cubetech_image_carousel_links'][0] != '' && $post_meta_data['cubetech_image_carousel_links'][0] != 'nope' && $post_meta_data['cubetech_image_carousel_links'][0] > 0 )
			$link = '<span class="cubetech-image-carousel-link"><a href="' . get_permalink( $post_meta_data['cubetech_image_carousel_links'][0] ) . '">' . get_option('cubetech_image_carousel_link_title') . '</a></span>';

		$args = array(
		    'post_type' => 'attachment',
		    'numberposts' => null,
		    'post_status' => null,
		    'post_parent' => $post->ID,
		    'exclude' => get_post_thumbnail_id($post->ID),
		);
		$attachments = get_posts($args);
			
		if ( count($attachments) > 0 ) {
			foreach($attachments as $a) {
				$attachments = (Array)$a;
				break;
			}
		}

		$contentreturn .= '
		<li class="cubetech-image-carousel-icon cubetech-image-carousel-slide-' . $i . '">
			' . $image . '
		</li>';
		
		$i++;
	}
	
	
	return $contentreturn . '</ul> ';
	
}
?>