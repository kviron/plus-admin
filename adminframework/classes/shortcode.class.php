<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Shortcodes Class
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSSFramework_Shortcode extends CSSFramework_Abstract{
	
	/**
	*
	* shortcode options
	* @access public
	* @var array
	*
	*/
	public $options = array();
	
	/**
	*
	* shortcodes options
	* @access public
	* @var array
	*
	*/
	public $shortcodes = array();
	
	/**
	*
	* exclude_post_types
	* @access public
	* @var array
	*
	*/
	public $exclude_post_types = array();
	
	/**
	*
	* instance
	* @access private
	* @var class
	*
	*/
	private static $instance = null;
	
	// run shortcode construct
	public function __construct( $options ) {
		
		$this->options = apply_filters( 'cs_shortcode_options', $options );
		$this->exclude_post_types = apply_filters( 'cs_shortcode_exclude', $this->exclude_post_types );
		
		if( ! empty( $this->options ) ) {
			
			$this->shortcodes = $this->get_shortcodes();
			$this->addAction( 'media_buttons', 'media_shortcode_button', 99 );
			$this->addAction( 'admin_footer', 'shortcode_dialog', 99 );
			$this->addAction( 'customize_controls_print_footer_scripts', 'shortcode_dialog', 99 );
			$this->addAction( 'wp_ajax_cssf-get-shortcode', 'shortcode_generator', 99 );
			
			$this->addAction( 'admin_enqueue_scripts', 'load_style_script' );
		}
		
	}
	
	public function load_style_script() {
		cssf_assets()->render_framework_style_scripts();
	}
	
	// instance
	public static function instance( $options = array() ){
		if ( is_null( self::$instance ) && CSSF_ACTIVE_SHORTCODE ) {
			self::$instance = new self( $options );
		}
		return self::$instance;
	}
	
	// add shortcode button
	public function media_shortcode_button( $editor_id ) {
		
		global $post;
		
		$post_type = ( isset( $post->post_type ) ) ? $post->post_type : '';
		
		if( ! in_array( $post_type, $this->exclude_post_types ) ) {
			echo '<a href="#" class="button button-primary cssf-shortcode" data-editor-id="'. $editor_id .'">'. esc_attr__( 'Add Shortcode', 'cssf-framework' ) .'</a>';
		}
		
	}
	
	// shortcode dialog
	public function shortcode_dialog() {
		?>
		<div id="cssf-shortcode-dialog" class="cssf-dialog" title="<?php _e( 'Add Shortcode', 'cssf-framework' ); ?>">
			<div class="cssf-dialog-header">
				<select class="<?php echo ( is_rtl() ) ? 'chosen-rtl ' : ''; ?>cssf-dialog-select" data-placeholder="<?php echo esc_attr__( 'Select a shortcode', 'cssf-framework' ); ?>">
					<option value=""><?php echo esc_attr__('Please select a shortcode','cssf-framework'); ?></option>
					<?php
					foreach ( $this->options as $group ) {
						echo '<optgroup label="'. $group['title'] .'">';
						foreach ( $group['shortcodes'] as $shortcode ) {
							$view = ( isset( $shortcode['view'] ) ) ? $shortcode['view'] : 'normal';
							echo '<option value="'. $shortcode['name'] .'" data-view="'. $view .'">'. $shortcode['title'] .'</option>';
						}
						echo '</optgroup>';
					}
					?>
				</select>
			</div>
			<div class="cssf-dialog-load">
				<div class="cssf-loading-indicator"><div class="cli cli-alert-circle"></div><?php echo esc_attr__( 'Please select a shortcode from the above select field...', 'cssf-framework' ) ?></div>
			</div>
			<div class="cssf-loading-indicator hidden"><div class="cssf-spinner"></div><?php echo esc_attr__( 'Loading...', 'cssf-framework' ) ?></div>
			<div class="cssf-insert-button hidden">
				<a href="#" class="button button-primary cssf-dialog-insert"><?php echo esc_attr__( 'Insert Shortcode', 'cssf-framework' ); ?></a>
			</div>
		</div>
		<?php
	}
	
	// shortcode generator function for dialog
	public function shortcode_generator() {
		
		$request = cssf_get_var( 'shortcode' );
		
		if( empty( $request ) ) { die(); }
		
		$shortcode = $this->shortcodes[$request];
		
		if( isset( $shortcode['fields'] ) ) {
			
			foreach ( $shortcode['fields'] as $key => $field ) {
				
				if( isset( $field['id'] ) ) {
					$field['attributes'] = ( isset( $field['attributes'] ) ) ? wp_parse_args( array( 'data-atts' => $field['id'] ), $field['attributes'] ) : array( 'data-atts' => $field['id'] );
				}
				
				$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
				
				if( in_array( $field['type'], array('image_select', 'checkbox') ) && isset( $field['options'] ) ) {
					$field['attributes']['data-check'] = true;
				}
				
				echo cssf_add_element( $field, $field_default, 'shortcode' );
				
			}
			
		}
		
		if( isset( $shortcode['clone_fields'] ) ) {
			
			$clone_id = isset( $shortcode['clone_id'] ) ? $shortcode['clone_id'] : $shortcode['name'];
			
			echo '<div class="cssf-shortcode-clone" data-clone-id="'. $clone_id .'">';
			echo '<a href="#" class="cssf-remove-clone"><i class="fa fa-trash"></i></a>';
			
			foreach ( $shortcode['clone_fields'] as $key => $field ) {
				
				$field['sub']        = true;
				$field['attributes'] = ( isset( $field['attributes'] ) ) ? wp_parse_args( array( 'data-clone-atts' => $field['id'] ), $field['attributes'] ) : array( 'data-clone-atts' => $field['id'] );
				$field_default       = ( isset( $field['default'] ) ) ? $field['default'] : '';
				
				if( in_array( $field['type'], array('image_select', 'checkbox') ) && isset( $field['options'] ) ) {
					$field['attributes']['data-check'] = true;
				}
				
				echo cs_add_element( $field, $field_default, 'shortcode' );
				
			}
			
			echo '</div>';
			
			echo '<div class="cssf-clone-button"><a id="shortcode-clone-button" class="button" href="#"><i class="fa fa-plus-circle"></i> '. $shortcode['clone_title'] .'</a></div>';
			
		}
		
		die();
	}
	
	// getting shortcodes from config array
	public function get_shortcodes() {
		
		$shortcodes = array();
		
		foreach ( $this->options as $group_value ) {
			foreach ( $group_value['shortcodes'] as $shortcode ) {
				$shortcodes[$shortcode['name']] = $shortcode;
			}
		}
		
		return $shortcodes;
	}
	
}
