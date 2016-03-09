jQuery.noConflict();
(function( $ ) {

	$(document).ready(function() {  

	    $('.icon-button').click(function() {  
		    var send_attachment_bkp = wp.media.editor.send.attachment;
		    console.log(send_attachment_bkp);
			var button = $(this);
			wp.media.editor.send.attachment = function(props, attachment) {
				button.prev().val(attachment.id);
				button.prev().prev().attr('src', attachment.url);
				button.hide();
				button.next().show();
				wp.media.editor.send.attachment = send_attachment_bkp;
			}
			wp.media.editor.open(button);
			return false;
	    }); 
	    $('.remove-icon').click(function(){
			var r = confirm("Are you sure?");
			if (r == true) {
				$(this).prev().show();
				$(this).prev().prev().val('');
				$(this).prev().prev().prev().attr('src', '');
				$(this).hide();
			}
			return false;
		});
	}); 

})(jQuery);
