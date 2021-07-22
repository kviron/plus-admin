<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Typography Advanced
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_typography_advanced extends CSSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		echo '<div class="cssf-typography_advanced cssf-multifield">';

		$defaults_value = array(
			'family'	=> 'Arial',
			'variant'	=> 'regular',
			'font'		=> 'websafe',
			'size'		=> 12,
			'height'	=> 20,
			'spacing'	=> '0',
			'align'		=> 'left',
			'transform'	=> 'none',
			'color'		=> '#000',
			'preview'	=> 'Lorem ipsum dolor sit amet',
		);

		$default_variants = apply_filters( 'cssf_websafe_fonts_variants', array(
			'regular',
			'italic',
			'700',
			'700italic',
			'inherit'
		));

		$websafe_fonts = apply_filters( 'cssf_websafe_fonts', array(
			'Arial',
			'Arial Black',
			'Comic Sans MS',
			'Impact',
			'Lucida Sans Unicode',
			'Tahoma',
			'Trebuchet MS',
			'Verdana',
			'Courier New',
			'Lucida Console',
			'Georgia, serif',
			'Palatino Linotype',
			'Times New Roman'
		));

		$value 				= wp_parse_args( $this->element_value(), $defaults_value );

		$family_value 		= $value['family'];
		$variant_value 		= $value['variant'];
		$value_size			= $value['size'];
		$value_height 		= $value['height'];
		$value_spacing 		= $value['spacing'];
		$value_align 		= $value['align'];
		$value_transform 	= $value['transform'];
		$value_color		= $value['color'];

		// Default Preview
		$value_preview		= (isset($this->field['default']['preview'])) ? $this->field['default']['preview'] : 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';

		$is_variant 		= ( isset( $this->field['variant'] ) && $this->field['variant'] === false ) ? false : true;
		$is_chosen 			= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$google_json 		= cssf_get_google_fonts();
		$chosen_rtl 		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		// Field Settings
		$settings = $this->field['settings'];


		if (is_object($google_json)){
			$googlefonts 			= array();

			foreach ( $google_json->items as $key => $font ) {
				$googlefonts[$font->family] = $font->variants;
			}

			$is_google 	= ( array_key_exists( $family_value, $googlefonts ) ) ? true : false;
			
			// Websafe Fonts
			$websafe_typography = array();
			$websafe_variants 	= array();
			foreach ( $websafe_fonts as $websafe_key => $websafe_value ) {
				$websafe_typography[$websafe_key] 	= $websafe_value ."|data-type:websafe";
				$websafe_variants[$websafe_key]		= $default_variants;
			}

			// Google Fonts
			$googlefonts_typography = array();
			$googlefonts_variants	= array();
			foreach ( $googlefonts as $google_key => $google_value) {
				$googlefonts_typography[$google_key] = $google_key ."|data-type:google";
				$googlefonts_variants[$google_key] = $google_value;
			}

			// Full List
			$typography_family_list = array(
				esc_attr__( 'Web Safe Fonts', 'cssf-framework' ) => $websafe_typography,
				esc_attr__( 'Google Fonts', 'cssf-framework' ) 	=> $googlefonts_typography,
			);
			$typography_family_variants = array(
				'websafe'	=> $websafe_variants,
				'google'	=> $googlefonts_variants,
			);
			$typography_family_variants = json_encode($typography_family_variants);

			if( ! empty( $is_variant ) ) {
				$variants_options = array();

				$variants = ( $is_google ) ? $googlefonts[$family_value] : $default_variants;
				$variants = ( $value['font'] === 'google' || $value['font'] === 'websafe' ) ? $variants : array( 'regular' );

				foreach ( $variants as $variant ) {
					$variants_options[$variant] = $variant;
				}
			}


			// Show Elements
			if ($settings['family'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[family]' ),
					'options'	=> $typography_family_list,
					'value'		=> $family_value,
					'class'		=> 'cssf-typo-family',
					'before'	=> '<label>'.esc_attr__('Font Family','cssf-framework').'</label>',
					'chosen'	=> false,
					'attributes'	=> array(
						'data-variants' => $typography_family_variants
					),
				));
			}
			if ($settings['variant'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[variant]' ),
					'options'	=> $variants_options,
					'value'		=> $variant_value,
					'class'		=> 'cssf-typo-variant',
					'before'	=> '<label>'.esc_attr__('Font Weight & Style','cssf-framework').'</label>',
				));
			}
			if ($settings['size'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[size]'),
					'settings'	=> array(
						'type'			=> 'append',
						'addon_value'	=> 'px',
					),
					'value'		=> $value_size,
					'attributes' => [
						'placeholder' => 'size'
					],
					'class'		=> 'cssf-typo-size',
					'before'	=> '<label>'.esc_attr__('Font Size','cssf-framework').'</label>',
				) );
			}

			if ($settings['height'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[height]'),
					'settings'	=> array(
						'type'			=> 'append',
						'addon_value'	=> 'px',
					),
					'value'		=> $value_height,
					'attributes' => [
						'placeholder' => 'height'
					],
					'class'		=> 'cssf-typo-height',
					'before'	=> '<label>'.esc_attr__('Line Height','cssf-framework').'</label>',
				) );
			}

			if ($settings['spacing'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[spacing]'),
					'settings'	=> array(
						'type'			=> 'append',
						'addon_value'	=> 'px',
					),
					'value'		=> $value_spacing,
					'attributes' => [
						'placeholder' => 'spacing'
					],
					'class'		=> 'cssf-typo-spacing',
					'before'	=> '<label>'.esc_attr__('Letter Spacing','cssf-framework').'</label>',
				) );
			}

			if ($settings['align'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[align]' ),
					'options'	=> [
						'left'		=> esc_attr__('Align Left','cssf-framework'),
						'center'	=> esc_attr__('Align Center','cssf-framework'),
						'right'		=> esc_attr__('Align Right','cssf-framework'),
						'justify'	=> esc_attr__('Justify','cssf-framework'),
					],
					'value'		=> $value_align,
					'class'		=> 'cssf-typo-align',
					'before'	=> '<label>'.esc_attr__('Text Align','cssf-framework').'</label>',
				));
			}

			if ($settings['transform'] !== false){
				echo cssf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[transform]' ),
					'options'	=> [
						'none'			=> esc_attr__('None','cssf-framework'),
						'capitalize'	=> esc_attr__('Capitalize','cssf-framework'),
						'uppercase'		=> esc_attr__('Uppercase','cssf-framework'),
						'lowercase'		=> esc_attr__('Lowercase','cssf-framework'),
						'initial'		=> esc_attr__('Initial','cssf-framework'),
						'inherit'		=> esc_attr__('Inherit','cssf-framework'),
					],
					'value'		=> $value_transform,
					'class'		=> 'cssf-typo-transform',
					'before'	=> '<label>'.esc_attr__('Text Transform','cssf-framework').'</label>',
				));
			}

			if ($settings['color'] !== false){
				echo cssf_add_element( array(
					'pseudo'		=> true,
					'id'			=> $this->field['id'].'_color',
					'type'			=> 'color_picker',
					'name'			=> $this->element_name('[color]'),
					'attributes'	=> array(
						'data-atts'		=> 'bgcolor',
					),
					'value'			=> $value_color,
					'default'		=> ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
					'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
					'class'			=> 'cssf-typo-color',
					'before'		=> '<label>'.esc_attr__('Font Color','cssf-framework').'</label>',
				));
			}


			$preview_styles = "--cssf-typo-preview-weight: $variant_value; --cssf-typo-preview-size: $value_size; --cssf-typo-preview-size: $value_height; --cssf-typo-preview-align: $value_align; --cssf-typo-preview-color: $value_color";

			echo 	'<div class="cssf-typo-preview" data-preview-id="cssf-typo-preview_'.$this->field['id'].'_preview" id="cssf-typo-preview_'.$this->field['id'].'_preview" style="'.$preview_styles.'">
						<div class="cssf-typo-preview-toggle"></div>
						<p>'.$value_preview.'</p>
					</div>';

			echo '<input type="text" name="'. $this->element_name( '[font]' ) .'" class="cssf-typo-font hidden" data-atts="font" value="'. $value['font'] .'" />';

		} else {

			echo esc_attr__( 'Error! Can not load json file.', 'cssf-framework' );

		}

		echo '</div>';

		echo $this->element_after();

	}

}
