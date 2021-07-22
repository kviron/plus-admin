<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: OEmbed
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Option_oembed extends CSSFramework_Options {
	
	public function __construct( $field = '', $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	static function init(){
		/**
		 * Register Hooks
		 */
		// Register AJAX handler
		add_action( 'wp_ajax_cssf-oembed-handler', array( 'CSSFramework_Option_oembed', 'oembed_handler' ) );

		// Register Field Assets - Styles and Scripts
		add_filter('cssf_register_framework_assets',array( 'CSSFramework_Option_oembed', 'register_assets' ),10);
	}

	static public function register_assets($styles_scripts){
		$styles = $styles_scripts[0];
		$scripts = $styles_scripts[1];

		$url = CSSF_URI . '/fields/oembed/';

		$styles['cssf-field-oembed'] = array(
			$url . 'css/styles.css',
			array(),
			'1.0.0',
			false,
		);

		$scripts['cssf-field-oembed'] = array(
			$url . 'js/scripts.js',
			array( 'cssf-plugins' ),
			'1.0.0',
			false,
		);

		return array($styles,$scripts);
	}
	
	public function oembed_handler(){
		// check the nonce
		if (check_ajax_referer( 'cssf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}
		// grab and escape args
		$args = array(
			'width' 	=> intval($_POST['width']),
			'height' 	=> intval($_POST['height'])
		);

		// generate the oEmbed
		$embed = wp_oembed_get($_POST['oembed_url'], $args);

		// generate the response
		if ($embed){
			// AJAX Response
			$response = array(
				'embed'	=> $embed,
			);
			wp_send_json_success($response);
		} else {
			// AJAX Response
			$response = array(
				'embed'	=> esc_attr__('Not a valid oEmbed source','cssf-framework'),
			);
			wp_send_json_error($response);
		}

		die();
	}

	/**
	 *
	 * get_image_size_dimensions()
	 *
	 * Get width and height for a defined image size
	 * @param  string $size A defined image size, eg 'medium'
	 * @return array ['width'] & ['height'] or false
	 */
	function get_image_size_dimensions($size) {
		global $_wp_additional_image_sizes;
		if (isset($_wp_additional_image_sizes[$size])) {
			$width = intval($_wp_additional_image_sizes[$size]['width']);
			$height = intval($_wp_additional_image_sizes[$size]['height']);
		} else {
			$width = get_option($size.'_size_w');
			$height = get_option($size.'_size_h');
		}

		if ( $width && $height ) {
			return array(
				'width' => $width,
				'height' => $height
			);
		} else return false;
	}
	

	/**
	 * Field Output
	 */
	public function output() {

		$value          = $this->element_value();
    	$value          = ( ! empty( $value ) ) ? $value : $this->field['default'];

		$settings = array(
			'placeholder'	=> ( isset( $this->field['settings']['placeholder'] ) ) ? $this->field['settings']['placeholder'] : esc_attr__('Search...','cssf-framework'),
			'preview_size'  => ( isset( $this->field['settings']['preview_size'] ) ) ? $this->field['settings']['preview_size'] : false,
			'width'  		=> ( isset( $this->field['settings']['width'] ) ) ? $this->field['settings']['width'] : 640,
			'height'  		=> ( isset( $this->field['settings']['height'] ) ) ? $this->field['settings']['height'] : 390,
		);

		$placeholder = $settings['placeholder'];

		if ($settings['preview_size']){
			$dimensions = $this->get_image_size_dimensions($field['preview_size']);
			$preview 	= wp_oembed_get($value, $dimensions);
			$width 		= $dimensions['width'];
			$height 	= $dimensions['height'];
		} else {
			$preview 	= wp_oembed_get($value);
			$width 		= $settings['width'];
			$height 	= $settings['height'];
		}

		$hide_preview 	= (!$preview) ? 'hidden' : '';
		$hide_loader 	= ($preview) ? 'hidden' : '';

		
		echo $this->element_before();

		echo "
			<input type='hidden' name='". $this->element_name() ."' value='". $this->element_value() ."' ". $this->element_class('cssf-oembed-value') ."/>
			<div class='cssf-field-inner-wrapper'>
				<div class='cssf-field-header'>
					<div class='cssf-field-text'>
						<input type='url' value='". $this->element_value() ."' placeholder='{$placeholder}' class='cssf-oembed-search'/>
					</div>
					<div class='cssf-actions'>
						<a data-name='cssf-clear-button' href='#' class='cssf-clear-button cssf-button-icon'><i class='cli cli-delete'></i></a>
					</div>
				</div>
				
				<div class='cssf-oembed-canvas' data-preview-width='{$width}' data-preview-height='{$height}'>
					<div class='cssf-oembed-canvas-media {$hide_preview}'>
						{$preview}
					</div>
					<div class='cssf-loader {$hide_loader}'>
						<i class='cli cli-image'></i>
					</div>
				</div>
			</div>
		";
		echo $this->element_after();
		
	}
	
}


CSSFramework_Option_oembed::init();