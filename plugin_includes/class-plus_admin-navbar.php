<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Top Navbar Builder
 *
 * @version 1.0.0
 *
 */

require_once CS_PLUS_PLUGIN_PATH .'/admin/includes/class-randomcolor.php';
use CastorStudio\RandomColor;

class Plus_admin_Navbar{
	function __construct(){

	}

	static function get_brand_logo(){
		// Brand Wrapper
		$logo_url = Plus_admin::gs('logo_url');
		if ($logo_url){
			$logo_url = str_replace('%admin_url%',admin_url(),$logo_url);
		} else { $logo_url = admin_url(); }


		$logo_type = Plus_admin::gs('logo_type');
		if ($logo_type == 'image'){
			// Logo Image
			$logo_img_url			= wp_get_attachment_url(Plus_admin::gs('logo_image'));
			$logo_img_collapsed_url	= wp_get_attachment_url(Plus_admin::gs('logo_image_collapsed'));

			$logo_container = "
				<div class='cs-plus-brand-logo-type--image'>
					<img src='{$logo_img_url}'>
				</div>
			";
		} else if ($logo_type == 'text'){
			// Logo Text
			$logo_icon 	= Plus_admin::gs('logo_icon');
			$logo_text 	= Plus_admin::gs('logo_text');
			$tpl_logo_icon = '';
			$tpl_logo_text = '';

			if ($logo_icon){
				$tpl_logo_icon = "
					<div class='cs-plus-brand_icon'>
						<i class='{$logo_icon}'></i>
					</div>
				";
			}

			if ($logo_text){
				preg_match('/\[subtitle\](.+)\[\/subtitle\]/', $logo_text, $subtitle);
				if (isset($subtitle[1])){
					$logo_text 		= preg_replace('/\[subtitle\](.+)\[\/subtitle\]/', '', $logo_text);
					$subtitle_text 	= $subtitle[1];
					$tpl_logo_text = "
						<div class='cs-plus-brand_text-title'>{$logo_text}</div>
						<div class='cs-plus-brand_text-subtitle'>{$subtitle_text}</div>
					";
				} else {
					$tpl_logo_text = "
						<div class='cs-plus-brand_text-title'>{$logo_text}</div>
					";
				}
			}

			$logo_container = "
				<div class='cs-plus-brand-logo-type--text'>
					{$tpl_logo_icon}
					<div class='cs-plus-brand_text'>
						{$tpl_logo_text}
					</div>
				</div>
			";
		}
		
		$brand_wrapper = "
			<div class='cs-plus-brand-wrapper'>
				<a href='{$logo_url}'>
					<div class='cs-plus-brand_brand'>
						<div class='cs-plus-brand_logo-static'>
							{$logo_container}
						</div>
					</div>
				</a>
			</div>
		";

		return $brand_wrapper;
	}

	static function init(){
		// NAVBAR BUILDER
		global $wp_admin_bar;

		$nodes = $wp_admin_bar->get_nodes();

		// Our own ordered $wp_admin_bar array
		$cs_adminbar = array();
		foreach ($nodes as $node){
			$has_parent = $node->parent;
			if (!$has_parent){
				$node_id = $node->id;
				$cs_adminbar[$node_id] = $node;
				$cs_adminbar[$node_id]->children = array();
			}
		}
		foreach ($nodes as $node){
			$has_parent = $node->parent;
			if ($has_parent){
				$cs_adminbar[$has_parent]->children[$node->id] = $node;
			}
		}

		// Nodes
		$ab_useractions 	= (isset($cs_adminbar['user-actions'])) ? $cs_adminbar['user-actions'] : false;
		$ab_comments 		= (isset($cs_adminbar['comments'])) ? $cs_adminbar['comments'] : false;
		$ab_updates 		= (isset($cs_adminbar['updates'])) ? $cs_adminbar['updates'] : false;
		$ab_sitename 		= (isset($cs_adminbar['site-name'])) ? $cs_adminbar['site-name'] : false;
		$ab_newcontent 		= (isset($cs_adminbar['new-content'])) ? $cs_adminbar['new-content'] : false;


		// Navbar Elements
		$navbar_builder 		= Plus_admin::gs('navbar_elements_builder');
		$active_elements 		= json_decode($navbar_builder['main']);
		$all_elements_data		= json_decode($navbar_builder['data'],true); // "true" to get the data into an array
		$active_elements_data 	= array_intersect_key($all_elements_data,array_flip($active_elements));
		$output_elements 		= '';

		// Indexed Elements
		// Sirve para ordenar los nuevos elementos indicando un index específico
		$indexed_elements = array(); $index = 10;
		if ($active_elements_data && is_array($active_elements_data)){
			foreach($active_elements_data as $item){
				$indexed_elements[$index] = $item;
				$index = $index + 10;
			}
		}

		// $_active_elements_data = apply_filters('cst_plusadmin/navbar/before_navbar_render',$active_elements_data);
		$_active_elements_data = apply_filters('cst_plusadmin/navbar/before_navbar_render',$indexed_elements);

		if ($_active_elements_data && is_array($_active_elements_data)){
			// Ordenamos por key el array filtrado con los nuevos elementos
			ksort($_active_elements_data);
			
			// Reindex active elements keys
			$active_elements 		= array_keys($_active_elements_data);
	
			// Convert back to stdclass object
			$active_elements_data 	= json_decode(json_encode($_active_elements_data));
			
			if ($active_elements && is_array($active_elements)){
				foreach ($active_elements as $element){
					$eData = self::_get_new_navbar_item($active_elements_data->$element);
					
					// Defaults
					$eType 						= $eData->type;
					$eName 						= $eData->name;
					$eVisibilityDevice 			= $eData->visibility_device;
					$eIcon 						= $eData->icon;
					$eIconSec 					= $eData->icon_secondary;
					$elementLabelStatus 		= $eData->label_status;
					$elementLabelText 			= $eData->label_text;
					$elementContainerId 		= $eData->element_settings->container_id;
					$elementContainerClass 		= $eData->element_settings->container_class;
					$elementWrapperType 		= $eData->element_settings->wrapper_type;
					$elementWrapperId			= $eData->element_settings->wrapper_id;
					$elementWrapperClass		= $eData->element_settings->wrapper_class;
					$elementWrapperAttr 		= $eData->element_settings->wrapper_attr;
					$elementSubmenu 			= $eData->submenu;
					$elementTemplate 			= $eData->template;
					$elementContent 			= $eData->content;
	
					$has_tooltip 				= false;
					$has_notifications			= false;
					$maybe_notificacion_badge 	= false;
					$maybe_tooltip				= false;
					$maybe_href 				= '#';
					$maybe_additional_attr 		= false;
					
		
					/*  ELEMENT TYPES
						---------------------------------------------------------------------- */
					// User Account
					if ($eType == 'useraccount' && $ab_useractions){
						global $current_user;
						
						$_user 				= $current_user->data;
						$user_name 			= $_user->display_name;
						$user_email			= $_user->user_email;
						$useravatar_status 	= $eData->user_avatar;
						$profile 			= (object) array(
							'avatar'			=> get_avatar( $current_user->ID ),
							'title'				=> __('My Account','plus_admin'),
							'href' 				=> $ab_useractions->children['user-info']->href,
							'edit_href' 		=> $ab_useractions->children['user-info']->href,
							'edit_title'		=> __('Edit my profile','plus_admin'),
							'logout_href'		=> wp_logout_url(),
							'logout_title'		=> __('Logout','plus_admin'),
						);
		
						// User Profile Links
						$_admin_settings_url 	= admin_url('admin.php?page=cs-plus-admin-settings');
						$_userLinks = array(
							array(
								esc_attr__('Admin Settings','plus_admin'),
								$_admin_settings_url,
								'_self'
							)
						);
						$_userLinks = apply_filters('cst_plusadmin/navbar/parse_elements/userprofilelinks', $_userLinks);
						$output_userLinks = '';
						if ($_userLinks && is_array($_userLinks)){
							foreach ($_userLinks as $link){
								$text 	= $link[0];
								$url 	= $link[1];
								$target = $link[2];
			
								$output_userLinks .= "
									<a href='{$url}' target='{$target}'>{$text}</a>
								";
							}
						}
	
						// Use User Avatar
						if ($useravatar_status){
							$elementContainerClass[] = 'cs-plus-header-toolbar-item-type--has-avatar';
							$elementContent = "
								<div class='cs-plus-header-toolbar-item_avatar'>
									{$profile->avatar}
								</div>
							";
						}
		
	
						// Tooltip
						$has_tooltip 	= $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? sprintf($tooltip_text,$user_name) : sprintf(esc_attr__('%s user account','plus_admin'),$user_name);
						}
		
						// User menu links
						$_admin_settings_url 	= admin_url('admin.php?page=cs-plus-admin-settings');
						$_userMenuLinks = array(
							array(
								'iconorimage'	=> 'mdi-settings1',
								'title'			=> esc_attr__('Admin Settings','plus_admin'),
								'subtitle'		=> esc_attr__('View Plus Admin Settings','plus_admin'),
								'url'			=> $_admin_settings_url,
							),
						);
						$_userMenuLinks = apply_filters('cst_plusadmin/navbar/parse_elements/usermenulinks', $_userMenuLinks);
						$output_userMenuLinks = '';
	
						if ($_userMenuLinks && is_array($_userMenuLinks)){
							foreach ($_userMenuLinks as $item){
								// Defaults
								$item = self::_get_new_usermenu_item($item);
	
								$iconbg = ($item->iconbg) ? "--item-bgcolor: $item->iconbg;" : '';
	
								$_maybe_iconorimage = ($item->iconorimage) ? "<div class='submenu-menuitem-iconorimage' style='{$iconbg}'>{$item->iconorimage}</div>" : '';
								$output_userMenuLinks .= "
									<a id='{$item->id}' class='submenu-menuitem {$item->class}' href='{$item->url}' title='{$item->title}' target='{$item->target}'>
										{$_maybe_iconorimage}
										<div class='submenu-menuitem-content'>
											<div class='submenu-menuitem-content_title'>{$item->title}</div>
											<div class='submenu-menuitem-content_subtitle'>{$item->subtitle}</div>
										</div>
									</a>
								";
							}
						}
	
						$elementSubmenu = "
							<div class='submenu-wrapper submenu-useraccount'>
								<div class='submenu-body'>
									<a class='submenu-avatar' aria-label='{$profile->title}' href='{$profile->edit_href}'>
										<div class='submenu-avatar-container'>
											<div class='avatar-img' title='{$profile->title}'>
												{$profile->avatar}
											</div>
											<span class='avatar-title'>{$profile->edit_title}</span>
										</div>
									</a>
									<div class='submenu-userinfo'>
										<div class='submenu-userinfo_username'>{$user_name}</div>
										<div class='submenu-userinfo_useremail'>{$user_email}</div>
										<div class='submenu-userinfo_userlinks'>
											{$output_userLinks}
										</div>
										<a class='button' href='{$profile->href}' target='_blank'>{$profile->title}</a>
									</div>
								</div>
								<div class='submenu-menu'>
									<div class='submenu-menu-wrapper'>
										{$output_userMenuLinks}
									</div>
								</div>
								<div class='submenu-footer'>
									<div class='submenu-footer-col'>
										<a id='cs-btn-logout' class='button button-warning' href='{$profile->logout_href}' target='_top'>
											<i class='mdi-exit_to_app'></i> {$profile->logout_title}
										</a>
									</div>
								</div>
							</div>
						";
					}
		
					// Comments
					if ($eType == 'comments' && $ab_comments != false){
						$_title 	= $ab_comments->title;
						$maybe_href	= $ab_comments->href;
		
						preg_match('/<span class=.screen-reader-text comments-in-moderation-text.>(.+)<\/span>/', $_title, $title);
						preg_match('/<span .+ aria-hidden=.true.>(.|\n)*?<\/span>/', $_title, $count);
		
						$title = $title[1];
						$count = $count[1];
		
						$has_tooltip 	= $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
		
						$has_notifications 	= $eData->notifications_status;
						$notification_count	= $count;
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? sprintf($tooltip_text,$notification_count) : $title;
						}
					}
					
					// Updates
					if ($eType == 'updates'){
						if ($ab_updates){
							$count 	= strip_tags($ab_updates->title);
							$title 	= strip_tags($ab_updates->meta['title']);
						} else {
							$count 	= false;
							$title 	= __('Your WordPress is up to date','plus_admin');
						}
						$maybe_href 	= admin_url('update-core.php');
						$has_tooltip 	= $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
		
						$has_notifications 	= $eData->notifications_status;
						$notification_count	= str_replace($title,'',$count);
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? sprintf($tooltip_text,$notification_count) : $title;
						}
					}
					
					// New Content
					if ($eType == 'newcontent' && $ab_newcontent != false){
						$newcontent = $ab_newcontent->children;
						$title 		= strip_tags($ab_newcontent->title);
		
						$has_tooltip 	= $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
						$viewMore_text 	= esc_attr__('View All','plus_admin');
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : $title;
						}
						
						$output = '';
						if ($newcontent && is_array($newcontent)){
							foreach ($newcontent as $content){
								$type 	= $content->id;
								$title 	= $content->title;
								$href 	= $content->href;
								$icon 	= ' mdi-add_circle';
			
								if ($type == 'new-post'){
									$icon = 'mdi-playlist_add';
								} else if ($type == 'new-media'){
									$icon = 'mdi-add_a_photo';
								} else if ($type == 'new-page'){
									$icon = 'mdi-note_add';
								} else if ($type == 'new-user'){
									$icon = 'mdi-person_add';
								}
			
								$tpl = "
									<div class='grid-item'>
										<a class='gb_c' href='{$href}'>
											<span class='grid-item-icon'>
												<i class='{$icon}'></i>
											</span>
											<span class='grid-item-title'>{$title}</span>
										</a>
									</div>
								";
			
								$output .= $tpl;
							}
						}
		
						$elementSubmenu = "
							<div class='submenu-wrapper submenu-newcontent'>
								<div class='submenu-body-wrapper'>
									<div class='submenu-body'>
										{$output}
									</div>
								</div>
								<div class='submenu-footer'>
									<div class='submenu-footer-col'>
										<a class='button btn-view-more-new-content' href='#'>{$viewMore_text}</a>
									</div>
								</div>
							</div>
						";
					}
		
					// Notifications
					if ($eType == 'notifications'){
						
					}
		
					// View Site
					if ($eType == 'viewsite' && $ab_sitename != false){
						$sitename 		= $ab_sitename->title;
						$title 			= $ab_sitename->children[0]->children[0]->title;
						$has_tooltip 	= $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
						$maybe_href 	= $ab_sitename->href;
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : "{$title}: {$sitename}";
						}
	
						$maybe_additional_attr .= " target='_blank'";
					}
		
					// Help
					if ($eType == 'help'){
						$has_tooltip = $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : esc_attr__('Page Help','plus_admin');
						}
					}
					
					// Screen Options
					if ($eType == 'screenoptions'){
						$has_tooltip = $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : esc_attr__('Screen Options','plus_admin');
						}
					}
					
					// Sidebar Toggle
					if ($eType == 'sidebartoggle'){
						$has_tooltip = $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : esc_attr__('Toggle Sidebar','plus_admin');
						}
	
						// Get current sidebar status
						$current_sidebar_status = (get_user_setting('plusSidebar') == 'visible') ? '--active' : '';
						$elementContainerClass[] = "cs-plus-header-toolbar-item_{$eType}{$current_sidebar_status}";
		
						$elementContent = "
							<span class='cs-plus-header-toolbar-item_toggle-icon-primary'>
								<span class='cs-plus-header-toolbar-item__icon'><i class='{$eIcon}'></i></span>
							</span>
							<span class='cs-plus-header-toolbar-item_toggle-icon-secondary'>
								<span class='cs-plus-header-toolbar-item__icon'><i class='{$eIconSec}'></i></span>
							</span>
						";
					}
					
					// Network Sites
					if ($eType == 'networksites'){
	
					}
					
					// Page Title
					if ($eType == 'pagetitle'){
						$pagetitle 				= get_admin_page_title();
						$elementWrapperType 	= 'div';
						$elementWrapperClass 	= 'plus-admin-page-title';
						$elementWrapperAttr 	= '';
						$elementContent 		= "<span>{$pagetitle}</span>";
					}
	
					// Flexible Space
					if ($eType == 'flexiblespace'){
						$elementWrapperType 	= 'div';
						$elementWrapperAttr 	= '';
						$elementContent 		= '';
					}
		
					// Site Brand
					if ($eType == 'sitebrand'){
						$brand_wrapper 			= self::get_brand_logo();
	
						$elementWrapperType 	= 'div';
						$elementWrapperAttr 	= '';
						$elementContent 		= $brand_wrapper;
					}
					
					// Custom Admin/Internal/External
					if ($eType == 'customlink'){
						$has_tooltip 	= $eData->tooltip_status;
						$tooltip_text 	= $eData->tooltip_text;
						$link_type 		= $eData->customlink_type;
	
						if ($link_type == 'admin' || $link_type == 'internal'){
							$maybe_href 	= admin_url($eData->customlink);
						} else if ($link_type == 'external' || !$link_type){
							$maybe_href 	= $eData->customlink;
	
							$maybe_additional_attr .= " target='_blank'";
						}
		
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : sprintf(esc_attr__('Go to: %s','plus_admin'),$maybe_href);
						}
	
					}
	
					// Custom Element
					if ($eType == 'custom'){
						$eName 								= $eData->name;
						$eIcon 								= $eData->icon;
						$eIconSec 							= $eData->icon_secondary;
						$eVisibilityDevice 					= $eData->visibility_device;
						$has_tooltip 						= $eData->tooltip_status;
						$tooltip_text 						= $eData->tooltip_text;
						$link_type 							= $eData->customlink_type;
						$elementLabelStatus 				= $eData->label_status;
						$elementLabelText 					= $eData->label_text;
						$elementContainerId 				= $eData->element_settings->container_id;
						$elementContainerClass[] 			= $eData->element_settings->container_class;
						$elementWrapperType 				= $eData->element_settings->wrapper_type;
						$elementWrapperId					= $eData->element_settings->wrapper_id;
						$elementWrapperClass				= $eData->element_settings->wrapper_class;
						$elementWrapperAdditionalAttr 		= $eData->element_settings->wrapper_attr;
						$elementSubmenu 					= $eData->element_settings->submenu;
	
						// Element Icon or Icons
						if ($eIcon && $eIconSec){
							$maybe_icontoggler = "
								<div class='cs-plus-header-toolbar-item_icontoggler'><span class='cs-plus-header-toolbar-item_toggle-icon-primary'><i class='{$eIcon}'></i></span><span class='cs-plus-header-toolbar-item_toggle-icon-secondary'><i class='{$eIconSec}'></i></span></div>
							";
							$hasIconToggler = true;
						} else {
							$eIcon = ($eIcon) ? $eIcon : (($eIconSec) ? $eIconSec : false);
							if ($eIcon) {
								$maybe_icontoggler = "<span class='cs-plus-header-toolbar-item__icon'><i class='{$eIcon}'></i></span>";
							} else {
								$maybe_icontoggler = '';
							}
							$hasIconToggler = false;
						}
	
						// Element Label
						if ($elementLabelStatus){
							if ($elementLabelText){
								$maybe_label = "<span class='cs-plus-header-toolbar-item__label'>{$elementLabelText}</span>";
							} else {
								$maybe_label = '';
							}
						} else {
							$maybe_label = '';
						}
	
						// Link Type
						if ($link_type == 'admin' || $link_type == 'internal'){
							$maybe_href 	= admin_url($eData->customlink);
						} else if ($link_type == 'external' || !$link_type){
							$maybe_href 	= $eData->customlink;
	
							$maybe_additional_attr .= " target='_blank'";
						}
		
						// Tooltip
						if ($has_tooltip){
							$maybe_tooltip = ($tooltip_text) ? $tooltip_text : '';
						}
	
						// Element Content
						$elementContent 	= $maybe_icontoggler . $maybe_label;
	
						// Element Wrapper Attributes
						$maybe_additional_attr .= ' '.$elementWrapperAdditionalAttr;
					}
		
		
	
	
					/*  Add General Tooltip Style Class
						---------------------------------------------------------------------- */
					if ($has_tooltip){
						$elementContainerClass[] = 'cs-plus-header-toolbar-item--has-tooltip';
					}
	
					/* 	Add Notification badge
						---------------------------------------------------------------------- */
					if ($has_notifications && $notification_count){
						$elementContent .= "<div class='cs-badge'><span>{$notification_count}</span></div>";
					}
	
	
					/*  Add Visibility Classes
						---------------------------------------------------------------------- */
					if ($eVisibilityDevice){
						$visibility_class = array();
						if (is_array($eVisibilityDevice)){
							$visibility_defaults 	= array('small','mobile','tablet','desktop');
							$hidden_on 				= array_diff($visibility_defaults,$eVisibilityDevice);
	
							foreach($hidden_on as $device){
								$visibility_class[] = "cs-plus-hidden-on-{$device}";
							}
						}
	
						// Update current element container class
						$elementContainerClass = array_merge($elementContainerClass,$visibility_class);
					} else {
						$elementContainerClass[] = 'cs-plus-hidden-on-all';
					}
	
	
					/*  Final Element Wrapper Additional Attributes
						---------------------------------------------------------------------- */
					$maybe_href 		= ($maybe_href) ? "href='{$maybe_href}'" : "";
					$maybe_tooltip  	= ($maybe_tooltip) ? " title='{$maybe_tooltip}' " : " ";
					$elementWrapperAttr = $maybe_href . $maybe_tooltip . $maybe_additional_attr;
	
		
					/*  Filter Element Classes
						---------------------------------------------------------------------- */
					$filter_element_container_class 	= apply_filters('cst_plusadmin/navbar/element_container_class',$elementContainerClass,$eData);
	
					$elementContainerClass 				= implode(' ',$filter_element_container_class);
	
	
					/*  Render Element Template
						---------------------------------------------------------------------- */
					if ($elementTemplate){
						$elementTemplate = apply_filters('cst_plusadmin/navbar/element_template',$elementTemplate,$eData);
	
						$tpl_vars 	= array("{{elementContainerId}}","{{elementContainerClass}}","{{elementWrapperType}}","{{elementWrapperId}}","{{elementWrapperClass}}","{{elementWrapperAttr}}","{{elementContent}}","{{elementSubmenu}}");
						$tpl_data 	= array($elementContainerId,$elementContainerClass,$elementWrapperType,$elementWrapperId,$elementWrapperClass,$elementWrapperAttr,$elementContent,$elementSubmenu);
						$tpl 		= str_replace($tpl_vars,$tpl_data,$elementTemplate);
	
						$output_elements .= $tpl;
					}
				}
			}
		}

		$navbar = "
				<div class='cs-plus-header'>
					<div class='cs-plus-header-toolbar'>
						{$output_elements}
					</div>
				</div>
			";
		echo $navbar;
	}


	/**
	 * Returns an object with all information about the item to include inside user submenu
	 *
	 * @param string $file the file to include
	 * @return object the file object
	 */
	private static function _get_new_usermenu_item($data){
		$data = (object) $data;

		// Icon Type
		if (isset($data->icontype)){
			if ($data->icontype == ''|| $data->icontype == 'icon'){
				$icontype = 'icon';
			} else if ($data->icontype == 'image'){
				$icontype = 'image';
			}
		} else {
			$icontype = 'icon';
		}

		// Icon BG Color
		if (isset($data->iconbg)){
			if ($data->iconbg == 'static'){
				$iconbg = false;
			} else if ($data->iconbg == 'random'){
				$iconbg = RandomColor::one();
			} else {
				$iconbg = $data->iconbg;
			}
		} else {
			$iconbg = false;
		}

		// Icon Content
		if ($data->iconorimage === false){
			$iconorimage = false;
		} else if ($icontype == 'image'){
			$iconorimage 	= '<img src="'.$data->iconorimage.'" />';
		} else if ($icontype == 'icon'){
			$iconorimage 	= '<span class="'.$data->iconorimage.'"></span>';
		}

		$newitem = new stdClass();
		$newitem->iconorimage 	= $iconorimage;
		$newitem->iconbg 		= $iconbg;
		$newitem->icontype 		= $icontype;
		$newitem->title			= $data->title;
		$newitem->subtitle 		= $data->subtitle;
		$newitem->url 			= (isset($data->url)) ? $data->url : '#';
		$newitem->target 		= (isset($data->target)) ? $data->target : '';
		$newitem->id 			= (isset($data->id)) ? $data->id : '';
		$newitem->class 		= (isset($data->class)) ? $data->class : '';

		return $newitem;
	}


	/**
	 * Returns an object with all information about the item to include in the top navbar
	 *
	 * @param string $file the file to include
	 * @return object the file object
	 */
	private static function _get_new_navbar_item($data){
		$type = (isset($data->type)) ? $data->type : false;
		$name = (isset($data->name)) ? $data->name : false;

		$icon 			= (isset($data->icon)) ? $data->icon : false;
		$icon_secondary = (isset($data->icon_secondary)) ? $data->icon_secondary : false;
		$eIcon			= ($icon) ? $icon : (($icon_secondary) ? $icon_secondary : '');

		$avatar 					= (isset($data->user_avatar)) ? $data->user_avatar : false;

		$visibility_device 			= (isset($data->visibility_device)) ? $data->visibility_device : array();
		$tooltip_status 			= (isset($data->tooltip_status)) ? $data->tooltip_status : false;
		$tooltip_text 				= (isset($data->tooltip_text)) ? $data->tooltip_text : '';
		$customlink_type 			= (isset($data->customlink_type)) ? $data->customlink_type : false;
		$customlink 				= (isset($data->customlink)) ? $data->customlink : false;
		$notifications_status		= (isset($data->notifications_status)) ? $data->notifications_status : false;
		$label_status 				= (isset($data->label_status)) ? $data->label_status : false;
		$label_text 				= (isset($data->label_text)) ? $data->label_text : '';

		$container_id 				= (isset($data->element_settings->container_id)) ? $data->element_settings->container_id : '';
		$containerClasses 			= array("cs-plus-header-toolbar-item","cs-plus-header-toolbar-item_{$type}","cs-plus-header-toolbar-item-type--{$type}");
		if (isset($data->element_settings->container_class)){
			array_push($containerClasses,$data->element_settings->container_class);
		}
		$container_class 			= $containerClasses;
		$wrapper_type 				= (isset($data->element_settings->wrapper_type) && ($data->element_settings->wrapper_type != false)) ? $data->element_settings->wrapper_type : 'a';
		$wrapper_id 				= (isset($data->element_settings->wrapper_id)) ? $data->element_settings->wrapper_id : '';
		$wrapper_class 				= (isset($data->element_settings->wrapper_class)) ? $data->element_settings->wrapper_class : '';
		$wrapper_attr 				= (isset($data->element_settings->wrapper_attr)) ? $data->element_settings->wrapper_attr : '';
		$submenu 					= (isset($data->submenu)) ? $data->submenu : '';

		$elementTemplate = "
			<div id='{{elementContainerId}}' class='{{elementContainerClass}}'>
				<{{elementWrapperType}} id='{{elementWrapperId}}' class='{{elementWrapperClass}}' {{elementWrapperAttr}}>
					{{elementContent}}
				</{{elementWrapperType}}>
				{{elementSubmenu}}
			</div>
		";
		$template = (isset($data->template)) ? $data->template : $elementTemplate;
		$content = (isset($data->content)) ? $data->content : "<span class='cs-plus-header-toolbar-item__icon'><i class='{$eIcon}'></i></span>";

		$newitem = new stdClass();
		$newitem->type 					= $type;
		$newitem->name					= $name;
		$newitem->icon					= $icon;
		$newitem->icon_secondary		= $icon_secondary;
		$newitem->user_avatar			= $avatar;
		$newitem->visibility_device		= $visibility_device;
		$newitem->tooltip_status		= $tooltip_status;
		$newitem->tooltip_text			= $tooltip_text;
		$newitem->customlink_type		= $customlink_type;
		$newitem->customlink			= $customlink;
		$newitem->notifications_status	= $notifications_status;
		$newitem->label_status			= $label_status;
		$newitem->label_text			= $label_text;
		$element_settings = (object) array(
			'container_id'		=> $container_id,
			'container_class'	=> $container_class,
			'wrapper_type'		=> $wrapper_type,
			'wrapper_id'		=> $wrapper_id,
			'wrapper_class'		=> $wrapper_class,
			'wrapper_attr'		=> $wrapper_attr,
		);
		$newitem->element_settings 		= $element_settings;
		$newitem->submenu				= $submenu;
		$newitem->template				= $template;
		$newitem->content 				= $content;
		
		return $newitem;
	}
}