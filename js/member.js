$ = jQuery;
var library_member_edit_form_submit = true;
$(function(){
	$form_member = $( ".member-register-form" );

	$('body').on('click', ".member-register-form input[type='submit']", function(){
		message_edit_form_submit = true;		
	});
	
	$('body').on('click', ".member-register-form input[type='file']", function(){
		message_edit_form_submit = false;		
	});
	
	$form_member.submit(function(){
		var $this = $(this);
		
		if ( message_edit_form_submit ) {
			$this.prop('action', '');
		}
		else{			
			$this.prop('action', '/library/api?call=fileUpload');
			
			/*
			$(".send .message-send .buttons > span.file-upload .text").toggle();
			$(".send .message-send .buttons > span.file-upload .loader").toggle();
			$(".send .message-send .buttons > span.file-upload input[type='file']").toggle();
			*/
			library_ajax_file_upload( $this, callback_profile_photo_ajax_file_upload );
			return false;
		}
	 });
});



function library_ajax_file_upload($form, callback_message_function)
{	
    var $upload_progress = $(".ajax-file-upload-progress-bar");
    $form.ajaxSubmit({
        beforeSend: function() {
            //console.log("seforeSend:");
            $upload_progress.show();
            var percentVal = '0%';
            $upload_progress.find('.percent').width(percentVal);
            $upload_progress.find('.caption').html('Upload: 0%');
        },
        uploadProgress: function(event, position, total, percentComplete) {
            //console.log("while uploadProgress:" + percentComplete + '%');
            var percentVal = percentComplete + '%';
            $upload_progress.find('.percent').width(percentVal);
            $upload_progress.find('.caption').html('Upload: ' + percentVal);
        },
        success: function() {
            //console.log("upload success:");
            var percentVal = '100%';
            $upload_progress.find('.percent').width(percentVal);
            $upload_progress.find('.caption').html('Upload: ' + percentVal);
        },
        complete: function(xhr) {
            //console.log("Upload completed!!");
            var re;
            try {
                re = JSON.parse(xhr.responseText);
            }
            catch ( e ) {
                return alert( xhr.responseText );
            }
            // console.log(re);
            callback_message_function( $form, re );
			
            setTimeout(function(){
                $upload_progress.hide();												
            }, 500);
			/*
            $.each($form.find("input[type='file']"), function(i, v){
                var name = $(this).prop('name');
                var markup = "<input type='file' name='" + name + "' multiple onchange='jQuery(this).parent().submit();'>";
                $(this).replaceWith(markup);
            });
			*/
        }
    });
}

function callback_profile_photo_ajax_file_upload( $form, re ){
	var data;
    try {
        data = JSON.parse(re);
    }
    catch (e) {
        alert(re);
        return;
    }
	console.log( data );
	if( data.code == 0 ){
		$( ".member-register-form input[name='fid']" ).val( data['files'][0]['fid'] );
		//console.log( data['files'][0]['thumbnails']['url_medium'] );
		//primary-photo
		$( ".member-register-form .primary-photo .photo > img" ).prop( "src", data['files'][0]['thumbnails']['url_medium']);
		$( ".member-register-form .primary-photo .photo > img" ).removeClass("fake");
	}
	else{
		alert( data.error );
	}
}