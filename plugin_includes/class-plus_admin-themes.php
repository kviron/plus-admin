<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Plus_admin_Themes{
    protected $themes;
    protected $themes_path;
    protected $themes_uri;

    /**
	 * Initialize the collections used to maintain the themes
	 *
	 * @since    1.0.0
	 */
    public function __construct() {
		$this->themes 			= array();
        $this->themes_to_load   = array(
            'themes'    => (object) array(
                'base'  => 'Plus_admin_theme',
                'path'  => CS_PLUS_PLUGIN_PATH . '/themes/',
                'uri'   => CS_PLUS_PLUGIN_URI . '/themes/',
            ),
        );
        $this->load_themes();
    }


	/**
	 * Load all available themes list
	 * If theme is activated/enabled, then run it
	 *
	 * @return void
	 */
    public function load_themes(){
        $themes_to_load = apply_filters('cst_plusadmin/load_themes',$this->themes_to_load);

        foreach ($themes_to_load as $_theme_key => $_theme_to_load){
            if (is_dir($_theme_to_load->path)){
                
                $themes_dir = new DirectoryIterator($_theme_to_load->path);

                foreach ($themes_dir as $theme){
                    if ($theme->isDir() && !$theme->isDot()) {
                        $_theme; $_theme_dir;
                        $theme_path 	= $theme->getRealPath();
                        $theme_name 	= $theme->getFilename();
                        $theme_uri 	    = $_theme_to_load->uri .'/'. $theme_name;
                        
                        $_theme_file    = $theme_path .'/'. $theme_name.'.php';
         
                        $include = $this->_get_include_object( $_theme_file, $_theme_to_load );
        
                        if (file_exists($_theme_file)){
                            
                            if ( !class_exists( $include->class ) ) {
                                @require_once $include->file;
                            }
                            if ( !class_exists( $include->class ) ) {
                                trigger_error( "{$this->name} -- Unable to load class {$include->class}. see the readme for class and file naming conventions" );
                                continue;
                            }
        
                            $theme_instance = new $include->class( $this );
                            $include->instance = $theme_instance;
                            $this->{$include->object_name} = $theme_instance;
                            $this->themes[$_theme_key][ $include->object_name ] = $include;
                        }
                    }
                }
            }
        }

        do_action('cst_plusadmin/load_themes_after',$this->themes);
	}
	

	/**
	 * Get themes
	 */
	public function get_themes(){
		return $this->themes;
	}


    /**
	 * Returns an object with all information about a file to include
	 *
	 * Fields:
	 * file - path to file
	 * name - Title case name of class
	 * object_name - lowercase name that will become $this->{object_name}
	 * native - whether this is a native boilerplate class
	 *  base - the base of the class name (either Plugin_Boilerplate or the parent class name)
	 *  class - The name of the class
	 *
	 * @param string $file the file to include
	 * @return object the file object
	 */
	private function _get_include_object( $file , $theme_data ) {
		$class = new stdClass();
		$class->file = $file;
		$name = basename( $file, '.php' );
		$raw_name = $name;
		$name = str_replace( '-', '_', $name );
		$name = str_replace( '_', ' ', $name );
		$class->raw_name 	= $raw_name;
		$class->name 		= str_replace( ' ', '_', ucwords( $name ) );
		$class->object_name = str_replace( ' ', '_', $name );
		$class->human_name 	= ucwords( $name );
        $class->path        = $theme_data->path . $class->raw_name;
        $class->uri         = $theme_data->uri . $class->raw_name;
        $class->base        = $theme_data->base;
        $class->type        = 'dynamic';
		$class->class 	    = $class->base . '_' . $class->name;
		
		return $class;
    }


    public function parse_theme_settings($theme,$settings){
		if ($theme && $settings){
            $theme_instance = $this->$theme;

            if ($theme_instance){
                $parsed_settings = $theme_instance->parse_settings($settings);
                return $parsed_settings;
            }
		}
	}

	public function parse_theme_stylesheet($theme_vars,$themes_to_parse){
        $themes = $this->themes;
        $buffer = "";
        
        // Add Theme Vars
        $buffer .= $theme_vars;

		foreach ($themes_to_parse as $theme_type => $theme_data){
			$theme_name = $theme_data->name;
            $_theme     = $themes[$theme_type][$theme_name];
            $stylesheet = $_theme->path . '/' . $_theme->raw_name .'.css';
			if (file_exists($stylesheet)){
                $buffer .= file_get_contents($stylesheet);
			}
		}
        
		// CSS MINIFY & COMPRESS
		// --------------------------------------------------------------------------
        
		// Remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		// Remove space after colons
		$buffer = str_replace(': ', ':', $buffer);
		// Remove whitespace
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
		// Remove space near commas
		$buffer = str_replace(', ', ',', $buffer);
		$buffer = str_replace(' ,', ',', $buffer);
		// Remove space before brackets
		$buffer = str_replace('{ ', '{', $buffer);
		$buffer = str_replace('} ', '}', $buffer);
		$buffer = str_replace(' {', '{', $buffer);
		$buffer = str_replace(' }', '}', $buffer);
		// Remove last dot with comma
		$buffer = str_replace(';}', '}', $buffer);
		// Remove space before and after >
		$buffer = str_replace('> ','>', $buffer);
		$buffer = str_replace(' >','>', $buffer);

		// Enable GZip encoding.
		$gzip_output = true;
		if ($gzip_output){
			if (extension_loaded('zlib')){
				ob_start('ob_gzhandler');
				ini_set('zlib.output_compression_level', 4);
			}
		}

		// Enable caching
		header('Cache-Control: public');

		// Expire in one day
		// header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

		// Set the correct MIME type, because Apache won't set it for us
		header("Content-Type: text/css");

		// Write everything out
		echo($buffer);

		header("Content-Length: ".ob_get_length());
		if (ob_get_level() > 0) { ob_end_flush(); }

		exit;
	}
}