/**
* -----------------------------------------------------------
*
* Castor Studio Framework
* A Lightweight and easy-to-use WordPress Options Framework
*
* Copyright 2018 - 2019 CastorStudio <support@castorstudio.com>
*
* -----------------------------------------------------------
*/
;
(function($, window, document, undefined) {
	'use strict';
	$.CSSFRAMEWORK = $.CSSFRAMEWORK || {};
	
	// caching selector
	var $cssf_body = $('body');
	// caching variables
	var cssf_is_rtl = $cssf_body.hasClass('rtl');
	
	
	// ============================================================================================================
	// CSSFRAMEWORK HELPER FUNCTIONS
	// ------------------------------------------------------
	$.CSSFRAMEWORK.HELPER = {
		FUNCTIONS: {
			string_to_slug: function(str){
				str = str.replace(/^\s+|\s+$/g, ''); // trim
				str = str.toLowerCase();
				
				// remove accents, swap ñ for n, etc
				var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
				var to   = "aaaaeeeeiiiioooouuuunc------";
				
				for (var i=0, l=from.length ; i<l ; i++){
					str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
				}
				
				str = str.replace('.', '-') // replace a dot by a dash 
				.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
				.replace(/\s+/g, '-') // collapse whitespace and replace by a dash
				.replace(/-+/g, '-'); // collapse dashes
				
				return str;
			},
			make_title: function(str) {
				return str.replace(/-/g, " ").replace(/\b[a-z]/g, function () {
					return arguments[0].toUpperCase();
				});
			},
			ucwords: function(str){
				// http://kevin.vanzonneveld.net
				return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
					return $1.toUpperCase();
				});
			},
			checkJSON: function(m){
				if (typeof m == 'object') { 
					try{ m = JSON.stringify(m); }
					catch(err) { return false; } 
				}
				
				if (typeof m == 'string') {
					try{ m = JSON.parse(m); }
					catch (err) { return false; } 
				}
				
				if (typeof m != 'object') { return false; }
				return true;
			},
		},
		COLOR_PICKER: {
			parse: function ($value) {
				var val = $value.replace(/\s+/g, ''),
				alpha = ( val.indexOf('rgba') !== -1 ) ? parseFloat(val.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
				rgba = ( alpha < 100 ) ? true : false;
				return {value: val, alpha: alpha, rgba: rgba};
			}
		},
		CSS_BUILDER: {
			validate: function (val) {
				var s = val;
				if ( $.isNumeric(val) ) {
					return val + 'px';
				} else if ( val.indexOf('px') > -1 || val.indexOf('%') > -1 || val.indexOf('em') > -1 ) {
					var checkPx = s.replace("px", "");
					var checkPct = s.replace("%", "");
					var checkEm = s.replace("em", "");
					if ( $.isNumeric(checkPx) || $.isNumeric(checkPct) || $.isNumeric(checkEm) ) {
						return val;
					} else {
						return "0px";
					}
				} else {
					return '0px';
				}
			},
			
			update: {
				border: function ($el) {
					$el.find('.cssf-css-builder-border').css({
						"border-top-left-radius": $el.find('.cssf-border-radius-top-left :input').val(),
						"border-top-right-radius": $el.find('.cssf-border-radius-top-right :input').val(),
						"border-bottom-right-radius": $el.find('.cssf-border-radius-bottom-left :input').val(),
						"border-bottom-left-radius": $el.find('.cssf-border-radius-bottom-right :input').val(),
						'border-style': $el.find('.cssf-element-border-style select').val(),
						'border-color': $el.find('.cssf-element-border-color input.cssf-field-color-picker').val(),
					});
					
					$el.find('.cssf-css-builder-margin').css({
						'background-color': $el.find('.cssf-element-background-color input.cssf-field-color-picker').val(),
						'color': $el.find('.cssf-element-text-color input.cssf-field-color-picker').val(),
					});
				},
				all: function ($el, $type, $main) {
					var $newVal = $el.val(),
					$val = $.CSSFRAMEWORK.HELPER.CSS_BUILDER.validate($newVal),
					$is_all = $('.cssf-' + $type + '-checkall').hasClass('checked');
					
					if ( $is_all === true ) {
						$main.find('.cssf-element.cssf-' + $type + ' :input').val($val);
					} else {
						$el.val($val);
					}
					$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.border($main);
				},
			}
		},
		LIMITER: {
			counter: function (val, countBy) {
				if ( $.trim(val) == '' ) {
					return 0;
				}
				return countBy ? val.match(/\S+/g).length : val.length;
			},
			subStr: function (val, start, len, subByWord) {
				if ( !subByWord ) {
					return val.substr(start, len);
				}
				var lastIndexSpace = val.lastIndexOf(' ');
				return val.substr(start, lastIndexSpace);
			}
		},
	};
	
	// CSSFRAMEWORK HELPER - SIBLINGS
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_SIBLINGS = function() {
		return this.each( function() {
			
			var $this     = $(this),
			$siblings = $this.find('.cssf--sibling'),
			multiple  = $this.data('multiple') || false;
			
			$siblings.on('click', function() {
				
				var $sibling = $(this);
				
				if( multiple ) {
					
					if( $sibling.hasClass('cssf--active') ) {
						$sibling.removeClass('cssf--active');
						$sibling.find('input').prop('checked', false).trigger('change');
					} else {
						$sibling.addClass('cssf--active');
						$sibling.find('input').prop('checked', true).trigger('change');
					}
					
				} else {
					
					$this.find('input').prop('checked', false);
					$sibling.find('input').prop('checked', true).trigger('change');
					$sibling.addClass('cssf--active').siblings().removeClass('cssf--active');
					
				}
				
			});
			
		});
	};
	
	// CSSFRAMEWORK HELPER - NUMBER: only allow number input
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_NUMBER = function() {
		return this.each( function() {
			$(this).on('input', function( e ) {
				this.value = this.value.replace(/\D/g,'');
			});
		});
	};
	
	// CSSFRAMEWORK HELPER - TOOLTIP
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_TOOLTIP = function() {
		return this.each(function() {
			var placement = (cssf_is_rtl) ? 'right' : 'left';
			var placement = (cssf_is_rtl) ? placement : (($(this).data('tooltipPlacement')) ? $(this).data('tooltipPlacement') : 'top' );
			$(this).cstooltip({
				html: true,
				placement: placement,
				container: 'body'
			});
		});
	};
	
	// CSSFRAMEWORK HELPER - UI DIALOG OVERLAY HELPER
	// ------------------------------------------------------
	if (typeof $.widget !== 'undefined' && typeof $.ui !== 'undefined' && typeof $.ui.dialog !== 'undefined') {
		$.widget('ui.dialog', $.ui.dialog, {
			_createOverlay: function() {
				this._super();
				if (!this.options.modal) {
					return;
				}
				this._on(this.overlay, {
					click: 'close'
				});
			}
		});
	}
	
	// ============================================================================================================
	
	
	
	
	// ======================================================
	// CSSFRAMEWORK TAB NAVIGATION
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_TAB_NAVIGATION = function() {
		return this.each(function() {
			var $this = $(this),
			$nav = $this.find('.cssf-nav'),
			$reset = $this.find('.cssf-reset'),
			$expand = $this.find('.cssf-expand-all');
			
			$nav.find('ul:first a').on('click', function(e) {
				e.preventDefault();
				var $el = $(this),
				$next = $el.next(),
				$target = $el.data('section');
				
				if ($next.is('ul')) {
					$next.slideToggle('fast');
					$el.closest('li').toggleClass('cssf-tab-active');
				} else {
					var $_tab_target = $('#cssf-tab-' + $target);
					$_tab_target.siblings().fadeOut(350).promise().done(function(){
						$_tab_target.fadeIn(350);
					});
					
					$nav.find('a').removeClass('cssf-section-active');
					$el.addClass('cssf-section-active');
					$reset.val($target);
					window.location.hash = $target;
				}
			});
			$expand.on('click', function(e) {
				e.preventDefault();
				$this.find('.cssf-body').toggleClass('cssf-show-all');
				$(this).find('.fa').toggleClass('fa-eye-slash').toggleClass('fa-eye');
			});
			
			var hash = location.hash.slice(1);
			if (hash){
				$('a[data-section="'+hash+'"]',$nav).trigger('click');
			} else {
				$('a',$nav).first().trigger('click');
			}
		});
	};
	// ======================================================
	
	
	// ======================================================
	// CSSFRAMEWORK TAB NAVIGATION - SCROLL TABS
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_NAV_SCROLL_TABS = function(){
		var scrollbar;
		var _navScrollTo = function(nav,item){
			var $nav 				= $('.cssf-nav-wrapper > ul',nav),
			$nav_parent 		= nav.parents('.cssf-body'),
			nav_parent_width 	= $nav_parent.outerWidth(),
			nav_width 			= $nav.outerWidth(true);
			
			if (item == 'nav_prev' || item == 'nav_next'){
				var nav_pos	= nav.data('position'),
				new_pos = nav_parent_width - (nav_parent_width * 0.1);
				
				if (item == 'nav_prev'){
					var new_pos = Math.abs(nav_pos) - new_pos;
				} else if (item == 'nav_next'){
					var new_pos = Math.abs(nav_pos) + new_pos;
				}
			} else {
				var $item 			= item.parents('li'),
				item_width 		= $item.outerWidth(true),
				item_pos		= $item.position().left;
				
				var mid_pos 		= nav_parent_width / 2,
				mid_item_width 	= item_width / 2,
				new_pos 		= item_pos - mid_pos + mid_item_width;
			}
			
			var limit_right 	= nav_width - nav_parent_width;
			
			if (new_pos <= 0){
				new_pos = 0;
			}
			if (new_pos >= limit_right){
				new_pos = nav_width - nav_parent_width;
			}
			
			
			var update_pos = function(position){
				nav.data('position',position);
				nav.get(0).style.setProperty('--nav-pos',position+'px');
			};
			update_pos('-'+new_pos);
			
			scrollbar.scroll({ x : new_pos }, 500, '',function(){});
		};
		
		return this.each(function() {
			var $this = $(this),
			_item = $('a.cssf-section-active',$this),
			_item = (_item.length) ? _item : $('a',$this).eq(0);
			
			scrollbar = $('.cssf-nav-inner-wrapper',$this).overlayScrollbars({
				className: "none",
				overflowBehavior : {
					x : "scroll",
					y : "hidden"
				},
				scrollbars: {
					autoHide: 'leave'
				}
			}).overlayScrollbars();
			
			if (_item.length){
				_navScrollTo($this,_item);
			}
			
			$this.on('click','a',function(e){
				e.preventDefault();
				var _item = $(this);
				_navScrollTo($this,_item);
			});
			
			$this.on('click','.cssf-nav-button',function(e){
				var type 	= $(this).data('type');
				
				if (type == 'prev'){
					_navScrollTo($this,'nav_prev');
				} else if (type == 'next'){
					_navScrollTo($this,'nav_next');
				}
			});
			
			$(window).scroll(function(){
				var wPosY 			= window.scrollY,
				nPosY 			= $this.scrollTop(),
				$cssf_wrapper 	= $('.cssf-framework'),
				$cssf_body 		= $('.cssf-body',$cssf_wrapper),
				offset 			= $cssf_body.offset().top,
				limit 			= offset - 64;
				
				if ($(window).scrollTop() >= limit) {
					$cssf_wrapper.addClass('cssf-fixed-header');
				} else {
					$cssf_wrapper.removeClass('cssf-fixed-header');
				}
			});
		});
	}
	// ======================================================
	
	
	// ======================================================
	// CSSFRAMEWORK DEPENDENCY
	// ------------------------------------------------------
	$.CSSFRAMEWORK.DEPENDENCY = function(el, param) {
		// Access to jQuery and DOM versions of element
		var base = this;
		base.$el = $(el);
		base.el = el;
		base.init = function() {
			base.ruleset = $.deps.createRuleset();
			// required for shortcode attrs
			var cfg = {
				show: function(el) {
					el.removeClass('hidden');
				},
				hide: function(el) {
					el.addClass('hidden');
				},
				log: false,
				checkTargets: false
			};
			if (param !== undefined) {
				base.depSub();
			} else {
				base.depRoot();
			}
			$.deps.enable(base.$el, base.ruleset, cfg);
		};
		base.depRoot = function() {
			base.$el.each(function() {
				$(this).find('[data-controller]').each(function() {
					var $this = $(this),
					_controller = $this.data('controller').split('|'),
					_condition = $this.data('condition').split('|'),
					_value = $this.data('value').toString().split('|'),
					_rules = base.ruleset;
					$.each(_controller, function(index, element) {
						var value = _value[index] || '',
						condition = _condition[index] || _condition[0];
						_rules = _rules.createRule('[data-depend-id="' + element + '"]', condition, value);
						_rules.include($this);
					});
				});
			});
		};
		base.depSub = function() {
			base.$el.each(function() {
				$(this).find('[data-sub-controller]').each(function() {
					var $this = $(this),
					_controller = $this.data('sub-controller').split('|'),
					_condition = $this.data('sub-condition').split('|'),
					_value = $this.data('sub-value').toString().split('|'),
					_rules = base.ruleset;
					$.each(_controller, function(index, element) {
						var value = _value[index] || '',
						condition = _condition[index] || _condition[0];
						_rules = _rules.createRule('[data-sub-depend-id="' + element + '"]', condition, value);
						_rules.include($this);
					});
				});
			});
		};
		base.init();
	};
	$.fn.CSSFRAMEWORK_DEPENDENCY = function(param) {
		return this.each(function() {
			new $.CSSFRAMEWORK.DEPENDENCY(this, param);
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK CHOSEN
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_CHOSEN = function() {
		return this.each( function() {
			var $this       = $(this),
			$inited     = $this.parent().find('.chosen-container'),
			is_sortable = $this.hasClass('cssf-chosen-sortable') || false,
			is_ajax     = $this.hasClass('cssf-chosen-ajax') || false,
			is_multiple = $this.attr('multiple') || false,
			set_width   = is_multiple ? '100%' : 'auto',
			set_options = $.extend({
				allow_single_deselect: true,
				disable_search_threshold: 10,
				width: set_width,
				// no_results_text: window.csf_vars.i18n.no_results_text,
			}, $this.data('chosen-settings'));
			
			if ($inited.length){
				$inited.remove();
			}
			
			// Chosen ajax
			if ( is_ajax ) {
				var set_ajax_options = $.extend({
					data: {
						type: 'post',
						nonce: '',
					},
					allow_single_deselect: true,
					disable_search_threshold: -1,
					width: '100%',
					min_length: 3,
					type_delay: 500,
					// typing_text: window.csf_vars.i18n.typing_text,
					// searching_text: window.csf_vars.i18n.searching_text,
					// no_results_text: window.csf_vars.i18n.no_results_text,
				}, $this.data('chosen-settings'));
				
				$this.CSFAjaxChosen(set_ajax_options);
			} else {
				$this.chosen(set_options);
			}
			
			// Chosen keep options order
			if( is_multiple ) {
				var $hidden_select = $this.parent().find('.cssf-hidden-select');
				var $hidden_value  = $hidden_select.val() || [];
				
				$this.on('change', function(obj, result) {
					
					if( result && result.selected ) {
						$hidden_select.append( '<option value="'+ result.selected +'" selected="selected">'+ result.selected +'</option>' );
					} else if( result && result.deselected ) {
						$hidden_select.find('option[value="'+ result.deselected +'"]').remove();
					}
					
					// Force customize refresh
					if( $hidden_select.children().length === 0 && window.wp.customize !== undefined ) {
						window.wp.customize.control( $hidden_select.data('customize-setting-link') ).setting.set('');
					}
					
					$hidden_select.trigger('change');
					
				});
				
				// Chosen order abstract
				// $this.CSFChosenOrder($hidden_value, true);
			}
			
			// Chosen sortable
			if (is_sortable){
				var $chosen_container = $this.parent().find('.chosen-container');
				var $chosen_choices   = $chosen_container.find('.chosen-choices');
				
				$chosen_choices.bind('mousedown', function( event ) {
					if( $(event.target).is('span') ) {
						event.stopPropagation();
					}
				});
				
				$chosen_choices.sortable({
					items: 'li:not(.search-field)',
					helper: 'orginal',
					cursor: 'move',
					placeholder: 'search-choice-placeholder',
					start: function(e,ui) {
						ui.placeholder.width( ui.item.innerWidth() );
						ui.placeholder.height( ui.item.innerHeight() );
					},
					update: function( e, ui ) {
						var select_options = '';
						var chosen_object  = $this.data('chosen');
						var $prev_select   = $this.parent().find('.cssf-hidden-select');
						
						$chosen_choices.find('.search-choice-close').each( function() {
							var option_array_index = $(this).data('option-array-index');
							$.each(chosen_object.results_data, function(index, data) {
								if( data.array_index === option_array_index ){
									select_options += '<option value="'+ data.value +'" selected>'+ data.value +'</option>';
								}
							});
						});
						
						$prev_select.children().remove();
						$prev_select.append(select_options);
						$prev_select.trigger('change');
					}
				});
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK IMAGE SELECTOR
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_IMAGE_SELECTOR = function() {
		return this.each(function() {
			$(this).find('label').on('click', function() {
				$(this).siblings().find('input').prop('checked', false);
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK SORTER
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_SORTER = function() {
		return this.each(function() {
			var $this = $(this),
			$enabled = $this.find('.cssf-enabled'),
			$disabled = $this.find('.cssf-disabled');
			$enabled.sortable({
				connectWith: $disabled,
				placeholder: 'ui-sortable-placeholder',
				start: function (event, ui) {
					ui.placeholder.height(ui.item.height());
				},
				update: function(event, ui) {
					var $el = ui.item.find('input');
					if (ui.item.parent().hasClass('cssf-enabled')) {
						$el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
					} else {
						$el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
					}
				}
			});
			// avoid conflict
			$disabled.sortable({
				connectWith: $enabled,
				placeholder: 'ui-sortable-placeholder',
				start: function (event, ui) {
					ui.placeholder.height(ui.item.height());
				},
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK MEDIA UPLOADER / UPLOAD
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_UPLOADER = function() {
		return this.each(function() {
			var $this = $(this),
			$add = $this.find('.cssf-add'),
			$input = $this.find('input'),
			wp_media_frame;
			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: $add.data('upload-type')
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					// Grab the selected attachment.
					var attachment = wp_media_frame.state().get('selection').first();
					$input.val(attachment.attributes.url).trigger('change');
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK IMAGE UPLOADER
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_IMAGE_UPLOADER = function() {
		return this.each(function() {
			var $this    = $(this),
			$add     = $this.find('.cssf-add'),
			$preview = $this.find('.cssf-image-preview'),
			$remove  = $this.find('.cssf-remove'),
			$input   = $this.find('input'),
			$img     = $this.find('img'),
			wp_media_frame;
			
			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: 'image'
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					var attachment = wp_media_frame.state().get('selection').first().attributes;
					var preview_size = $preview.data('preview-size');
					if (preview_size == 'custom'){
						var thumbnail = attachment.url;	
					} else {
						if (typeof preview_size === 'undefined') {
							preview_size = 'thumbnail';
						}
						var thumbnail = (typeof attachment['sizes'][preview_size] !== 'undefined') ? attachment['sizes'][preview_size]['url'] : attachment.url;
					}
					$preview.removeClass('hidden');
					$remove.removeClass('hidden');
					$img.attr('src', thumbnail);
					$input.val(attachment.id).trigger('change');
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$input.val('').trigger('change');
				$preview.addClass('hidden');
				$remove.addClass('hidden');
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK IMAGE GALLERY
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_IMAGE_GALLERY = function() {
		return this.each(function() {
			var $this 	= $(this),
			$edit 	= $this.find('.cssf-edit'),
			$remove = $this.find('.cssf-remove'),
			$list 	= $this.find('ul'),
			$input 	= $this.find('input'),
			$img 	= $this.find('img'),
			wp_media_frame,
			wp_media_click;
			
			$this.on('click', '.cssf-add, .cssf-edit', function(e) {
				var $el = $(this),
				what = ($el.hasClass('cssf-edit')) ? 'edit' : 'add',
				state = (what === 'edit') ? 'gallery-edit' : 'gallery-library';
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				//
				// Comentado para forzar que la galeria se actualice cada vez que se abre
				//
				// if (wp_media_frame) {
				// 	wp_media_frame.open();
				// 	wp_media_frame.setState(state);
				// 	return;
				// }
				
				// Create the media frame.
				wp_media_frame = wp.media({
					title: 'Select or Upload Media Of Your Chosen Persuasion',
					button: {
						text: 'Use this media'
					},
					library: {
						type: 'image'
					},
					frame: 'post',
					state: 'gallery',
					multiple: true
				});
				// Open the media frame.
				wp_media_frame.on('open', function() {
					var $input 	= $this.find('input');
					var ids 	= $input.val();
					
					if (ids) {
						var get_array = ids.split(',');
						var library = wp_media_frame.state('gallery-edit').get('library');
						wp_media_frame.setState(state);
						get_array.forEach(function(id) {
							var attachment = wp.media.attachment(id);
							library.add(attachment ? [attachment] : []);
						});
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('update', function() {
					var inner = '';
					var ids = [];
					var images = wp_media_frame.state().get('library');
					images.each(function(attachment) {
						var attributes = attachment.attributes;
						var thumbnail = (typeof attributes.sizes.thumbnail !== 'undefined') ? attributes.sizes.thumbnail.url : attributes.url;
						inner += '<li data-image-id="'+attributes.id+'"><img src="' + thumbnail + '"></li>';
						ids.push(attributes.id);
					});
					$input.val(ids).trigger('change');
					$list.html('').append(inner);
					$remove.removeClass('hidden');
					$edit.removeClass('hidden');
				});
				// Finally, open the modal.
				wp_media_frame.open();
				wp_media_click = what;
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$list.html('');
				$input.val('').trigger('change');
				$remove.addClass('hidden');
				$edit.addClass('hidden');
			});
			
			
			
			// Sortable Funcionality
			// -------------------------------------------------------
			$list.sortable({
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				stop: function(event, ui) {
					var parent 	= ui.item.parents('.cssf-fieldset'),
					input 	= parent.children('input'),
					list 	= parent.children('ul'),
					ids 	= [];
					
					$('li',list).each(function(){
						ids.push($(this).data("imageId"));
					});
					
					// order = order.toString();
					input.val(ids).trigger('change');
				}
			});
			$list.disableSelection();
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK TYPOGRAPHY
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_TYPOGRAPHY = function() {
		return this.each(function() {
			var typography 				= $(this),
			family_select 			= typography.find('.cssf-typo-family'),
			variants_select 		= typography.find('.cssf-typo-variant'),
			typography_type 		= typography.find('.cssf-typo-font'),
			typography_size			= typography.find('.cssf-typo-size'),
			typography_height 		= typography.find('.cssf-typo-height'),
			typography_spacing 		= typography.find('.cssf-typo-spacing'),
			typography_align		= typography.find('.cssf-typo-align'),
			typography_transform 	= typography.find('.cssf-typo-transform'),
			typography_color 		= typography.find('.cssf-typo-color');
			
			family_select.on('change', function() {
				var _this = $(this),
				_type = _this.find(':selected').data('type') || 'custom',
				_variants = _this.find(':selected').data('variants');
				if (variants_select.length) {
					variants_select.find('option').remove();
					$.each(_variants.split('|'), function(key, text) {
						variants_select.append('<option value="' + text + '">' + text + '</option>');
					});
					variants_select.find('option[value="regular"]').attr('selected', 'selected').trigger('chosen:updated');
				}
				typography_type.val(_type);
			});
			
			// Typography Advanced Live Preview
			// ---------------------------------------------
			var preview 		= $(".cssf-typo-preview",typography),
			previewToggle	= $(".cssf-typo-preview-toggle",preview),
			previewId		= $(preview).data("previewId"),
			currentFamily 	= $(this).find('.cssf-typo-family').val();
			
			var livePreviewRefresh = function(){
				var preview_weight 		= variants_select.val(),
				preview_size		= typography_size.val(),
				preview_height		= typography_height.val(),
				preview_spacing		= typography_spacing.val(),
				preview_align 		= typography_align.val(),
				preview_transform	= typography_transform.val(),
				preview_color 		= typography_color.val();
				
				var style = {
					"--cssf-typo-preview-weight":preview_weight,
					"--cssf-typo-preview-size":preview_size+"px",
					"--cssf-typo-preview-height":preview_height+"px",
					"--cssf-typo-preview-spacing":preview_spacing+"px",
					"--cssf-typo-preview-align":preview_align,
					"--cssf-typo-preview-transform":preview_transform,
					"--cssf-typo-preview-color":preview_color
				};
				setPreviewStyle("#"+$(preview).attr("id"),style);
			}
			
			// Update Preview
			// ------------------------------
			if (preview.length){
				$(preview).css("font-family", currentFamily);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + currentFamily +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			}
			
			family_select.on('change',function(){
				$('head').find("."+previewId).remove();
				var font = $(this).val();
				$(preview).css("font-family", font);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + font +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			});
			
			variants_select.on('change',function(){ livePreviewRefresh(); });
			typography_type.on('change',function(){ livePreviewRefresh(); });
			typography_size.on('change',function(){ livePreviewRefresh(); });
			typography_height.on('change',function(){ livePreviewRefresh(); });
			typography_align.on('change',function(){ livePreviewRefresh(); });
			typography_color.on('change',function(){ livePreviewRefresh(); });
			typography_spacing.on('change',function(){ livePreviewRefresh(); });
			typography_transform.on('change',function(){ livePreviewRefresh(); });
			
			// Toggle Preview BG Style
			// ------------------------------
			$(previewToggle).on("click",function(){
				$(preview).toggleClass("cssf-typo-preview-toggle_dark");
			});
			
			
			
			//-----------------------------------------------------------------
			// HELPER FUNCTIONS
			//-----------------------------------------------------------------
			function setPreviewStyle( element, propertyObject ){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.setProperty(property, propertyObject[property]);
				}
			}
			
			function removeStyle( element, propertyObject){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.removeProperty(propertyObject[property]);
				}
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK GROUP
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_GROUP = function () {
		return this.each( function () {
			var $this = $( this ),
			$elem = $( this );
			
			if ( $this.find( '> .cssf-fieldset' ).length > 0 ) {
				$elem = $this.find( '> .cssf-fieldset' );
			}
			
			var field_groups 	= $elem.find( '> .cssf-groups' ),
			accordion_group = $elem.find( ' > .cssf-accordion' ),
			clone_group 	= $elem.find( '.cssf-group:first' ).clone();
			
			var _first 			= $elem.find( '.cssf-group:first' );
			
			var $heading = field_groups.find( '> .cssf-group > .cssf-group-title-wrapper' );
			
			if ( accordion_group.length ) {
				accordion_group.each( function () {
					$( this ).accordion( {
						header: '> .cssf-group > .cssf-group-title-wrapper',
						collapsible: true,
						active: false,
						animate: 250,
						heightStyle: 'content',
						icons: {
							'header': 'dashicons dashicons-arrow-right',
							'activeHeader': 'dashicons dashicons-arrow-down'
						},
						beforeActivate: function (event, ui) {
							$( ui.newPanel ).CSSFRAMEWORK_DEPENDENCY( 'sub' );
							if (isSorting){
								isSorting = false;
								return false;
							}
						}
					} );
				} );
			}
			
			var isSorting = false;
			var sortable_settings = {
				handle: '.cssf-group-title-wrapper',
				helper: 'original',
				placeholder: 'widget-placeholder',
				start: function (event, ui) {
					isSorting = true;
					
					var inside = ui.item.children( '.cssf-group-content' );
					if ( inside.css( 'display' ) === 'block' ) {
						// inside.hide();
						field_groups.sortable( 'refreshPositions' );
					}
					ui.placeholder.height(ui.item.height());
				},
				stop: function (event, ui) {
					$(ui.item).find('textarea').each(function(){
						var default_wysiwyg_id    = $(this).data('original_id');
						var editor_id 				= $(this).attr('id');
						var tmp_wysiwyg_settings  = tinyMCEPreInit.mceInit[default_wysiwyg_id];
						
						tinyMCE.execCommand('mceRemoveEditor',false, editor_id );
						tinymce.init(tmp_wysiwyg_settings);
						tinyMCE.execCommand('mceAddEditor',false, editor_id );
					});
					
					// Para colapsar y quitar el foco al elemento activo del accordion
					// ui.item.children('.cssf-group-title-wrapper').triggerHandler( 'focusout' );
					// accordion_group.accordion({
					// 	active: false
					// });
				}
			};
			
			field_groups.sortable(sortable_settings);
			
			
			
			
			$elem.find( '> .cssf-add-group' ).on( 'click', function (e) {
				e.preventDefault();
				
				// NUEVAS VARIABLES 2019
				var $this     				= $(this),
				$g_parent 				= $this.parents('.cssf-element-group'),
				$g_parent				= $this.parent(),
				$group_wrapper 			= $g_parent.children('.cssf-group'),
				group_id  				= $group_wrapper.attr('data-field-id'),
				group_unique_id 		= $group_wrapper.attr('data-unique-id'),
				
				$groupfields_wrapper 	= $g_parent.children('.cssf-groups'),
				field_id  				= $groupfields_wrapper.attr('data-field-id'),
				unique_id 				= $groupfields_wrapper.attr('data-unique-id'),
				data_count 				= $this.attr('data-count'),
				current_index 			= (data_count == 'initial') ? 'initial' : parseInt(data_count),
				new_index 				= (data_count == 'initial') ? 0 : current_index + 1;
				
				
				var current_unique_id 	= field_id + unique_id +"["+current_index+"]",
				new_unique_id 		= field_id + unique_id +"["+new_index+"]",
				current_nonce_id 	= group_id + "[_nonce]"+ group_unique_id +"["+current_index+"]",
				new_nonce_id 		= group_id + "[_nonce]"+ group_unique_id +"["+new_index+"]";
				// current_nonce_id 	= field_id + unique_id +"["+current_index+"][_nonce]",
				// new_nonce_id 		= field_id + unique_id +"["+new_index+"][_nonce]";
				
				var all_fields = [];
				var nonce_fields = [];
				
				var renombralo = function(target,is_nonce = false,is_normal_field = false){
					var is_nonce = (is_nonce) ? true : false;
					
					var $element 	= $(target),
					is_group 	= ($element.hasClass('cssf-element-group')) ? true : false,
					is_fieldset = ($element.hasClass('cssf-element-fieldset')) ? true : false,
					has_title 	= ($element.hasClass('cssf-field-no-title')) ? false : true,
					field_id 	= $(clone_group).attr('data-field-id'),
					unique_id	= $(clone_group).attr('data-unique-id');
					
					if (!is_normal_field){
						if (is_nonce){
							var current_field_id 	= $element.attr('data-field-id'),
							new_field_id 		= current_field_id.replace(current_nonce_id,new_nonce_id);
							
							$(target).attr('data-field-id',new_field_id);
						} else {
							var current_field_id 		= $element.attr('data-field-id'),
							current_field_id_clean 	= current_field_id.replace( /\[_nonce\]/g, '' ),
							new_field_id 			= current_field_id_clean.replace(current_unique_id,new_unique_id);
							
							$(target).attr('data-field-id',new_field_id);
							
						}
					}
					
					var $found_elements = $element.find('input, select, textarea');
					
					$found_elements.each(function(index,target){
						var current_name 		= $(target).attr('name'),
						current_name_clean 	= current_name.replace( /\[_nonce\]/g, '' ),
						new_name 			= current_name_clean.replace(current_unique_id,new_unique_id),
						is_nonce_already 	= ($(target).attr('data-is-nonce')) ? true : false;
						
						var replace_from 		= current_unique_id,
						replace_to 			= new_unique_id;
						
						if (is_nonce){
							new_name			= current_name.replace(current_nonce_id,new_nonce_id);
							replace_from 		= current_nonce_id,
							replace_to 			= new_nonce_id;
							
							$(target).attr('data-is-nonce',true);
							
							nonce_fields.push(target);
						}
						
						if (is_normal_field){
							if (!all_fields.includes(new_name)){
								// all_fields.push(new_name); // Comentado porque hacia conflicto con el field "checkbox" cuando tiene multiples "options"
								
								var new_name_nonce = current_name.replace(current_nonce_id,new_nonce_id);
								new_name = (is_nonce_already) ? new_name_nonce : new_name;
								$(target).attr('name',new_name);
							}
						}
					});
				};
				
				$(clone_group).find('.cssf-element.cssf-element-group').each(function(index,target){
					var $group 		= $(target),
					has_title 	= ($group.hasClass('cssf-field-no-title')) ? false : true;
					
					if (has_title){
						var group_tpl 	= $group.children('.cssf-fieldset').children('.cssf-group'),
						groups 		= $group.children('.cssf-fieldset').children('.cssf-groups');
						
						renombralo(group_tpl,true);
						renombralo(groups);
					} else {
						var group_tpl 	= $group.children('.cssf-group'),
						groups 		= $group.children('.cssf-groups');
						
						renombralo(group_tpl,true);
						renombralo(groups);
					}
				});
				
				$(clone_group).find('.cssf-element').each(function(index,target){
					renombralo(target,false,true);
				});
				
				$.each(nonce_fields,function(index,target){
					$(target).removeAttr('data-is-nonce');
				});
				
				var cloned = clone_group.clone().removeClass( 'hidden' );
				field_groups.append( cloned );
				
				if ( accordion_group.length ) {
					field_groups.sortable('destroy');
					field_groups.sortable(sortable_settings);
					
					field_groups.accordion( 'refresh' );
					field_groups.accordion( {
						active: cloned.index()
					} );
				}
				
				$( this ).attr( 'data-count', new_index );
				
				cloned.find( '.cssf-field-group' ).CSSFRAMEWORK_GROUP();
				
				cloned.CSSFRAMEWORK_DEPENDENCY( 'sub' );
				cloned.CSSFRAMEWORK_RELOAD_PLUGINS();
			} );
			
			field_groups.on( 'click', '.cssf-remove-group', function (e) {
				e.preventDefault();
				$( this ).closest( '.cssf-group' ).remove();
			} );
			
			
		} )
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK RESET CONFIRM
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_CONFIRM = function() {
		return this.each(function() {
			$(this).on('click', function(e) {
				var self = $(this);
				if (!self.data('submit')){
					e.preventDefault();
					$.confirm({
						title: 'Restore options?',
						content: 'Are you sure that you want to continue?',
						theme: 'supervan', // 'material', 'bootstrap'
						buttons: {
							yes: {
								text: 'Continue',
								action: function(){
									self.data('submit',true);
									self.trigger('click');
								}
							},
							no: {
								text: 'Cancel',
								action: function(){
									self.data('submit',false);
								}
							},
						}
					});
				}
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK SAVE OPTIONS
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_SAVE = function() {
		return this.each(function() {
			var $this 	= $(this),
			$text 	= $this.data('save'),
			$value 	= $this.val(),
			$ajax 	= $('#cssf-save-ajax');
			
			$(document).on('keydown', function(event) {
				if (event.ctrlKey || event.metaKey) {
					if (String.fromCharCode(event.which).toLowerCase() === 's') {
						event.preventDefault();
						$this.trigger('click');
					}
				}
			});
			$this.on('click', function(e) {
				if ($ajax.length) {
					
					var dialog_tpl = function(type,icon,icon_content,msg){
						return '<div class="cssf-save-dialog cssf-save-dialog--'+type+'"><div class="cssf-save-dialog-icon"><i class="'+icon+'"></i>'+icon_content+'</div><div class="cssf-save-dialog-message">'+msg+'</div></div>';
					};
					
					$text = $('.cssf-i18n-ajax-save-saving').val();
					var saving_dialog = $.dialog({
						lazyOpen: 	true,
						title: 		null,
						content: 	dialog_tpl('saving',false,'<div class="cssf-spinner"></div>',$text),
						theme: 		'supervan',
						closeIcon: 	false,
					});
					
					saving_dialog.open();
					
					if (typeof tinyMCE === 'object') {
						tinyMCE.triggerSave();
					}
					
					var serializedOptions = $('#cssframework_form').serialize();
					
					$.post('options.php', serializedOptions).error(function() {
						$text = $('.cssf-i18n-ajax-save-error').val();
						var msg = dialog_tpl('error','cli cli-x','',$text);
						saving_dialog.setContent(msg);
					}).success(function() {
						$text = $('.cssf-i18n-ajax-save-success').val();
						var msg = dialog_tpl('success','cli cli-circle-check-alt','',$text);
						saving_dialog.setContent(msg);
					}).complete(function(){
						var closeTimeout = setTimeout(function(){
							saving_dialog.close();
						},1200);
					});
					e.preventDefault();
				} else {
					$this.addClass('disabled').attr('value', $text);
				}
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK ICONS MANAGER
	// ------------------------------------------------------
	$.CSSFRAMEWORK.ICONS_MANAGER = function() {
		var base = this,
		onload = true,
		$parent;
		base.init = function() {
			$cssf_body.on('click', '.cssf-icon-add', function(e) {
				e.preventDefault();
				var $this 	= $(this),
				$dialog = $('#cssf-icon-dialog'),
				$load 	= $dialog.find('.cssf-dialog-load'),
				$select = $dialog.find('.cssf-dialog-select'),
				$insert = $dialog.find('.cssf-dialog-insert'),
				$search = $dialog.find('.cssf-icon-search');
				// set parent
				$parent = $this.closest('.cssf-icon-select');
				
				// open dialog
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {
						my: 'center',
						at: 'center',
						of: window
					},
					open: function() {
						// fix scrolling
						$cssf_body.addClass('cssf-icon-scrolling');
						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');
						// set viewpoint
						$(window).on('resize', function() {
							var height = $(window).height(),
							load_height = Math.floor(height - 237),
							set_height = Math.floor(height - 125);
							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$load.css('height', load_height);
						}).resize();
					},
					close: function() {
						$cssf_body.removeClass('cssf-icon-scrolling');
					}
				});
				// load icons
				if (onload) {
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'cssf-get-icons'
						},
						success: function(content) {
							$load.html(content);
							onload = false;
							$load.on('click', 'a', function(e) {
								e.preventDefault();
								var icon = $(this).data('icon');
								$parent.find('i').removeAttr('class').addClass(icon);
								$parent.find('input').val(icon).trigger('change');
								$parent.find('.cssf-icon-preview').removeClass('hidden');
								$parent.find('.cssf-icon-remove').removeClass('hidden');
								$dialog.dialog('close');
							});
							$search.keyup(function() {
								var value = $(this).val(),
								$icons = $load.find('a');
								$icons.each(function() {
									var $ico = $(this);
									if ($ico.data('icon').search(new RegExp(value, 'i')) < 0) {
										$ico.hide();
									} else {
										$ico.show();
									}
								});
							});
							$load.find('.cssf-icon-tooltip').cstooltip({
								html: true,
								placement: 'top',
								container: 'body'
							});
							$load.accordion({
								collapsible: true,
								icons: {
									header: "dashicons dashicons-plus",
									activeHeader: "dashicons dashicons-minus"
								},
								heightStyle: "content"
							});
						}
					});
				}
			});
			$cssf_body.on('click', '.cssf-icon-remove', function(e) {
				e.preventDefault();
				var $this = $(this),
				$parent = $this.closest('.cssf-icon-select');
				$parent.find('.cssf-icon-preview').addClass('hidden');
				$parent.find('input').val('').trigger('change');
				$this.addClass('hidden');
			});
		};
		// run initializer
		base.init();
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK IMAGE GALLERY CUSTOM
	// ------------------------------------------------------
	$.CSSFRAMEWORK.IMAGE_GALLERY_CUSTOM = function() {
		var base 	= this,
		onload 	= true,
		$parent;
		
		base.init = function() {
			$cssf_body.on('click', '.cssf-image-add', function(e) {
				e.preventDefault();
				var $this 	= $(this),
				$dialog = $('#cssf-image-dialog'),
				$load 	= $dialog.find('.cssf-dialog-load'),
				$select = $dialog.find('.cssf-dialog-select'),
				$insert = $dialog.find('.cssf-dialog-insert'),
				$search = $dialog.find('.cssf-image-search');
				
				var $spinner = $dialog.find('.cssf-loading-indicator').clone();
				
				var images_path = $this.data('imagesPath'),
				path 		= $this.data('path'),
				uri			= $this.data('uri');
				
				// set parent
				$parent = $this.closest('.cssf-image-select');
				
				// open dialog
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {
						my: 'center',
						at: 'center',
						of: window
					},
					open: function() {
						$spinner.removeClass('hidden');
						$load.html('').append($spinner);
						
						// fix scrolling
						$cssf_body.addClass('cssf-image-scrolling');
						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');
						// set viewpoint
						$(window).on('resize', function() {
							var height 		= $(window).height(),
							load_height = Math.floor(height - 237),
							set_height 	= Math.floor(height - 125);
							
							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$load.css('height', load_height);
						}).resize();
					},
					close: function() {
						$cssf_body.removeClass('cssf-image-scrolling');
					}
				});
				// load images
				if (onload){
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 		'cssf-get-images',
							images_path:	images_path,
							path:			path,
							uri:			uri,
							
						},
						success: function(content) {
							$spinner = $('.cssf-loading-indicator',$load);
							$spinner.addClass('hidden');
							
							// $load.html(content);
							$load.append(content);
							
							// Comentado para forzar la carga de la galeria cada vez
							// onload = false;
							
							$load.on('click', 'a', function(e) {
								e.preventDefault();
								var image 			= $(this).data('image'),
								preview_uri 	= $(this).data('imageUri');
								
								$parent.find('img').attr('src',preview_uri);
								$parent.find('input').val(image).trigger('change');
								// $parent.find('.cssf-image-add').addClass('hidden');
								$parent.find('.cssf-image-preview').removeClass('hidden');
								$parent.find('.cssf-image-remove').removeClass('hidden');
								$dialog.dialog('close');
							});
							$search.keyup(function() {
								var value = $(this).val(),
								$images = $load.find('a');
								$images.each(function() {
									var $ico = $(this);
									if ($ico.data('image').search(new RegExp(value, 'i')) < 0) {
										$ico.hide();
									} else {
										$ico.show();
									}
								});
							});
							$load.find('.cssf-image-tooltip').cstooltip({
								html: true,
								placement: 'top',
								container: 'body'
							});
							// $load.accordion({
							// 	collapsible: true,
							// 	images: {
							// 		header: "cli cli-plus",
							// 		activeHeader: "cli cli-minus"
							// 	},
							// 	heightStyle: "content"
							// });
						}
					});
				}
			});
			$cssf_body.on('click', '.cssf-image-remove', function(e) {
				e.preventDefault();
				var $this 	= $(this),
				$parent = $this.closest('.cssf-image-select');
				
				$parent.find('.cssf-image-add').removeClass('hidden');
				$parent.find('.cssf-image-preview').addClass('hidden');
				$parent.find('input').val('').trigger('change');
				$this.addClass('hidden');
			});
		};
		// run initializer
		base.init();
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK SHORTCODE MANAGER
	// ------------------------------------------------------
	$.CSSFRAMEWORK.SHORTCODE_MANAGER = function() {
		var base = this,
		deploy_atts;
		
		base.init = function() {
			var $dialog 			= $('#cssf-shortcode-dialog'),
			$insert 			= $dialog.find('.cssf-dialog-insert'),
			$shortcodeload 		= $dialog.find('.cssf-dialog-load'),
			$selector 			= $dialog.find('.cssf-dialog-select'),
			$spinner_tpl		= $dialog.find('.cssf-loading-indicator.hidden').clone(),
			shortcode_target 	= false,
			shortcode_name,
			shortcode_view,
			shortcode_clone,
			$shortcode_button,
			editor_id;
			
			$cssf_body.on('click', '.cssf-shortcode', function(e) {
				e.preventDefault();
				// init chosen
				// $selector.CSSFRAMEWORK_CHOSEN();
				$shortcode_button = $(this);
				shortcode_target = $shortcode_button.hasClass('cssf-shortcode-textarea');
				editor_id = $shortcode_button.data('editor-id');
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {
						my: 'center',
						at: 'center',
						of: window
					},
					open: function() {
						// fix scrolling
						$cssf_body.addClass('cssf-shortcode-scrolling');
						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');
						// set viewpoint
						$(window).on('resize', function() {
							var height = $(window).height(),
							load_height = Math.floor(height - 281),
							set_height = Math.floor(height - 125);
							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$shortcodeload.css('height', load_height);
						}).resize();
					},
					close: function() {
						shortcode_target = false;
						$cssf_body.removeClass('cssf-shortcode-scrolling');
					}
				});
			});
			$selector.on('change', function() {
				var $elem_this = $(this);
				shortcode_name = $elem_this.val();
				shortcode_view = $elem_this.find(':selected').data('view');
				// check val
				if (shortcode_name.length) {
					$spinner_tpl.appendTo($shortcodeload).removeClass('hidden');
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'cssf-get-shortcode',
							shortcode: shortcode_name
						},
						success: function(content) {
							$shortcodeload.html(content);
							$insert.parent().removeClass('hidden');
							shortcode_clone = $('.cssf-shortcode-clone', $dialog).clone();
							$shortcodeload.CSSFRAMEWORK_DEPENDENCY();
							$shortcodeload.CSSFRAMEWORK_DEPENDENCY('sub');
							$shortcodeload.CSSFRAMEWORK_RELOAD_PLUGINS();
							$spinner.addClass('hidden');
						}
					});
				} else {
					$insert.parent().addClass('hidden');
					$shortcodeload.html('');
				}
			});
			$insert.on('click', function(e) {
				e.preventDefault();
				var send_to_shortcode 	= '',
				ruleAttr 			= 'data-atts',
				cloneAttr 			= 'data-clone-atts',
				cloneID 			= 'data-clone-id';
				
				switch (shortcode_view) {
					case 'contents':
					$('[' + ruleAttr + ']', '.cssf-dialog-load').each(function() {
						var _this = $(this),
						_atts = _this.data('atts');
						send_to_shortcode += '[' + _atts + ']';
						send_to_shortcode += _this.val();
						send_to_shortcode += '[/' + _atts + ']';
					});
					break;
					case 'clone':
					send_to_shortcode += '[' + shortcode_name; // begin: main-shortcode
					// main-shortcode attributes
					$('[' + ruleAttr + ']', '.cssf-dialog-load .cssf-element:not(.hidden)').each(function() {
						var _this_main = $(this),
						_this_main_atts = _this_main.data('atts');
						console.log(_this_main_atts);
						send_to_shortcode += base.validate_atts(_this_main_atts, _this_main); // validate empty atts
					});
					send_to_shortcode += ']'; // end: main-shortcode attributes
					// multiple-shortcode each
					$('[' + cloneID + ']', '.cssf-dialog-load').each(function() {
						var _this_clone = $(this),
						_clone_id = _this_clone.data('clone-id');
						send_to_shortcode += '[' + _clone_id; // begin: multiple-shortcode
						// multiple-shortcode attributes
						$('[' + cloneAttr + ']', _this_clone.find('.cssf-element').not('.hidden')).each(function() {
							var _this_multiple = $(this),
							_atts_multiple = _this_multiple.data('clone-atts');
							// is not attr content, add shortcode attribute else write content and close shortcode tag
							if (_atts_multiple !== 'content') {
								send_to_shortcode += base.validate_atts(_atts_multiple, _this_multiple); // validate empty atts
							} else if (_atts_multiple === 'content') {
								send_to_shortcode += ']';
								send_to_shortcode += _this_multiple.val();
								send_to_shortcode += '[/' + _clone_id + '';
							}
						});
						send_to_shortcode += ']'; // end: multiple-shortcode
					});
					send_to_shortcode += '[/' + shortcode_name + ']'; // end: main-shortcode
					break;
					case 'clone_duplicate':
					// multiple-shortcode each
					$('[' + cloneID + ']', '.cssf-dialog-load').each(function() {
						var _this_clone = $(this),
						_clone_id = _this_clone.data('clone-id');
						send_to_shortcode += '[' + _clone_id; // begin: multiple-shortcode
						// multiple-shortcode attributes
						$('[' + cloneAttr + ']', _this_clone.find('.cssf-element').not('.hidden')).each(function() {
							var _this_multiple = $(this),
							_atts_multiple = _this_multiple.data('clone-atts');
							// is not attr content, add shortcode attribute else write content and close shortcode tag
							if (_atts_multiple !== 'content') {
								send_to_shortcode += base.validate_atts(_atts_multiple, _this_multiple); // validate empty atts
							} else if (_atts_multiple === 'content') {
								send_to_shortcode += ']';
								send_to_shortcode += _this_multiple.val();
								send_to_shortcode += '[/' + _clone_id + '';
							}
						});
						send_to_shortcode += ']'; // end: multiple-shortcode
					});
					break;
					default:
					send_to_shortcode += '[' + shortcode_name;
					$('[' + ruleAttr + ']', '.cssf-dialog-load .cssf-element:not(.hidden)').each(function() {
						var _this = $(this),
						_atts = _this.data('atts');
						// is not attr content, add shortcode attribute else write content and close shortcode tag
						if (_atts !== 'content') {
							send_to_shortcode += base.validate_atts(_atts, _this); // validate empty atts
						} else if (_atts === 'content') {
							send_to_shortcode += ']';
							send_to_shortcode += _this.val();
							send_to_shortcode += '[/' + shortcode_name + '';
						}
					});
					send_to_shortcode += ']';
					break;
				}
				if (shortcode_target) {
					var $textarea = $shortcode_button.next();
					$textarea.val(base.insertAtChars($textarea, send_to_shortcode)).trigger('change');
				} else {
					// base.send_to_editor(send_to_shortcode, editor_id);
					var $editor_parent 	= $shortcode_button.parents('.wp-editor-wrap'),
					the_editor_id 	= $('.wp-editor-area',$editor_parent).attr('id');
					base.send_to_editor(send_to_shortcode, the_editor_id);
				}
				deploy_atts = null;
				$dialog.dialog('close');
			});
			// cloner button
			var cloned = 0;
			$dialog.on('click', '#shortcode-clone-button', function(e) {
				e.preventDefault();
				// clone from cache
				var cloned_el = shortcode_clone.clone().hide();
				cloned_el.find('input:radio').attr('name', '_nonce_' + cloned);
				$('.cssf-shortcode-clone:last').after(cloned_el);
				// add - remove effects
				cloned_el.slideDown(100);
				cloned_el.find('.cssf-remove-clone').show().on('click', function(e) {
					cloned_el.slideUp(100, function() {
						cloned_el.remove();
					});
					e.preventDefault();
				});
				// reloadPlugins
				cloned_el.CSSFRAMEWORK_DEPENDENCY('sub');
				cloned_el.CSSFRAMEWORK_RELOAD_PLUGINS();
				cloned++;
			});
		};
		base.validate_atts = function(_atts, _this) {
			var el_value;
			if (_this.data('check') !== undefined && deploy_atts === _atts) {
				return '';
			}
			deploy_atts = _atts;
			if (_this.closest('.pseudo-field').hasClass('hidden') === true) {
				return '';
			}
			if (_this.hasClass('pseudo') === true) {
				return '';
			}
			if (_this.is(':checkbox') || _this.is(':radio')) {
				el_value = _this.is(':checked') ? _this.val() : '';
			} else {
				el_value = _this.val();
			}
			if (_this.data('check') !== undefined) {
				el_value = _this.closest('.cssf-element').find('input:checked').map(function() {
					return $(this).val();
				}).get();
			}
			if (el_value !== null && el_value !== undefined && el_value !== '' && el_value.length !== 0) {
				return ' ' + _atts + '="' + el_value + '"';
			}
			return '';
		};
		base.insertAtChars = function(_this, currentValue) {
			var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;
			if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
				obj.focus();
				return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
			} else {
				obj.focus();
				return currentValue;
			}
		};
		base.send_to_editor = function(html, editor_id) {
			var tinymce_editor;
			if (typeof tinymce !== 'undefined') {
				tinymce_editor = tinymce.get(editor_id);
			}
			if (tinymce_editor && !tinymce_editor.isHidden()) {
				tinymce_editor.execCommand('mceInsertContent', false, html);
			} else {
				var $editor = $('#' + editor_id);
				$editor.val(base.insertAtChars($editor, html)).trigger('change');
			}
		};
		// run initializer
		base.init();
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK COLORPICKER
	// ------------------------------------------------------
	if (typeof Color === 'function') {
		// adding alpha support for Automattic Color.js toString function.
		Color.fn.toString = function() {
			// check for alpha
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}
			var hex = parseInt(this._color, 10).toString(16);
			if (this.error) {
				return '';
			}
			// maybe left pad it
			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}
			return '#' + hex;
		};
	}
	$.CSSFRAMEWORK.PARSE_COLOR_VALUE = function(val) {
		var value = val.replace(/\s+/g, ''),
		alpha = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
		rgba = (alpha < 100) ? true : false;
		return {
			value: value,
			alpha: alpha,
			rgba: rgba
		};
	};
	$.fn.CSSFRAMEWORK_COLORPICKER = function() {
		return this.each(function() {
			var $this = $(this);
			
			// check for user custom color palettes
			var picker_palettes = $this.data('colorpalettes');
			picker_palettes = (picker_palettes) ? picker_palettes.toString().split(",") : false;
			
			// check for rgba enabled/disable
			if ($this.data('rgba') !== false) {
				// parse value
				var picker = $.CSSFRAMEWORK.PARSE_COLOR_VALUE($this.val());
				// wpColorPicker core
				$this.wpColorPicker({
					palettes: picker_palettes,
					// wpColorPicker: clear
					clear: function() {
						$this.trigger('keyup');
					},
					// wpColorPicker: change
					change: function(event, ui) {
						var ui_color_value = ui.color.toString();
						// update checkerboard background color
						$this.closest('.wp-picker-container').find('.cssf-alpha-slider-offset').css('background-color', ui_color_value);
						$this.val(ui_color_value).trigger('change');
					},
					// wpColorPicker: create
					create: function() {
						// set variables for alpha slider
						var a8cIris = $this.data('a8cIris'),
						$container = $this.closest('.wp-picker-container'),
						// appending alpha wrapper
						$alpha_wrap = $('<div class="cssf-alpha-wrap">' + '<div class="cssf-alpha-slider"></div>' + '<div class="cssf-alpha-slider-offset"></div>' + '<div class="cssf-alpha-text"></div>' + '</div>').appendTo($container.find('.wp-picker-holder')),
						$alpha_slider = $alpha_wrap.find('.cssf-alpha-slider'),
						$alpha_text = $alpha_wrap.find('.cssf-alpha-text'),
						$alpha_offset = $alpha_wrap.find('.cssf-alpha-slider-offset');
						// alpha slider
						$alpha_slider.slider({
							// slider: slide
							slide: function(event, ui) {
								var slide_value = parseFloat(ui.value / 100);
								// update iris data alpha && wpColorPicker color option && alpha text
								a8cIris._color._alpha = slide_value;
								$this.wpColorPicker('color', a8cIris._color.toString());
								$alpha_text.text((slide_value < 1 ? slide_value : ''));
							},
							// slider: create
							create: function() {
								var slide_value = parseFloat(picker.alpha / 100),
								alpha_text_value = slide_value < 1 ? slide_value : '';
								// update alpha text && checkerboard background color
								$alpha_text.text(alpha_text_value);
								$alpha_offset.css('background-color', picker.value);
								// wpColorPicker clear for update iris data alpha && alpha text && slider color option
								$container.on('click', '.wp-picker-clear', function() {
									a8cIris._color._alpha = 1;
									$alpha_text.text('');
									$alpha_slider.slider('option', 'value', 100).trigger('slide');
								});
								// wpColorPicker default button for update iris data alpha && alpha text && slider color option
								$container.on('click', '.wp-picker-default', function() {
									var default_picker = $.CSSFRAMEWORK.PARSE_COLOR_VALUE($this.data('default-color')),
									default_value = parseFloat(default_picker.alpha / 100),
									default_text = default_value < 1 ? default_value : '';
									a8cIris._color._alpha = default_value;
									$alpha_text.text(default_text);
									$alpha_slider.slider('option', 'value', default_picker.alpha).trigger('slide');
								});
								// show alpha wrapper on click color picker button
								$container.on('click', '.wp-color-result', function() {
									$alpha_wrap.toggle();
								});
								// hide alpha wrapper on click body
								$cssf_body.on('click.wpcolorpicker', function() {
									$alpha_wrap.hide();
								});
							},
							// slider: options
							value: picker.alpha,
							step: 1,
							min: 1,
							max: 100
						});
					}
				});
			} else {
				// wpColorPicker default picker
				$this.wpColorPicker({
					palettes: picker_palettes,
					clear: function() {
						$this.trigger('keyup');
					},
					change: function(event, ui) {
						$this.val(ui.color.toString()).trigger('change');
					}
				});
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// BACKUP field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_BACKUP_IMPORT = function(){
		return this.each(function(){
			var self 				= this,
			$btn_import_backup 	= $('.cssf-import-backup',this),
			$import_textarea	= $('.cssf-import-backup_data',this);
			
			// Import Backup data
			$btn_import_backup.on('click',function(e){
				e.preventDefault();
				
				var $self 				= $(this),
				backup_to_import 	= $import_textarea.val();
				
				if (backup_to_import){
					if ($self.data('status')) { return false; }
					
					// Spinner
					var btn_text = $self.html();
					$self.data('status','working').attr('disabled','disabled').addClass('cssf-button-disabled').html('<div class="cssf-spinner"></div>');
					
					
					// AJAX Call
					var action = 'cssf-import-options';
					var data = {
						nonce: 			cssf_framework.nonce, 	// Security nonce
						action: 		action,					// Ajax Action
						// Parameters
						options:		backup_to_import,
					};
					
					$.ajax({
						url: 	cssf_framework.ajax_url,
						type: 	'post',
						data: 	data,
						success: function(response){
							if (response.success){
								// Empty Textarea
								$import_textarea.val('');
								
								if (response.data.code == 'ok'){
									console.log(response.data.message);
									location.reload();
								}
							}
						},
						complete: function(){
							$self.data('status',false).removeAttr('disabled').removeClass('cssf-button-disabled').html(btn_text);
						}
					});
				}
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// Slider field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_SLIDER = function() {
		return this.each( function() {
			var $this 		= $( this ),
			input 		= $('input.cssf-slider_value',$this),
			input1 		= $('input[name$="[slider1]"]',$this),
			input2		= $('input[name$="[slider2]"]',$this),
			slider 		= $('.cssf-slider > div.cssf-slider-wrapper', $this ),
			data 		= $('.cssf-slider',$this).data( 'sliderOptions' ),
			step 		= data.step || 1,
			min 		= data.min || 0,
			max 		= data.max || 100,
			round 		= data.round || false,
			tooltip 	= data.tooltip || false,
			handles 	= data.handles || false,
			has_input 	= data.input || false;
			
			var parseInteger = function(value){
				return parseFloat(parseFloat(value).toFixed(2));
			}
			
			var connect 	= (handles) ? [ false , true, false] : [ true , false ];
			var val 		= (handles) ? [ parseInteger(data.slider1) , parseInteger(data.slider2) ] : [ parseInteger(data.slider1) ];
			var tooltips	= (handles) ? ((tooltip) ? [ true, true ] : [ false, false ]) : ((tooltip) ? [ true ] : [ false ]);
			
			var slider = slider.get(0);
			var instance = slider.noUiSlider;
			if (!instance){
				noUiSlider.create(slider, {
					start: val,
					connect: connect,
					tooltips: tooltips,
					step: step,
					range: {
						'min': [  parseInteger( min ) ],
						'max': [ parseInteger( max ) ]
					}
				});
			}
			
			slider.noUiSlider.on('update', function ( values, handle ) {
				var value = (round) ? Math.round(values[handle]) : values[handle];
				(handle ? input2 : input1).val( value );
				input.val( value ).trigger('change');
			});
			
			input1.on("change",function(){
				var val1 = input1.val(),
				val2 = input2.val();
				
				var val 		= (handles) ? [ parseInteger(val1) , parseInteger(val2) ] : [ parseInteger(val1) ];
				updateSliderVal(val);
			});
			input2.on("change",function(){
				var val1 = input1.val(),
				val2 = input2.val();
				
				var val 		= (handles) ? [ parseInteger(val1) , parseInteger(val2) ] : [ parseInteger(val1) ];
				updateSliderVal(val);
			});
			
			function updateSliderVal(value) {
				slider.noUiSlider.updateOptions({
					start: value
				});
				input.val( value );
			}
		} );
	};
	// ======================================================
	
	// ======================================================
	// Easing Editor field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_EASINGEDITOR = function() {
		return this.each(function(){
			var $p1, $p2, $handle, $easingselector, $input, $inputType, $preview, $size, ctx;
			var self 	= this;
			
			ctx 			= $(".cssf-easing-editor__bezierCurve",self).get(0).getContext("2d");
			$easingselector = $('.easingSelector',self);
			$input 			= $('input[name$="[easingSelector]"]',self);
			$inputType 		= $('input.easingSelectorType',self);
			$preview 		= $('.cssf-easing-editor__preview',self).get(0);
			$size 			= 200;
			$p1				= $(".p1",self);
			$p2				= $(".p2",self);
			
			
			$(document).ready(function(){
				// Toggle Button
				// ------------------------------------------------------
				var btn_toggle 	= $('a.button[name$="[toggleEditor]"]',self),
				btn_icon 	= $("<span />",{ class: "dashicons dashicons-visibility"});
				
				btn_toggle.prepend(btn_icon);
				btn_toggle.on('click',function(){
					$('.cssf-easing-editor__graph-outer-wrapper',self).slideToggle({
						start: function(){
							// $easingselector.trigger('change');
							
							var type 	= $('option',$easingselector).last().prop('selected'),
							// value 	= (type) ? getHandles(true) : $easingselector.val();
							value 	= $input.val();
							
							// Update Handles Positions
							updateHandles(value);	
							// Render Graph
							renderWrap(ctx);
						}
					});
					$('span',this).toggleClass('dashicons-hidden','dashicons-visibility');
				});
				
				
				// Easing Curve Graph - Dragable handles
				// - Draggable handles
				// - Easing Select box change event to update the graph
				// ------------------------------------------------------
				$(".p1, .p2",self).draggable({
					containment: 'parent',
					start: function(){
						setCustomEasing();
					},
					drag: function(event, ui) {
						renderWrap(ctx);
						setDemoValue('drag');
					},
					stop: function(){
						renderWrap(ctx);
						setTransitionFn();
						setDemoValue('drag');
					}
				});
				
				$easingselector.on('change', function(){
					var $this 	= $(this),
					value 	= $this.val();
					
					// Update Handles Positions
					updateHandles(value);
					
					// Render Graph
					renderWrap(ctx);
					setDemoValue();
				});
				
				
				// First Render Easing Curve Graph
				// ------------------------------------------------------
				renderWrap(ctx);
				setTransitionFn();				
				setDemoValue();
			});
			
			
			// HELPER FUNCTIONS
			// --------------------------------------------------------------------
			function setStyle( element, propertyObject ){
				var elem = element.style;
				for (var property in propertyObject){
					elem.setProperty(property, propertyObject[property]);
				}
			}
			function updateHandles(values){
				var values 	= values.split(",");
				
				$p1.css("left", values[0] * $size);
				$p1.css("top", 	(1 - values[1]) * $size);
				$p2.css("left", values[2] * $size);
				$p2.css("top", 	(1 - values[3]) * $size);
			}
			
			function getHandles(string){
				var handles = [],
				p1 		= $p1.position(),
				p2 		= $p2.position();
				
				if($.browser.mozilla) {
					var p1x = adjustValue( (p1.left) / $size);
					var p1y = adjustValue( 1 - (p1.top) / $size);
					var p2x = adjustValue( (p2.left) / $size);
					var p2y = adjustValue( 1 - (p2.top) / $size);
				} else {
					var p1x = adjustValue( (p1.top + 5) / $size);
					var p1y = adjustValue( 1 - (p1.left + 4) / $size);
					var p2x = adjustValue( (p2.top + 5) / $size);
					var p2y = adjustValue( 1 - (p2.left + 4) / $size);
				}
				
				handles.push(p1x);
				handles.push(p1y);
				handles.push(p2x);
				handles.push(p2y);
				
				if (string){
					handles = p1x +","+ p1y +","+ p2x +","+ p2y;
				}
				
				return handles;
			}
			
			function setCustomEasing(){
				$('option',$easingselector).last().prop('selected',true);
				$inputType.val('custom');
			}
			
			function setDemoValue(type) { 
				var value;
				if (type == 'drag') {
					value = getHandles();
					$inputType.val('custom');
				} else {
					value = $easingselector.val();
					$inputType.val('default');
				}
				$input.val(value);
				
				var style = {
					"--easingTypeAnimation":'cubic-bezier('+value+')'
				};
				setStyle($preview,style);
			}
			function setTransitionFn() {
				// console.log('Seteando estilo a la variable box');
			}
			
			// this just removes leading 0 and truncates values
			function adjustValue(val) {	
				val = val.toFixed(2);
				val = val.toString().replace("0.", ".").replace("1.00", "1").replace(".00", "0");
				return val;
			}
			
			function renderWrap(ctx) {
				var p1 = $p1.position(),
				p2 = $p2.position();
				
				render(ctx,{
					x: p1.left,
					y: p1.top
				},{
					x: p2.left,
					y: p2.top
				});
			};
			
			function render(ctx, p1, p2) {
				var ctx = ctx;
				ctx.clearRect(0,0,$size,$size);
				
				ctx.setLineDash([]);
				ctx.beginPath();
				ctx.lineWidth = 3;
				ctx.strokeStyle = "#0073AA";
				ctx.moveTo(0,$size);
				
				// p1 (x,y) p2 (x,y)
				ctx.bezierCurveTo(p1.x,p1.y,p2.x,p2.y,$size,0);				
				ctx.stroke();
				ctx.closePath();
				
				ctx.setLineDash([4, 4]);
				ctx.beginPath();
				ctx.strokeStyle = "#444"; //"#e4e4e4" "#d6d6d6"
				ctx.lineWidth = 1;
				ctx.moveTo(0,$size);
				
				// p1 (x,y)
				ctx.lineTo(p1.x + 0,p1.y + 0);
				ctx.stroke(); 
				
				ctx.moveTo($size,0);
				
				// p2 (x,y)
				ctx.lineTo(p2.x + 0,p2.y + 0);
				ctx.stroke();
				ctx.closePath();
				
				if($.browser.mozilla) {
					$(".p1X", self).html( adjustValue( (p1.x) / $size) );
					$(".p1Y", self).html( adjustValue( 1 - (p1.y) / $size) );
					$(".p2X", self).html( adjustValue( (p2.x) / $size) );
					$(".p2Y", self).html( adjustValue( 1 - (p2.y) / $size) );
				} else {
					$(".p1X", self).html( adjustValue( (p1.x + 5) / $size) );
					$(".p1Y", self).html( adjustValue( 1 - (p1.y + 4) / $size) );
					$(".p2X", self).html( adjustValue( (p2.x + 5) / $size) );
					$(".p2Y", self).html( adjustValue( 1 - (p2.y + 4) / $size) );
				}
				
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK TYPOGRAPHY ADVANCED
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_TYPOGRAPHY_ADVANCED = function() {
		return this.each(function() {
			var typography 				= $(this),
			family_select 			= typography.find('.cssf-typo-family'),
			variants_select 		= typography.find('.cssf-typo-variant'),
			typography_type 		= typography.find('.cssf-typo-font'),
			typography_size			= typography.find('.cssf-typo-size'),
			typography_height 		= typography.find('.cssf-typo-height'),
			typography_spacing 		= typography.find('.cssf-typo-spacing'),
			typography_align		= typography.find('.cssf-typo-align'),
			typography_transform 	= typography.find('.cssf-typo-transform'),
			typography_color 		= typography.find('.cssf-typo-color');
			
			family_select.on('change', function() {
				var _this 		= $(this),
				_selected 	= _this.find(':selected'),
				_type	 	= _this.find(':selected').data('type') || 'custom',
				_variants 	= _this.data('variants'),
				_variants 	= _variants[_type][_selected.val()];
				
				if (variants_select.length) {
					variants_select.find('option').remove();
					$.each(_variants, function(key,text){
						variants_select.append('<option value="' + text + '">' + text + '</option>');
					});
					
					// Trigger only if is chosen
					variants_select.find('option[value="regular"]').attr('selected', 'selected');
				}
				typography_type.val(_type);
			});
			
			// Typography Advanced Live Preview
			// ---------------------------------------------
			var preview 		= $(".cssf-typo-preview",typography),
			previewToggle	= $(".cssf-typo-preview-toggle",preview),
			previewId		= $(preview).data("previewId"),
			currentFamily 	= $(this).find('.cssf-typo-family').val();
			
			var livePreviewRefresh = function(){
				var preview_weight 		= variants_select.val(),
				preview_size		= typography_size.val(),
				preview_height		= typography_height.val(),
				preview_spacing		= typography_spacing.val(),
				preview_align 		= typography_align.val(),
				preview_transform	= typography_transform.val(),
				preview_color 		= typography_color.val();
				
				var style = {
					"--cssf-typo-preview-weight":preview_weight,
					"--cssf-typo-preview-size":preview_size+"px",
					"--cssf-typo-preview-height":preview_height+"px",
					"--cssf-typo-preview-spacing":preview_spacing+"px",
					"--cssf-typo-preview-align":preview_align,
					"--cssf-typo-preview-transform":preview_transform,
					"--cssf-typo-preview-color":preview_color
				};
				setPreviewStyle("#"+$(preview).attr("id"),style);
			}
			
			// Update Preview
			// ------------------------------
			if (preview.length){
				$(preview).css("font-family", currentFamily);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + currentFamily +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			}
			
			family_select.on('change',function(){
				$('head').find("."+previewId).remove();
				var font = $(this).val();
				$(preview).css("font-family", font);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + font +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			});
			
			variants_select.on('change',function(){ livePreviewRefresh(); });
			typography_type.on('change',function(){ livePreviewRefresh(); });
			typography_size.on('change',function(){ livePreviewRefresh(); });
			typography_height.on('change',function(){ livePreviewRefresh(); });
			typography_align.on('change',function(){ livePreviewRefresh(); });
			typography_color.on('change',function(){ livePreviewRefresh(); });
			typography_spacing.on('change',function(){ livePreviewRefresh(); });
			typography_transform.on('change',function(){ livePreviewRefresh(); });
			
			// Toggle Preview BG Style
			// ------------------------------
			$(previewToggle).on("click",function(){
				$(preview).toggleClass("cssf-typo-preview-toggle_dark");
			});
			
			//-----------------------------------------------------------------
			// HELPER FUNCTIONS
			//-----------------------------------------------------------------
			function setPreviewStyle( element, propertyObject ){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.setProperty(property, propertyObject[property]);
				}
			}
			
			function removeStyle( element, propertyObject){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.removeProperty(propertyObject[property]);
				}
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// Accordion field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_ACCORDION = function() {
		return this.each(function(){
			var self = this;
			
			$(self).accordion({
				header: '.cssf-accordion-title',
				collapsible: true,
				active: false,
				animate: 350,
				heightStyle: 'content',
				icons: {
					'header': 'cli cli-arrow-down',
					'activeHeader': 'cli cli-arrow-up'
				},
				beforeActivate: function(event, ui) {
					// Comentado porque generaba conflicto con switcher con "dependency" dentro de un group
					// Se comenta la linea y se mantiene el funcionamiento correcto
					// $(ui.newPanel).CSSFRAMEWORK_DEPENDENCY('sub');
				}
			});
			
			// $(self).CSSFRAMEWORK_RELOAD_PLUGINS();
		});
	};
	// ======================================================
	
	// ======================================================
	// Angle field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_ANGLE = function() {
		return this.each( function() {
			var dis 		= $( this ),
			input 		= $('.cssf-anglepicker input',dis),
			anglePicker = $('.cssf-anglepicker > div.cssf-anglepicker-wrapper > .anglepicker', dis ),
			data 		= $('.cssf-anglepicker',dis).data( 'angleOptions' ),
			distance 	= data.distance || 1,
			delay 		= data.delay || 1,
			snap 		= data.snap || 1,
			min 		= data.min || 0,
			shiftSnap 	= data.shiftSnap || 15,
			clockwise 	= data.clockwise || false,
			value 		= data.value || 0;
			
			$(anglePicker).anglepicker({
				start: function(e, ui) {
					
				},
				change: function(e, ui) {
					$(input).val(ui.value);
				},
				stop: function(e, ui) {
					
				},
				distance: 	distance,
				delay: 		delay,
				snap: 		snap,
				min: 		min,
				shiftSnap: 	shiftSnap,
				clockwise: 	clockwise,
				value: 		value,
			});
			
			$(input).on('blur',function(){
				var value = $(input).val();
				$(anglePicker).anglepicker('value',value);
			});
		} );
	};
	// ======================================================
	
	// ======================================================
	// Code Editor field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_CODEEDITOR = function() {
		$('.cssf-field-code_editor').each(function(index) {
			var $editorContainer = $( this ).find( '.code-editor-container' );
			
			// Get textarea to get/save data
			var $editorTextarea = $editorContainer.prev( 'textarea' );
			
			// Add ID to ace-editor-container
			$editorContainer.attr( 'id', 'aceeditor' + index );
			
			// Get theme and language
			var editorTheme = $editorContainer.data( 'theme' );
			var editorMode = $editorContainer.data( 'mode' );
			
			// Inicialize ACE editor
			var editor = ace.edit( 'aceeditor' + index );
			
			// Set editor settings
			editor.setTheme( 'ace/theme/' + editorTheme );
			editor.getSession().setMode( 'ace/mode/' + editorMode );
			
			editor.setOptions({
				enableBasicAutocompletion: true,
				enableSnippets: true,
				enableLiveAutocompletion: true
			});
			
			// Save data in textarea on ACE editor change
			editor.getSession().on( 'change', function () {
				$editorTextarea.val( editor.getSession().getValue() );
			});
			
			// Get data on load
			editor.getSession().setValue( $editorTextarea.val() );
		});
	};
	// ======================================================
	
	// ======================================================
	// Background Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_BACKGROUND = function() {
		return this.each(function() {
			var $this 			= $(this),
			$add 			= $this.find('.cssf-add'),
			$preview 		= $this.find('.cssf-image-preview'),
			$remove 		= $this.find('.cssf-remove'),
			$input 			= $this.find('input'),
			$input_image 	= $input.first(),
			$img 			= $this.find('img'),
			wp_media_frame;
			
			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: 'image'
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					var attachment = wp_media_frame.state().get('selection').first().attributes;
					var preview_size = $preview.data('preview-size');
					if (preview_size == 'custom'){
						var thumbnail = attachment.url;	
					} else {
						if (typeof preview_size === 'undefined') {
							preview_size = 'thumbnail';
						}
						var thumbnail = (typeof attachment['sizes'][preview_size] !== 'undefined') ? attachment['sizes'][preview_size]['url'] : attachment.url;
					}
					$preview.removeClass('hidden');
					$remove.removeClass('hidden');
					$img.attr('src', thumbnail);
					$input_image.val(attachment.id).trigger('change');
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$input_image.val('').trigger('change');
				$preview.addClass('hidden');
				$remove.addClass('hidden');
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// Animate.css Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_ANIMATE_CSS = function(){
		return this.each(function () {
			var $parent = $(this);
			var iteration_delay_timeout;
			var update_preview = function(){
				var effect_val 				= $('.cssf-animation--effect',$parent).val(),
				iteration_val 			= $('.cssf-animation--iteration',$parent).val(),
				iteration_delay_val 	= $('.cssf-animation--iteration_delay',$parent).val(),
				delay_val				= $('.cssf-animation--delay',$parent).val(),
				$preview 				= $parent.find('.animation-preview h3');
				
				var effect_class = effect_val + " animated ";
				
				$preview.removeClass();
				$preview.addClass(effect_class).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
					$(this).removeClass();
				});
				
				clearTimeout(iteration_delay_timeout);
				if (iteration_delay_val.length){
					if (iteration_val == 'infinite'){
						iteration_delay_timeout = setTimeout(function(){
							update_preview();
						},iteration_delay_val);
					}
				}
			};
			$("select",$parent).on('change', function(){
				update_preview();
			});
			$("input",$parent).on('change', function(){
				update_preview();
			});
		})
	};
	
	// ======================================================
	// CSS BUILDER Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_CSS_BUILDER = function () {
		return this.each(function () {
			var $this = $(this);
			
			$this.find('.cssf-css-checkall').on('click', function () {
				$(this).toggleClass('checked');
			});
			
			$this.find('.cssf-element.cssf-margin :input').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.all($(this), 'margin', $this);
			});
			
			$this.find('.cssf-element.cssf-padding :input').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.all($(this), 'padding', $this);
			});
			
			$this.find('.cssf-element.cssf-border :input').on('change, blur', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.all($(this), 'border', $this);
			});
			
			$this.find('.cssf-element.cssf-border-radius :input').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.all($(this), 'border-radius', $this);
			});
			
			$this.find('.cssf-element-border-style select').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.border($this);
			});
			
			$this.find('.cssf-element-border-color input.cssf-field-color-picker').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.border($this);
			});
			
			$this.find('.cssf-element-text-color input.cssf-field-color-picker').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.border($this);
			});
			
			$this.find('.cssf-element-background-color input.cssf-field-color-picker').on('change', function () {
				$.CSSFRAMEWORK.HELPER.CSS_BUILDER.update.border($this);
			});
			
		})
	};
	
	// ======================================================
	// TEXT LIMITER Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_LIMITER = function () {
		return this.each(function () {
			var $this = $(this),
			$parent = $this.parent(),
			$limiter = $parent.find('> .text-limiter'),
			$counter = $limiter.find('span.counter'),
			$limit = parseInt($limiter.find('span.maximum').html()),
			$countByWord = 'word' == $limiter.data('limit-type');
			
			var $val = $.CSSFRAMEWORK.HELPER.LIMITER.counter($this.val(), $countByWord);
			$counter.html($val);
			
			$this.on('input', function () {
				var text = $this.val(),
				length = $.CSSFRAMEWORK.HELPER.LIMITER.counter(text, $countByWord);
				
				if ( length > $limit ) {
					text = $.CSSFRAMEWORK.HELPER.LIMITER.subStr(text, 0, $limit, $countByWord);
					$this.val(text);
					$counter.html($limit);
				} else {
					$counter.html(length);
				}
			});
		})
	};
	
	// ======================================================
	// CHECKBOX LABELED Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_CHECKBOX_LABELED = function(){
		return this.each(function () {
			var $this = $(this);
			$this.labelauty();
		});
	};
	
	// ======================================================
	// CHECKBOX ICHECK Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_CHECKBOX_ICHECK = function(){
		return this.each(function () {
			var $this = $(this);
			$this.iCheck({
				// handle: 'checkbox,radio',
				checkboxClass: 'cssf-checkbox-icheck--checkbox',
				radioClass: 'cssf-checkbox-icheck--radio',
			}).on('ifChanged', function(event){
				$this.trigger('change');
			}).on('change',function(){
				$this.iCheck('update');
			});
		});
	};
	
	// ======================================================
	// WPLinks
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_WPLINKS = function () {
		return this.each( function () {
			$( this ).on( 'click', function (e) {
				e.preventDefault();
				
				var $this = $( this ),
				$parent = $this.parent(),
				$textarea = $parent.find( '#sample_wplinks' ),
				$link_submit = $( "#wp-link-submit" ),
				$cssf_link_submit = $( '<input type="submit" name="cssf-link-submit" id="cssf_link-submit" class="button-primary" value="' + $link_submit.val() + '">' );
				$link_submit.hide();
				$cssf_link_submit.insertBefore( $link_submit );
				var $dialog = !window.wpLink && $.fn.wpdialog && $( "#wp-link" ).length ? {
					$link: !1,
					open: function () {
						this.$link = $( '#wp-link' ).wpdialog( {
							title: wpLinkL10n.title,
							width: 480,
							height: "auto",
							modal: !0,
							dialogClass: "wp-dialog",
							zIndex: 3e5
						} )
					},
					close: function () {
						this.$link.wpdialog( 'close' );
					}
				} : window.wpLink;
				
				$dialog.open( $textarea.attr( 'id' ) );
				$cssf_link_submit.unbind( 'click.cssf-wpLink' ).bind( 'click.cssf-wpLink', function (e) {
					e.preventDefault(), e.stopImmediatePropagation();
					
					var $url = $( "#wp-link-url" ).length ? $( "#wp-link-url" ).val() : $( "#url-field" ).val(),
					$title = $( "#wp-link-text" ).length ? $( "#wp-link-text" ).val() : $( "#link-title-field" ).val(),
					$checkbox = $( $( "#wp-link-target" ).length ? "#wp-link-target" : "#link-target-checkbox" ),
					$target = $checkbox[ 0 ].checked ? " _blank" : "";
					
					$parent.find( 'span.link-title-value' ).html( $title );
					$parent.find( 'span.url-value' ).html( $url );
					$parent.find( 'span.target-value' ).html( $target );
					
					$parent.find( 'input.cssf-url' ).val( $url );
					$parent.find( 'input.cssf-title' ).val( $title );
					$parent.find( 'input.cssf-target' ).val( $target );
					
					$dialog.close(),
					$link_submit.show(),
					$cssf_link_submit.unbind( "click.cssf-wpLink" ),
					$cssf_link_submit.remove(),
					$( "#wp-link-cancel" ).unbind( "click.cssf-wpLink" ),
					window.wpLink.textarea = "";
					
					$this.trigger( 'cssf-links-updated' );
				} );
				
				$( "#wp-link-cancel" ).unbind( "click.cssf-wpLink" ).bind( "click.cssf-wpLink", function (e) {
					e.preventDefault(),
					$dialog.close(),
					$cssf_link_submit.unbind( "click.cssf-wpLink" ),
					$cssf_link_submit.remove(),
					$( "#wp-link-cancel" ).unbind( "click.cssf-wpLink" ),
					window.wpLink.textarea = "";
					
					$this.trigger( 'cssf-links-updated' );
				} );
			} )
		} )
	};
	// ======================================================
	
	// ======================================================
	// Color Theme field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_COLORTHEME = function() {
		return this.each(function(){
			var self = this;
			
			var $parent 			= $('.cssf-schemes',self),
				$builder 			= $('.cssf-scheme-builder',self),
				$controls 			= $('.cssf-schemes-controls',self),
				$schemes_list 		= $('.cssf-schemes-list',self),
				$predefined_schemes = $('.cssf-color-scheme-predefined_schemes',self),
				$custom_schemes		= $('.cssf-color-scheme-custom_schemes',self),
				$input 				= $('.cssf-color-scheme-scheme_name',$controls),
				$btn_save 			= $('.cssf-color-scheme-save_scheme',$controls),
				$btn_import 		= $('.cssf-color-scheme-import_scheme',$controls),
				$import_box			= $('.cssf-schemes-import',$controls),
				$btn_import_submit 	= $('.cssf-schemes-import_submit',$import_box),
				$btn_export			= $('.cssf-color-scheme-export_scheme',$controls),
				field_unique 		= $('.cssf-color-scheme-unique',self).val(),
				options_unique 		= $('input[name=option_page]','#cssframework_form').val(),
				$preview_template 	= $('.cssf-scheme-preview-template',self),
				$user_roles_wrapper = $('.cssf-schemes-user-roles-outer-wrapper .cssf-schemes-user-roles-wrapper',self);
			
			
			
			/**
			* 
			* USER ROLES
			* 
			* */ 
			
			// Callback When color has been updated
			$(self).on('cssf-color_theme-update-end',function(event,field_id,color){
				updateUserRoleScheme('color');
			});
			
			
			// 1. When Selecting a userrole
			$('.cssf-schemes-user-role',$user_roles_wrapper).on('click',function(){
				var $self 		= $(this),
					role 		= $self.data('role'),
					scheme_id 	= $self.data('currentSchemeId'),
					scheme_type = $self.data('currentSchemeType'),
					load_status = $parent.data('status');
				
				if (load_status) { return; }
				
				// Update Role Status Helper Function
				var update_role_status = function(status = false){
					if (status == 'loading'){
						// Update Global Loading Status
						$parent.data('status','loading');
						$self.addClass('cssf-loading-scheme');
					} else {
						// Update Global Loading Status
						$parent.data('status',false);
						$self.removeClass('cssf-loading-scheme');
					}
				}
				
				update_role_status('loading');
				
				// Update current role data
				$user_roles_wrapper.data('current-role',role);
				
				// Toggle Active Class
				$('.cssf-schemes-user-role',$user_roles_wrapper).removeClass('cssf-schemes-user-role__active');
				$self.addClass('cssf-schemes-user-role__active');
				
				var scheme_to_load;
				var checkJSON = function(m) {
					if (typeof m == 'object') { 
						try{ m = JSON.stringify(m); }
						catch(err) { return false; } 
					}
					
					if (typeof m == 'string') {
						try{ m = JSON.parse(m); }
						catch (err) { return false; } 
					}
					
					if (typeof m != 'object') { return false; }
					return true;
				};
				if (role == 'cssf_default_scheme'){
					var scheme_value = $('.cssf-userrole-input-field.cssf-userrole_cssf_default_scheme_scheme',$user_roles_wrapper).val();
					scheme_to_load = scheme_value;
				} else if (scheme_id == 'default_theme'){
					var $default_scheme = $('.cssf-schemes-user-role[data-role="cssf_default_scheme"]'),
					scheme_id 		= $default_scheme.data('currentSchemeId'),
					scheme_type 	= $default_scheme.data('currentSchemeType');
					
					var scheme_value = $('.cssf-userrole-input-field.cssf-userrole_cssf_default_scheme_scheme',$user_roles_wrapper).val();
					
					scheme_to_load = scheme_value;
				} else {
					var scheme_value 	= $('.cssf-userrole-input-field.cssf-userrole_'+role+'_scheme',$user_roles_wrapper).val();
					scheme_to_load 		= scheme_value;
				}
				
				$('.cssf-schemes-item',$schemes_list).removeClass('cssf-schemes-item-current');
				$('*[data-scheme-id="'+scheme_id+'"][data-scheme-type="'+scheme_type+'"]',$schemes_list).addClass('cssf-schemes-item-current');
				
				if (checkJSON(scheme_to_load)){
					scheme_to_load = JSON.parse(scheme_to_load);
					var load_scheme = setTimeout(function(){
						$.each(scheme_to_load,function(index,value){
							$('.cssf-field-color-picker[data-field-name='+index+']').wpColorPicker('color',value);
						});
						
						clearTimeout(changeColorTimeout);
						
						update_role_status(false);
					},10);
				} else {
					update_role_status(false);
				}
			});
			
			$('.cssf-schemes-user-role_options .cssf-schemes-user-role_options-item.cssf-schemes-user-role_options-item--delete',$user_roles_wrapper).on('click',function(e){
				e.preventDefault();
				e.stopImmediatePropagation();
				var $this 						= $(this),
				$userrole 					= $this.parents('.cssf-schemes-user-role'),
				userrole 					= $userrole.data('role'),
				$userrole_input_scheme_id	= $('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme_id',$user_roles_wrapper),
				$userrole_input_scheme		= $('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme',$user_roles_wrapper),
				$options 					= $this.parents('.cssf-schemes-user-role_options'),
				$default 					= $('*[data-role="cssf_default_scheme"]',$user_roles_wrapper),
				default_scheme_id 			= $default.data('currentSchemeId'),
				default_scheme_type 		= $default.data('currentSchemeType'),
				default_scheme_value		= $('.cssf-userrole-input-field.cssf-userrole_cssf_default_scheme_scheme',$user_roles_wrapper).val();
				
				
				$options.addClass('cssf-schemes-user-role_options__hidden');
				
				$userrole.data('currentSchemeId','default_theme').data('currentSchemeType','');
				$('.cssf-schemes-user-role_scheme span',$userrole).text($('.cssf-schemes-user-role_name',$default).text());
				$userrole_input_scheme_id.val('default_theme');
				$userrole_input_scheme.val('default_theme');
				// $userrole_input_scheme.val(default_scheme_value);
			});
			
			
			var updateUserRoleScheme = function(type){
				var userrole 				= $user_roles_wrapper.data('current-role'),
					$userrole 				= $('*[data-role="'+userrole+'"]',$user_roles_wrapper),
					userrole_current_scheme = $userrole.data('currentSchemeId'),
					$current_scheme 		= $('.cssf-schemes-item-current',$schemes_list),
					scheme_id 				= $current_scheme.data('scheme-id'),
					scheme_type 			= $current_scheme.data('scheme-type'),
					scheme_name 			= $('.preview_text',$current_scheme).text(),
					update_type 			= type;
				
				var isobject = function(x){
					return Object.prototype.toString.call(x) === '[object Object]';
				};
				var getkeys = function(obj, prefix){
					var keys = Object.keys(obj);
					prefix = prefix ? prefix + '.' : '';
					
					return keys.reduce(function(result, key){
						if(isobject(obj[key])){
							result = result.concat(getkeys(obj[key], prefix + key));
						} else {
							result.push(prefix + key);
						}
						return result;
					}, []);
				};
				var getPath = function(str){
					var period = str.lastIndexOf('.');
					var path = str.substring(0, period);
					// path 	= path.replace(/[\w\s-]+/g, function () { return '["' + arguments[0] + '"]';});
					// path 	= path.replace(/\./g, '');
					return path;
				}
				var deepFind = function(obj, path) {
					var paths = path.split('.')
					, current = obj
					, i;
					
					for (i = 0; i < paths.length; ++i) {
						if (current[paths[i]] == undefined) {
							return undefined;
						} else {
							current = current[paths[i]];
						}
					}
					return current;
				}
				
				if (update_type == 'schemes'){
					if (userrole != 'cssf_default_scheme'){
						$('.cssf-schemes-user-role_options',$userrole).removeClass('cssf-schemes-user-role_options__hidden');
					}
					
					var jsonData 	= $('.cssf-scheme-builder :input',self).serializeJSONCSSF();
					var jsonData 	= JSON.parse(jsonData),
						keys 		= getkeys(jsonData),
						path 		= getPath(keys[0]),
						deepfind 	= deepFind(jsonData,path),
						data 		= JSON.stringify(deepfind);
					
					$userrole.data('currentSchemeId',scheme_id).data('currentSchemeType',scheme_type);
					$('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme_id',$user_roles_wrapper).val(scheme_id+":"+scheme_type);
					$('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme',$user_roles_wrapper).val(data);
					$('.cssf-schemes-user-role_scheme span',$userrole).text(scheme_name);
				} else if (update_type == 'color'){
					var jsonData 	= $('.cssf-scheme-builder :input',self).serializeJSONCSSF();
					var jsonData 	= JSON.parse(jsonData),
						keys 		= getkeys(jsonData),
						path 		= getPath(keys[0]),
						deepfind 	= deepFind(jsonData,path),
						data 		= JSON.stringify(deepfind);
					
					$('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme',$user_roles_wrapper).val(data);
					
					if (userrole_current_scheme == 'default_scheme'){
						$('.cssf-schemes-user-role_options',$userrole).removeClass('cssf-schemes-user-role_options__hidden');
						$userrole.data('currentSchemeId',scheme_id).data('currentSchemeType',scheme_type);
						$('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme_id',$user_roles_wrapper).val(scheme_id+":"+scheme_type);
						$('.cssf-userrole-input-field.cssf-userrole_'+userrole+'_scheme',$user_roles_wrapper).val(data);
						$('.cssf-schemes-user-role_scheme span',$userrole).text(scheme_name);
					}
				}
			};

			// Hide Import Box Content
			$import_box.slideUp();
			
			// Select Color Scheme
			$schemes_list.on('click','.cssf-schemes-item',function(e){
				e.preventDefault();
				
				var $self 				= $(this),
					scheme_id 			= $self.data('schemeId'),
					scheme_type 		= $self.data('schemeType'),
					predefined_schemes 	= ($predefined_schemes.val()) ? JSON.parse($predefined_schemes.val()) : null,
					custom_schemes 		= ($custom_schemes.val()) ? JSON.parse($custom_schemes.val()) : null,
					all_schemes 		= {},
					status 				= $schemes_list.data('status');
				
				if (status != 'working'){
					$schemes_list.data('status','working');
					all_schemes['predefined'] 	= predefined_schemes;
					all_schemes['custom']		= custom_schemes;
					
					var current_scheme 			= all_schemes[scheme_type][scheme_id];
					
					if (current_scheme){
						$self.addClass('cssf-loading-scheme');
						
						var load_scheme = setTimeout(function(){
							// Update Current Scheme ID, val & list border style
							scheme_type = (scheme_type == 'custom') ? 'custom' : 'predefined';
							$('.cssf-color-scheme-current_id',$parent).val(scheme_id);
							$('.cssf-color-scheme-current_type',$parent).val(scheme_type);
							
							$('.cssf-schemes-item-current',$schemes_list).removeClass('cssf-schemes-item-current');
							$self.addClass('cssf-schemes-item-current');
							
							// Clear all color pickers
							var $allColorFields = $('.cssf-field-color-picker',$builder);
							$.each($allColorFields,function(index,target){
								var _colorValue = $(target).val(),
								_colorDefault = $(target).data('default-color'),
								_colorName 		= $(target).data('field-name');
								
								if (current_scheme.scheme[_colorName] === undefined || current_scheme.scheme[_colorName] === "" || current_scheme.scheme[_colorName] == 'transparent'){
									_colorDefault = 'rgba(255,255,255,0)';
									$('.cssf-field-color-picker[data-field-name='+_colorName+']').wpColorPicker('color','rgba(255,255,255,0)');
								}
							});
							
							$.each(current_scheme.scheme,function(index,value){
								$('.cssf-field-color-picker[data-field-name='+index+']').wpColorPicker('color',value);
							});
							
							$self.removeClass('cssf-loading-scheme');
							
							// Update Schemes List Status
							$schemes_list.data('status','');
							
							
							// Set Current Scheme to active userrole
							clearTimeout(changeColorTimeout);
							updateUserRoleScheme('schemes');
						},10);
					}
				}
			});
			
			
			// Save New Scheme
			$btn_save.on('click',function(e){
				e.preventDefault();
				var scheme_name = $input.val();
				
				if (scheme_name){
					if ($btn_save.data('status')) { return false; }
					
					// Spinner
					var btn_save_text = $btn_save.html();
					$btn_save.data('status','working').attr('disabled','disabled').addClass('cssf-button-disabled').html('<div class="cssf-spinner"></div>');
					
					// Get Current Color Scheme
					var colors = $('.cssf-scheme-builder .cssf-field-color_picker .cssf-field-color-picker',self);
					var current_scheme = {};
					
					$(colors).each(function(){
						var current = $(this),
						color	= current.val(),
						name 	= current.data('fieldName');
						
						current_scheme[name] = color;
					});
					
					var new_scheme = {
						name: scheme_name,
						scheme: current_scheme
					};
					
					// AJAX Call
					var action = 'cssf-color-scheme_save';
					var data = {
						nonce: 			cssf_framework.nonce, 	// Security nonce
						action: 		action,					// Ajax Action
						// Parameters
						field_unique:	field_unique,
						options_unique:	options_unique,
						scheme:			new_scheme,
					};
					
					$.ajax({
						url: 	cssf_framework.ajax_url,
						type: 	'post',
						data: 	data,
						success: function(response){
							if (response.success){
								// Update Custom Schemes
								$custom_schemes.val(response.data.schemes);
								
								// Add Scheme Preview
								var $preview = $($preview_template.html()).clone();
								var scheme_slug = $.CSSFRAMEWORK.HELPER.FUNCTIONS.string_to_slug(scheme_name);
								$preview.attr('data-scheme-id',scheme_slug);
								$('.cssf-schemes-item_delete',$preview).attr('data-scheme-id',scheme_slug);
								$('.preview_text',$preview).html($.CSSFRAMEWORK.HELPER.FUNCTIONS.make_title(scheme_slug));
								
								// current_scheme
								var colores = $preview_template.data('schemeColors');
								var color_vars = '';
								$.each(colores,function(index,value){
									index++;
									color_vars += "--color"+index+":"+current_scheme[value]+";";
								});
								
								$preview.attr('style',color_vars);
								
								// Add new scheme preview
								$preview.appendTo($schemes_list);
							}
						},
						complete: function(){
							$btn_save.data('status',false).removeAttr('disabled').removeClass('cssf-button-disabled').html(btn_save_text);
						}
					});
				}
				
			});
			
			
			// Import Schemes
			$btn_import.on('click',function(e){
				e.preventDefault();
				$import_box.slideToggle();
			});
			// Import Submit Schemes
			$btn_import_submit.on('click',function(e){
				e.preventDefault();
				
				var $self 				= $(this),
				$import_textarea 	= $('.cssf-schemes-import_data',$controls),
				$import_checkbox 	= $('.cssf-schemes-import_overwrite',$controls),
				schemes_to_import 	= $import_textarea.val(),
				schemes_overwrite 	= $import_checkbox.prop('checked');
				
				if (schemes_to_import){
					if ($self.data('status')) { return false; }
					
					// Spinner
					var btn_text = $self.html();
					$self.data('status','working').attr('disabled','disabled').addClass('cssf-button-disabled').html('<div class="cssf-spinner"></div>');
					
					
					// AJAX Call
					var action = 'cssf-color-scheme_import';
					var data = {
						nonce: 			cssf_framework.nonce, 	// Security nonce
						action: 		action,					// Ajax Action
						// Parameters
						field_unique:	field_unique,
						options_unique:	options_unique,
						schemes:		schemes_to_import,
						overwrite:		schemes_overwrite,
					};
					
					$.ajax({
						url: 	cssf_framework.ajax_url,
						type: 	'post',
						data: 	data,
						success: function(response){
							if (response.success){
								// Update Custom Schemes
								$custom_schemes.val(response.data.schemes);
								
								var schemes 			= JSON.parse(response.data.schemes),
								$schemes_to_append 	= $(document.createDocumentFragment());
								
								$.each(schemes,function(index,scheme){
									// Add Scheme Preview
									var $preview 		= $($preview_template.html()).clone(),
									scheme_name 	= $.CSSFRAMEWORK.HELPER.FUNCTIONS.make_title(scheme.name),
									scheme_slug 	= index,
									current_scheme 	= scheme.scheme;
									
									$preview.attr('data-scheme-id',scheme_slug);
									$('.cssf-schemes-item_delete',$preview).attr('data-scheme-id',scheme_slug);
									$('.preview_text',$preview).html(scheme_name);
									
									// current_scheme
									var colores = $preview_template.data('schemeColors');
									var color_vars = '';
									$.each(colores,function(index,value){
										index++;
										color_vars += "--color"+index+":"+current_scheme[value]+";";
									});
									
									$preview.attr('style',color_vars).appendTo($schemes_to_append);
								});
								
								// Add new scheme preview
								$('.cssf-schemes-item[data-scheme-type=custom]',$schemes_list).remove();
								$schemes_list.append($schemes_to_append);
								
								// Empty Textarea
								$import_textarea.val('');
								$import_checkbox.prop('checked',false).trigger('change');
							}
						},
						complete: function(){
							$self.data('status',false).removeAttr('disabled').removeClass('cssf-button-disabled').html(btn_text);
						}
					});
				}
			});
			
			
			// Delete Scheme
			$schemes_list.on('click','.cssf-schemes-item_delete',function(e){
				e.preventDefault();
				e.stopPropagation();
				
				var $self 		= $(this),
				$preview 	= $self.parents('.cssf-schemes-item'),
				scheme_id 	= $self.data('schemeId');
				
				
				if (!$self.data('status')){
					$self.data('status','confirm');
					$self.html('<div class="cssf-button-inner"><a href="#">Are you sure?</a></div>');
					return false;
				}
				var status = $self.data('status');
				if (status && status != 'confirm') { return false; }
				
				// Spinner
				$self.data('status','working').addClass('cssf-visible').html('<div class="cssf-spinner"></div>');
				
				// AJAX Call
				var action = 'cssf-color-scheme_delete';
				var data = {
					nonce: 			cssf_framework.nonce, 	// Security nonce
					action: 		action,					// Ajax Action
					// Parameters
					field_unique:	field_unique,
					options_unique:	options_unique,
					scheme: 		scheme_id,
				};
				
				var state = null;
				
				$.ajax({
					url: 	cssf_framework.ajax_url,
					type: 	'post',
					data: 	data,
					success: function(response){
						if (response.success){
							state = 'success';
							// Update Custom Schemes
							$custom_schemes.val(response.data.schemes);
							
							$self.addClass('cssf-visible').html('<i class="cli cli-check"></i>')
							
							// Remove Scheme Preview
							var setStyle = function( element, propertyObject ){
								var elem = element.style;
								for (var property in propertyObject){
									elem.setProperty(property, propertyObject[property]);
								}
							}
							var style = { '--item-background-color':'rgba(220, 49, 49, 1)'};
							setStyle($preview[0],style);
							$preview.delay(500).queue(function(){
								$(this).addClass('cssf-schemes-item-deleted').dequeue().one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
									$(this).remove();
								});
							});
						}
					},
					complete: function(){
						$self.data('status',false).removeClass('cssf-visible');
						if (!state){
							$self.html('<i class="cli cli-trash"></i>');
						}
					}
				});
			}).on('mouseleave','.cssf-schemes-item_delete',function(e){
				var $self 		= $(this);
				
				if ($self.data('status') != 'working'){
					$self.html('<i class="cli cli-trash"></i>').data('status',false);
				}
				
			});
			
			
			$('.cssf-scheme-section',self).accordion({
				header: '.cssf-accordion-title',
				collapsible: true,
				clearStyle: true,
				active: false,
				animate: 350,
				heightStyle: 'content',
				icons: {
					'header': 'cli cli-arrow-down',
					'activeHeader': 'cli cli-arrow-up'
				},
				beforeActivate: function(event, ui) {
					$(ui.newPanel).CSSFRAMEWORK_DEPENDENCY('sub');
				}
			});
			
			var changeColorTimeout;
			$('.cssf-field-color-picker',self).each(function() {
				var $this = $(this);
				
				$this.wpColorPicker({
					//   palettes: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f']
					change: function(event, ui){
						var element = event.target;
						
						var $target 	= $(event.target),
						field_id 	= $target.data('fieldName'),
						color 		= ui.color.toString();
						
						clearTimeout(changeColorTimeout);
						changeColorTimeout = setTimeout(function(){
							$this.parents('.cssf-field-color_theme').trigger( "cssf-color_theme-update-end", [ field_id, color ] );
						},1800);
						
						$this.parents('.cssf-field-color_theme').trigger( "cssf-color_theme-update", [ field_id, color ] );
					}
				});
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// CSSFRAMEWORK FILE UPLOADER
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_FILE_UPLOADER = function() {
		return this.each(function() {
			var $this    		= $(this),
			$add     		= $this.find('.cssf-add'),
			$preview 		= $this.find('.cssf-file-preview'),
			$preview_type 	= $('.cssf-preview',$preview),
			$preview_title 	= $('.cssf-preview-data-file_title',$preview),
			$preview_name 	= $('.cssf-preview-data-file_name span',$preview),
			$preview_size 	= $('.cssf-preview-data-file_size span',$preview),
			$preview_link 	= $('.cssf-preview-data-file_link span',$preview),
			$remove  		= $this.find('.cssf-remove'),
			$input   		= $this.find('input'),
			$img     		= $this.find('img'),
			wp_media_frame;
			
			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: $add.data('upload-type'),
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					var attachment = wp_media_frame.state().get('selection').first().attributes;
					
					$preview.removeClass('hidden');
					$remove.removeClass('hidden');
					
					// Update File ID 
					$input.val(attachment.id).trigger('change');
					
					// Update File Data
					$preview_type.addClass('cssf-file-preview--type_'+attachment.subtype);
					$preview_title.html(attachment.title);
					$preview_name.html(attachment.filename);
					$preview_size.html(attachment.filesizeHumanReadable);
					$preview_link.html(attachment.link);
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$input.val('').trigger('change');
				$remove.addClass('hidden');
				$preview.addClass('hidden');
				// Clean Preview Data
				$preview_type.removeClass().addClass('cssf-preview');
				$preview_title.html('');
				$preview_name.html('');
				$preview_size.html('');
				$preview_link.html('');
			});
		});
	};
	// ======================================================
	
	// ======================================================
	// Layout Builder field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_LAYOUTBUILDER = function() {
		return this.each( function() {
			var $self 		= $( this ),
			top 		= $('.layout-section__top',$self),
			left 		= $('.layout-section__left',$self),
			right 		= $('.layout-section__right',$self),
			bottom 		= $('.layout-section__bottom',$self),
			main 		= $('.layout-section__main',$self),
			buttonbar 	= $('.layout-section__buttonbar',$self),
			elements 	= $('.cssf-uls-layout__elements',$self);
			
			var containers = [
				top[0],
				left[0],
				right[0],
				bottom[0],
				main[0],
				buttonbar[0],
				elements[0]
			];
			
			var updateLayoutSections = function(el){
				var section_top 		= $('.cssf-uls-layout-element',top),
				section_left 		= $('.cssf-uls-layout-element',left),
				section_right 		= $('.cssf-uls-layout-element',right),
				section_bottom 		= $('.cssf-uls-layout-element',bottom),
				section_main 		= $('> .cssf-uls-layout-element',main), // only direct child, to avoid buttonbar elements
				section_buttonbar 	= $('.cssf-uls-layout-element',buttonbar),
				section_elements	= $('.cssf-uls-layout-element',elements);
				
				var getElements = function(section){
					var map = jQuery.map( section, function( n, i ) {
						return $(n).data('layoutElementName');
					});
					var array = Object.values(map);
					return JSON.stringify(array);	
				};
				
				$('input.section__top',$self).val(getElements(section_top));
				$('input.section__left',$self).val(getElements(section_left));
				$('input.section__right',$self).val(getElements(section_right));
				$('input.section__bottom',$self).val(getElements(section_bottom));
				$('input.section__main',$self).val(getElements(section_main));
				$('input.section__buttonbar',$self).val(getElements(section_buttonbar));
				$('input.section__elements',$self).val(getElements(section_elements));
			};

			// Plus Admin Top Navbar Sorting
			Sortable.create(elements[0], { 
				animation: 300,
				group: "csbuilder",
				onEnd: function (evt) {
					updateLayoutSections();
				},
			});
			Sortable.create(main[0], { 
				animation: 300,
				group: "csbuilder",
				onEnd: function (evt) {
					updateLayoutSections();
				},
			});
			
			updateLayoutSections();
		});
	};
	// ======================================================

	// ======================================================
	// RELOAD WYSIWYG FIELDS
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_RELOAD_WYSIWYG = function() {
		var group_wysiwyg           = this;
		
		if (group_wysiwyg && group_wysiwyg.length){
			
			group_wysiwyg.each(function(){
				
				if( jQuery(this).find('.mce-tinymce').length <= 0 ) {
					var field_group_i         	= jQuery(this).closest('.cssf-group').index();
					var field_group_i 			= jQuery(this).closest('.cssf-group').children('.cssf-group-content').attr('id');
					var default_wysiwyg_id    	= jQuery(this).find('textarea').attr('id');
					var group_wysiwyg_id      	= default_wysiwyg_id + '_' + field_group_i;
					var tmp_wysiwyg_settings  	= tinyMCEPreInit.mceInit[default_wysiwyg_id];
					
					// console.log("Creating WP Editor:",default_wysiwyg_id,tmp_wysiwyg_settings);
					
					jQuery(this).find('textarea').attr('id', group_wysiwyg_id).data('original_id',default_wysiwyg_id);
					jQuery(this).find('.wp-media-buttons .add_media').data('editor', group_wysiwyg_id);
					jQuery(this).find('.wp-editor-tabs .wp-switch-editor').attr('data-wp-editor-id', group_wysiwyg_id);
					
					tinymce.init(tmp_wysiwyg_settings); 
					tinyMCE.execCommand('mceAddEditor', false, group_wysiwyg_id);
				}
			});
		}
	};
	// ======================================================
	
	// ======================================================
	// ON WIDGET-ADDED RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.CSSFRAMEWORK.WIDGET_RELOAD_PLUGINS = function() {
		$(document).on('widget-added widget-updated', function(event, $widget) {
			$widget.CSSFRAMEWORK_RELOAD_PLUGINS();
			$widget.CSSFRAMEWORK_DEPENDENCY();
		});
	};
	// ======================================================
	


	// ======================================================
	// RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.CSSFRAMEWORK.EXTERNAL_PLUGINS = {
		'init': function(){
			for (var key in $.CSSFRAMEWORK.PLUGINS){
				$.CSSFRAMEWORK.PLUGINS[key].init();
			}
		},
		'reload': function(){
			for (var key in $.CSSFRAMEWORK.PLUGINS){
				$.CSSFRAMEWORK.PLUGINS[key].reload();
			}
		}
	};
	
	$.CSSFRAMEWORK.PLUGINS = {};
	$.fn.CSSFRAMEWORK_RELOAD_PLUGINS = function() {
		return this.each(function() {
			
			// Field Plugins
			// ------------------------------
			$('.cssf-chosen', this).CSSFRAMEWORK_CHOSEN();
			$('.cssf-field-image-select', this).CSSFRAMEWORK_IMAGE_SELECTOR();
			$('.cssf-field-image', this).CSSFRAMEWORK_IMAGE_UPLOADER();
			$('.cssf-field-gallery', this).CSSFRAMEWORK_IMAGE_GALLERY();
			$('.cssf-field-sorter', this).CSSFRAMEWORK_SORTER();
			$('.cssf-field-upload', this).CSSFRAMEWORK_UPLOADER();
			$('.cssf-field-typography', this).CSSFRAMEWORK_TYPOGRAPHY();
			$('.cssf-field-color-picker', this).CSSFRAMEWORK_COLORPICKER();
			$('.cssf-field-wysiwyg',this).CSSFRAMEWORK_RELOAD_WYSIWYG();
			
			$('.cssf-has-tooltip', this).CSSFRAMEWORK_TOOLTIP();								// To add tooltip functionality on other fields
			$('.cssf-field-slider', this).CSSFRAMEWORK_SLIDER();								// Slider field 
			$('.cssf-field-easing_editor', this).CSSFRAMEWORK_EASINGEDITOR();					// Easing Editor field 
			$('.cssf-field-typography_advanced', this).CSSFRAMEWORK_TYPOGRAPHY_ADVANCED(); 		// Typography Advanced field 
			$('.cssf-field-accordion', this).CSSFRAMEWORK_ACCORDION();							// Accordion field 
			$('.cssf-field-angle', this).CSSFRAMEWORK_ANGLE();									// Angle field 
			$('.cssf-field-code_editor', this).CSSFRAMEWORK_CODEEDITOR();						// Code Editor field 
			$('.cssf-field-background', this).CSSFRAMEWORK_BACKGROUND();						// Background Editor field 
			$('.cssf-field-animate_css', this).CSSFRAMEWORK_ANIMATE_CSS();						// Animate.css field 
			$('.cssf-field-css_builder', this).CSSFRAMEWORK_CSS_BUILDER();						// CSS Builder 
			$('input[data-limit-element="1"]', this).CSSFRAMEWORK_LIMITER();					// Text Input Limiter 
			$('textarea[data-limit-element="1"]', this).CSSFRAMEWORK_LIMITER();					// Textarea Limiter 
			$('.cssf-field-checkbox .cssf-checkbox-labeled').CSSFRAMEWORK_CHECKBOX_LABELED(); 	// Labeled Checkboxes
			$('.cssf-field-checkbox .cssf-checkbox-icheck').CSSFRAMEWORK_CHECKBOX_ICHECK(); 	// iCheck Checkboxes
			$('.cssf-field-radio .cssf-checkbox-icheck').CSSFRAMEWORK_CHECKBOX_ICHECK(); 		// iCheck Checkboxes
			$('.cssf-wp-link', this).CSSFRAMEWORK_WPLINKS();									// WPLinks Field 
			$('.cssf-field-color_theme', this).CSSFRAMEWORK_COLORTHEME();						// Accordion field 
			$('.cssf-field-file', this).CSSFRAMEWORK_FILE_UPLOADER();
			$('.cssf-field-builder_navbar', this).CSSFRAMEWORK_LAYOUTBUILDER();					// Layout Builder: Navbar 
			$('.cssf-field-backup', this).CSSFRAMEWORK_BACKUP_IMPORT();							// BACKUP AJAX field 
			
			
			// Field Siblings
			$('.cssf-field-button_set',this).find('.cssf-siblings').CSSFRAMEWORK_SIBLINGS();
			
			
			// Helper
			$('.cssf-help', this).CSSFRAMEWORK_TOOLTIP();
			$('.cssf-number', this).CSSFRAMEWORK_NUMBER();
			
			$.CSSFRAMEWORK.EXTERNAL_PLUGINS.reload();
		});
	};
	// ======================================================
	// JQUERY DOCUMENT READY
	// ------------------------------------------------------
	$(document).ready(function() {
		$('.cssf-framework').CSSFRAMEWORK_TAB_NAVIGATION();
		$('.cssf-framework .cssf-nav').CSSFRAMEWORK_NAV_SCROLL_TABS();
		// $('.cssf-reset-confirm, .cssf-import-backup').CSSFRAMEWORK_CONFIRM();
		$('.cssf-reset-confirm').CSSFRAMEWORK_CONFIRM();
		$('.cssf-content, .wp-customizer, .widget-content, .cssf-taxonomy').CSSFRAMEWORK_DEPENDENCY();
		$('.cssf-field-group').CSSFRAMEWORK_GROUP();
		$('.cssf-save').CSSFRAMEWORK_SAVE();
		$.CSSFRAMEWORK.ICONS_MANAGER();
		$.CSSFRAMEWORK.IMAGE_GALLERY_CUSTOM();
		$.CSSFRAMEWORK.SHORTCODE_MANAGER();
		$.CSSFRAMEWORK.WIDGET_RELOAD_PLUGINS();
		
		var loadTimeout = setTimeout(function(){
			// $.CSSFRAMEWORK.EXTERNAL_PLUGINS.init();
			
			$cssf_body.CSSFRAMEWORK_RELOAD_PLUGINS();
		},0);
		// $('.cssf-field-wysiwyg').CSSFRAMEWORK_RELOAD_WYSIWYG(); // Causaba un error de id_undefined
		
	});
})(jQuery, window, document);