/**
 * Login Page Manager
 * 
 * @version 1.0.0
 */
(function( $, window, undefined ) {
    'use strict';

	var settings    = _PLUS_ADMIN.settings;
	
	_PLUS_ADMIN.loginPageManager = {
		init: function(){
			this.remoteBgGalleryDownload();
		},
		remoteBgGalleryDownload: function(){
			var self = this;

			$('#descargaya').on('click',function(e){
				e.preventDefault();
				self.descargalo();
			})

		},
		descargalo: function(){
			var action = 'remotedownloadlagallery';
			var data = {
				nonce: 			plus_admin.nonce, 	// Security nonce
				action: 		action,				// Ajax Action
				// Parameters
				// no parameters
			};

			var $btn 			= $('#descargaya');
			var tmp_btn_value 	= $btn.text();
			var $parent_field 	= $btn.parents('.cssf-field-content');
			$btn.html('<div class="cssf-spinner"></div> Downloading...').addClass('cssf-button--disabled').attr('disabled','disabled');

			$.ajax({
				url: 	plus_admin.ajax_url,
				type: 	'post',
				data: 	data,
				success: function(response){
					if (response.success){
						// _PLUS_ADMIN.notificationCenter.newToast('Menu updated succesfully...');
						_PLUS_ADMIN._debug("LPG:",response.data);
						$parent_field.fadeOut();
					} else {
						_PLUS_ADMIN._debug("LPG - Error:",response.data);
						$parent_field.fadeOut();
					}
				},
				complete: function(){
					$btn.html(tmp_btn_value).removeClass('cssf-button--disabled').removeAttr('disabled');
				}
			});
		}
	}
    
    $(document).ready(function() {
		_PLUS_ADMIN.loginPageManager.init();
		
    });

})( jQuery, window );