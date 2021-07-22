jQuery( function ( $ ) {
	'use strict';

	// ======================================================
	// NAVBAR BUILDER field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSSFRAMEWORK_NAVBARBUILDER = function() {
		return this.each(function(){
			var $this 		= $(this);

			var $enabled 		= $('.cssf-layout-builder__enabled',$this),
				$disabled 		= $('.cssf-layout-builder__disabled',$this),
				$elements 		= $('.cssf-layout-builder-elements-wrapper',$disabled),
				$main 			= $('.cssf-layout-builder_section--main',$this),
				$elements_data 	= $('input.elements__data',$this),
				$parent 		= $this.children('.cssf-element-fieldset'),
				current_eId		= false;

			const updateLayoutSections = function(){
				var $enabled 				= $('.cssf-layout-builder__enabled',$this),
					$disabled 				= $('.cssf-layout-builder__disabled',$this),
					section_main_elements 	= $('.cssf-layout-builder_element',$main),
					section_all_elements	= $('.cssf-layout-builder_element',$this);
				
				var enabled_count 	= $('.cssf-layout-builder_element',$enabled),
					disabled_count 	= $('.cssf-layout-builder_element',$disabled);
				
				var getElements = function(section){
					var map = jQuery.map( section, function( n, i ) {
						return $(n).data('layoutElementName');
					});
					var array = Object.values(map);
					return JSON.stringify(array);
				};


				// Check for deleted or added elements
				var tempData = {};
				var current_data = $elements_data.val();
				if ($.CSSFRAMEWORK.HELPER.FUNCTIONS.checkJSON(current_data)){
					var current_data = JSON.parse(current_data);

					$.each(section_all_elements,function(index,target){
						var eId = $(target).data('layout-element-name');
						var eData = current_data[eId];
						if (eData !== 'undefined'){
							tempData[eId] = eData;
						}
					});
				}
				// Update elements data
				var newVal = JSON.stringify(tempData);
				$elements_data.val(newVal);


				// Show/Hide Placeholder messages
				if (enabled_count.length <= 0){
					$('.cssf-layout-placeholder',$enabled).removeClass('cssf-pseudo-hidden');
				} else {
					$('.cssf-layout-placeholder',$enabled).addClass('cssf-pseudo-hidden');
				}
				if (disabled_count.length <= 0){
					$('.cssf-layout-placeholder',$disabled).removeClass('cssf-pseudo-hidden');
				} else {
					$('.cssf-layout-placeholder',$disabled).addClass('cssf-pseudo-hidden');
				}
				
				// Update Values
				$('input.section__main',$this).val(getElements(section_main_elements));
				$('input.section__elements',$this).val(getElements(section_all_elements));
			}

			const fillEditorFields = function(eData = false){
				var default_data = {
					'id':					false,
					'uniqueid':				false,
					'type': 				false,
					'name': 				false,
					'visibility_device':	[],
					'user_avatar': 			false,
					'icon': 				false,
					'icon_secondary': 		false,
					'customlink': 			false,
					'customlink_type':		false,
					'tooltip_status': 		false,
					'tooltip_text': 		false,
					'label_status':			false,
					'label_text':			false,
					'notifications_status': false,
					'element_settings':{
						'container_id': 	false,
						'container_class':	false,
						'wrapper_type':		false,
						'wrapper_id':		false,
						'wrapper_class':	false,
						'wrapper_attr':		false,
						'submenu':			false,
						'template':			false,
					}
				};

				// Change Editor UI
				if (eData){
					$('.cssf-field-id_editor_action option[value="edit"]',$parent).attr('selected','selected').trigger('change');
					$parent.addClass('cssf-builder-editing');
					$('.editor_action_title',$parent).html($('.cssf-navbar-builder-edit_element',$this).html());
				} else {
					$('.cssf-field-id_editor_action option[value="add"]',$parent).attr('selected','selected').trigger('change');
					$parent.removeClass('cssf-builder-editing');
					$('.editor_action_title',$parent).html($('.cssf-navbar-builder-add_element',$this).html());
				}
				
				// Set Default Element Data
				eData = $.extend(default_data,eData);

				// Update Global element id
				current_eId = eData.id;

				// Element Type
				if (eData.type){
					$('.element_type option[value="'+eData.type+'"]',$parent).attr('selected','selected').trigger('change');
				} else {
					$('.element_type',$parent).prop('selectedIndex', 0).trigger('change');
				}

				// Element Visibility
				if (eData.visibility_device){
					$('.element_visibility_device',$parent).siblings('.cssf-chosen-clone').find('option').remove();
					$.each(eData.visibility_device,function(index,value){
						var $opt = $('.element_visibility_device option[value=' + value + ']',$parent);
						if ($opt){
							$('.element_visibility_device',$parent).siblings('.cssf-chosen-clone').append(new Option($opt.text(),$opt.val(),true,true));
						}
					});

					$('.element_visibility_device',$parent).setSelectionOrder(eData.visibility_device,true);
				} else {
					$('.element_visibility_device',$parent).prop('selectedIndex', -1).trigger('change').trigger("chosen:updated");
					$('.element_visibility_device',$parent).siblings('.cssf-chosen-clone').find('option').remove();

					$('.element_visibility_device',$parent).setSelectionOrder([],true);
				}

				// User Avatar
				if (eData.user_avatar){
					$('.element_useravatar_status',$parent).prop('checked',eData.user_avatar).trigger('change');
				} else {
					$('.element_useravatar_status',$parent).prop('checked',false).trigger('change');
				}

				// Element Icons
				var $fIcon = $('.element_icon',$parent).parents('.cssf-icon-select');
				var $sIcon = $('.element_icon_secondary',$parent).parents('.cssf-icon-select');
				// First Icon
				if (eData.icon){
					$('.element_icon',$parent).val(eData.icon);
					$('.cssf-icon-preview',$fIcon).removeClass('hidden');
					$('.cssf-icon-preview i',$fIcon).removeClass().addClass(eData.icon);
					$('.cssf-button',$fIcon).removeClass('hidden');
				} else {
					$('.cssf-icon-remove',$fIcon).trigger('click');
				}
				// Secondary Icon
				if (eData.icon_secondary){
					$('.element_icon_secondary',$parent).val(eData.icon_secondary);
					$('.cssf-icon-preview',$sIcon).removeClass('hidden');
					$('.cssf-icon-preview i',$sIcon).removeClass().addClass(eData.icon_secondary);
					$('.cssf-button',$sIcon).removeClass('hidden');
				} else {
					$('.cssf-icon-remove',$sIcon).trigger('click');
				}

				// Custom Link
				if (eData.customlink_type){
					$('.element_customlink_type option[value="'+eData.customlink_type+'"]',$parent).attr('selected','selected').trigger('change');
				} else {
					$('.element_customlink_type',$parent).prop('selectedIndex', 0).trigger('change');
				}

				if (eData.customlink){
					if (eData.customlink_type == 'admin'){
						$('.element_customlink_adminUri option[value="'+eData.customlink+'"]',$parent).attr('selected','selected').trigger('change'); // Fill selected
						$('.element_customlink_customUri',$parent).val(''); // Empty field
					} else {
						$('.element_customlink_customUri',$parent).val(eData.customlink); // Fill selected
						$('.element_customlink_adminUri',$parent).prop('selectedIndex', 0).trigger('change'); // Empty field
					}
				} else {
					$('.element_customlink_adminUri',$parent).prop('selectedIndex', 0).trigger('change');
					$('.element_customlink_customUri',$parent).val('');
				}
				
				// Tooltip
				if (eData.tooltip_status){
					$('.element_tooltip_status',$parent).prop('checked',eData.tooltip_status).trigger('change');
					$('.element_tooltip_custom_status',$parent).prop('checked',eData.tooltip_custom_status).trigger('change');
				} else {
					$('.element_tooltip_status',$parent).prop('checked',false);
					$('.element_tooltip_custom_status',$parent).prop('checked',false);
				}
				if (eData.tooltip_text){
					$('.element_tooltip_text',$parent).val(eData.tooltip_text);
				} else {
					$('.element_tooltip_text',$parent).val('');
				}

				// Notifications
				if (eData.notifications_status){
					$('.element_notifications_status',$parent).prop('checked',eData.notifications_status).trigger('change');
				} else {
					$('.element_notifications_status',$parent).prop('checked',false);
				}

				// Label
				if (eData.label_status){
					$('.element_label_status',$parent).prop('checked',eData.label_status).trigger('change');
				} else{
					$('.element_label_status',$parent).prop('checked',false);
				}

				if (eData.label_text){
					$('.element_label_text',$parent).val(eData.label_text);
				} else {
					$('.element_label_text',$parent).val('');
				}

				// Container Element
				if (eData.element_settings.container_id){
					$('.element_container_id',$parent).val(eData.element_settings.container_id);
				} else {
					$('.element_container_id',$parent).val('');
				}

				if (eData.element_settings.container_class){
					$('.element_container_class',$parent).val(eData.element_settings.container_class);
				} else {
					$('.element_container_class',$parent).val('');
				}

				// Wrapper Element
				if (eData.element_settings.wrapper_type){
					$('.element_wrapper_type option[value="'+eData.element_settings.wrapper_type+'"]',$parent).attr('selected','selected').trigger('change');
				} else {
					// $('.element_wrapper_type option:selected',$parent).val('');
					$('.element_wrapper_type',$parent).prop('selectedIndex', 0).trigger('change');
				}
				if (eData.element_settings.wrapper_id){
					$('.element_wrapper_id',$parent).val(eData.element_settings.wrapper_id);
				} else {
					$('.element_wrapper_id',$parent).val('');
				}
				if (eData.element_settings.wrapper_class){
					$('.element_wrapper_class',$parent).val(eData.element_settings.wrapper_class);
				} else {
					$('.element_wrapper_class',$parent).val('');
				}
				if (eData.element_settings.wrapper_attr){
					$('.element_wrapper_attributes',$parent).val(eData.element_settings.wrapper_attr);
				} else {
					$('.element_wrapper_attributes',$parent).val('');
				}
			}

			const addEditElement = function(eId = false){
				var is_updating = false;
				if (!eId){
					var eId 		= Math.random().toString(36).substring(2, 7) + Math.random().toString(36).substring(2, 7);
				} else {
					is_updating = true;
				}
				// Get element data
				var eType 					= $('.element_type',$parent).val(),
					eName 					= $('.element_type option:selected',$parent).text(),
					// eVisibilityDevice 		= $('.element_visibility_device',$parent).val(),
					// eVisibilityDevice 		= $('.element_visibility_device option:selected',$parent).toArray().map(item => item.value), // .join()
					eVisibilityDevice		= $('.element_visibility_device',$parent).siblings('.cssf-chosen-clone').val(),
					eUserAvatar 			= $('.element_useravatar_status',$parent).prop('checked'),
					eIcon 					= $('.element_icon',$parent).val(),
					eIconSecondary  		= $('.element_icon_secondary',$parent).val(),
					eCustomLinkType 		= $('.element_customlink_type option:selected',$parent).val(),
					eCustomLinkCustomUri	= $('.element_customlink_customUri',$parent).val(),
					eCustomLinkAdminUri		= $('.element_customlink_adminUri',$parent).val(),
					eTooltipStatus 			= $('.element_tooltip_status',$parent).prop('checked'),
					eTooltipText			= $('.element_tooltip_text',$parent).val(),
					eNotifications 			= $('.element_notifications_status',$parent).prop('checked'),
					eLabelStatus 			= $('.element_label_status',$parent).prop('checked'),
					eLabelText				= $('.element_label_text',$parent).val(),
					eSettingsContainerId	= $('.element_container_id',$parent).val(),
					eSettingsContainerClass	= $('.element_container_class',$parent).val(),
					eSettingsWrapperType 	= $('.element_wrapper_type option:selected',$parent).val(),
					eSettingsWrapperId		= $('.element_wrapper_id',$parent).val(),
					eSettingsWrapperClass	= $('.element_wrapper_class',$parent).val(),
					eSettingsWrapperAttr	= $('.element_wrapper_attributes',$parent).val(),
					ePreviewIcon 			= (eIcon) ? eIcon : ((eIconSecondary) ? eIconSecondary : ''),
					ePreviewText 			= false;

				if (!eType) { return false; }

				// Element Types
				if (eType == 'pagetitle'){
					ePreviewIcon	= 'cli cli-type';
					ePreviewText 	= true;
				} else if (eType == 'flexiblespace'){
					ePreviewIcon	= 'cli cli-separator-vertical';
					ePreviewText 	= true;
				} else if (eType == 'sitebrand'){
					ePreviewIcon	= 'cli cli-rocket';
					ePreviewText 	= true;
				} else if (eType == 'custom'){
					ePreviewIcon 	= (eIcon) ? eIcon : ((eIconSecondary) ? eIconSecondary : 'cli cli-square-dashed');
					ePreviewText	= true;
				}

				// Create new element data object
				var eData = {
					'id': 					eId,
					'uniqueid':				eId,
					'type': 				eType,
					'name': 				eName,
					'visibility_device':	eVisibilityDevice,
					'user_avatar': 			eUserAvatar,
					'icon': 				eIcon,
					'icon_secondary': 		eIconSecondary,
					'customlink_type':		eCustomLinkType,
					'customlink': 			(eCustomLinkType == 'admin') ? eCustomLinkAdminUri : eCustomLinkCustomUri,
					'tooltip_status': 		eTooltipStatus,
					'tooltip_text': 		eTooltipText,
					'label_status':			eLabelStatus,
					'label_text':			eLabelText,
					'notifications_status': eNotifications,
					'element_settings':{
						'container_id': 	eSettingsContainerId,
						'container_class':	eSettingsContainerClass,
						'wrapper_type':		eSettingsWrapperType,
						'wrapper_id':		eSettingsWrapperId,
						'wrapper_class':	eSettingsWrapperClass,
						'wrapper_attr':		eSettingsWrapperAttr,
						'submenu':			false,
						'template':			false,
					}
				};

				// Check elements data type
				var currentElementsData = $elements_data.val();
				if ($.CSSFRAMEWORK.HELPER.FUNCTIONS.checkJSON(currentElementsData)){
					var updatedElementsData = JSON.parse(currentElementsData);
				} else {
					var updatedElementsData = {};
				}

				// Update elements data
				updatedElementsData[eId] = eData;
				var newVal = JSON.stringify(updatedElementsData);
				$elements_data.val(newVal);


				// Create new element on the builder
				var $new_element 	= $('<div />',{class: 'cssf-layout-builder_element layout-element__'+eId+' cssf-pseudo-hidden cssf-layout-builder_element-type--'+eType, attr: {
					'data-layout-element-name': eId,
					'title': eName,
				}});
				var $element_toolbar 	= $('<div />',{class: 'cssf-layout-builder_element-toolbar'}),
					$btn_edit			= $('<a />',{class: 'cssf-lb-btn--edit', title: 'Edit Element'}).appendTo($element_toolbar),
					$btn_delete 		= $('<a />',{class: 'cssf-lb-btn--delete', title: 'Delete Element'}).appendTo($element_toolbar);
				$element_toolbar.appendTo($new_element);
				var $element_content 	= $('<div />',{class: 'cssf-layout-builder_element-content'}),
					$element_icon 		= $('<i />',{class: ePreviewIcon}).appendTo($element_content);
				if (ePreviewText){
					var $element_previewText = $('<span />',{html: eName}).appendTo($element_content);
				}
				$element_content.appendTo($new_element);

				if (is_updating){
					var $current_element 	= $('.layout-element__'+eId,$this);
					
					$current_element.replaceWith($new_element);
					$new_element.removeClass('cssf-pseudo-hidden');
				} else {
					// Add new element to DOM
					$new_element.appendTo($elements).delay(0).queue(function(){
						$(this).removeClass('cssf-pseudo-hidden');
						$(this).dequeue();
					});
				}
			}

			// Plus Admin Top Navbar Sorting
			Sortable.create($elements[0], { 
				animation: 350,
				group: "csnbarbuilder",
				onEnd: function (evt) {
					updateLayoutSections();
				},
			});
			Sortable.create($main[0], { 
				animation: 350,
				group: "csnbarbuilder",
				onEnd: function (evt) {
					updateLayoutSections();
				},
			});

			// Set Initial State
			fillEditorFields(false);
			updateLayoutSections();



			// Edit Element & Delete Element buttons
			$this.on('click','.cssf-layout-builder_element a',function(e){
				e.preventDefault();
				e.stopImmediatePropagation();
				var $btn 		= $(this),
					btnType 	= $btn.attr('class'),
					$element 	= $btn.parents('.cssf-layout-builder_element');
					
				if (btnType == 'cssf-lb-btn--edit'){
					var eId 			= $element.data('layout-element-name'),
						elements_data 	= JSON.parse($elements_data.val()),
						eData 			= elements_data[eId];

					fillEditorFields(eData);
				} else if (btnType == 'cssf-lb-btn--delete'){
					$element.addClass('cssf-layout-builder_element--deleting').delay(200).queue(function(){
						$(this).addClass('cssf-layout-builder_element--deleted');
						$(this).dequeue();
					});
					var removeTimeout = setTimeout(function(){
						$element.remove();
						updateLayoutSections();
					},600);
				}
			});
			// Double click to edit element
			$this.on('dblclick','.cssf-layout-builder_element',function(e){
				var $element 		= $(this),
					eId 			= $element.data('layout-element-name'),
					elements_data 	= JSON.parse($elements_data.val()),
					eData 			= elements_data[eId];

				fillEditorFields(eData);
			});


			// "ADD ELEMENT" submit button
			$('.cssf-navbar-builder-add_element',$this).on('click',function(e){
				e.preventDefault();

				addEditElement();
				fillEditorFields(false);
				updateLayoutSections();
			});

			// "EDIT ELEMENT" submit button
			$('.cssf-navbar-builder-edit_element',$this).on('click',function(e){
				e.preventDefault();

				addEditElement(current_eId);
				fillEditorFields(false);
				updateLayoutSections();

				current_eId = false;
			});

			// "CANCEL EDITION" submit button
			$('.cssf-navbar-builder-edit_cancel',$this).on('click',function(e){
				e.preventDefault();
				
				fillEditorFields(false);
				updateLayoutSections();
			});


		});
	};


	/**
	 * Define Fields Methods
	 */
	var NavbarBuilder_field = {
		init: function(){
			$('.cssf-field-navbar_builder', 'body').CSSFRAMEWORK_NAVBARBUILDER();
		},
		reload: function(){
			this.init();
		}
	}


	/**
	 * Register the new field on CSSF Framework
	 */
	var CSSFRAMEWORK = {
		navbar_builder: {
			init: function(){
				NavbarBuilder_field.init();
			},
			reload: function(){
				NavbarBuilder_field.reload();
			}
		}
	}
	// Update Framework Plugins list
	$.CSSFRAMEWORK.PLUGINS = $.extend(true, $.CSSFRAMEWORK.PLUGINS, CSSFRAMEWORK );
});
