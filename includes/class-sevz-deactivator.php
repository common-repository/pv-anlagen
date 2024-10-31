<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/includes
 * @author     Christina Neufeld <neufeld_christina@yahoo.com>
 */
class SEVZ_Deactivator {

	/**
	 * delete all cron jobs
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook('sevz_get_json_data_0');
		wp_clear_scheduled_hook('sevz_get_json_data_1');
		wp_clear_scheduled_hook('sevz_get_json_data_2');
		wp_clear_scheduled_hook('sevz_get_json_data_3');

		wp_clear_scheduled_hook('sevz_set_flag_hookname_0');
		wp_clear_scheduled_hook('sevz_set_flag_hookname_1');
		wp_clear_scheduled_hook('sevz_set_flag_hookname_2');
		wp_clear_scheduled_hook('sevz_set_flag_hookname_3');			

		wp_clear_scheduled_hook('sevz_delete_flag_0');
		wp_clear_scheduled_hook('sevz_delete_flag_1');
		wp_clear_scheduled_hook('sevz_delete_flag_2');
		wp_clear_scheduled_hook('sevz_delete_flag_3');

		wp_clear_scheduled_hook('sevz_get_json_data_day');
		wp_clear_scheduled_hook('sevz_get_json_data_month');
		wp_clear_scheduled_hook('sevz_get_json_data_year');

		wp_clear_scheduled_hook('sevz_save_imsys_data_hookname');
		wp_clear_scheduled_hook('sevz_save_imsys_h_hookname');
		wp_clear_scheduled_hook('sevz_save_imsys_day_hookname');
		wp_clear_scheduled_hook('sevz_save_imsys_month_hookname');

		wp_clear_scheduled_hook('sevz_startpage_hookname');

	}
}
