jQuery( function ( $ ) {
	'use strict';
	
	// ======================================================
	// Spinner field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_SPINNER = function() {
		return this.each( function() {
			
			var $this   = $(this),
			$input  = $this.find('input'),
			$inited = $this.find('.ui-spinner-button');
			
			if( $inited.length ) {
				$inited.remove();
			}
			
			$input.spinner({
				max: $input.data('max') || 100,
				min: $input.data('min') || 0,
				step: $input.data('step') || 1,
				spin: function (event, ui ) {
					$input.val(ui.value).trigger('change');
					$(this).change();
				}
			});
			
			
		});
	};
	
	
	/**
	* Define Fields Methods
	*/
	var Spinner_field = {
		init: function(){
			$('.cssf-field-spinner','body').CSSFRAMEWORK_SPINNER()
		},
		reload: function(){
			this.init();
		}
	}
	
	
	/**
	* Register the new field on CSSF Framework
	*/
	var CSSFRAMEWORK = {
		test: {
			init: function(){
				Spinner_field.init();
			},
			reload: function(){
				Spinner_field.reload();
			}
		}
	}
	// Update Framework Plugins list
	$.CSSFRAMEWORK.PLUGINS = $.extend(true, $.CSSFRAMEWORK.PLUGINS, CSSFRAMEWORK );
});