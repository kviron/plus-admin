/**
 * Admin Menu Manager
 * @version 1.0.0
 */
(function( $, window, undefined ) {
    'use strict';

    var settings    = _PLUS_ADMIN.settings;
    var $body       = $('body');

    _PLUS_ADMIN.adminMenuManager = {
		init: function(){
			_PLUS_ADMIN._debug("Admin Menu Manager Init");

			this.menuOriginalOrder 	= false;
			this.menuOriginalData 	= false;
			this.menuOriginalSep	= false;
			this.menuWrapper 		= $('.cs-menu-manager-full-menu-wrapper');

			this.liveEditing();
			this.liveEditingInit();
			this.iconPanel();
			this.orderToolbar();
			this.getMenuOrder();
			this.editPanelToggle();
			this.submenuShowHide();
			this.menuDisplay();
			this.menuSortable();
			this.submenuSortable();
			this.menuSave();
			this.menuReset();
			this.menuChange();
			this.tooltips();
		},
		liveEditing: function(e){
			$('.cs-menu-manager-full-menu-wrapper').on('input','.cs-admin-menu-rename, .cs-admin-submenu-rename',function(e){
				var parent_item 	= $(this).closest('.cs-menu-manager_item'),
					item_heading 	= $('.cs-menu-manager_item-heading-title',parent_item),
					item_title		= $('.cs-menu-title',item_heading),
					title			= $(this).val(),
					noti_count 		= parent_item.data('menuNotifications'),
					noti_bubble 	= '<span class="awaiting-mod count-'+noti_count+'"><span class="pending-count">'+noti_count+'</span></span>';
				
				item_title.html(title.replace('%bubble%',noti_bubble));
				parent_item.closest('.cs-menu-manager_menu-wrapper').data('menu-name',title);
			});
		},
		liveEditingInit: function(e){
			$('.cs-menu-manager-full-menu-wrapper .cs-admin-menu-rename, .cs-admin-submenu-rename').trigger('input');
		},
		iconPanel: function(e) {
			$('.cs-menu-manager-full-menu-wrapper').on('click','.cs-menu-icon-panel_toggle',function(e) {
				e.stopPropagation();
				var panel 	= $(this).parent().find(".cs-menu-manager_icons-panel");

				panel.data('visibility','shown').show();
			});
			$(document).on('click', ".cs-menu-manager_icons-panel-icon", function() {
				var icon_new 	= $(this).attr("data-class"),
					parent 		= $(this).parent().parent(),
					main 		= parent.find(".cs-menu-icon-panel_toggle"),
					icon_old 	= main.attr("data-class");

				parent.find("input").attr("value", icon_new);
				parent.find("input").val(icon_new);

				var parent_item 	= parent.parents('.cs-admin-menu-item'),
					item_heading 	= $('.cs-menu-manager_item-heading-title',parent_item),
					item_ico		= $('.cs-menu-title-icon',item_heading),
					item_title		= $('.cs-menu-title',item_heading);
				
				item_ico.removeClass().addClass('cs-menu-title-icon dashicons-before').addClass(icon_new);
				parent_item.data('menu-icon',icon_new);

				main.removeClass(icon_old).addClass(icon_new);
				main.attr("data-class", icon_new);
				return false;
			});
	
			$(document).on('click', "body", function() {
				var icon_panel = $(".cs-menu-manager_icons-panel");

				$.each(icon_panel,function(){
					var icon_panel = $(this);

					if (icon_panel.data('visibility') == 'shown') {
						icon_panel.data('visibility','hidden').hide();
					}
				});

			});
		},
		orderToolbar: function() {
			var self = this;

			$('.cs-menu-manager_item-order-toolbar_action').on('click',function(e) {
				e.preventDefault();

				var $el 		= $(this),
					expand 		= $el.hasClass("cs-mm-action--expand"),
					collapse 	= $el.hasClass("cs-mm-action--collapse");

				if (expand || collapse){
					var action = (expand) ? 'expand' : 'collapse';

					$(".cs-admin-menu-item","#cs-menu-manager").each(function() {
						var $el 			= $(this),
							submenu 		= $('.cs-menu-manager_submenu-wrapper',$el),
							submenu_state 	= submenu.data('state');
						
						if (action == 'expand'){
							if (submenu_state == 'closed'){
								self.submenuToggle($el,'show');
							}
						}
						if (action == 'collapse'){
							if (submenu_state == 'open'){
								self.submenuToggle($el,'hide');
							}
						}
					});
				}
				if ($el.hasClass("cs-mm-action--order-original")) {
					self.getMenuOrder('original');
				}
				if ($el.hasClass("cs-mm-action--order-asc")) {
					self.getMenuOrder('asc');
				}
				if ($el.hasClass("cs-mm-action--order-desc")) {
					self.getMenuOrder('desc');
				}
			});
		},
		getMenuOrder: function(type){
			var self 		= this,
				_menu_order = [],
				_menu_order_original = [],
				_menu_data 	= {},
				_separator 	= {},
				_separator_original = {};
			
			var i = 0;

			$(".cs-admin-menu-item","#cs-menu-manager").each(function() {
				var $el 			= $(this),
					menu_id 		= $el.data('menuId'),
					menu_index		= $el.data('menuIndex'),
					menu_name 		= $el.data('menuName'),
					menu_item 		= {};

				menu_item = {
					id: 	menu_id,
					index: 	menu_index,
					name: 	menu_name,
					obj: 	$el,
				};
				
				var _name 	= menu_name +'_'+ menu_index,
					_id 	= menu_id +'_'+ menu_index,
					_item 	= [ _name , _id ];


				if (menu_id == 'menu-separator'){
					_separator[i] = menu_item;
					_separator_original[i] = menu_item;
				}
				i++;

				_menu_order_original.push(_item);
				_menu_order.push(_item);
				_menu_data[_id] = menu_item;
			});

			// Save Original Menu
			if ((type == 'update') || (!self.menuOriginalOrder)){
				self.menuOriginalOrder 	= _menu_order_original;
				self.menuOriginalSep	= _separator_original;
			}

			// New Menu Wrapper
			var _new_menu_wrapper 	= $('<div />',{class: 'new-menu-wrapper'});

			// Sorting Actions
			var _menu_to_sort 		= _menu_order,
				_separators_to_sort = _separator;

			var _menuSorter = function(a,b){
				if (a[0] === b[0]) {
					return 0;
				} else {
					return (a[0] < b[0]) ? -1 : 1;
				}
			}
			if (type == 'original'){
				_menu_to_sort 		= self.menuOriginalOrder;
				_separators_to_sort = self.menuOriginalSep;
				_PLUS_ADMIN.notificationCenter.newToast('Initial menu order restored...');
			}
			if (type == 'asc'){
				_menu_to_sort.sort(_menuSorter);
			}
			if (type == 'desc'){
				_menu_to_sort.sort(_menuSorter).reverse();
			}


			// Return Final Menu
			$.each(_menu_to_sort,function(key,value){
				var menu_id 	= value[1],
					menu_item 	= _menu_data[menu_id];

				if (menu_item !== undefined && menu_item.id != 'menu-separator'){
					_new_menu_wrapper.append(menu_item.obj);
				}
			});

			// If "keep separators position" is enabled
			$.each(_separators_to_sort,function(key,value){
				var menu_item = value.obj;
				_new_menu_wrapper.children().eq(key).before(menu_item);
			});

			$('.cs-menu-manager-full-menu-wrapper').html(_new_menu_wrapper.contents());
		},
		editPanelToggle: function() {
			$('.cs-menu-manager-full-menu-wrapper').on('click','.cs-mm-action-editpanel',function(e) {
				var el 			= $(this),
					editPanel 	= el.parents('.cs-menu-manager_item').children(".cs-menu-manager_item-edit-panel");

				if (!el.hasClass("cs-menu-submenu-open")) {
					el.addClass("cs-menu-submenu-open");
					editPanel.slideDown();
				} else {
					el.removeClass("cs-menu-submenu-open");
					editPanel.slideUp();
				}
			});
		},
		submenuShowHide: function() {
			var self 			= this;

			$('.cs-menu-manager-full-menu-wrapper').on('click','.cs-mm-action-toggle-submenu',function(e) {
				var $el 			= $(this),
					parent 			= $el.parents(".cs-admin-menu-item"),
					submenu 		= $el.parents('.cs-menu-manager_menu-wrapper').children(".cs-menu-manager_submenu-wrapper"),
					submenu_state 	= submenu.data('state');

				if (submenu_state == 'closed'){
					self.submenuToggle(parent,'show');
				} else {
					self.submenuToggle(parent,'hide');
				}
			});
		},
		submenuToggle: function(element,type){
			var toolbar_btn			= $('.cs-mm-action-toggle-submenu',element),
				icon				= $('i',toolbar_btn),
				tooltip_expand 		= toolbar_btn.data('titleExpand'),
				tooltip_collapse	= toolbar_btn.data('titleCollapse'),
				submenu 			= element.children(".cs-menu-manager_submenu-wrapper");

			if (type == 'show'){
				toolbar_btn.addClass("cs-menu-submenu-open").attr('title',tooltip_collapse);
				icon.removeClass().addClass('cli cli-unfold-less');
				submenu.data('state','open').slideDown();
			} else if (type == 'hide'){
				toolbar_btn.removeClass("cs-menu-submenu-open").attr('title',tooltip_expand);
				icon.removeClass().addClass('cli cli-unfold-more');
				submenu.data('state','closed').slideUp();
			}
		},
		menuDisplay: function() {
			$('.cs-menu-manager-full-menu-wrapper').on('click','.cs-mm-action-display',function(e) {
				var $el 			= $(this),
					icon 			= $('i',$el),
					tooltip_show 	= $el.data('titleShow'),
					tooltip_hide 	= $el.data('titleHide'),
					parent_item 	= $el.parents('.cs-menu-manager_item'),
					menuItem 		= parent_item.parent();

				if ($el.hasClass("cs-menu-disabled")) {
					$el.removeClass("cs-menu-disabled").addClass("cs-menu-enabled").attr('title',tooltip_show);
					icon.removeClass('cli-eye-off').addClass('cli-eye');
					menuItem.removeClass("disabled").addClass("enabled").data('menu-state','enabled');
				} else if ($el.hasClass("cs-menu-enabled")) {
					$el.removeClass("cs-menu-enabled").addClass("cs-menu-disabled").attr('title',tooltip_hide);
					icon.removeClass('cli-eye').addClass('cli-eye-off');
					menuItem.removeClass("enabled").addClass("disabled").data('menu-state','disabled');
				}
			});
		},
		menuSave: function(){
			var instance = this;
			$('#cs-admin-menu_save').on('click',function(e) {
				e.preventDefault();
				instance._spinner('show');

				var userrole	= $('#cs-admin-current-user-role').val(),
					admin_menu 	= {};
				var admin_menu_order = [];

				$(".cs-admin-menu-item").each(function() {
					var $el = $(this),
						menu_id 		= $el.data('menuId'),
						menu_slug 		= $el.data('menuSlug'),
						menu_index		= parseFloat($el.data('menuIndex')),
						menu_state 		= $el.data('menuState'),
						menu_name 		= $el.data('menuName'),
						menu_icon		= $el.data('menuIcon'),
						menu_item 		= {},
						menu_submenu 	= [];

					var menu_item_order 	= {},
						menu_submenu_order 	= [];

					
					$(".cs-admin-submenu-item",$el).each(function() {
						var $el 			= $(this),
							submenu_id 		= $el.data('menuId'),
							submenu_slug 	= $el.data('menuSlug'),
							submenu_state 	= $el.data('menuState'),
							submenu_name	= $el.data('menuName'),
							submenu_item 	= {};
						
						submenu_item = {
							id: submenu_id,
							slug: submenu_slug,
							state: submenu_state,
							name: submenu_name,
						};

						// menu_submenu.push(submenu_item);

						menu_submenu[submenu_id] = submenu_item;
						menu_submenu_order.push(submenu_id);
					});

					menu_item = {
						id: menu_id,
						slug: menu_slug,
						index: menu_index,
						state: menu_state,
						name: menu_name,
						icon: menu_icon,
						submenu: menu_submenu
					};

					menu_item_order = {
						menu: menu_index,
						submenu: menu_submenu_order,
					};

					// admin_menu.push(menu_item);
					admin_menu[menu_index] = menu_item;
					admin_menu_order.push(menu_item_order);
				});

				var action = 'ipido_menu_save';
				var data = {
					nonce: 			plus_admin.nonce, 	// Security nonce
					action: 		action,				// Ajax Action
					// Parameters
					userrole:		userrole,
					adminmenu:		admin_menu,
					adminmenuorder: admin_menu_order,
				};
	
				$.ajax({
					url: 	plus_admin.ajax_url,
					type: 	'post',
					data: 	data,
					success: function(response){
						_PLUS_ADMIN.notificationCenter.newToast('Menu updated succesfully...');
					},
					complete: function(){
						instance._spinner('hide');
					}
				});
			});
		},
		menuReset: function() {
			var instance = this;
			$('#cs-admin-menu_reset').on('click',function(e) {
				e.preventDefault();
				instance._menuPreloader('show');
				instance._spinner('show');

				var userrole	= $('#cs-admin-current-user-role').val();
				var action = 'ipido_menu_reset';
				var data = {
					nonce: 			plus_admin.nonce, 	// Security nonce
					action: 		action,				// Ajax Action
					// Parameters
					userrole:		userrole,
				};
	
				$.ajax({
					url: 	plus_admin.ajax_url,
					type: 	'post',
					data: 	data,
					success: function(response){
						// location.reload();
						instance._menuUpdate(response.data);
					},
					complete: function(){
						instance._menuPreloader('hide');
						instance._spinner('hide');
					}
				});
			});
		},
		menuChange: function(){
			var instance = this;
			$('#cs-menu-manager-roles').on('change',function(e){
				var current_user_role = $(this).val();

				$('#cs-admin-current-user-role').val(current_user_role);
			});
			$('#cs-admin-menu_change').on('click',function(e) {
				e.preventDefault();
				instance._menuPreloader('show');

				var userrole	= $('#cs-admin-current-user-role').val();

				var action = 'ipido_menu_change';
				var data = {
					nonce: 			plus_admin.nonce, 	// Security nonce
					action: 		action,				// Ajax Action
					// Parameters
					userrole:		userrole,
				};
	
				$.ajax({
					url: 	plus_admin.ajax_url,
					type: 	'post',
					data: 	data,
					success: function(response){
						instance._menuUpdate(response.data);
						_PLUS_ADMIN.notificationCenter.newToast('Menu updated succesfully...');
					},
					complete: function(){
						instance._menuPreloader('hide');
					}
				});
			});
		},
		_spinner: function(state){
			var spinner = $('.cs-plus-spinner'),
				btns 	= $('.cs-mm-btn');
			
			if (state == 'show') {
				spinner.addClass('cs-plus-spinner--visible');
				btns.attr('disabled','disabled');
			} else if (state == 'hide'){
				spinner.removeClass('cs-plus-spinner--visible');
				btns.removeAttr('disabled');
			}
		},
		_menuPreloader: function(state){
			var menu_wrapper 	= $('#cs-menu-manager'),
				spinner 		= $('.cs-menu-manager-spinner',menu_wrapper);
			
			if (state == 'show'){
				menu_wrapper.addClass('cs-loading');
			} else if (state == 'hide'){
				menu_wrapper.removeClass('cs-loading');
			}
		},
		_menuUpdate: function(data){
			$('.cs-menu-manager-full-menu-wrapper').html(data);
			this.getMenuOrder('update');
			this.menuSortable();
			this.submenuSortable();
			this.tooltipsToolbarHeading();
			this.liveEditingInit();
		},
		menuSortable: function(){
			if ($.isFunction($.fn.sortable)) {
				$("#cs-menu-manager .cs-menu-manager-full-menu-wrapper").sortable({
					handle: ".cs-menu-manager_item-heading",
					placeholder: "ui-state-highlight",
					start: function(e, ui){
						var height = ui.item.height() - 4;
						ui.placeholder.height(height);
					}
				}).disableSelection();
			}
		},
		submenuSortable: function(){
			if ($.isFunction($.fn.sortable)) {
				$('.cs-menu-manager_submenu-wrapper','.cs-menu-manager-full-menu-wrapper').sortable({
					placeholder: "ui-state-highlight",
					start: function(e, ui){
						var height = ui.item.height() - 4;
						ui.placeholder.height(height);
					}
				}).disableSelection();
			}
		},
		tooltips: function(){
			this.tooltipsToolbarOrder();
			this.tooltipsToolbarHeading();
		},
		tooltipsToolbarOrder: function(){
			tippy('.cs-menu-manager_item-order-toolbar_action',{
				arrow: true,
				arrowType: 'round',
				zIndex: 10002,
				dynamicTitle: true,
			});
		},
		tooltipsToolbarHeading: function(){
			tippy('.cs-menu-manager_item-heading-toolbar_action',{
				arrow: true,
				arrowType: 'round',
				zIndex: 10002,
				dynamicTitle: true,
			});
		}
    }
    
    $(document).ready(function() {
        _PLUS_ADMIN.adminMenuManager.init();
    });

})( jQuery, window );