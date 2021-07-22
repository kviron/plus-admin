<?php
/*-------------------------------------------------------------------------------------------------
- This file is part of the cssf package.                                                          -
- This package is Open Source Software. For the full copyright and license                        -
- information, please view the LICENSE file which was distributed with this                       -
- source code.                                                                                    -
-                                                                                                 -
- @package    cssf                                                                                -
- @author     Varun Sridharan <varunsridharan23@gmail.com>                                        -
 -------------------------------------------------------------------------------------------------*/

/**
 * Class cssframework_Option_links
 */
class CSSFramework_Option_links extends CSSFramework_Options {

	/**
	 * cssframework_Option_links constructor.
	 *
	 * @param        $field
	 * @param string $value
	 * @param string $unique
	 */
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
		if ( ! class_exists( '_WP_Editors' ) ) {
			require_once ABSPATH . 'wp-includes/class-wp-editor.php';
			$this->addAction( 'admin_footer', 'add_links_template', 99 );
		}
        wp_enqueue_script('wplink');
        wp_enqueue_style( 'editor-buttons' );
	}


	public function add_links_template() {
		_WP_Editors::wp_link_dialog();
	}

	public function output() {
		$default = array(
			'url'    => '',
			'title'  => '',
			'target' => '',
		);
		$data    = empty( $this->element_value() ) ? array() : $this->element_value();
		$arg     = wp_parse_args( $data, $default );

		echo $this->element_before();
		$attributes = $this->element_attributes( array( 'class' => 'cssf_wp_link_picker_container' ) );
		echo '<div ' . $attributes . '>';

		echo '<input type="hidden" value="' . $arg['url'] . '" class="cssf-url" name="' . $this->element_name( '[url]' ) . '"/>';
		echo '<input type="hidden" value="' . $arg['title'] . '" class="cssf-title" name="' . $this->element_name( '[title]' ) . '"/>';
		echo '<input type="hidden" value="' . $arg['target'] . '" class="cssf-target" name="' . $this->element_name( '[target]' ) . '"/>';

		echo '<span class="link"><strong>' . esc_attr__( 'URL :', 'cssf-framework' ) . '</strong> <span class="url-value">' . $arg['url'] . '</span> </span><br/>';
		echo '<span class="link-title"><strong>' . esc_attr__( 'Title :', 'cssf-framework' ) . '</strong> <span class="link-title-value">' . $arg['title'] . '</span> </span> <br/> ';
		echo '<span class="target"><strong>' . esc_attr__( 'Target :', 'cssf-framework' ) . '</strong> <span class="target-value">' . $arg['target'] . '</span> </span> <br/><br/> ';
		echo '<a href="#" class="button cssf-wp-link">' . esc_attr__( 'Select URL', 'cssf-framework' ) . '</a>';

		echo '<input id="sample_wplinks" type="hidden" />';
		echo '</div>';
		echo $this->element_after();
	}
}
