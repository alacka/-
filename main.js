js/admin_js/main.js




//возвращаем live метод 
jQuery.fn.live = function (types, data, fn) {
	jQuery(this.context).on(types,this.selector,data,fn);
	return this;
};

(function($){ 
	$(function(){ 
		if ( $( ".sortabled-dvt" ).length > 0 ) {
            $( ".sortabled-dvt" ).each(function(){
                $(this).sortable();
                console.log('1111')
                
            });
		}
		  
		
		$('.add_new_image').click(function(){
		    $(this).prev('div').append('<p><input type="hidden" name="'+$(this).data('name')+'" value=""  size="27" /><input type="button" value="Загрузить" class="specialclass button"></p>');
		});
		
		
		$('span.lopad').live( 'click' , function(){  
			$(this).parent('p').children('input').val('');
			$(this).parent('p').children('input').css("display","none");
			$(this).parent('p').children('img').remove();
			$(this).unwrap();
			$(this).remove();
		});  
		
		
		var media_frame;
		var formlabel = 0;
		
		// Bind to our click event in order to open up the new media experience.
		$(document.body).on('click.crOpenMediaManager', '.specialclass', function(e){ //open-media is the class of our form button
			// Prevent the default action from occuring.
			e.preventDefault();
			// Get our Parent element
			formlabel = jQuery(this).parent();
			// If the frame already exists, re-open it.
			if ( media_frame ) {
				media_frame.open();
				return;
			}
			media_frame = wp.media.frames.media_frame = wp.media({
				
				//Create our media frame
				className: 'media-frame cr-media-frame',
				frame: 'select', //Allow Select Only
				multiple: false, //Disallow Mulitple selections
				library: {
					type: 'image' //Only allow images
				},
			});
			media_frame.on('select', function(){
				// Grab our attachment selection and construct a JSON representation of the model.
				var media_attachment = media_frame.state().get('selection').first().toJSON();
				
				// Send the attachment URL to our custom input field via jQuery.
				
				formlabel.find('input[type="hidden"]').val(media_attachment.id);
				formlabel.find('input[type="hidden"]');
				formlabel.find('input.specialclass,label,br').remove();
				formlabel.prepend('<img class="lpoad" src="'+ media_attachment.url +'" alt="" width="260px"><span class="lopad"></span>');
			});
			
			
			// Now that everything has been set, let's open up the frame.
			media_frame.open();
		});
		
		
		
	});  
})(jQuery) 	

