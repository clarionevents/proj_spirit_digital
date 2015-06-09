jQuery	=	jQuery.noConflict();
jQuery(document).ready(function(){
	
	jQuery('.ajax-fancybox').fancybox({
		hideOnContentClick : false,
		width:'75%',
		height:'90%',
		autoDimensions: true,
		type : 'ajax',// type may be 'iframe' and 'ajax'
		showTitle: false,
		scrolling: 'no',
	});
});

function showOptions(id){
	jQuery('#fancybox'+id).trigger('click');
}