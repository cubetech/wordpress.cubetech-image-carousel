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
	
	if ( get_option('cubetech_image_carousel_show_groups') != false )
		$return .= '<h2>' . $tax->name . '</h2>';
	
	$args = array(
		'posts_per_page'  	=> 999,
		'numberposts'     	=> $numberposts,
		'offset'          	=> $offset,
		'orderby'         	=> $orderby,
		'order'           	=> $order,
		'post_type'       	=> 'cubetech_image_carousel',
		'post_status'     	=> $poststatus,
		'suppress_filters' 	=> true,
	);
		
	$posts = get_posts($args);
	
	$return .= cubetech_image_carousel_content($posts);
	
	$return .= '<div class="cubetech-image-carousel-clear">';
	
	if ( get_option('cubetech_image_carousel_show_hr') != false )
		$return .= '<hr />';
	
	$return .= '</div></div>';
		
	return $return;

}

add_shortcode('cubetech-image-carousel', 'cubetech_image_carousel_shortcode');

function cubetech_image_carousel_content($posts) {

	$contentreturn = '<ul class="cubetech-image-carousel">';
	$imagecontent = '<div class="cubetech-image-carousel-content">';
	
	$i = 0;
	
	foreach ($posts as $post) {
	
		$post_meta_data = get_post_custom($post->ID);
		$terms = wp_get_post_terms($post->ID, 'cubetech_image_carousel_group');
		$function = $post_meta_data['cubetech_image_carousel_function'][0];
		$edu = $post_meta_data['cubetech_image_carousel_edu'][0];
		$mail = $post_meta_data['cubetech_image_carousel_mail'][0];
		$phone = $post_meta_data['cubetech_image_carousel_phone'][0];
		
		$titlelink = array('', '');
		
		$title = '<h3 class="cubetech-image-carousel-title">' . $post->post_title . '</h3>';
		
		$image = get_the_post_thumbnail( $post->ID, 'cubetech-image-carousel-thumb', array('class' => 'cubetech-image-carousel-thumb cubetech-image-carousel-slide-' . $i ) );
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
			$secondimage .= wp_get_attachment_image($attachments['ID'], 'cubetech-image-carousel-thumb', false, array('class' => 'cubetech-image-carousel-thumb-second cubetech-image-carousel-slide-' . $i . '-second' ) );
		}

		$contentreturn .= '
		<li class="cubetech-image-carousel-icon cubetech-image-carousel-slide-' . $i . '">
			' . $image . '
			' . $secondimage . '
			<p>' . $post_meta_data['cubetech_image_carousel_imagetitle'][0] . '</p>
			<span class="cubetech-image-carousel-thumb-active-icon">&nbsp;</span>
		</li>';
		
		$imagecontent .= '
		<div class="cubetech-image-carousel-slide" id="cubetech-image-carousel-slide-' . $i . '">
			' . $title . '
			<p>' . $post->post_content . '</p>
			<p>' . $link . '</p>
		</div>';
		
		$i++;

	}
	
	
	return $contentreturn . '<li class="cubetech-image-carousel-empty">&nbsp;</li><hr /></ul> ' . $imagecontent . '</div>';
	
}
?>
