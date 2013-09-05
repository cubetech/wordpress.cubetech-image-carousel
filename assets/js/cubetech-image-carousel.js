jQuery(function() {

	var cubetechTimeOut;
	jQuery('.cubetech-image-carousel').mouseover( function() {
		clearTimeout(cubetechTimeOut);
	});
	jQuery('.cubetech-image-carousel').mouseout( function() {
		cubetechTimeOut = setTimeout(cubetechShowDiv, 2500);
	});
	jQuery('.cubetech-image-carousel-content').mouseover( function() {
		clearTimeout(cubetechTimeOut);
	});
	jQuery('.cubetech-image-carousel-content').mouseout( function() {
		cubetechTimeOut = setTimeout(cubetechShowDiv, 2500);
	});

	cubetechShowDiv();
	
	jQuery('.cubetech-image-carousel-slide').first().fadeIn();
	jQuery('.cubetech-image-carousel-thumb-second').first().fadeIn();
	jQuery('.cubetech-image-carousel-thumb-active-icon').first().addClass('cubetech-image-carousel-thumb-active');
	jQuery('.cubetech-image-carousel-icon').first().addClass('cubetech-image-carousel-active');

	function cubetechShowDiv() {
	    if(jQuery('.cubetech-image-carousel-slide').length) {
	    	var cubetechID = jQuery('.cubetech-image-carousel-slide:visible').index();
	    	
	    	jQuery('.cubetech-image-carousel-slide').fadeOut(100);
	    	jQuery('.cubetech-image-carousel-thumb-second').fadeOut(100);
	    	jQuery('.cubetech-image-carousel-thumb-active-icon').removeClass('cubetech-image-carousel-thumb-active');
	    	jQuery('.cubetech-image-carousel-icon').removeClass('cubetech-image-carousel-active');
	    	
	    	if (jQuery('.cubetech-image-carousel-slide').length == cubetechID + 1) var cubetechID = -1;
	    	
	    	jQuery('.cubetech-image-carousel-slide').eq(cubetechID + 1).fadeIn(200);
	    	jQuery('.cubetech-image-carousel-thumb-second').eq(cubetechID + 1).fadeIn(200);
	    	jQuery('.cubetech-image-carousel-thumb-active-icon').eq(cubetechID + 1).addClass('cubetech-image-carousel-thumb-active');
	    	jQuery('.cubetech-image-carousel-icon').eq(cubetechID + 1).addClass('cubetech-image-carousel-active');
	    	
	        cubetechTimeOut = setTimeout(cubetechShowDiv, 5000);
	    }
	}
	
	jQuery('li.cubetech-image-carousel-icon').hover(function() {
		var cubetechHoverID = jQuery(this).index();
		jQuery('.cubetech-image-carousel-slide').not(':eq(' + cubetechHoverID + ')').fadeOut(100);
		jQuery('.cubetech-image-carousel-thumb-second').not(':eq(' + cubetechHoverID + ')').fadeOut(100);
		jQuery('.cubetech-image-carousel-thumb-active-icon').not(':eq(' + cubetechHoverID + ')').removeClass('cubetech-image-carousel-thumb-active');
		jQuery('.cubetech-image-carousel-icon').not(':eq(' + cubetechHoverID + ')').removeClass('cubetech-image-carousel-active');

		jQuery('.cubetech-image-carousel-slide').eq(cubetechHoverID).fadeIn(200);
		jQuery('.cubetech-image-carousel-thumb-second').eq(cubetechHoverID).fadeIn(200);
		jQuery('.cubetech-image-carousel-thumb-active-icon').eq(cubetechHoverID).addClass('cubetech-image-carousel-thumb-active');
		jQuery('.cubetech-image-carousel-icon').eq(cubetechHoverID).addClass('cubetech-image-carousel-active');
	});

});