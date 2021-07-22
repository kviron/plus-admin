(function( $, window, undefined ) {
	'use strict';

	var $document 	= $(document),
		$window 	= $(window),
		$body 		= $(document.body);

	window._PLUS_ADMIN = {};
	var _PLUS_ADMIN = window._PLUS_ADMIN;

	_PLUS_ADMIN.settings = {};
	var settings = _PLUS_ADMIN.settings;

	/**
	 * DEBUG Log
	 * 
	 * @since 1.0.0
	 */
	settings.debug = true;
	var CS_DEBUG = function(msg,obj = false){
		if (settings.debug){
			var version = settings.general.plugin_version;
			console.log('[>] PLUS Admin '+version+': '+msg);
			if (obj){
				console.log(obj);
			}
		}
	};


	_PLUS_ADMIN.general = {
		init: function(){
			CS_DEBUG('General Settings Init');

			// this.windowSizeWatcher();
			this.submenu();
			this.metabox_cards();
			this.tooltips();
			this.body_scrollbar();
			this.network_admin_menu();
		},
		windowSizeWatcher: function(){
			this.windowSize();
			$(window).on('resize',function(){
				_PLUS_ADMIN.general.windowSize();
			});
		},
		windowSize: function(){
			var $wrapper 	= $('body'),
				is_mobile 	= jQuery.browser.mobile,
				width 		= $(window).width(),
				height 		= $(window).height(),
				orientation = window.orientation;
	
			var prefix 		= "plus_",
				wrapper_class 	= '';
	
			// Is Mobile
			$wrapper.data(prefix+'is-mobile',is_mobile);
			wrapper_class += (is_mobile) ? 'is_mobile' : 'is_desktop';
			
			// Orientation
			orientation = (orientation != 0) ? 'landscape' : 'portrait';
			$wrapper.data(prefix+'orientation',orientation);
			wrapper_class += ' '+orientation;
	
			// Size
			var size;  
			if (width <= 360){
				size = 'xxs';
			} else if (width <= 576){
				size = 'xs';
			} else if (width <= 768){
				size = 'sm';
			} else if (width <= 992){
				size = 'md';
			} else if (width <= 1200){
				size = 'xl';
			} else if (width > 1200){
				size = 'xxl';
			}
			$wrapper.data(prefix+'width',width).data(prefix+'height',height).data(prefix+'size',size);
			wrapper_class += ' size-'+size;

			// Auto-fold Watcher
			if (settings.adminmenu.autofold){
				if (width <= settings.adminmenu.autofold_breakpoint){
					wrapper_class += ' cs-plus-sidebar--autofolded';
				}
			}
	
			$wrapper.removeClass('size-xxs size-xs size-sm size-md size-xl size-xxl is_mobile is_desktop landscape portrait auto-fold cs-plus-sidebar--autofolded').addClass(wrapper_class);
		},
		metabox_cards: function(){
			var self 			= this;
			var $body 			= $('body'),
				$metabox_card 	= $('.postbox',$body);

			$metabox_card.on('mousedown','.hndle',function(e){
				self.metabox_cards_updater();
				console.log("Clickeando el titulo");
				setTimeout(function(){
					self.metabox_cards_updater(true);
				},1000)
			});
		},
		metabox_cards_updater: function(reset = false){
			var self 			= this;
			var $body 			= $('body'),
				$metabox_card 	= $('.postbox',$body);
			
			$.each($metabox_card,function(index,target){
				var $inside = $('.inside',this),
					height 	= $inside.outerHeight();
				
				var setStyle = function( element, propertyObject ){
					var elem = element.style;
					for (var property in propertyObject){
						elem.setProperty(property, propertyObject[property]);
					}
				};

				var style = {};
				if (!reset){
					style['--cs-card-height'] = height +'px';
				} else {
					style['--cs-card-height'] = 'px';
				}

				setStyle(this,style);
			});

			// Autoupdater
			// var metaboxDynamicHeightUpdater = setTimeout(function(){
			// 	self.metabox_card();
			// },150);
		},
		submenu: function(){
			var sidebar 	= $('#adminmenu'),
				menuClass 	= 'wp-has-submenu-expanded';

			$('li.wp-has-current-submenu',sidebar).addClass(menuClass);
		},
		tooltips: function(){
			CS_DEBUG('Initializing Tooltips');
			tippy('.cs-plus-header-toolbar .cs-plus-header-toolbar-item--has-tooltip > a',{
				theme: 'google',
				zIndex: 100002,
			});
			tippy('.cs-plus-header-toolbar .cs-plus-header-toolbar-item--has-tooltip > div',{
				theme: 'google',
				zIndex: 100002,
			});
		},
		/*+
		 * Body Custom Scrollbars
		 * @since 
		 **/
		body_scrollbar: function(){
			if (settings.general.body_scrollbar){
				$('body').overlayScrollbars({
					scrollbars: {
						autoHide: 'leave'
					}
				});
			}
		},
		/**
		 * Network Admin Menu Item
		 * @since 2.0.0
		 */
		network_admin_menu: function(){
			var is_multisite 		= settings.general.is_multisite,
				is_super_admin		= settings.general.is_super_admin,
				is_network_admin	= settings.general.is_network_admin;

			if (is_multisite && is_super_admin){
				var my_sites 	= $('#wp-admin-bar-my-sites .ab-sub-wrapper'),
					super_admin	= $('#wp-admin-bar-my-sites-super-admin .ab-sub-wrapper > .ab-submenu',my_sites),
					sites_list 	= $('#wp-admin-bar-my-sites-list',my_sites);
					
				if (!is_network_admin){
					var menu_item 		= $('<li />',{id: 'cs-plus-network-menu-toggle', class: 'wp-has-submenu wp-not-current-submenu menu-top'}),
						icon 			= $('<i />',{class: 'cli cli-home'}),
						icon_wrapper	= $('<div />',{class: 'wp-menu-image', html: icon}),
						name 			= 'Network Menu',
						name_wrapper 	= $('<div />',{class: 'wp-menu-name', html: name}),
						anchor 			= $('<a />',{class: 'wp-has-submenu cs-plus-network', attr: {
							title: name,
						}}).append(icon_wrapper).append(name_wrapper).appendTo(menu_item),
						submenu 		= $('<ul />',{class: 'wp-submenu wp-submenu-wrap',html: super_admin}).appendTo(menu_item);
				} else {
					var main_site_url 	= $('li > a',sites_list).eq(0).attr('href');
					var menu_item 		= $('<li />',{id: 'cs-plus-network-menu-toggle', class: 'menu-top'}),
						icon 			= $('<i />',{class: 'cli cli-corner-up-left'}),
						icon_wrapper	= $('<div />',{class: 'wp-menu-image', html: icon}),
						name 			= 'Back to Main Site',
						name_wrapper 	= $('<div />',{class: 'wp-menu-name', html: name}),
						anchor 			= $('<a />',{class: 'cs-plus-network', attr: {
							href: main_site_url,
							title: name,
						}}).append(icon_wrapper).append(name_wrapper).appendTo(menu_item);
				}
				menu_item.prependTo($('#adminmenu'));
			}
		},
	}


	_PLUS_ADMIN.topNavbar = {
		init: function(){
			CS_DEBUG("TopNavbar Init");

			this.toolbar();
			this.submenu();
			this.position();
			this.screenTabs();
			this.networkSites();
			this.sidebarToggle();
			this.fullScreen();
			this.iconToggler();
		},
		toolbar: function(){
			var $thebrand = $('<div />',{ class: 'thisisthebrand'});
			$(".thisisthebrand").detach().children().appendTo($thebrand);
			$('#adminmenuwrap').prepend($thebrand);
		},
		submenu: function(){
			var $toolbar 		= $('.cs-plus-header-toolbar'),
				$toolbar_item 	= $('.cs-plus-header-toolbar-item',$toolbar);
				
			$.each($toolbar_item,function(index,target){
				var $toolbar_item	= $(target),
					$anchor			= $('> a',$toolbar_item),
					$submenu 		= $('.submenu-wrapper',$toolbar_item);
				
				if ($submenu.length){
					// Toggle to open/close item submenu dropdown
					$anchor.on('click',function(e){
						e.preventDefault();
						$toolbar_item.toggleClass('cs-submenu-visible');
						$submenu.removeClass('submenu-newcontent--expanded');
					});
				}
			});

			$.each($('.btn-view-more-new-content',$toolbar),function(index,target){
				var $btn = $(target);
				$btn.on('click',function(e){
					e.preventDefault();
					$btn.parents('.submenu-newcontent').toggleClass('submenu-newcontent--expanded');
					
				});
			});
		},
		position: function(){
			if (settings.navbar.position == 'fixed'){
				this.fixedTitle();
			}
		},
		fixedTitle: function(){
			$body = $('body');
			if (!settings.fixedTitle){
				settings.fixedTitle = true;
				$body.addClass('cs-plus-fixed-title');
			}
		},
		unfixedTitle: function(){
			$body = $('body');
			if (settings.fixedTitle){
				settings.fixedTitle = false;
				$body.removeClass('cs-plus-fixed-title');
			}
		},
		screenTabs: function(){
			CS_DEBUG('Initializing Screen Tabs');

			// Replace Screen Options / Help button events with Fancybox popup
			var screenTabs 	= {
				screenOptionsTitle: 	settings.navbar.screen_title,
				helpTabsTitle:	 		settings.navbar.help_title,
			};
			window.screenMeta = {
				init: function() {}
			};
			if ($('.cs-plus-header-toolbar a' ).length){
				// Screen options
				// $('.cs-plus-header-toolbar-item_screenoptionstab').on( 'click', function() {
				$('.cs-plus-header-toolbar-item_screenoptions > a').on( 'click', function() {
					if (typeof tb_show === "function") {
						var screen_options = $('#screen-options-wrap').length;
						tb_show( screenTabs.screenOptionsTitle, '#TB_inline?inlineId=screen-options-wrap' );
					} else {
						_PLUS_ADMIN.notificationCenter.newToast('This page does not have Screen Options');
					}
				});
				// Help
				// $('.cs-plus-header-toolbar-item_helptab').on('click',function(){
				$('.cs-plus-header-toolbar-item_help > a').on('click',function(){
					var screen_help_tabs = $('#contextual-help-wrap .contextual-help-tabs-wrap').children().length;
					if (screen_help_tabs){
						if (typeof tb_show === "function") {
							tb_show( screenTabs.helpTabsTitle, '#TB_inline?inlineId=contextual-help-wrap' );
						} else {
							_PLUS_ADMIN.notificationCenter.newToast('Oh no! We can\'t display the help panel!');
						}
					} else {
						_PLUS_ADMIN.notificationCenter.newToast('This page does not have Help Options');
					}
				});
			}
		},
		networkSites: function(){
			$('.cs-plus-header-toolbar-item_networksites').on('click',function(e){
				e.preventDefault();
				_PLUS_ADMIN.networkSidebar.sidebarToggle();
			});
		},
		sidebarToggle: function(){
			var self = this;
			$('.cs-plus-header-toolbar-item_sidebartoggle').on('click',function(e){
				e.preventDefault();

				self.sidebarExpandCollapse('auto');
			});

			$('#adminmenuwrap').on('click',function(e){
				if (e.target !== this) { return; }
				e.preventDefault();

				self.sidebarExpandCollapse('auto');
			});
		},
		sidebarExpandCollapse: function(type){
			var self 				= this,
				$body 				= $('body'),
				sidebar_status 		= settings.general.sidebar_status, // mfold is "o" or "f"
				mode 				= settings.adminmenu.mode,
				current_status 		= settings.adminmenu.current_status; // plusSidebar is "visible" or "hidden"

			// if (mode == 'offcanvas'){
				if (current_status == 'hidden'){
					self.sidebarExpand();
				} else if (current_status == 'visible'){
					self.sidebarCollapse();
				}
			// }
			var current_status 		= settings.adminmenu.current_status;
		},
		sidebarExpand: function(){
			var self 			= this,
				$body 			= $('body'),
				$navbar_item 	= $('.cs-plus-header-toolbar-item_sidebartoggle');
			
			$body.data('sidebarToggle','expanded').addClass('cs-plus-expanded-folded-menu');
			$body.removeClass('cs-plus-sidebar-visibility--hidden').addClass('cs-plus-sidebar-visibility--visible');
			$navbar_item.addClass('cs-plus-header-toolbar-item_sidebartoggle--active');

			settings.adminmenu.current_status = 'visible';
			self.sidebarMaybeRemember(settings.adminmenu.current_status);
		},
		sidebarCollapse: function(){
			var self 			= this,
				$body 			= $('body'),
				$navbar_item 	= $('.cs-plus-header-toolbar-item_sidebartoggle');

			$body.data('sidebarToggle','collapsed').removeClass('cs-plus-expanded-folded-menu');
			$body.removeClass('cs-plus-sidebar-visibility--visible').addClass('cs-plus-sidebar-visibility--hidden');
			$navbar_item.removeClass('cs-plus-header-toolbar-item_sidebartoggle--active');

			settings.adminmenu.current_status = 'hidden';
			self.sidebarMaybeRemember(settings.adminmenu.current_status);

			if (settings.adminmenu.autocollapse_submenu){
				$('#adminmenu li.wp-has-submenu.wp-has-submenu-expanded','body').each(function(index,element){
					// a.wp-has-submenu
					$(element).removeClass('wp-has-submenu-expanded');
					$('.wp-submenu').slideUp();
				});
			}
		},
		sidebarMaybeRemember: function(status){
			var maybe_remember 	= false,
				remember_status = settings.adminmenu.remember_current_status,
				sidebar_mode 	= settings.adminmenu.mode;

			if (remember_status == 'always'){
				maybe_remember = true;
			} else if (remember_status == 'fixed' && sidebar_mode == 'fixed'){
				maybe_remember = true;
			} else if (remember_status == 'offcanvas' && sidebar_mode == 'offcanvas'){
				maybe_remember = true;
			} else if (remember_status == 'never' || !remember_status){
				maybe_remember = false;
			}

			if (maybe_remember){
				setUserSetting('plusSidebar',status);
			}
		},
		/**
		 * Fullscreen listener
		 * 
		 * @since 1.0.2
		 */
		fullScreen: function(){
			$('*[data-toggle-fullscreen]').on('click',function(e){
				var $self = this;
				e.preventDefault();

				if (document.fullscreenElement) {
					$(this).data('fullscreen-disabled');
					document.exitFullscreen();
				} else {
					$(this).data('fullscreen-enabled');
					document.documentElement.requestFullscreen();
				}
			});
		},
		/**
		 * Icon Toggler
		 * 
		 * @since 1.0.2
		 */
		iconToggler: function(){
			$('.cs-plus-header-toolbar-item_icontoggler').on('click',function(e){
				$(this).toggleClass('cs-plus-header-toolbar-item_icontoggler--active');
			});
		}
	}


	_PLUS_ADMIN.sidebar = {
		init: function(){
			_PLUS_ADMIN._debug("Sidebar Init");

			this.submenu();
			this.position();
		},
		submenu: function(){
			var sidebar 	= $('#adminmenu'),
				menuClass 	= 'wp-has-submenu-expanded';

			$('a.wp-has-submenu',sidebar).on('click',function(e){
				e.preventDefault();

				var parent 		= $(this).parents('li'),
					submenu 	= $('.wp-submenu',parent);

				if (settings.adminmenu.accordion){
					var target 	= parent,
						menus 	= $('li.wp-has-submenu-expanded',sidebar);
					
					$.each(menus,function(){
						var parent 	= $(this),
							submenu = $('.wp-submenu',parent);

						if (target[0] !== parent[0]){
							submenu.slideUp();
							parent.removeClass(menuClass);
						}
					});
				}

				if (!parent.hasClass(menuClass)){
					submenu.slideDown(400);
					parent.addClass(menuClass);
				} else {
					submenu.slideUp(400,function(){
						parent.removeClass(menuClass);
					});
				}
			})
		},
		position: function(){
			if (settings.adminmenu.brand_position == 'fixed'){
				this.fixedBrand();
			}
			if (settings.adminmenu.position == 'fixed'){
				this.fixedSidebar();
			}
			if (settings.adminmenu.scrollbar == true){
				this.fixedSidebar();
			}
		},
		fixedBrand: function(){
			$body = $('body');
			if (!settings.fixedBrand){
				settings.fixedBrand = true;
				$body.addClass('cs-plus-sidebar-brand-fixed');
			}
		},
		unfixedBrand: function(){
			$body = $('body');
			if (settings.fixedBrand){
				settings.fixedBrand = false;
				$body.removeClass('cs-plus-sidebar-brand-fixed');
			}
		},
		fixedSidebar: function(){
			$body = $('body');
			if (!settings.fixedSidebar){
				settings.fixedSidebar = true;
				$body.removeClass('cs-plus-sidebar-brand-fixed').addClass('cs-plus-sidebar-fixed');

				if (settings.adminmenu.scrollbar){
					$('#adminmenu').overlayScrollbars({
						className : "os-theme-dark",
						scrollbars: {
							autoHide: 'leave'
						}
					});
				}
			}
		},
		unfixedSidebar: function(){
			$body = $('body');
			if (settings.fixedSidebar){
				settings.fixedSidebar = false;
				$('#adminmenu').overlayScrollbars().destroy();
				$body.removeClass('cs-plus-sidebar-fixed');
			}
		}
	}


	_PLUS_ADMIN.networkSidebar = {
		init: function(){
			if (settings.general.is_multisite){
				_PLUS_ADMIN._debug("Network Sidebar Init");
	
				this.sidebar = $('#cs-plus-network-sites-sidebar'); 
				this.submenu();
				this.scrollbar();
				this.searchfield();
				this.closeListener();
			}
		},
		scrollbar: function(){
			$('#cs_wp-admin-bar-my-sites-list-wrapper').overlayScrollbars({
				scrollbars: {
					autoHide: 'leave'
				}
			});
		},
		searchfield: function(){
			var sidebar 	= this.sidebar,
				$search 	= sidebar.find('.cs-network-site-search'),
				$sites_data = sidebar.find('#cs_wp-admin-bar-my-sites-list');

			$search.keyup(function() {
				var value = $(this).val(),
				$sites = $sites_data.find('li.cs-network-site');
				$sites.each(function() {
					var $site = $(this);
					if ($site.data('name').search(new RegExp(value, 'i')) < 0) {
						$site.hide();
					} else {
						$site.show();
					}
				});
			});
		},
		sidebarToggle: function(){
			var sidebar = this.sidebar;
			sidebar.toggleClass('cs-sites-sidebar-visible');
		},
		submenu: function(){
			var sidebar 	= this.sidebar,
				menuClass 	= 'wp-has-submenu-expanded';

			$('a.wp-has-submenu',sidebar).on('click',function(e){
				e.preventDefault();

				var parent 		= $(this).parents('li'),
					submenu 	= $('.wp-submenu',parent);

				// if (settings.adminmenu.accordion){
					var target 	= parent,
						menus 	= $('li.wp-has-submenu-expanded',sidebar);
					
					$.each(menus,function(){
						var parent 	= $(this),
							submenu = $('.wp-submenu',parent);

						if (target[0] !== parent[0]){
							submenu.slideUp();
							parent.removeClass(menuClass);
						}
					});
				// }

				if (!parent.hasClass(menuClass)){
					parent.addClass(menuClass);
					submenu.slideDown(400);
				} else {
					submenu.slideUp(400,function(){
						parent.removeClass(menuClass);
					});
				}
			})
		},
		closeListener: function(){
			var self 	= this,
				sidebar = this.sidebar,
				wrapper = $('.cs-plus-network-sites-sidebar-wrapper',sidebar),
				menu 	= $('#cs_wp-admin-bar-my-sites-list',sidebar);
			
			sidebar.on('click',function(e){
				self.sidebarToggle();
			});
			wrapper.on('click',function(e){
				e.stopPropagation();
			});
		}
	};


	_PLUS_ADMIN.notificationCenter = {
		init: function(){
			if (settings.notifications.status){
				CS_DEBUG('Notifications Center Init');
				_PLUS_ADMIN.notificationCenter.toastQueue = [];
				_PLUS_ADMIN.notificationCenter.toastIndex = 1;
				_PLUS_ADMIN.notificationCenter.toastIsVisible = false;

				this.notifications();
				this.notificationPopup();
			}
		},
		notificationPopup: function(){
			var top_navbar 	= $('.cs-plus-header .cs-plus-header-toolbar'),
				notify		= $('.cs-plus-header-toolbar-item_notifications',top_navbar);
			
			if (notify.length){
				CS_DEBUG('Notification Popup Ready');
				var notification_popup 	= $('<div />',{ class: 'cs-plus-notification-center-popup cs-plus-header-toolbar-item_submenu'});
					// new_header 			= $('<div />',{ class: 'noty-title',html: 'Nuevas'}).appendTo(notification_popup);
				
				var noty_item = $("\
					<div class='noty-title'>Nuevas</div>\
					<div class='noty-container'>\
						<ul>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
						</ul>\
					</div>\
					<div class='noty-title'>Nuevas</div>\
					<div class='noty-container'>\
						<ul>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
							<li class='noty-item'>\
								<div class='noty-item-container'>\
									<div class='noti-item--icon'></div>\
									<div class='noti-item--body'></div>\
								</div>\
							</li>\
						</ul>\
					</div>\
				");

				noty_item.appendTo(notification_popup);

				notify.append(notification_popup);
			}
		},
		notifications: function(){
			var self = this;
			var notification_count = 0;
			var important_flag = false;
			var alerts = [];
			
			var alert_classes = '.update-nag, .notice, .notice-success, .updated, .settings-error, .error, .notice-error, .notice-warning, .notice-info';
			var $alerts = $( alert_classes )
				// .not( '.inline, .theme-update-message, .hidden, .hide-if-js' )
				.not( '.hidden, .hide-if-js' )
				.not( '#gadwp-notice, .rs-update-notice-wrap' );

			var greens = [ 'updated', 'notice-success' ];
			var reds = [ 'error', 'notice-error', 'settings-error' ];
			var blues = [ 'update-nag', 'notice', 'notice-info', 'update-nag', 'notice-warning' ];
	
			$alerts.each(function(i){
				var $alert = $(this);

				// Skip if alert is empty
				if ( ! $alert.html().replace( /^\s+|\s+$/g, '' ).length ) {
					return true;
				}
	
				// Determine the priority
				var j;
				var priority = 'neutral';
				// Red
				for ( j = 0; j < reds.length; j += 1 ) {
					if ( $alert.hasClass( reds[ j ] ) ) {
						if ( ! $alert.hasClass( 'updated' ) ) { // Because of .settings-error.updated
							priority = 'red';
						}
					}
				}

				var alert = {
					msg: 		$alert.html(),
					priority:	priority,
				};

				alerts.push({alert});
	
				// Add it to the notification list
				notification_count += 1;
			});

			if ( notification_count ) {
				// $alerts.remove();
				
				$.each(alerts, function(alert){
					var msg = alerts[alert].alert.msg;
					self.newToast(msg);
				});

				// Add Top Navbar Badge
				// cs-plus-dropdown cs-plus-header-toolbar-item cs-plus-header-toolbar-item_notifications
				var top_navbar 	= $('.cs-plus-header .cs-plus-header-toolbar'),
					notify		= $('.cs-plus-header-toolbar-item_notifications',top_navbar);
				
				if (notify.length){
					var badge = $('<div />',{class: 'cs-badge',html: notification_count});
					badge.appendTo(notify);
				}
			}
		},
		newToast: function(msg){
			if (settings.notifications.status){
				CS_DEBUG('Showing a notification');

				var index = _PLUS_ADMIN.notificationCenter.toastIndex++;
				_PLUS_ADMIN.notificationCenter.toastQueue.push({
					index:  index,
					msg: 	msg,
				});
	
				this.nextToast();
			} else {
				CS_DEBUG('Notification Center disabled.');
			}
		},
		nextToast: function(){
			var toasts = _PLUS_ADMIN.notificationCenter.toastQueue;
			var status = _PLUS_ADMIN.notificationCenter.toastIsVisible;
			if (!status){
				_PLUS_ADMIN.notificationCenter.toastIsVisible = true;
				if (toasts.length >= 1){
					var toast = toasts[0];
					var msg = toast.msg;
					this.showToast(msg);
					var newQueue = toasts.splice(1, 1);
					_PLUS_ADMIN.notificationCenter.toastQueue = newQueue;
				} else {
					_PLUS_ADMIN.notificationCenter.toastIsVisible = false;
				}
			} else {
				// showing alert
			}
		},
		showToast: function(msg){
			var self = this;
			$.toast({
				text: 		msg,
				hideAfter: 	settings.notifications.duration,
				stack: 		1,
				position: 	'bottom-right',
				beforeHide:		function(){
					_PLUS_ADMIN.notificationCenter.toastIsVisible = false;
				},
				afterHidden: 	function(){
					self.nextToast();
				},
			});
		}
	}


	_PLUS_ADMIN.themeLiveUpdate = {
		init: function(){
			CS_DEBUG("Theme Live Update Init");
			this.liveUpdate();
		},
		liveUpdate: function(){
			var self = this;

			$('.cssf-field-color_theme').on('cssf-color_theme-update',function(event,field_id,color){
				// Prefix CSS Variable
				var css_var = '--cs-plus-theme_' + field_id;

				if (css_var){
					var style = {};
					style[css_var] = color;
	
					self.setStyle(":root",style);
				}
			});
		},
		/**
		 * HELPER FUNCTIONS
		 */
		setStyle: function( element, propertyObject ){
			var elem = document.querySelector(element).style;
			for (var property in propertyObject){
				elem.setProperty(property, propertyObject[property]);
			}
		},
		removeStyle: function( element, propertyObject){
			var elem = document.querySelector(element).style;
			for (var property in propertyObject){
				elem.removeProperty(propertyObject[property]);
			}
		}
	}


	_PLUS_ADMIN.selectBox = function(){
		CS_DEBUG("Selectbox Init");

		$('.tablenav select, #typeselector, #cs-menu-manager_user-role-selector select').select2({
			minimumResultsForSearch: -1
		});

		// DEPRECATED
		// Revisar en su reemplazo: MutationObserver
		$('body').on('DOMNodeInserted', 'select', function () {
			$(this).select2();
		});

		var selectTimeout = setTimeout(function(){
			$('.attachment-filters').select2({
				minimumResultsForSearch: -1
			});
		},0);
	}


	_PLUS_ADMIN.userProfileSettings = function(){
		CS_DEBUG("UserProfile Init");

		var adminTab 		= $('#cssf-tab-user_profile'),
			allSwitch 		= $('.cssf-field-switcher',adminTab),
			settings 		= $('.cssf-field-checkbox',adminTab),
			_fromAllSwitch 	= false;
		
		$('input[type=checkbox]',settings).on('change',function(){
			if (!_fromAllSwitch){
				var checks 	= $('input[type=checkbox]',settings),
					checked = $('input[type=checkbox]:checked',settings);
				
				if (checks.length == checked.length){
					$('input[type=checkbox]',allSwitch).prop('checked',true);
				} else {
					if ($('input[type=checkbox]:checked',allSwitch).length){
						$('input[type=checkbox]',allSwitch).prop('checked',false);
					}
				}
			}
		});
		$('input[type=checkbox]',allSwitch).on('change',function(e){
			var theSwitch 	= $(this),
				checks 		= $('input[type=checkbox]',settings);
			_fromAllSwitch 	= true;
			if (theSwitch.is(':checked')){
				$.each(checks,function(){
					$(this).prop('checked', true).trigger('change');
				});
			} else {
				$.each(checks,function(){
					$(this).prop('checked', false).trigger('change');
				});
			}
			_fromAllSwitch 	= false;
		});
		
	}



	$(document).ready(function() {
		_PLUS_ADMIN.topNavbar.init();
		_PLUS_ADMIN.sidebar.init();
		_PLUS_ADMIN.networkSidebar.init();
		_PLUS_ADMIN.notificationCenter.init();
		_PLUS_ADMIN.general.init();
		_PLUS_ADMIN.themeLiveUpdate.init();
		// _PLUS_ADMIN.selectBox();
		_PLUS_ADMIN.userProfileSettings();
	});

	/**
	 * Define Public API
	 */
	_PLUS_ADMIN._debug = CS_DEBUG;

})( jQuery, window );