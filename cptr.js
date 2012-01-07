jQuery(document).ready(function($) {
	
	// make the related posts list sortable
	jQuery('#related-posts').sortable();	
		
	// once a category is selected, give the user the option to filter the results
	$("#filtered").keyup(function() {
		var value = $(this).val();
		value =  $.trim(value);
		value = value.replace(/ /gi, '|');
		$("#available-posts div").each(function () {
			if ($(this).attr('title').search(new RegExp(value, "i")) < 0) {
				$(this).fadeOut('fast'); 
			} 
			else { 
				$(this).fadeIn('fast'); 
			}
		});
	})
	
	// fetch posts per category selected
	$('.cptr_button').click(function(){ 
			
		// make the call (Yo, Wazzzaaaaaa?)
		$.ajax({
			type: "post",
			url: AjaxHandler.ajaxurl,
			data: { action: 'cptr-cats', cptr_post_type: $('#posttype').val(), postID: $('#h_pid').val(), howMany: $('#howmany').val(), orderBy: $('#orderby').val(), orderIn: $('#orderin').val()  },
			beforeSend: function() {$("#available-posts").html('Loading...');}, 
			success: function(response){ 
				//dump the list with post from the category selected
				$('div#available-posts').html(response);									
				//highlight as added the ones already in the list
				var $set2 = $('#related-posts');				
				var $duplicates = $.grep($('#available-posts > div'), function(el) {
	 				return $set2.children('#' + el.id).length > 0;
				});				
				
				$($duplicates).find('a')
					.removeClass('addme')
					.addClass('added')
					.html('Added');
													
			}//success	
		});//ajax		
		
	}); //change
			
	// clone items (avoid duplicates as well)
	$('.addme').live('click', function() {
		var post = $(this).parents('div.thepost');
		var postId = post.attr("id");

		if ( $("#related-posts").find( "#" + postId ).size() ) 
			return false;
			
		post.clone()
			.fadeIn('normal')
			.appendTo('#related-posts')
			.find('a.addme')
			.html('Remove')
			.removeClass('addme')
			.addClass('removeme')
			.siblings('input')
			.attr('name','reladded[]');

		$(this).removeClass('addme')
			.addClass('added')
			.html('Added');
		
		return false;
	});	
	
	// remove items
	$('.removeme').live('click', function() {
		var post = $(this).parents('div.thepost');
		var postId = post.attr("id");
		post.fadeOut('normal', function() { 
			$(this).remove(); 
		});
		
		$('#available-posts div#' + postId).find('a')
			.removeClass('added')
			.addClass('addme')
			.html('Add');
			
		return false;
	});

	// oh well, people do click a lot
	$('.added').live('click', function() {
		alert('This post is already in your list');
		return false;
	});
	
	
	
}); //ready