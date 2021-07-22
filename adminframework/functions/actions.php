<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_get_icons' ) ) {
	function cssf_get_icons() {

		do_action( 'cssf_add_icons_before' );

		// $jsons = apply_filters('cssf_add_icons_json', glob( CSSF_DIR . '/fields/icon/*.json' ));
		$jsons = apply_filters('cssf_add_icons_json', glob( CSSF_DIR . '/assets/icons/*.json' ));

		if( ! empty( $jsons ) ) {

			foreach ( $jsons as $path ) {

				$object = cssf_get_icon_fonts( 'assets/icons/'. basename( $path ) );

				if( is_object( $object ) ) {

					echo ( count( $jsons ) >= 2 ) ? '<h4 class="cssf-icon-title">'. $object->name .'</h4>' : '';

					echo '<div class="cssf-icon-accordion-content">';
					foreach ( $object->icons as $icon ) {
						$value = "";
						if (is_object($icon)) { 
							$class 	= $icon->class;
							$icon 	= $icon->icon;
							echo '<a class="cssf-icon-tooltip" data-icon="'. $icon .'" data-title="'. $icon .'"><span class="cssf-icon cssf-selector"><i class="'. $class .'">'.$icon.'</i></span></a>';
						} else {
							echo '<a class="cssf-icon-tooltip" data-icon="'. $icon .'" data-title="'. $icon .'"><span class="cssf-icon cssf-selector"><i class="'. $icon .'"></i></span></a>';
						}
					}
					echo '</div>';

				} else {
					echo '<h4 class="cssf-icon-title">'. esc_attr__( 'Error! Can not load json file.', 'cssf-framework' ) .'</h4>';
				}

			}

		}

		do_action( 'cssf_add_icons' );
		do_action( 'cssf_add_icons_after' );

		die();
	}
	add_action( 'wp_ajax_cssf-get-icons', 'cssf_get_icons' );
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_export_options' ) ) {
	function cssf_export_options() {
		// Decode Options to export
		if (!empty($_GET['export'])){
			$options 			= explode(',',cssf_decode_string($_GET['export']));
			$to_export_data 	= array();
			foreach($options as $option){
				$to_export_data[$option] = get_option($option);
			}
			$to_export_data = cssf_encode_string( $to_export_data );
		}

		header('Content-Type: plain/text');
		header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');

		// echo cssf_encode_string( get_option( CSSF_OPTION ) );
		// $option_array = !empty( $_GET['option_array'] ) ? $_GET['option_array'] : CSSF_OPTION;

		// echo cssf_encode_string( get_option( $option_array ) );

		echo $to_export_data;

		die();
	}
	add_action( 'wp_ajax_cssf-export-options', 'cssf_export_options' );
}
/**
 *
 * Import options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_import_options' ) ) {
	function cssf_import_options() {
		// check the nonce
		if (check_ajax_referer( 'cssf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}


		// Decode Options to import
		if (!empty($_POST['options'])){
			$options 			= cssf_decode_string($_POST['options']);

			if (!is_array($options)){
				wp_send_json_error(esc_attr__('Not valid backup data','cssf-framework'));
			}

			foreach ($options as $option_name => $value){
				update_option($option_name,$value);
			}
			
			// AJAX Response
			$response = array(
				'code'		=> 'ok',
				'message'	=> esc_attr__('Successfully imported backup','cssf-framework'),
			);
			wp_send_json_success($response);
		} else {
			wp_send_json_error(esc_attr__('Empty import data','cssf-framework'));
		}


		die();
	}
	add_action( 'wp_ajax_cssf-import-options', 'cssf_import_options' );
}





/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_set_icons' ) ) {
	function cssf_set_icons() {

		echo '<div id="cssf-icon-dialog" class="cssf-dialog hidden" title="'. esc_attr__( 'Add Icon', 'cssf-framework' ) .'">';
		echo '<div class="cssf-dialog-header cssf-text-center"><input type="text" placeholder="'. esc_attr__( 'Search a Icon...', 'cssf-framework' ) .'" class="cssf-icon-search" /></div>';
		echo '<div class="cssf-dialog-load"><div class="cssf-loading-indicator"><div class="cssf-spinner"></div>'. esc_attr__( 'Loading...', 'cssf-framework' ) .'</div></div>';
		echo '</div>';

	}
	add_action( 'admin_footer', 'cssf_set_icons' );
	add_action( 'customize_controls_print_footer_scripts', 'cssf_set_icons' );
}

















/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if (!function_exists('cssf_get_images')){
	function cssf_get_images(){
		$_path 			= cssf_decode_string($_POST['path']);
		$_uri 			= cssf_decode_string($_POST['uri']);
		$_images_path 	= $_POST['images_path'];

		$images_path 	= $_path . $_images_path;
		$images_uri	 	= $_uri . $_images_path;

		do_action( 'cssf_add_images_before' );

		// $images = apply_filters('cssf_add_image_gallery_custom', glob( CSSF_DIR . '/assets/images/images_gallery/*.*' ));
		$images = apply_filters('cssf_add_image_gallery_custom', glob( $images_path .'full/*.{jpg,jpeg,png,gif}', GLOB_BRACE ));

		if (!empty($images)){
			foreach ($images as $image){
				$image_info 		= pathinfo($image);
				$_image_name 		= $image_info['filename'];
				$_image_basename 	= $image_info['basename'];
				$_full_uri 			= "{$images_uri}full/{$_image_basename}";
				$_thumb_uri 		= "{$images_uri}thumbs/{$_image_basename}";

				echo "
					<a class='cssf-image-tooltip' data-image-uri='{$_thumb_uri}' data-image='{$_image_basename}' data-title='{$_image_name}'>
						<span class='cssf-image cssf-selector'><img src='{$_thumb_uri}'></span>
					</a>
				";
			}
		} else {
			echo esc_attr__('No image was found in the specified directory.','plus_admin');
		}

		do_action( 'cssf_add_images' );
		do_action( 'cssf_add_images_after' );

		die();
	}
	add_action( 'wp_ajax_cssf-get-images', 'cssf_get_images' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'cssf_set_custom_image_gallery' ) ) {
	function cssf_set_custom_image_gallery() {
		echo '<div id="cssf-image-dialog" class="cssf-dialog hidden" title="'. esc_attr__( 'Add image', 'cssf-framework' ) .'">';
		echo '<div class="cssf-dialog-header cssf-text-center"><input type="text" placeholder="'. esc_attr__( 'Search a image...', 'cssf-framework' ) .'" class="cssf-image-search" /></div>';
		echo '<div class="cssf-dialog-load"><div class="cssf-loading-indicator"><div class="cssf-spinner"></div>'. esc_attr__( 'Loading...', 'cssf-framework' ) .'</div></div>';
		echo '</div>';

	}
	add_action( 'admin_footer', 'cssf_set_custom_image_gallery' );
	add_action( 'customize_controls_print_footer_scripts', 'cssf_set_custom_image_gallery' );
}








/**
 * 
 * Field: Color Theme
 * 
 * @version 1.0
 * 
 */
if (!function_exists('cssf_color_theme_save_scheme_callback')){
	function cssf_color_theme_save_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'cssf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}
	
		// Request Vars
		$options_unique 	= $_POST['options_unique'];
		$path 				= $_POST['field_unique'];
		$new_scheme 		= $_POST['scheme'];
		$scheme_name 		= str_replace(' ','_',sanitize_title($new_scheme['name']));
		$scheme_scheme 		= $new_scheme['scheme'];

		$settings 			= get_option($options_unique);
		$path_to_schemes 	= $path . "[custom_schemes]";
		$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove cssframework unique id
		$custom_schemes = cssf_arrayValueFromKeys($settings, $_path);

		// Check if JSON or already saved by the framework "save" button
		if (cssf_isJSON($custom_schemes)){
			$custom_schemes = json_decode($custom_schemes,true);
		}

		// Add New Scheme
		if (!isset($custom_schemes[$scheme_name])){
			$custom_schemes[$scheme_name] = array(
				'name' 		=> $scheme_name,
				'scheme' 	=> $scheme_scheme
			);
		} else {
			wp_send_json_error('Already added');
		}

		// Save New Scheme
		cssf_arrayValueFromKeys($settings,$_path,$custom_schemes);
		update_option($options_unique,$settings);

		// AJAX Response
		$response = array(
			'message'	=> 'Added',
			'schemes'	=> json_encode($custom_schemes)
		);
		wp_send_json_success($response);

		die();
	}
	add_action('wp_ajax_cssf-color-scheme_save', 'cssf_color_theme_save_scheme_callback');
}

if (!function_exists('cssf_color_theme_delete_scheme_callback')){
	function cssf_color_theme_delete_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'cssf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}

		// Request Vars
		$options_unique 	= $_POST['options_unique'];
		$path 				= $_POST['field_unique'];
		$scheme 			= $_POST['scheme'];

		$settings 			= get_option($options_unique);
		$path_to_schemes 	= $path . "[custom_schemes]";
		$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove cssframework unique id
		$custom_schemes 	= cssf_arrayValueFromKeys($settings, $_path);

		// Check if JSON or already saved by the framework "save" button
		if (cssf_isJSON($custom_schemes)){
			$custom_schemes = json_decode($custom_schemes,true);
		}

		// Delete Scheme by ID
		if (isset($custom_schemes[$scheme])){
			unset($custom_schemes[$scheme]);
		} else {
			wp_send_json_error('Does not exist');
		}

		// Update Schemes Collection
		cssf_arrayValueFromKeys($settings,$_path,$custom_schemes);
		update_option($options_unique,$settings);

		// AJAX Response
		$response = array(
			'message'	=> 'Deleted',
			'schemes'	=> json_encode($custom_schemes)
		);
		wp_send_json_success($response);

		die();
	}
	add_action('wp_ajax_cssf-color-scheme_delete', 'cssf_color_theme_delete_scheme_callback');
}

if (!function_exists('cssf_color_theme_export_scheme_callback')){
	function cssf_color_theme_export_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'cssf-framework-nonce', 'nonce', false ) == false ) {
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
			wp_send_json_error();
		}

		// Request Vars
		$path 				= cssf_decode_string($_REQUEST['field_unique']);
		$path_to_schemes 	= $path . "[custom_schemes]";
		$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove cssframework unique id
		preg_match('/^[^\[]+/',$path_to_schemes,$_p);
		$options_unique 	= $_p[0];

		$settings 			= get_option($options_unique);
		$custom_schemes 	= cssf_arrayValueFromKeys($settings, $_path);

		if ($custom_schemes){
			header('Content-Type: plain/text');
			header('Content-disposition: attachment; filename=custom-color-schemes-'. gmdate( 'd-m-Y' ) .'.txt');
			header('Content-Transfer-Encoding: binary');
			header('Pragma: no-cache');
			header('Expires: 0');
			echo cssf_encode_string( $custom_schemes );
		} else {
			wp_die(esc_attr__('No color schemes to export','cssf-framework'));
		}


		die();
	}
	add_action('wp_ajax_cssf-color-scheme_export', 'cssf_color_theme_export_scheme_callback');
}

if (!function_exists('cssf_color_theme_import_scheme_callback')){
	function cssf_color_theme_import_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'cssf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}

		// Request Vars
		$options_unique 	= $_POST['options_unique'];
		$path 				= $_POST['field_unique'];
		$schemes_to_import	= cssf_decode_string($_POST['schemes']);
		$overwrite			= $_POST['overwrite'];

		if ($schemes_to_import){
			$settings 			= get_option($options_unique);
			$path_to_schemes 	= $path . "[custom_schemes]";
			$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove cssframework unique id
			$custom_schemes 	= cssf_arrayValueFromKeys($settings, $_path);

			// Check if JSON or already saved by the framework "save" button
			if (cssf_isJSON($schemes_to_import)){
				$schemes_to_import = json_decode($schemes_to_import,true);
			}
			if ($custom_schemes){
				if (cssf_isJSON($custom_schemes)){
					$custom_schemes = json_decode($custom_schemes,true);
				}
			} else {
				$custom_schemes = array();
			}


			// Overwrite or Rename Imported Schemes
			if ($overwrite === 'true'){
				$custom_schemes = $schemes_to_import;
			} else {
				// echo "Agregando al final y renombrando las que existen...";
				$new_schemes_to_import = array();

				foreach($schemes_to_import as $key => $scheme){
					$index = 1;
	
					rename:
					$index++;
					$_key = $key .'-'. $index;
					if (isset($custom_schemes[$_key])){
						goto rename;
					} else {
						$new_schemes_to_import[$_key] = $scheme;
					}
				}

				// Merge
				$custom_schemes = array_merge($custom_schemes,$new_schemes_to_import);
			}


			// Update Schemes Collection
			cssf_arrayValueFromKeys($settings,$_path,$custom_schemes);
			update_option($options_unique,$settings);
	
			// AJAX Response
			$response = array(
				'message'	=> 'Imported',
				'schemes'	=> json_encode($custom_schemes)
			);
			wp_send_json_success($response);
		} else {
			wp_send_json_error('Invalid');
		}


		die();
	}
	add_action('wp_ajax_cssf-color-scheme_import', 'cssf_color_theme_import_scheme_callback');
}


if (!function_exists('cssf_isJSON')){
	/**
	 * Check if object or string is a valid JSON object
	 *
	 * @param [type] $string
	 * @return void
	 */
	function cssf_isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}

if (!function_exists('cssf_arrayValueFromKeys')){
	/**
	 * Function to get the value of an array based on a string path
	 * Ex: [settings][parent_field][field][subfield][target]
	 *
	 * @param array $array
	 * @param [type] $keys
	 * @param boolean $value
	 * @return void
	 */
	function cssf_arrayValueFromKeys(&$array = array(), $keys, $value = false){
		$keys = explode('][', trim($keys, '[]'));
		$reference = &$array;
		foreach ($keys as $key) {
			if (!array_key_exists($key, $reference)) {
				$reference[$key] = [];
			}
			$reference = &$reference[$key];
		}
		if ($value === false){
			return $reference;
		} else {
			$reference = $value;
		}
	}
}