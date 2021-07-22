<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Plus_admin_recaptcha{
	private $key_public;
	private $key_private;
	private $theme;
	private $size;
	private $language;
	private $_counter = 0;

	/**
	 *	Holding the singleton instance
	 */
    private static $_instance = null;


	public function __construct($settings) {
		$this->key_public 	= $settings['key_public'];
		$this->key_private 	= $settings['key_private'];
		$this->theme		= $settings['theme'];
		$this->size			= $settings['size'];
		$this->language		= $settings['language'];
	}

    /**
	 *	Prevent from creating more instances
	 */
    private function __clone() { }
    

	public static function instance($settings){
		if ( is_null( self::$_instance ) )
			self::$_instance = new self($settings);
		return self::$_instance;
    }
	

	public function print_foot() {
		$key_public = $this->key_public;
		$language_param = '';
		?>
		<script type="text/javascript">
		var recaptcha_widgets={};
		function wp_recaptchaLoadCallback(){
			try {
				grecaptcha;
			} catch(err){
				return;
			}
			var e = document.querySelectorAll ? document.querySelectorAll('.g-recaptcha:not(.wpcf7-form-control)') : document.getElementsByClassName('g-recaptcha'),
				form_submits;

			for (var i=0;i<e.length;i++) {
				(function(el){
					var wid;
					// check if captcha element is unrendered
					if ( ! el.childNodes.length) {
						wid = grecaptcha.render(el,{
							'sitekey':'<?php echo $key_public ?>',
							'theme':el.getAttribute('data-theme') || 'light',
							'callback' : function(r){ get_form_submits(el).setEnabled(true); } /* enable submit buttons */ 
						});
						el.setAttribute('data-widget-id',wid);
					} else {
						wid = el.getAttribute('data-widget-id');
						grecaptcha.reset(wid);
					}
				})(e[i]);
			}
		}

		// if jquery present re-render jquery/ajax loaded captcha elements
		if ( typeof jQuery !== 'undefined' )
			jQuery(document).ajaxComplete( function(evt,xhr,set){
				if (xhr.responseText && xhr.responseText.indexOf('<?php echo $key_public ?>') !== -1){
					wp_recaptchaLoadCallback();
				}
			});
		</script>
		<?php
			$recaptcha_api_url = "https://www.google.com/recaptcha/api.js";
			$recaptcha_api_url = add_query_arg(
				array(
					'onload' => 'wp_recaptchaLoadCallback',
					'render' => 'explicit',
				),$recaptcha_api_url);

			if ($language_code = apply_filters('wp_recaptcha_language' , get_locale() ) ){
				$recaptcha_api_url = add_query_arg('hl',$language_code,$recaptcha_api_url);
			}
		?>
		<script src="<?php echo esc_url( $recaptcha_api_url ) ?>" async defer></script><?php
	}


	public function get_html( $attr = array() ) {
		$public_key = $this->key_public;
		$theme 		= $this->theme;
		$size 		= $this->size;

		$default = array(
			'id'			=> 'g-recaptcha-'.$this->_counter++,
			'class'			=> "g-recaptcha",
			'data-sitekey'	=> $public_key,
			'data-theme' 	=> $theme,
			'data-size'		=> $size,
		);
		$attr = wp_parse_args( $attr , $default );
		$attr_str = '';
		foreach ( $attr as $attr_name => $attr_val )
			$attr_str .= sprintf( ' %s="%s"' , $attr_name , esc_attr( $attr_val ) );
		$return = "<div {$attr_str}></div>";
		$return .= '<noscript>';
		if ( $theme ) {
			$return .= '<div style="width: 302px; height: 462px;">' .
							'<div style="width: 302px; height: 422px; position: relative;">' .
								'<div style="width: 302px; height: 422px; position: absolute;">' .
									'<iframe src="https://www.google.com/recaptcha/api/fallback?k='.$attr['data-sitekey'].'"' .
											' frameborder="0" scrolling="no"' .
											' style="width: 302px; height:422px; border-style: none;">' .
									'</iframe>' .
								'</div>' .
							'</div>' .
							'<div style="width: 300px; height: 60px; border-style: none;' .
								' bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;' .
								' background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">' .
								'<textarea id="g-recaptcha-response" name="g-recaptcha-response"' .
											' class="g-recaptcha-response"' .
											' style="width: 250px; height: 40px; border: 1px solid #c1c1c1;' .
													' margin: 10px 25px; padding: 0px; resize: none;" value="">' .
								'</textarea>' .
							'</div>' .
						'</div><br>';
		} else {
			$return .= esc_attr__('Please enable JavaScript to submit this form.','plus_admin');
		}
		$return .= '<br></noscript>';
		return $return;
	}


	public function check() {
		$private_key = $this->key_private;
		$user_response = isset( $_REQUEST['g-recaptcha-response'] ) ? $_REQUEST['g-recaptcha-response'] : false;

		if ($user_response !== false){
			if (!$this->_last_result){
				$remote_ip 	= $_SERVER['REMOTE_ADDR'];
				$url 		= "https://www.google.com/recaptcha/api/siteverify?secret=$private_key&response=$user_response&remoteip=$remote_ip";
				$response 	= wp_remote_get( $url );

				if (!is_wp_error($response)){
					$response_data = wp_remote_retrieve_body( $response );
					$this->_last_result = json_decode($response_data);
				} else {
					$this->_last_result = (object) array( 'success' => false , 'wp_error' => $response);
				}

				if (is_object($this->_last_result)) {
					if ( $this->_last_result->success ) {
						$this->_last_result = (object) array('success' => true, 'wp_error' => false);
					} else {
						if (isset($this->_last_result->{'error-codes'})){
							$error_codes = $this->_last_result->{'error-codes'};

							$this->_last_result = (object) array('success' => false, 'wp_error' => $error_codes);

							// if (in_array('missing-input-response',$error_codes)){
							// 	$this->_last_result = (object) array('success' => false, 'wp_error' => 'missing-input-response');
							// }
						}
					}
				}
			}
			// return $this->_last_result->success;
			return $this->_last_result;
		} 
		return false;
	}

	public static function get_google_errors_as_string( $response ) {
        $string = '';
        $codes = array(
			// 'timedout-or-duplicate',
			'missing-input-secret'		=> esc_attr__('<strong>Captcha Error:</strong> The secret parameter is missing.','plus_admin'),
            'invalid-input-secret' 		=> esc_attr__('<strong>Captcha Error:</strong> The secret parameter is invalid or malformed.','plus_admin'),
			'missing-input-response' 	=> esc_attr__('<strong>Captcha Error:</strong> The response parameter is missing.','plus_admin'),
			'invalid-input-response' 	=> esc_attr__('<strong>Captcha Error:</strong> The response parameter is invalid or malformed.','plus_admin') 
		);

        // foreach ($response->{'error-codes'} as $code) {
        //     $string .= $codes[$code].' ';
		// }
		
		foreach ($response as $code) {
			$string .= $codes[$code].' ';
		}
		// $string = $codes[$response];
        return trim($string);
    }


	/**
	 *	Test public and private key
	 *
	 *	@return bool
	 */
	public function test_keys(){
		$pub_okay = $this->test_public_key();
		$prv_okay = $this->test_private_key();
		return $prv_okay && $pub_okay;
	}

	/**
	 * Test public key
	 */
	public function test_public_key($key = null){
		if (is_null( $key )){
			$key = $this->key_public;
		}
		$pub_key_url 		= sprintf( "http://www.google.com/recaptcha/api/challenge?k=%s" , $key );
		$pub_response 		= wp_remote_get( $pub_key_url );
		$pub_response_body 	= wp_remote_retrieve_body( $pub_response );
		return ! is_wp_error( $pub_response ) && ! strpos( $pub_response_body ,'Format of site key was invalid');
	}

	/**
	 * Test private key
	 */
	public function test_private_key($key = null){
		if (is_null( $key )){
			$key = $this->key_private;
		}
		$prv_key_url 		= sprintf( "http://www.google.com/recaptcha/api/verify?privatekey=%s" , $key );
		$prv_response 		= wp_remote_get( $prv_key_url );
		$prv_response_body 	= wp_remote_retrieve_body( $prv_response );
		return ! is_wp_error( $prv_response ) && ! strpos( $prv_response_body ,'invalid-site-private-key');
	}
}