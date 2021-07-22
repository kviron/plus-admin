<?php

/**
 * Register all actions and filters for the module
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Plus_admin
 * @subpackage Plus_admin/modules
 */
class Plus_admin_Login_Theme {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;


	/**
	 * The array of shortcodes registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $shortcodes    The shortcodes registered with WordPress to fire when the plugin loads.
	 */
	protected $shortcodes;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
		$this->shortcodes = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new shortcode to the collection to be registered with WordPress
	 *
	 * @since     1.0.0
	 * @param     string        $tag           The name of the new shortcode.
	 * @param     object        $component      A reference to the instance of the object on which the shortcode is defined.
	 * @param     string        $callback       The name of the function that defines the shortcode.
	 */
	public function add_shortcode( $tag, $component, $callback, $priority = 10, $accepted_args = 1) {
		$this->shortcodes = $this->add( $this->shortcodes, $tag, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		if ($this->_gs('login_page_status')){

			foreach ( $this->filters as $hook ) {
				add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}
	
			foreach ( $this->actions as $hook ) {
				add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}
	
			foreach ( $this->shortcodes as $hook ) {
				add_shortcode( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}
		}
	}


	
	/**
	 * Login Page Utilities
	 */
	public function the_header($layout = 'layout1',$classes = false){
		$background_content 		= $this->background_content();
		$side_logo					= $this->get_side_logo();
		$outside_logo				= $this->get_outside_logo();
		$additional_content_logo 	= $this->additional_content_logo();
		$additional_content 		= $this->additional_content();

		if ($layout == 'layout1'){
			echo "
				<div id='cs-plus-admin-login-page-wrapper' class='{$classes}'>
					<div class='cs-plus-admin-background-wrapper'>
						{$background_content}
					</div>
					<div class='cs-plus-admin-content-wrapper'>
						<div class='cs-plus-admin-content-inner-wrapper'>
							{$outside_logo}
							<div class='cs-plus-admin-content'>
								<div class='cs-plus-admin-additional-content'>
									{$side_logo}
									{$additional_content_logo}
									{$additional_content}
								</div>
								<div class='cs-plus-admin-login-wrapper'>
			";
		}
	}
	public function the_footer($layout = 'layout1'){
		$login_form_footer 	= $this->footer_form();
		$footer				= $this->footer_page();

		if ($layout == 'layout1'){
			echo "
									<div class='cs-plus-admin-login-form-footer'>
										{$login_form_footer}
									</div>
								</div><!-- /.cs-plus-admin-login-wrapper -->
							</div><!-- /.cs-plus-admin-content -->
						</div><!-- /.cs-plus-admin-content-inner-wrapper -->
						<div class='cs-plus-admin-login-footer'>
							{$footer}
						</div>
					</div><!-- /.cs-plus-admin-content-wrapper -->
				</div><!-- /.cs-plus-admin-login-page-wrapper -->
			";
		}
	}

	/**
	 * Login Links
	 */
	public function get_link_login(){
		$text 	= esc_attr__( 'Log in' );
		$url 	= esc_url( wp_login_url() );

		return "
			<div class='cs-plus-admin-login-link-login'>
				<a href='{$url}'>{$text}</a>
			</div>
		";
	}
	public function get_link_lostpassword($forced_text = false,$prepend_text = false,$append_text = false){
		$text 	= esc_attr__( 'Lost your password?' );
		$text 	= ($forced_text) ? $forced_text : $text;
		// $text 	= (!$text) ? esc_attr__( 'Lost your password?' ) : $text;
		$url 	= esc_url( wp_lostpassword_url() );

		if ($this->_gs('login_page_link_lostpassword_status')){
			if (!$this->_gs('login_page_link_lostpassword_visibility')){
				return "
					<div class='cs-plus-admin-login-link-lostpw'>
						{$prepend_text} <a href='{$url}'>{$text}</a> {$append_text}
					</div>
				";
			}
			return false;
		}
		return "
			<div class='cs-plus-admin-login-link-lostpw'>
				{$prepend_text} <a href='{$url}'>{$text}</a> {$append_text}
			</div>
		";
	}
	public function get_link_register($forced_text = false,$prepend_text = false,$append_text = false){
		$text 	= esc_attr__( 'Register' );
		$text 	= ($forced_text) ? $forced_text : $text;
		$url 	= esc_url( wp_registration_url() );

		if ( ! isset( $_GET['checkemail'] ) || ! in_array( $_GET['checkemail'], array( 'confirm', 'newpass' ) ) ) {
			if ( get_option( 'users_can_register' ) ) {
				return "
					<div class='cs-plus-admin-login-link-register'>
						{$prepend_text} <a href='{$url}'>{$text}</a> {$append_text}
					</div>
				";
			}
		}
		return false;
	}
	public function get_forgetmenot(){
		$rememberme = ! empty( $_POST['rememberme'] );
		$checked 	= checked( $rememberme );
		$text 		= esc_attr__( 'Remember Me' );
		
		if (!$this->_gs('login_page_rememberme_status') || !$this->_gs('login_page_rememberme_visibility')){
			return "
				<div class='cs-plus-admin-forgetmenot'><label for='rememberme'><input name='rememberme' type='checkbox' id='rememberme' value='forever' {$checked} /> {$text}</label></div>
			";
		}
		return false;
	}
	public function get_back_to_site(){
		$text 	= sprintf( esc_attr__( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) );
		$url 	= esc_url( home_url( '/' ) );

		if (!$this->_gs('login_page_link_back_visibility')){
			return "
				<div class='cs-plus-admin-login-link-backtosite'>
					<a href='{$url}'>{$text}</a>
				</div>
			";
		}
		return false;
	}

	// Get dynamic Login Link or Lost Password Link
	public function get_link_login_or_lostpw(){
		if (isset($_GET['action']) && ($_GET['action'] == 'lostpassword')){
			return $this->get_link_login();
		} else {
			return $this->get_link_lostpassword();
		}
	}

	/**
	 * Login Logo
	 */
	public function get_side_logo(){
		$side_logo = $this->get_login_logo();
		return ($this->settings->show_side_logo) ? $side_logo : null;
	}
	public function get_outside_logo(){
		$outside_logo = $this->get_login_logo();
		$outside_logo = "<div class='cs-plus-admin-content-outside-logo'>{$outside_logo}</div>";
		return ($this->settings->show_outside_logo) ? $outside_logo : null;
	}
	public function get_login_logo(){
		if ( is_multisite() ) {
			$login_header_url   = network_home_url();
			$login_header_title = get_network()->site_name;
		} else {
			$login_header_url   = esc_attr__( 'https://wordpress.org/' );
			$login_header_title = esc_attr__( 'Powered by WordPress' );
		}
		$login_header_url 	= apply_filters('cst_plusadmin/login/headerurl', $login_header_url );
		$login_header_title = apply_filters('cst_plusadmin/login/headertitle', $login_header_title );
		if ( is_multisite() ) {
			$login_header_text = get_bloginfo( 'name', 'display' );
		} else {
			$login_header_text = $login_header_title;
		}

		$url 	= esc_url( $login_header_url );
		$title 	= esc_attr( $login_header_title );

		return "
			<div class='cs-plus-admin-login-logo'>
				<h1><a href='{$url}' title='{$title}' tabindex='-1'>{$login_header_text}</a></h1>
			</div>
		";
	}

	/**
	 * Get Current Page
	 */
	public function get_current_page(){
		if ($GLOBALS['pagenow'] === 'wp-login.php'){
			if (!empty( $_REQUEST['action'])){
				$action = $_REQUEST['action'];
				if ($action === 'register'){
					return "register";
				} else if ($action === 'lostpassword'){
					return 'lostpassword';
				}
			} else {
				return 'login';
			}
		}
	}

	/**
	 * Extracts the youtube id from a youtube url.
	 * Returns false if the url is not recognized as a youtube url.
	 */
	public function get_youtube_id($video_url){
		if (preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $video_url, $matches)) {
			// preg_match("#([\/|\?|&]vi?[\/|=]|youtu\.be\/|embed\/)([a-zA-Z0-9_-]+)#", $link, $matches);
			return $matches[0];
		}
		return false;
	}

	/**
	 * Extracts the vimeo id from a vimeo url.
	 * Returns false if the url is not recognized as a vimeo url.
	 */
	public function get_vimeo_id($url){
		if (preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*([0-9]{6,11})[?]?.*#', $url, $m)) {
			return $m[1];
		}
		return false;
	}

	/**
	 * Extracts the daily motion id from a daily motion url.
	 * Returns false if the url is not recognized as a daily motion url.
	 */
	public function get_dailymotion_id($url){
		if (preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $url, $m)) {
			if (isset($m[6])) {
				return $m[6];
			}
			if (isset($m[4])) {
				return $m[4];
			}
			return $m[2];
		}
		return false;
	}



	/**
	 * Extra Functionality
	 */
	public function additional_content_logo(){}
	public function additional_content(){}
	public function background_content(){}
	public function footer_form(){}
	public function footer_page(){}


	/**
	 * Get Module Settings
	 */
	public function _gs($option){
		return Plus_admin::get_module('login_page_manager')->gs($option);
	}

	/**
	 * Get Active Theme Settings
	 */
	public function _gst($option){
		return Plus_admin::get_module('login_page_manager')->gs($this->theme_prefix .'__'. $option);
	}

}
	