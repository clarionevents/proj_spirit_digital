(function($j) {
	
			  $j('.product-slider').flexslider({
			    animation: "slide",
			    animationLoop: false,
			    slideshow: false,
			    itemWidth: 146,
			    itemMargin: 10,
			    minItems: 2,
			    maxItems: 4,
			    controlNav:false
			  });
   
   $j('.img-direction-nav li').on('click',function(){
			  	currentVisible = $j('.product-image-gallery img.visible');
			  	dataIndex =  currentVisible.attr('id');
			  	if(dataIndex == 'image-main'){
			  		
			  		dataIndex = 0;
			  	}else{
			  		dataIndex = dataIndex.split("-");
			  		dataIndex = parseInt(dataIndex[1],10);
			  		
			  	}
			  	
			  	
			  	if($j(this).hasClass('img-nav-prev')){
			  			dataIndex --;
			  		}else if($j(this).hasClass('img-nav-next')){
			  			dataIndex ++;
			  		}
			  	
			  	if($j('#image-' + (dataIndex)).get(0)){
			  		$j('.product-image-thumbs li.item-' + (dataIndex) + ' a').click();
			  	}
			  	
			  	// var slider = $j('.product-slider').data('flexslider');
				// var animationSpeed = slider.vars.animationSpeed; 	//save animation speed to reset later
				// slider.vars.animation = 'slide';
				// slider.vars.animationSpeed = 2;
				// slider.vars.animationDuration = 600;
				// slider.flexAnimate(dataIndex, true); 				//position index for desired slide goes here
				// slider.vars.animationSpeed = animationSpeed;
				
			  });
		  
		  	  $j('.product-slider').on('mouseenter',function(){
		  	  	
		  	  	$j('.product-slider .flex-direction-nav a').css({
		  	  		'opacity':1
		  	  	});
		  	  });
		  	  
		  	   $j('.product-slider').on('mouseleave',function(){
		  	  	
		  	  	$j('.product-slider .flex-direction-nav a').css({
		  	  		'opacity':0
		  	  	});
		  	  });
})(jQuery);