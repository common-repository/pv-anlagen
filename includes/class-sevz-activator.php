<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Solar-Energy-Visualizer
 * @subpackage Solar-Energy-Visualizer/includes
 * @author     Christina Neufeld <neufeld_christina@yahoo.com>
 */
class SEVZ_Activator {

	/**
	 * activate important functions
	 *
	 * create invoice page, create credit page, add user roles, add user tables
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/*if ( ! function_exists( 'PostCreator' ) ) {


			if(!get_page_by_title('RechnungFirst')){
				PostCreator( 'RechnungFirst', 'page', '[show_invoice]' );
			}
			if(!get_page_by_title('GutschriftFirst')){
				PostCreator( 'GutschriftFirst', 'page', '[add_credit]' );
			}

		}*/

		// add user roles
		/*	if ( ! wp_next_scheduled( 'pv_add_user_roles' ) ) {
			wp_schedule_single_event( time(), 'pv_add_user_roles' );
		}
		// add user tables
		if ( ! wp_next_scheduled( 'pv_add_user_tables' ) ) {
			wp_schedule_single_event( time(), 'pv_add_user_tables' );
		}

		add_action( 'pv_add_user_roles', 'pv_add_user_roles_function' );
		add_action( 'pv_add_user_tables', 'pv_add_user_tables_function' );*/
	}

}
