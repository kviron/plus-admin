<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Add framework element
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_add_element' ) ) {
	/**
	* Adds A CSSF Field & Renders it.
	*
	* @param array  $field
	* @param string $value
	* @param string $unique
	* @param bool   $force
	*
	* @return string
	*/
	function cssf_add_element( $field = array(), $value = '', $unique = '', $is_sub = false ) {
		$output = '';

		$value   = ( !isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
		$value   = ( isset( $field['value'] ) ) ? $field['value'] : $value;
		
		// if ( isset( $field['instance_id'] ) && false === $force ) {
		// 	$_instance = cssf_field_registry()->get( $field['instance_id'] );
		// 	if ( $_instance instanceof CSSFramework_Options ) {
		// 		ob_start();
		// 		$_instance->final_output();
		// 		return ob_get_clean();
		// 	}
		// 	return cssf_add_element( $field, $value, $unique, true );
		// } else {
			$class = 'CSSFramework_Option_' . $field ['type'];
			if ( isset( $field['clone'] ) && true === $field['clone'] ) {
				$class = 'CSSFramework_Field_Cloner';
			}
			cssf_autoloader( $class );
			if ( class_exists( $class ) ) {
				ob_start();
				$element = new $class( $field, $value, $unique, $is_sub );
				$element->final_output();
				$output .= ob_get_clean();
			} else {
				$output .= '<p>' . sprintf( esc_attr__( 'This field class is not available! %s', 'cssf-framework' ), '<strong>' . $class . '</strong>' ) . ' </p > ';
			}
		// }
		return $output;
	}
}
if ( ! function_exists( 'cssf_unarray_fields' ) ) {
	/**
	* Returns all field types that can be unarrayed.
	*
	* @return array
	*/
	function cssf_unarray_fields() {
		return apply_filters( 'cssf_unarray_fields_types', array( 'tab', 'group', 'fieldset', 'accordion' ) );
	}
}
if ( ! function_exists( 'cssf_is_unarray_field' ) ) {
	/**
	* Checks if field type is unarray.
	*
	* @param mixed $type .
	*
	* @return bool
	*/
	function cssf_is_unarray_field( $type ) {
		if ( is_array( $type ) && isset( $type['clone'] ) && true === $type['clone'] ) {
			return true;
		} elseif ( is_array( $type ) && isset( $type['type'] ) ) {
			return in_array( $type['type'], cssf_unarray_fields() );
		}
		return in_array( $type, cssf_unarray_fields() );
	}
}
if ( ! function_exists( 'cssf_is_unarrayed' ) ) {
	/**
	* Checks if field is unarray.
	*
	* @param mixed $field .
	*
	* @return bool
	*/
	function cssf_is_unarrayed( $field = array() ) {
		if ( cssf_is_unarray_field( $field ) ) {
			if ( isset( $field['un_array'] ) && true === $field['un_array'] ) {
				return true;
			}
		}
		return false;
	}
}


/**
*
* Encode string for backup options
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_encode_string' ) ) {
	function cssf_encode_string( $string ) {
		return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( $string ), 9 ) ) ), '+/', '-_' ), '=' );
	}
}

/**
*
* Decode string for backup options
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_decode_string' ) ) {
	function cssf_decode_string( $string ) {
		return unserialize( gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $string, '-_', '+/' ), '=' ) ) ) ) );
	}
}

/**
*
* Get google font from json file
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_get_google_fonts' ) ) {
	function cssf_get_google_fonts() {
		
		global $cssf_google_fonts;
		
		if( ! empty( $cssf_google_fonts ) ) {
			
			return $cssf_google_fonts;
			
		} else {
			
			ob_start();
			cssf_locate_template( 'fields/typography/google-fonts.json' );
			$json = ob_get_clean();
			
			$cssf_google_fonts = json_decode( $json );
			
			return $cssf_google_fonts;
		}
		
	}
}

/**
*
* Get icon fonts from json file
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_get_icon_fonts' ) ) {
	function cssf_get_icon_fonts( $file ) {
		
		ob_start();
		cssf_locate_template( $file );
		$json = ob_get_clean();
		
		return json_decode( $json );
		
	}
}

/**
*
* Array search key & value
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_array_search' ) ) {
	function cssf_array_search( $array, $key, $value ) {
		
		$results = array();
		
		if ( is_array( $array ) ) {
			if ( isset( $array[$key] ) && $array[$key] == $value ) {
				$results[] = $array;
			}
			
			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, cssf_array_search( $sub_array, $key, $value ) );
			}
			
		}
		
		return $results;
		
	}
}


/**
* cssf_multi_array_search
* 
* This function search a multidimensional array and return the requested $key value
* Only returns the first key value
* 
* @date 14/12/2018
* @since 2.0.0
*/
if (!function_exists('cssf_search_multi_array')){
	function cssf_search_multi_array( array $array, $key ){
		while( $array ) {
			if ( isset( $array[ $key ] ) ) { 
				return $array[ $key ]; 
			}
			$segment = array_shift( $array );
			if( is_array( $segment ) ) {
				if( $return = cssf_search_multi_array( $segment, $key ) ) {
					return $return;
				}
			}
		}
		return false;
	}
}

/**
*
* Getting POST Var
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_get_var' ) ) {
	function cssf_get_var( $var, $default = '' ) {
		
		if( isset( $_POST[$var] ) ) {
			return $_POST[$var];
		}
		
		if( isset( $_GET[$var] ) ) {
			return $_GET[$var];
		}
		
		return $default;
		
	}
}

/**
*
* Getting POST Vars
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_get_vars' ) ) {
	function cssf_get_vars( $var, $depth, $default = '' ) {
		
		if( isset( $_POST[$var][$depth] ) ) {
			return $_POST[$var][$depth];
		}
		
		if( isset( $_GET[$var][$depth] ) ) {
			return $_GET[$var][$depth];
		}
		
		return $default;
		
	}
}

if ( ! function_exists( 'cssf_js_vars' ) ) {
	/**
	* Converts PHP Array into JS JSON String with script tag and returns it.
	*
	* @param      $object_name
	* @param      $l10n
	* @param bool $with_script_tag
	*
	* @return string
	*/
	function cssf_js_vars( $object_name = '', $l10n, $with_script_tag = true ) {
		foreach ( (array) $l10n as $key => $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}
			$l10n[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}
		$script = null;
		if ( ! empty( $object_name ) ) {
			$script = "var $object_name = " . wp_json_encode( $l10n ) . ';';
		} else {
			$script = wp_json_encode( $l10n );
		}
		if ( ! empty( $after ) ) {
			$script .= "\n$after;";
		}
		if ( $with_script_tag ) {
			return '<script type="text/javascript" >' . $script . '</script>';
		}
		return $script;
	}
}

/**
* ERROR Handler
*/
global $cssf_errors;
$cssf_errors = array();
if ( ! function_exists( 'cssf_add_errors' ) ) {
	/**
	* Adds Error to global $cssf_error array.
	*
	* @param $errs
	*
	*/
	function cssf_add_errors( $errs ) {
		global $cssf_errors;
		if ( is_array( $cssf_errors ) && is_array( $errs ) ) {
			$cssf_errors = array_merge( $cssf_errors, $errs );
		} else {
			$cssf_errors = $errs;
		}
	}
}

if ( ! function_exists( 'cssf_get_errors' ) ) {
	/**
	* Returns gloabl $cssf_errors.
	*
	* @return array
	*/
	function cssf_get_errors() {
		global $cssf_errors;
		return $cssf_errors;
	}
}








if ( ! function_exists( 'cssf_modern_navs' ) ) {
	/**
	* Renders Modern Theme Menu
	*
	* @param      $navs
	* @param      $class
	* @param null $parent
	*/
	function cssf_modern_navs( $navs, $class, $parent = null ) {
		$parent = ( null === $parent ) ? '' : 'data-parent-section="' . $parent . '"';
		foreach ( $navs as $i => $nav ) :
			$title = ( isset( $nav['title'] ) ) ? $nav['title'] : '';
			$href  = ( isset( $nav['href'] ) && false !== $nav['href'] ) ? $nav['href'] : '#';
			if ( ! empty( $nav['submenus'] ) ) {
				$is_active    = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' style="display: block;"' : '';
				$is_active_li = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' cssf-tab-active ' : '';
				echo '<li class="cssf-sub ' . $is_active_li . '">';
				echo '<a href="#" class="cssf-arrow">' . $class->icon( $nav ) . ' ' . $title . '</a>';
				echo '<ul ' . $is_active . '>';
				cssf_modern_navs( $nav['submenus'], $class, $nav['name'] );
				echo '</ul>';
				echo '</li>';
			} else {
				if ( isset( $nav['is_separator'] ) && true === $nav['is_separator'] ) {
					echo '<li><div class="cssf-seperator">' . $class->icon( $nav ) . ' ' . $title . '</div></li>';
				} else {
					$is_active = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? "class='cssf-section-active'" : '';
					echo '<li>';
					echo '<a ' . $is_active . ' href="' . $href . '" ' . $parent . ' data-section="' . $nav['name'] . '">' . $class->icon( $nav ) . ' ' . $title . '</a>';
					echo '</li>';
				}
			}
		endforeach;
	}
}
if ( ! function_exists( 'cssf_simple_render_submenus' ) ) {
	/**
	* @param array $menus
	* @param null  $parent_name
	* @param array $class
	*/
	function cssf_simple_render_submenus( $menus = array(), $parent_name = null, $class = array() ) {
		global $cssf_submenus;
		$return = array();
		$first  = current( $menus );
		$first  = isset( $first['name'] ) ? $first['name'] : false;
		foreach ( $menus as $nav ) {
			if ( isset( $nav['is_separator'] ) && true === $nav['is_separator'] ) {
				continue;
			}
			$title     = ( isset( $nav['title'] ) ) ? $nav['title'] : '';
			$is_active = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' current ' : '';
			if ( empty( $is_active ) ) {
				$is_active = ( $parent_name !== $class->active() && $first === $nav['name'] ) ? 'current' : $is_active;
			}
			$href = '#';
			if ( isset( $nav['href'] ) && ( false !== $nav['href'] && '#' !== $nav['href'] && true !== $nav['is_internal_url'] ) ) {
				$href = $nav['href'];
				$is_active .= ' has-link ';
			}
			if ( isset( $nav['query_args'] ) && is_array( $nav['query_args'] ) ) {
				$url  = remove_query_arg( array_keys( $nav['query_args'] ) );
				$href = add_query_arg( array_filter( $nav['query_args'] ), $url );
				$is_active .= ' has-link ';
			}
			$icon     = $class->icon( $nav );
			$return[] = '<li> <a href="' . $href . '" class="' . $is_active . '" data-parent-section="' . $parent_name . '" data-section="' . $nav['name'] . '">' . $icon . ' ' . $title . '</a>';
		}
		$cssf_submenus[ $parent_name ] = implode( '|</li>', $return );
	}
}





/*
*  cssf_get_attachment
*
*  This function will return an array of attachment data
*
*  @type	function
*  @date	14/12/2018
*  @since	2.0.0
*
*  @param	$post (mixed) either post ID or post object
*  @return	(array)
*/

if (!function_exists('cssf_get_attachment')) {
	function cssf_get_attachment( $attachment ) {
		
		// get post
		if( !$attachment = get_post($attachment) ) {
			return false;
		}
		
		// validate post_type
		if( $attachment->post_type !== 'attachment' ) {
			return false;
		}
		
		// vars
		$sizes_id = 0;
		$meta = wp_get_attachment_metadata( $attachment->ID );
		$attached_file = get_attached_file( $attachment->ID );
		$attachment_url = wp_get_attachment_url( $attachment->ID );
		
		// get mime types
		if( strpos( $attachment->post_mime_type, '/' ) !== false ) {
			list( $type, $subtype ) = explode( '/', $attachment->post_mime_type );
		} else {
			list( $type, $subtype ) = array( $attachment->post_mime_type, '' );
		}
		
		// vars
		$response = array(
			'ID'						=> $attachment->ID,
			'id'						=> $attachment->ID,
			'title'       				=> $attachment->post_title,
			'filename'					=> wp_basename( $attached_file ),
			'filesize'					=> 0,
			'filesize_humanreadable'	=> 0,
			'url'						=> $attachment_url,
			'link'						=> get_attachment_link( $attachment->ID ),
			'alt'						=> get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'author'					=> $attachment->post_author,
			'description'				=> $attachment->post_content,
			'caption'					=> $attachment->post_excerpt,
			'name'						=> $attachment->post_name,
			'status'					=> $attachment->post_status,
			'uploaded_to'				=> $attachment->post_parent,
			'date'						=> $attachment->post_date_gmt,
			'modified'					=> $attachment->post_modified_gmt,
			'menu_order'				=> $attachment->menu_order,
			'mime_type'					=> $attachment->post_mime_type,
			'type'						=> $type,
			'subtype'					=> $subtype,
			'icon'						=> wp_mime_type_icon( $attachment->ID )
		);
		
		// filesize
		if( isset($meta['filesize']) ) {
			$response['filesize'] = $meta['filesize'];
		} elseif( file_exists($attached_file) ) {
			$response['filesize'] = filesize( $attached_file );
		}
		$response['filesize_humanreadable']	= cssf_human_filesize($response['filesize']);
		
		// image
		if( $type === 'image' ) {
			
			$sizes_id = $attachment->ID;
			$src = wp_get_attachment_image_src( $attachment->ID, 'full' );
			
			$response['url'] = $src[0];
			$response['width'] = $src[1];
			$response['height'] = $src[2];
			
			// video
		} elseif( $type === 'video' ) {
			
			// dimentions
			$response['width'] = acf_maybe_get($meta, 'width', 0);
			$response['height'] = acf_maybe_get($meta, 'height', 0);
			
			// featured image
			if( $featured_id = get_post_thumbnail_id($attachment->ID) ) {
				$sizes_id = $featured_id;
			}
			
			// audio
		} elseif( $type === 'audio' ) {
			
			// featured image
			if( $featured_id = get_post_thumbnail_id($attachment->ID) ) {
				$sizes_id = $featured_id;
			}				
		}
		
		
		// sizes
		if( $sizes_id ) {
			
			// vars
			$sizes = get_intermediate_image_sizes();
			$data = array();
			
			// loop
			foreach( $sizes as $size ) {
				$src = wp_get_attachment_image_src( $sizes_id, $size );
				$data[ $size ] = $src[0];
				$data[ $size . '-width' ] = $src[1];
				$data[ $size . '-height' ] = $src[2];
			}
			
			// append
			$response['sizes'] = $data;
		}
		
		// return
		return $response;
		
	}
}



/**
* Human Filesize
*/

if (!function_exists('cssf_human_filesize')) {
	function cssf_human_filesize($bytes) {
		$i = floor(log($bytes, 1024));
		return round($bytes / pow(1024, $i), [0,0,2,2,3][$i]).['B','kB','MB','GB','TB'][$i];
	}
}
















/**
*
* Load options fields
*
* @since 1.0.0
* @version 1.0.0
*
*/
if ( ! function_exists( 'cssf_load_option_fields' ) ) {
	function cssf_load_option_fields() {
		$located_fields = array();
		foreach ( glob( CSSF_DIR .'/fields/*/*.php' ) as $cs_field ) {
			$located_fields[] = basename( $cs_field );
			cssf_locate_template( str_replace(  CSSF_DIR, '', $cs_field ) );
		}
		$override_name = apply_filters( 'cssf_framework_override', 'cssf-framework-override' );
		$override_dir  = get_template_directory() .'/'. $override_name .'/fields';
		if( is_dir( $override_dir ) ) {
			foreach ( glob( $override_dir .'/*/*.php' ) as $override_field ) {
				if( ! in_array( basename( $override_field ), $located_fields ) ) {
					cssf_locate_template( str_replace( $override_dir, '/fields', $override_field ) );
				}
			}
		}
		do_action('cssf_load_option_fields');
	}
}