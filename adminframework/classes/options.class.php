<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
abstract class CSSFramework_Options extends CSSFramework_Abstract {

	/**
	 * total_cols
	 *
	 * @var int
	 */
	public static $total_cols = 0;

	/**
	 * field
	 *
	 * @var array|null
	 */
	public $field = null;

	/**
	 * value
	 *
	 * @var null|string|array
	 */
	public $value = null;

	/**
	 * org_value
	 *
	 * @var null|string
	 */
	public $org_value = null;

	/**
	 * unique
	 *
	 * @var null|string
	 */
	public $unique = null;

	/**
	 * multilang
	 *
	 * @var bool|mixed|null
	 */
	public $multilang = null;

	/**
	 * row_after
	 *
	 * @var null
	 */
	public $row_after = null;

	/**
	 * js_settings
	 *
	 * @var null
	 */
	public $js_settings = null;

	/**
	 * uid
	 *
	 * @var null
	 */
	public $uid = null;



  public function __construct( $field = array(), $value = '', $unique = '', $is_sub = false ) {
    // $this->field      = $field;
    $this->field      = wp_parse_args( $field, $this->get_defaults() );
    $this->value      = $value;
    $this->org_value  = $value;
    $this->unique     = $unique;
	$this->multilang  = $this->element_multilang();
	
	if ($is_sub === true){
		// Lo hice por un problema que ocurrió con un fieldset y los themes en UWAC
		$this->field['sub'] = true;
	}
  }

  public function element_value( $value = '' ) {
    $value = $this->value;
    if ( is_array( $this->multilang ) && is_array( $value ) ) {
      $current  = $this->multilang['current'];
      if( isset( $value[$current] ) ) {
        $value = $value[$current];
      } else if( $this->multilang['current'] == $this->multilang['default'] ) {
        $value = $this->value;
      } else {
        $value = '';
      }
    } else if ( ! is_array( $this->multilang ) && isset( $this->value['multilang'] ) && is_array( $this->value ) ) {
      $value = array_values( $this->value );
      $value = $value[0];
    } else if ( is_array( $this->multilang ) && ! is_array( $value ) && ( $this->multilang['current'] != $this->multilang['default'] ) ) {
      $value = '';
    }
    return $value;

  }
  public function element_name( $extra_name = '', $multilang = false ) {

    $element_id      = ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';
    $extra_multilang = ( ! $multilang && is_array( $this->multilang ) ) ? '['. $this->multilang['current'] .']' : '';
    return ( isset( $this->field['name'] ) ) ? $this->field['name'] . $extra_name : $this->unique .'['. $element_id .']'. $extra_multilang . $extra_name;

  }
  public function element_name____esc_attr__( $extra_name = '', $multilang = false ) {
		$element_id      = ( isset( $this->field ['id'] ) ) ? $this->field ['id'] : '';
		$extra_multilang = ( ! $multilang && is_array( $this->multilang ) ) ? '[' . $this->multilang ['current'] . ']' : '';
		$unique          = $this->get_unique( $element_id ) . $extra_multilang . $extra_name;
		$fname           = $unique;
		if ( isset( $this->field['name'] ) ) {
			$fname = $this->field['name'] . $extra_name;
		} elseif ( isset( $this->field['name_before'] ) || isset( $this->field['name_after'] ) ) {
			$fname = isset( $this->field['name_before'] ) ? $this->field['name_before'] . $fname : $fname;
			$fname = isset( $this->field['name_after'] ) ? $fname . $this->field['name_after'] : $fname;
		}

		#return ( isset( $this->field ['name'] ) ) ? $this->field ['name'] . $extra_name : $unique;
		return $fname;
	}

public function element_type() {
    $type = ( isset( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : $this->field['type'];
    return $type;
}
	
public function element_raw_type() {
    $type = $this->field['type'];
    return $type;
}

public function element_class( $el_class = '' ) {
	$field_class = ( isset( $this->field['class'] ) ) ? ' ' . $this->field['class'] : '';
	return ( $field_class || $el_class ) ? ' class="'. $el_class . $field_class .'"' : '';
}

public function element_attributes( $el_attributes = array(), $extra_more = array() ) {
	$attributes = (isset($this->field['attributes'])) ? $this->field['attributes'] : array();

	if (isset($this->field['style'])){
		$attributes['style'] = $this->field['style'];
	}

	$element_id  = ( isset( $this->field ['id'] ) ) ? $this->field ['id'] : '';
	$is_in_array = in_array( $this->field['type'], array( 'text', 'textarea' ) );

	if ( false !== $el_attributes ) {
		$sub_elemenet  = ( isset( $this->field ['sub'] ) ) ? 'sub-' : '';
		$el_attributes = ( is_string( $el_attributes ) || is_numeric( $el_attributes ) ) ? array(
			'data-' . $sub_elemenet . 'depend-id' => $element_id . '_' . $el_attributes,
		) : $el_attributes;
		$el_attributes = ( empty( $el_attributes ) && isset( $element_id ) ) ? array(
			'data-' . $sub_elemenet . 'depend-id' => $element_id,
		) : $el_attributes;
	}

	if ( true === $is_in_array && ( isset( $this->field['limit'] ) && $this->field['limit'] > 0 ) ) {
		$el_attributes['data-limit-element'] = true;
	}

	if ( ! empty( $extra_more ) ) {
		$el_attributes = wp_parse_args( $el_attributes, $extra_more );
	}

	$attributes = wp_parse_args( $attributes, $el_attributes );

	return $this->array_to_html_attrs( $attributes );
}

public function element_before() {
	return ( isset( $this->field['before'] ) ) ? $this->field['before'] : '';
}

public function element_after() {
    $out = $this->element_text_limit();

	$out .= $this->element_desc_before();
	$out .= $this->element_info();

    $out .= ( isset( $this->field['after'] ) ) ? $this->field['after'] : '';
    $out .= $this->element_after_multilang();
    $out .= $this->element_get_error();
    // $out .= $this->element_help();
    $out .= $this->element_debug();
	$out .= $this->element_js_settings();
	
    return $out;
}

public function element_debug() {

    $out = '';

    if ( ( isset( $this->field['debug'] ) && $this->field['debug'] === true ) || ( defined( 'CSSF_OPTIONS_DEBUG' ) && CSSF_OPTIONS_DEBUG ) ){

		$value = $this->element_value();

		$out .= "<pre>";
		$out .= "<strong>". esc_attr__( 'CONFIG', 'cssf-framework' ) .":</strong>";
		$out .= "\n";
		ob_start();
		var_export( $this->field );
		$out .= htmlspecialchars( ob_get_clean() );
		$out .= "\n\n";
		$out .= "<strong>". esc_attr__( 'USAGE', 'cssf-framework' ) .":</strong>";
		$out .= "\n";
		$out .= ( isset( $this->field['id'] ) ) ? "cssf_get_option( '". $this->field['id'] ."' );" : '';

      	if( ! empty( $value ) ) {
			$out .= "\n\n";
			$out .= "<strong>". esc_attr__( 'VALUE', 'cssf-framework' ) .":</strong>";
			$out .= "\n";
			ob_start();
			var_export( $value );
			$out .= htmlspecialchars( ob_get_clean() );
      	}

      	$out .= "</pre>";

    }

    if( ( isset( $this->field['debug_light'] ) && $this->field['debug_light'] === true ) || ( defined( 'CSSF_OPTIONS_DEBUG_LIGHT' ) && CSSF_OPTIONS_DEBUG_LIGHT ) ) {

		$out .= "<pre>";
		$out .= "<strong>". esc_attr__( 'USAGE', 'cssf-framework' ) .":</strong>";
		$out .= "\n";
		$out .= ( isset( $this->field['id'] ) ) ? "cssf_get_option( '". $this->field['id'] ."' );" : '';
		$out .= "\n";
		$out .= "<strong>". esc_attr__( 'ID', 'cssf-framework' ) .":</strong>";
		$out .= "\n";
		$out .= ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';
		$out .= "</pre>";

    }
    return $out;
}

public function element_get_error_______esc_attr__() {

    global $cssf_errors;

    $out = '';

    if( ! empty( $cssf_errors ) ) {
      foreach ( $cssf_errors as $key => $value ) {
        if( isset( $this->field['id'] ) && $value['code'] == $this->field['id'] ) {
          $out .= '<p class="cssf-text-warning">'. $value['message'] .'</p>';
        }
      }
    }
    return $out;
}

public function element_get_error() {
	$cssf_errors = cssf_get_errors();
	$out         = '';
	if ( ! empty( $cssf_errors ) ) {
		foreach ( $cssf_errors as $key => $value ) {
			$fid = isset( $this->field['error_id'] ) ? $this->field['error_id'] : $this->field['id'];
			if ( isset( $this->field ['id'] ) && $fid === $value ['code'] ) {
				$out .= '<p class="cssf-text-warning">' . $value ['message'] . '</p>';
			}
		}
	}
	return $out;
}

public function element_help() {
	$defaults = array(
		'icon'     	=> 'cli cli-help-circle',
		'type'			=> 'text',
		'content'  	=> '',
		'position'	=> 'bottom',
	);
	$help     = array();
	if ( isset( $this->field['help'] ) ) {
		if ( ! is_array( $this->field['help'] ) ) {
			$this->field['help'] = array( 'content' => $this->field['help'] );
		}
		$help = wp_parse_args( $this->field['help'], $defaults );
	}

	$html = false;

	// Image Tooltip
	if ($help['type'] == 'image'){
		$html 		= true;
		$help['content'] 	= "<img src='".$help['content']."' />";
	}

	return ( ! empty( $help['content'] ) ) ? '<span class="cssf-help" data-html="'.$html.'" data-placement="' . $help['position'] . '" data-title="' . $help['content'] . '"><span class="' . $help['icon'] . '"></span></span>' : '';
}

public function element_after_multilang() {

    $out = '';

    if ( is_array( $this->multilang ) ) {

      $out .= '<fieldset class="hidden">';

      foreach ( $this->multilang['languages'] as $key => $val ) {

        // ignore current language for hidden element
        if( $key != $this->multilang['current'] ) {

          // set default value
          if( isset( $this->org_value[$key] ) ) {
            $value = $this->org_value[$key];
          } else if ( ! isset( $this->org_value[$key] ) && ( $key == $this->multilang['default'] ) ) {
            $value = $this->org_value;
          } else {
            $value = '';
          }

          $cache_field = $this->field;

          unset( $cache_field['multilang'] );
          $cache_field['name'] = $this->element_name( '['. $key .']', true );

          $class = 'CSSFramework_Option_' . $this->field['type'];
          $element = new $class( $cache_field, $value, $this->unique );

          ob_start();
          $element->output();
          $out .= ob_get_clean();

        }
      }

      $out .= '<input type="hidden" name="'. $this->element_name( '[multilang]', true ) .'" value="true" />';
      $out .= '</fieldset>';
      $out .= '<p class="cssf-text-field-desc">'. sprintf( esc_attr__( 'You are editing language: ( <strong>%s</strong> )', 'cssf-framework' ), $this->multilang['current'] ) .'</p>';

    }

    return $out;
  }

  public function element_data( $type = '' ){
    $options = array();
    $query_args = ( isset( $this->field['query_args'] ) ) ? $this->field['query_args'] : array();
	
	// sanitize type name
	if( in_array( $type, array( 'page', 'pages' ) ) ) {
		$option = 'page';
	} else if( in_array( $type, array( 'post', 'posts' ) ) ) {
		$option = 'post';
	} else if( in_array( $type, array( 'category', 'categories' ) ) ) {
		$option = 'category';
	} else if( in_array( $type, array( 'tag', 'tags' ) ) ) {
		$option = 'post_tag';
	} else if( in_array( $type, array( 'menu', 'menus' ) ) ) {
		$option = 'nav_menu';
	} else {
		$option  = '';
	}

    switch( $type ) {
		case 'admin_pages':
		case 'admin_page':
		case 'adminpages':
		case 'adminpage':
			global $menu, $submenu;

			$output = '';

			$options = array(
				'all_pages' => esc_attr__('All Pages','cssf-framework'),
			);

			if (current_user_can('manage_options')){
				$core_items = array('menu-dashboard','menu-posts','menu-media','menu-pages','menu-comments','menu-appearance','menu-plugins','menu-users','menu-tools','menu-settings');
				$core_items = array('edit.php','post-new.php','post.php','edit-tags.php','upload.php','media-new.php','edit-comments.php','comment.php','themes.php','widgets.php','nav-menus.php','theme-editor.php','plugins.php','plugin-install.php','plugin-editor.php','users.php','user-new.php','user-edit.php','profile.php','tools.php','import.php','admin.php','export.php','options-general.php','options-writing.php','options-reading.php','options-discussion.php','options-media.php','options-permalink.php','options-general.php','link-manager.php','link-add.php','link.php','index.php','update-core.php','sites.php');

				if (is_array($menu)){
					foreach($menu as $key => $item){
						/**
						 * The elements in each item array are :
						 * 0: Menu title
						 * 1: Minimum level or capability required.
						 * 2: The URL of the item's file
						 * 3: Page Title
						 * 4: Classes
						 * 5: ID
						 * 6: Icon for top level menu
						 **/

						$maybe_separator 	= ($item[4] == 'wp-menu-separator') ? true : false;
						if (!$maybe_separator){
							$item_name 		= $item[0];
							$item_slug 		= sanitize_title($item_name);
							$item_url 		= (isset($item[2])) ? $item[2] : null;
							$item_id 		= (isset($item[5])) ? $item[5] : null;
							$item_key 		= $item_url;

							preg_match('/(?:.+\.php)/', $item_url,$is_core_item);

							if (!$is_core_item){
								$item_key	= "admin.php?page={$item_url}";
							}
			
							if (isset($submenu[$item_url])){
								$item_submenu 	= $submenu[$item_url];
								$_item_submenu 	= array();
								
								foreach($item_submenu as $subkey => $subitem){
									$subitem_name 	= $subitem[0];
									$subitem_url 	= $subitem[2];

									preg_match('/(?:.+\.php)/', $subitem_url,$is_core_subitem);
									if (!$is_core_subitem){
										$subitem_url = "admin.php?page={$subitem_url}";
									}
									$_item_submenu[$subitem_url] = wp_strip_all_tags($subitem_name);
								}
		
								// Add new OPT Group
								$options[wp_strip_all_tags($item_name)] = $_item_submenu;
							} else {
								$options[$item_key] = wp_strip_all_tags($item_name);
							}
						}
					}
				}
			}
		break;
		
		case 'page':
        case 'pages':
        case 'post':
        case 'posts':

          // term query required for ajax select
          if( ! empty( $term ) ) {

            $query             = new WP_Query( wp_parse_args( $query_args, array(
              's'              => $term,
              'post_type'      => $option,
              'post_status'    => 'publish',
              'posts_per_page' => 25,
            ) ) );

          } else {

            $query          = new WP_Query( wp_parse_args( $query_args, array(
              'post_type'   => $option,
              'post_status' => 'publish',
            ) ) );

          }

          if ( ! is_wp_error( $query ) && ! empty( $query->posts ) ) {
            foreach ( $query->posts as $item ) {
              $options[$item->ID] = $item->post_title;
            }
          }

        break;
	  
		case 'products':
		case 'product':
			$query_args['post_type'] = 'product';
			$query_args['posts_per_page'] = '-1';
			$posts = get_posts( $query_args );

			if ( ! is_wp_error( $posts ) && ! empty( $posts ) ) {
				foreach ( $posts as $post ) {
					$options[$post->ID] = $post->post_title;
				}
			}

		break;

		case 'categories':
		case 'category':
			$categories = get_categories( $query_args );

			if ( ! is_wp_error( $categories ) && ! empty( $categories ) && ! isset( $categories['errors'] ) ) {
				foreach ( $categories as $category ) {
					$options[$category->term_id] = $category->name;
				}
			}
		break;

		case 'tags':
		case 'tag':
			$taxonomies = ( isset( $query_args['taxonomies'] ) ) ? $query_args['taxonomies'] : 'post_tag';
			$tags = get_terms( $taxonomies, $query_args );

			if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$options[$tag->term_id] = $tag->name;
				}
			}
		break;

		case 'menus':
		case 'menu':
			$menus = wp_get_nav_menus( $query_args );

			if ( ! is_wp_error( $menus ) && ! empty( $menus ) ) {
				foreach ( $menus as $menu ) {
					$options[$menu->term_id] = $menu->name;
				}
			}
		break;

		case 'post_types':
		case 'post_type':
			$query_args = ($query_args) ? $query_args : array('show_in_nav_menus' => true);

			$post_types = get_post_types($query_args,'objects');

			if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					$options[$post_type->name] = ucfirst($post_type->label);
				}
			}
		break;

		case 'cpt':
		case 'cpts':
			$query_args = ($query_args) ? $query_args : array('public'   => true,'_builtin' => false);

			$post_types = get_post_types($query_args,'objects');

			if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
				foreach ( $post_types as $post_type ) {
					$options[$post_type->name] = ucfirst($post_type->label);
				}
			}
		break;


		case 'sidebar':
		case 'sidebars':
			global $wp_registered_sidebars;
			if( ! empty( $wp_registered_sidebars ) ) {
				foreach( $wp_registered_sidebars as $sidebar ) {
					$options[$sidebar['id']] = $sidebar['name'];
				}
			}
		break;
	  
		case 'role':
        case 'roles':
		case 'user_role':
		case 'user_roles':
			global $wp_roles;
			if( is_object( $wp_roles ) ) {
				$roles 	= $wp_roles->roles;
				$options = array();
				if( ! empty( $wp_roles ) ) {
					foreach($roles as $key => $value){
						$options[$key] = $value['name'];
					}
				}
			}
		break;

		case 'user':
        case 'users':

          if( ! empty( $term ) ) {

            $query      = new WP_User_Query( array(
              'search'  => '*'. $term .'*',
              'number'  => 25,
              'orderby' => 'title',
              'order'   => 'ASC',
              'fields'  => array( 'display_name', 'ID' )
            ) );

          } else {

            $query = new WP_User_Query( array( 'fields' => array( 'display_name', 'ID' ) ) );

          }

          if( ! is_wp_error( $query ) && ! empty( $query->get_results() ) ) {
            foreach( $query->get_results() as $item ) {
              $options[$item->ID] = $item->display_name;
            }
          }

        break;

        case 'sidebar':
        case 'sidebars':

          global $wp_registered_sidebars;

          if( ! empty( $wp_registered_sidebars ) ) {
            foreach( $wp_registered_sidebars as $sidebar ) {
              $options[$sidebar['id']] = $sidebar['name'];
            }
          }

          $array_search = true;

        break;

		case 'custom':
		case 'callback':
			if( is_callable( $query_args['function'] ) ) {
				$options = call_user_func( $query_args['function'], $query_args['args'] );
			}
		break;

		default:

          if( function_exists( $type ) ) {
            if( ! empty( $term ) ) {
              $options = call_user_func( $type, $query_args );
            } else {
              $options = call_user_func( $type, $term, $query_args );
            }
          }

        break;
    }

    return $options;
  }
  public function element_wp_query_data_title( $type, $values ) {

	$options = array();

	if( ! empty( $values ) && is_array( $values ) ) {

	  foreach( $values as $value ) {

		switch( $type ) {

		  case 'post':
		  case 'posts':
		  case 'page':
		  case 'pages':

			$title = get_the_title( $value );

			if( ! is_wp_error( $title ) && ! empty( $title ) ) {
			  $options[$value] = $title;
			}

		  break;

		  case 'category':
		  case 'categories':
		  case 'tag':
		  case 'tags':
		  case 'menu':
		  case 'menus':

			$term = get_term( $value );

			if( ! is_wp_error( $term ) && ! empty( $term ) ) {
			  $options[$value] = $term->name;
			}

		  break;

		  case 'user':
		  case 'users':

			$user = get_user_by( 'id', $value );

			if( ! is_wp_error( $user ) && ! empty( $user ) ) {
			  $options[$value] = $user->display_name;
			}

		  break;

		  case 'sidebar':
		  case 'sidebars':

			global $wp_registered_sidebars;

			if( ! empty( $wp_registered_sidebars[$value] ) ) {
			  $options[$value] = $wp_registered_sidebars[$value]['name'];
			}

		  break;

		  case 'role':
		  case 'roles':

			global $wp_roles;

			if( ! empty( $wp_roles ) && ! empty( $wp_roles->roles ) && ! empty( $wp_roles->roles[$value] ) ) {
			  $options[$value] = $wp_roles->roles[$value]['name'];
			}

		  break;

		  case 'post_type':
		  case 'post_types':

			  $post_types = get_post_types( array( 'show_in_nav_menus' => true ) );

			  if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) && ! empty( $post_types[$value] ) ) {
				$options[$value] = ucfirst( $value );
			  }

		  break;

		  default:

			if( function_exists( $type .'_title' ) ) {
			  $options[$value] = call_user_func( $type .'_title', $value );
			} else {
			  $options[$value] = ucfist( $value );
			}

		  break;

		}

	  }

	}

	return $options;

  }

  public function checked( $helper = '', $current = '', $type = 'checked', $echo = false ) {

    if ( is_array( $helper ) && in_array( $current, $helper ) ) {
      $result = ' '. $type .'="'. $type .'"';
    } else if ( $helper == $current ) {
      $result = ' '. $type .'="'. $type .'"';
    } else {
      $result = '';
    }

    if ( $echo ) {
      echo $result;
    }

    return $result;

  }

  public function element_multilang() {
    return ( isset( $this->field['multilang'] ) ) ? cssf_language_defaults() : false;
  }







  protected function get_defaults() {
		return wp_parse_args( $this->field_defaults(), array(
			'id'          => '',
			'title'       => null,
			'type'        => null,
			'desc'        => null,
			'default'     => false,
			'help'        => false,
			'class'       => '',
			'wrap_class'  => '',
			'dependency'  => false,
			'before'      => null,
			'after'       => null,
			'attributes'  => array(),
			'only_field'  => false,
			'settings'    => array(),
			'label_type'  => 'left',
		) );
  }
  
  protected function field_defaults() {
		return array();
  }


  /**
	 * Converts Array into HTML Attribute String
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public function array_to_html_attrs( $attributes ) {
		$atts = '';
		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $key => $value ) {
				if ( 'only-key' === $value ) {
					$atts .= ' ' . esc_attr( $key );
				} else {
					$atts .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
				}
			}
		}
		return $atts;
	}

  /**
	 * outputs JS settings HTML
	 *
	 * @return null|string
	 */
	public function element_js_settings() {
		return $this->js_settings;
  }
  
  	/**
	 * @param       $field_id
	 * @param array $default
	 *
	 * @return array|string
	 */
	public function _unarray_values( $field_id, $default = array() ) {
		if ( cssf_is_unarray_field( $this->field['type'] ) ) {
			if ( true === $this->field['un_array'] ) {
				if ( isset( $this->value[ $field_id ] ) ) {
					return $this->value[ $field_id ];
				} else {
					return $default;
				}
			} else {
				return ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : ( isset( $default[ $field_id ] ) ? $default[ $field_id ] : false );
			}
		}
		return ( empty( $this->value ) ) ? $default : $this->value;
  }
  

  /**
	 * @param $option
	 * @param $key
   * 
   * Usado en Checkbox y Radio
	 *
	 * @return array
	 */
	public function element_handle_option( $option, $key ) {
		if ( ! is_array( $option ) ) {
			$option = array(
				'label' => $option,
				'key'   => $key,
			);
		}

		$defaults = array(
			'label'      => '',
			'key'        => '',
			'attributes' => array(),
			'disabled'   => '',
			'icon'       => '',
		);

		$option = wp_parse_args( $option, $defaults );

		if ( true === $option['disabled'] ) {
			$option['attributes']['disabled'] = 'disabled';
		}

		if ( '' === $option['key'] ) {
			$option['key'] = $key;
		}

		return array(
			'id'         => $option['key'],
			'value'      => $option['label'],
			'attributes' => $option['attributes'],
			'icon'       => $option['icon'],
		);
  }
  


  /**
	 * Checks For Select Class And Returns IT.
	 *
	 * @return string
	 */
	public function select_style() {
		if ( ( isset( $this->field['select2'] ) && true === $this->field['select2'] ) || false !== strpos( $this->field['class'], 'select2' ) ) {
			return ( is_rtl() ) ? ' select2 select2-rtl' : 'select2';
		} elseif ( ( isset( $this->field['chosen'] ) && true === $this->field['chosen'] ) || false !== strpos( $this->field['class'], 'chosen' ) ) {
			return ( is_rtl() ) ? ' chosen chosen-rtl' : 'chosen';
		} elseif ( ( isset( $this->field['selectize'] ) && true === $this->field['selectize'] ) || false !== strpos( $this->field['class'], 'selectize' ) ) {
			return 'selectize';
		}
	}





  /**
	 * @param array  $field
	 * @param string $value
	 * @param string $unique
	 *
	 * @return string
	 */
	public function add_field( $field = array(), $value = '', $unique = '' ) {
		$field['uid'] = $this->uid;
		return cssf_add_element( $field, $value, $unique );
	}

	public function final_output() {
		if ( 'hidden' === $this->element_type() ) {
			echo $this->output();
		} else {
			if ( isset( $this->field['only_field'] ) && true === $this->field['only_field'] ) {
				echo $this->output();
			} else {
				echo $this->element_wrapper();
				echo $this->output();
				echo $this->element_wrapper( false );
			}
		}
  }
  
  	/**
	 * @return mixed
	 */
	abstract public function output();

	/**
	 * @param bool $is_start
	 */
	public function element_wrapper( $is_start = true ) {
		if ( true === $is_start ) {
			$this->row_after 	= '';
			$sub             	= ( isset( $this->field['sub'] ) ) ? 'sub-' : '';
			$languages       	= cssf_language_defaults();
			$raw_type 			= ($this->element_type() !== $this->element_raw_type()) ? ' cssf-field-' . $this->element_raw_type() : null;

			$wrap_class      	= 'cssf-element cssf-element-' . $this->element_type() . ' cssf-field-' . $this->element_type() . $raw_type . ' ';
			$wrap_class 		.= ( ! empty( $this->field['id'] ) ) ? 'cssf-field-id_' . sanitize_title( $this->field ['id'] ) : '';
			$wrap_class 		.= ( ! empty( $this->field['wrap_class'] ) ) ? ' ' . $this->field['wrap_class'] : '';
			$wrap_class 		.= ( ! empty( $this->field['title'] ) ) ? ' cssf-element-' . sanitize_title( $this->field ['title'] ) : ' cssf-field-no-title ';
			$wrap_class 		.= ( isset( $this->field ['pseudo'] ) ) ? ' cssf-pseudo-field' : '';

			$is_hidden = ( isset( $this->field ['show_only_language'] ) && ( $this->field ['show_only_language'] != $languages ['current'] ) ) ? ' hidden ' : '';

			$wrap_attr = ( isset( $this->field['wrap_attributes'] ) && is_array( $this->field['wrap_attributes'] ) ) ? $this->field['wrap_attributes'] : array();
			if ( is_array( $this->field['dependency'] ) && false !== $this->field['dependency'] ) {
				$is_hidden                                  = ' hidden';
				$wrap_attr[ 'data-' . $sub . 'controller' ] = $this->field ['dependency'] [0];
				$wrap_attr[ 'data-' . $sub . 'condition' ]  = $this->field ['dependency'] [1];
				$wrap_attr[ 'data-' . $sub . 'value' ]      = $this->field ['dependency'] [2];
			}
			$wrap_attr = $this->array_to_html_attrs( $wrap_attr );

			if ( isset( $this->field['columns'] ) ) {
				$wrap_class .= ' cssf-column cssf-column-' . $this->field['columns'] . ' ';

				if ( 0 == self::$total_cols ) {
					$wrap_class .= ' cssf-column-first ';
					echo '<div class="cssf-element cssf-row">';
				}

				self::$total_cols += $this->field['columns'];

				if ( 12 == self::$total_cols ) {
					$wrap_class .= ' cssf-column-last ';

					$this->row_after  = '</div>';
					self::$total_cols = 0;
				}
      		}
			if(isset($this->field['label_type']) && ($this->field['label_type'] == 'top')){
				$label_type = 'top';
				$wrap_class .= " cssf-element-label--{$label_type}";
			}
			
			$wrap_class .= ' ' . $is_hidden;
			echo '<div class="' . $wrap_class . '" ' . $wrap_attr . ' >';
			$this->element_title();
			echo $this->element_title_before();
		} else {
			echo $this->element_title_after();
			echo '<div class="clear"></div>';
			echo '</div>';
			echo $this->row_after;
		}
  	}
  
  	public function element_title() {
		if (true === isset( $this->field['title'])){
			if (!empty( $this->field ['title'])){
				echo '<div class="cssf-title"><h4>' . $this->field ['title'] . '</h4>' . $this->element_subtitle() . ' ' . $this->element_help() . '</div>';
			}
		}
  	}

  /**
	 * @return string
	 */
	public function element_title_before() {
		return ( isset( $this->field ['title'] ) && ! empty( $this->field ['title'] ) ) ? '<div class="cssf-fieldset">' : '';
	}

	/**
	 * @return string
	 */
	public function element_title_after() {
		return ( isset( $this->field ['title'] ) && ! empty( $this->field ['title'] ) ) ? '</div>' : '';
  }
  
  /**
	 * @return string
	 */
	public function element_subtitle() {
		return ( isset( $this->field['subtitle'] ) ) ? '<div class="cssf-subtitle">' . $this->field['subtitle'] . '</div>' : '';
	}

	public function element_text_limit() {
		$return      = '';
		$is_in_array = in_array( $this->field['type'], array( 'text', 'textarea' ) );
		if ( true === $is_in_array && ( isset( $this->field['limit'] ) && $this->field['limit'] > 0 ) ) {
			if ( $this->field['limit'] > 0 ) {
				$type = isset( $this->field['limit_type'] ) ? $this->field['limit_type'] : 'character';
				$text = 'word' === $type ? esc_attr__( 'Word Count', 'text-limiter' ) : esc_attr__( 'Character Count', 'text-limiter' );
				return '<div class="text-limiter" data-limit-type="' . esc_attr( $type ) . '"> <span>' . esc_html( $text ) . ': <span class="counter">0</span>/<span class="maximum">' . esc_html( $this->field['limit'] ) . '</span></span></div>';
			}
		}
		return $return;
	}

	public function element_desc_before() {
		return ( isset( $this->field['desc'] ) ) ? '<p class="cssf-text-field-desc">' . $this->field['desc'] . '</p>' : '';
	}
	
	public function element_info(){
		return ( isset( $this->field['info'] ) ) ? '<div class="cssf-text-field-info">'. $this->field['info'] .'</div>' : '';
	}




}

// load all of fields
// cssf_load_option_fields();