<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Animate.css
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSSFramework_Option_animate_css extends CSSFramework_Options {

    /**
     * CSSFramework_Option_animate_css constructor.
     * @param array  $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field = array(), $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
		$this->element_before();
		
		$settings = array(
			'preview_text'	=> ( isset($this->field['settings']['preview_text'])) ? $this->field['settings']['preview_text'] : 'Animate.css',
		);

		$defaults_value = array(
			'effect'			=> '',
			'iteration'			=> '',
			'iteration_delay'	=> array(
				'slider1'	=> '',
				'slider2'	=> '',
			),
			'delay'				=> '',
		);

		$this->value  = wp_parse_args( $this->element_value(), $defaults_value );

		$iteration_delay = ($this->value['iteration_delay']['slider1']) ? $this->value['iteration_delay']['slider1'] : $this->value['iteration_delay'];
		// $value = ($this->element_value()) ? $this->element_value() : (($this->field['default']) ? $this->field['default'] : '');

		echo "<div class='cssf-multifield'>";
        echo cssf_add_element(array(
            'pseudo'    => true,
            // 'id'        => $this->field['id'],
            'type'      => 'select',
			'name'		=> $this->element_name('[effect]'),
			'class'		=> 'cssf-animation--effect',
            'options'   => $this->animation_styles(),
			'value'     => $this->value['effect'],
			'columns'	=> 3,
			'before'	=> '<label>'.esc_attr__('Effect','cssf-framework').'</label>',
		));
		echo cssf_add_element( array(
			'pseudo'		=> true,
			'type'			=> 'select',
			'name'			=> $this->element_name('[iteration]'),
			'class'			=> 'cssf-animation--iteration',
			'options'		=> array(
				'once'		=> esc_attr__('Run Once','cssf-framework'),
				'infinite'	=> esc_attr__('Run Infinite','cssf-framework'),
			),
			'value'			=> $this->value['iteration'],
			'columns'		=> 3,
			'before'		=> '<label>'.esc_attr__('Iteration Count','cssf-framework').'</label>',
		) );
		echo cssf_add_element(array(
			'pseudo'        => true,
			'type'          => 'slider',
			'name'          => $this->element_name('[iteration_delay]'),
			'class'			=> 'cssf-animation--iteration_delay',
			'attributes'    => array(
				'data-atts'     => 'iteration_delay',
			),
			'value'         => array(
				'slider1' => $iteration_delay,
				'slider2' => 0,
			),
			'columns'		=> 6,
			'before'		=> '<label>'.esc_attr__('Iteration Delay','cssf-framework').'</label>',
			'settings'		=> array(
				'step'		=> 1,
				'min'		=> 0,
				'max'		=> 20000,
				'unit'		=> esc_attr__('ms.','cssf-framework'),
				'input'		=> true,
				'round'		=> true,
			),
		));
		// echo cssf_add_element(array(
		// 	'pseudo'        => true,
		// 	'type'          => 'slider',
		// 	'name'          => $this->element_name( '[delay]' ),
		// 	'class'			=> 'cssf-animation--delay',
		// 	'attributes'    => array(
		// 		'data-atts'     => 'delay',
		// 	),
		// 	'value'         => array(
		// 		'slider1' => $this->value['delay'],
		// 		'slider2' => 0,
		// 	),
		// 	'columns'		=> 6,
		// 	'before'		=> '<label>'.esc_attr__('Initial Delay','cssf-framework').'</label>',
		// 	'settings'		=> array(
		// 		'step'		=> 1,
		// 		'min'		=> 0,
		// 		'max'		=> 5,
		// 		'unit'		=> esc_attr__('sec.','cssf-framework'),
		// 		'input'		=> true,
		// 		'round'		=> true,
		// 	),
		// ));
		echo "</div>";

		$preview_text = $settings['preview_text'];
		echo "
			<div class='animation-preview'>
				<h3 contentEditable='true'>{$preview_text}</h3>
			</div>
		";

        $this->element_after();
    }

    protected function animation_styles() {
        return apply_filters('cssf_animation_styles', array(
            'Attention Seekers'  => array(
                "bounce"     => 'bounce',
                "flash"      => 'flash',
                "pulse"      => 'pulse',
                "rubberBand" => 'rubberBand',
                "shake"      => 'shake',
                "swing"      => 'swing',
                "tada"       => 'tada',
                "wobble"     => 'wobble',
                "jello"      => 'jello',
            ),
            'Bouncing Entrances' => array(
                "bounceIn"      => 'bounceIn',
                "bounceInDown"  => 'bounceInDown',
                "bounceInLeft"  => 'bounceInLeft',
                "bounceInRight" => 'bounceInRight',
                "bounceInUp"    => 'bounceInUp',
            ),
            'Bouncing Exits'     => array(
                "bounceOut"      => 'bounceOut',
                "bounceOutDown"  => 'bounceOutDown',
                "bounceOutLeft"  => 'bounceOutLeft',
                "bounceOutRight" => 'bounceOutRight',
                "bounceOutUp"    => 'bounceOutUp',
            ),
            'Fading Entrances'   => array(
                "fadeIn"         => 'fadeIn',
                "fadeInDown"     => 'fadeInDown',
                "fadeInDownBig"  => 'fadeInDownBig',
                "fadeInLeft"     => 'fadeInLeft',
                "fadeInLeftBig"  => 'fadeInLeftBig',
                "fadeInRight"    => 'fadeInRight',
                "fadeInRightBig" => 'fadeInRightBig',
                "fadeInUp"       => 'fadeInUp',
                "fadeInUpBig"    => 'fadeInUpBig',
            ),
            'Fading Exits'       => array(
                "fadeOut"         => 'fadeOut',
                "fadeOutDown"     => 'fadeOutDown',
                "fadeOutDownBig"  => 'fadeOutDownBig',
                "fadeOutLeft"     => 'fadeOutLeft',
                "fadeOutLeftBig"  => 'fadeOutLeftBig',
                "fadeOutRight"    => 'fadeOutRight',
                "fadeOutRightBig" => 'fadeOutRightBig',
                "fadeOutUp"       => 'fadeOutUp',
                "fadeOutUpBig"    => 'fadeOutUpBig',
            ),
            "Flippers"           => array(
                "flip"     => 'flip',
                "flipInX"  => 'flipInX',
                "flipInY"  => 'flipInY',
                "flipOutX" => 'flipOutX',
                "flipOutY" => 'flipOutY',
            ),
            "Lightspeed"         => array(
                "lightSpeedIn"  => 'lightSpeedIn',
                "lightSpeedOut" => 'lightSpeedOut',
            ),
            "Rotating Entrances" => array(
                "rotateIn"          => 'rotateIn',
                "rotateInDownLeft"  => 'rotateInDownLeft',
                "rotateInDownRight" => 'rotateInDownRight',
                "rotateInUpLeft"    => 'rotateInUpLeft',
                "rotateInUpRight"   => 'rotateInUpRight',
            ),
            "Rotating Exits"     => array(
                "rotateOut"          => 'rotateOut',
                "rotateOutDownLeft"  => 'rotateOutDownLeft',
                "rotateOutDownRight" => 'rotateOutDownRight',
                "rotateOutUpLeft"    => 'rotateOutUpLeft',
                "rotateOutUpRight"   => 'rotateOutUpRight',
            ),
            "Sliding Entrances"  => array(
                "slideInUp"    => 'slideInUp',
                "slideInDown"  => 'slideInDown',
                "slideInLeft"  => 'slideInLeft',
                "slideInRight" => 'slideInRight',

            ),
            "Sliding Exits"      => array(
                "slideOutUp"    => 'slideOutUp',
                "slideOutDown"  => 'slideOutDown',
                "slideOutLeft"  => 'slideOutLeft',
                "slideOutRight" => 'slideOutRight',

            ),
            "Zoom Entrances"     => array(
                "zoomIn"      => 'zoomIn',
                "zoomInDown"  => 'zoomInDown',
                "zoomInLeft"  => 'zoomInLeft',
                "zoomInRight" => 'zoomInRight',
                "zoomInUp"    => 'zoomInUp',
            ),
            "Zoom Exits"         => array(
                "zoomOut"      => 'zoomOut',
                "zoomOutDown"  => 'zoomOutDown',
                "zoomOutLeft"  => 'zoomOutLeft',
                "zoomOutRight" => 'zoomOutRight',
                "zoomOutUp"    => 'zoomOutUp',
            ),
            "Specials"           => array(
                "hinge"        => 'hinge',
                "jackInTheBox" => 'jackInTheBox',
                "rollIn"       => 'rollIn',
                "rollOut"      => 'rollOut',
            ),
        ));
    }
}