jQuery( function ( $ ) {
	'use strict';

	// ======================================================
	// OEmbed field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_OEMBED = function() {
		return this.each(function(){
			var $this 		= $(this),
				$input 		= $this.find('input.cssf-oembed-search'),
				$clear_btn	= $this.find('a.cssf-clear-button');

			var oembed_timeout;

			var clear_oembed = function($context){
				var $embed_search	= $context.find('input.cssf-oembed-search'),
					$embed_input 	= $context.find('input.cssf-oembed-value'),
					$embed_wrapper 	= $context.find('.cssf-oembed-canvas'),
					$embed_preview 	= $embed_wrapper.find('.cssf-oembed-canvas-media'),
					$loader 		= $embed_wrapper.find('.cssf-loader');

				$embed_search.val('');
				$embed_input.val('');
				$embed_preview.html('').addClass('hidden');
				$loader.removeClass('hidden');
			}
	
			var get_oembed = function($context, e) {
				var oembed_url 		= $context.find('input.cssf-oembed-search').val(),
					$embed_input 	= $context.find('input.cssf-oembed-value'),
					$embed_wrapper 	= $context.find('.cssf-oembed-canvas'),
					$embed_preview 	= $embed_wrapper.find('.cssf-oembed-canvas-media'),
					$loader 		= $embed_wrapper.find('.cssf-loader');
	
				if (oembed_url.length < 6) {
					clear_oembed($context);
					return;
				}
	
				var $loader_temp 	= $loader.html();
				var $spinner 		= '<div class="cssf-spinner"></div> Loading...';
				$loader.html($spinner);

				// AJAX Call
				var action = 'cssf-oembed-handler';
				var data = {
					nonce: 			cssf_framework.nonce, 	// Security nonce
					action: 		action,					// Ajax Action
					// Parameters
					oembed_url:	 	oembed_url,
					width: 			$embed_wrapper.attr('data-preview-width'),
					height: 		$embed_wrapper.attr('data-preview-height')
				};
				$.ajax({
					url: 		cssf_framework.ajax_url,
					type: 		'POST',
					dataType: 	'json',
					data: 		data,
					success: function(response) {
						$embed_preview.html(response.data.embed).removeClass('hidden');
						if (response.success){
							$embed_input.val(oembed_url);
						}
					},
					complete: function(){
						$loader.html($loader_temp).addClass('hidden');
					}
				});
			};

			$clear_btn.on('click',function(e){
				e.preventDefault();
				var $context = $input.parents('.cssf-field-oembed');
				clear_oembed($context);
			});

			$input.on('input', function(e){
				e.preventDefault();

				var $input = $(this);
				if (oembed_timeout) {
					clearTimeout(oembed_timeout);
				}

				oembed_timeout = setTimeout(function(){
					var $context = $input.parents('.cssf-field-oembed');
					get_oembed($context, e);
				}, 500);
			});
		});
	};


	/**
	 * Define Fields Methods
	 */
	var OEmbed_field = {
		init: function(){
			$('.cssf-field-oembed', 'body').CSSFRAMEWORK_OEMBED();
		},
		reload: function(){
			this.init();
		}
	}


	/**
	 * Register the new field on CSSF Framework
	 */
	var CSSFRAMEWORK = {
		OEmbed: {
			init: function(){
				OEmbed_field.init();
			},
			reload: function(){
				OEmbed_field.reload();
			}
		}
	}
	// Update Framework Plugins list
	$.CSSFRAMEWORK.PLUGINS = $.extend(true, $.CSSFRAMEWORK.PLUGINS, CSSFRAMEWORK );
});
