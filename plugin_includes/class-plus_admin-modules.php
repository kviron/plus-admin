<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
class Plus_admin_Modules{
    protected $modules;

    /**
	 * Initialize the collections used to maintain the modules
	 *
	 * @since    1.0.0
	 */
    public function __construct() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugin_includes/class-plus_admin-module.php';
        
		$this->modules 			= array();
		$this->modules_path 	= CS_PLUS_PLUGIN_PATH . '/modules/';
        $this->modules_uri 		= CS_PLUS_PLUGIN_URI . '/modules/';
        $this->load_modules();
    }


	/**
	 * Load all available modules list
	 * If module is activated/enabled, then run it
	 *
	 * @return void
	 */
    public function load_modules(){
		$modules_dir = new DirectoryIterator($this->modules_path);
		
		foreach ($modules_dir as $module){
			if ($module->isDir() && !$module->isDot()) {
				$_module; $_module_dir;
				$module_path 	= $module->getRealPath();
				$module_name 	= $module->getFilename();
                $module_uri 	= $this->modules_uri .'/'. $module_name;
                
                $_module_file   = $module_path .'/'. $module_name.'.php';
 
                $include = $this->_get_include_object( $_module_file );

                
				if (file_exists($_module_file)){
                    
                    if (!class_exists( $include->class )){
						require_once $include->file;
                    }
                    if ( !class_exists( $include->class ) ) {
                        trigger_error( "{$this->name} -- Unable to load class {$include->class}. see the readme for class and file naming conventions" );
                        continue;
                    }
                    // $this->{$include->object_name} = new $include->class( $this );
                    $this->{$include->object_name} = call_user_func($include->class . '::getInstance');
					$this->modules[ $include->object_name ] = $include;
				}
            }
		}

        //do this after all modules have loaded so we know API exists
		
		do_action('cst_plusadmin/modules/load_all_before');

		$enabled_modules = Plus_admin::gs('modules');
		if ($enabled_modules){
			foreach ( $this->modules as $name => $class){
				if (in_array($name,$enabled_modules)){
					do_action("cst_plusadmin/module/{$name}_init_before");
					$this->{$name}->init();
					do_action("cst_plusadmin/module/{$name}_init");
					$this->{$name}->run();
					do_action("cst_plusadmin/module/{$name}_run");
				}
			}
		}

		do_action('cst_plusadmin/modules/load_all_after');
	}
	

	/**
	 * Get Modules
	 */
	public function get_modules(){
		return $this->modules;
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
	private function _get_include_object( $file ) {
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
		$class->path 		= dirname( $file );
		$class->uri 		= $this->modules_uri . $raw_name;
        $class->base 		= 'Plus_admin_Module';
		$class->class 		= $class->base . '_' . $class->name;
		
		return $class;
	}

}