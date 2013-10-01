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
	$('#cptr-meta-box .cptr_button').click(function(){ 
			
		// make the call (Yo, Wazzzaaaaaa?)
		$.ajax({
			type: "post",
			url: cptrSettings.ajaxurl,
			data: {
				action: 'cptr-cats',
				cptr_post_type: $('#posttype').val(),
				postID: $('#h_pid').val(),
				howMany: $('#howmany').val(),
				orderBy: $('#orderby').val(),
				orderIn: $('#orderin').val(),
				filtered: $('#filtered').val(),
			},
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
					.html(cptrSettings.btn_Added_text);

			}//success	
		});//ajax		
		
	}); //change
			
	// clone items (avoid duplicates as well)
	$('#cptr-meta-box').on('click', '.addme', function(e) {

		e.preventDefault();

		var post = $(this).parents('div.thepost');
		var postId = post.attr("id");

		if ( $("#related-posts").find( "#" + postId ).size() ) 
			return false;
			
		post.clone()
			.fadeIn('normal')
			.appendTo('#related-posts')
			.find('a.addme')
			.html(cptrSettings.btn_Remove_text)
			.removeClass('addme')
			.addClass('removeme')
			.siblings('input')
			.attr('name','reladded[]');

		$(this).removeClass('addme')
			.addClass('added')
			.html(cptrSettings.btn_Added_text);
		
		return false;
	});	
	
	// remove items
	$('#cptr-meta-box').on('click', '.removeme', function(e) {

		e.preventDefault();

		var post = $(this).parents('div.thepost');
		var postId = post.attr("id");
		post.fadeOut('normal', function() { 
			$(this).remove(); 
		});
		
		$('#available-posts div#' + postId).find('a')
			.removeClass('added')
			.addClass('addme')
			.html(cptrSettings.btn_Add_text);
			
		return false;
	});

	// oh well, people do click a lot
	$('#cptr-meta-box').on('click', '.added', function(e) {

		e.preventDefault();

		alert(cptrSettings.duplicate_post_text);
	});
	
	
	
}); //ready