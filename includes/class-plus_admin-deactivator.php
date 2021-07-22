<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Plus_admin
 * @subpackage Plus_admin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Plus_admin
 * @subpackage Plus_admin/includes
 * @author     Castorstudio <support@castorstudio.com>
 */
class Plus_admin_Deactivator {

	/**
	 * Remove all plugin generated settings
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if (Plus_admin::gs('resetsettings_status')){
			delete_option('cs_plusadmin_settings');
			delete_option('cs_plus_admin_lpm_settings');
			delete_option('cs_plus_admin_amm_settings');
			delete_option('cs_plusadmin_adminmenu');
			delete_option('cs_plusadmin_adminsubmenu');
			delete_option('cs_plusadmin_status');
		}
	}

}
