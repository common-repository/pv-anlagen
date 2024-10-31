<?php

/**
 * Solar-Energy-Visualizer
 *
 * Plugin Name: Solar-Energy-Visualizer
 * Plugin URI:  
 * Description: This plugin is used to display and save all data produced by a PV system and the power consumption data in the individual user area and to book the data in a credit/invoice file.
 * Version:     1.0.2
 * Author:      Christina Neufeld
 * Author URI:  
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: solar-energy-visualizer
 * Requires at least: 4.7
 * Tested up to: 6.0
 * Requires PHP: 7.0
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SOLAR_ENERGY_VISUALIZER_VERSION', '1.0.1' );


$timestamp = json_encode(date("H"), JSON_NUMERIC_CHECK);
$day  = date("d");
$year = date("Y");
$month = date("m");
$today_time = date("Y-m-d\TH:i:s\Z");

$today = date('d.m.Y');
$now = time(); // or choose a startdate you want 
$aYearPrev = strtotime('-1 Year', $now);
$twoYearsPrev = strtotime('-2 Year', $now);
$years = date('Y', $aYearPrev);

$yesterdaycal = strtotime('-1 days', $now);
$yesterdayday = date('d', $yesterdaycal);
$yesterdaymonth = date('m', $yesterdaycal);
$yesterdayyear = date('Y', $yesterdaycal);
$yesterdaydate = date('d.m.Y', $yesterdaycal);

$tomorrowPrev = strtotime('+1 Days', $now);
$tomorrow = date('d.m.Y', $tomorrowPrev);
$tomorrowday = date('d', $tomorrowPrev);
$tomorrowmonth = date('m', $tomorrowPrev);
$tomorrowyear = date('Y', $tomorrowPrev);

$last_month_cal = strtotime('-1 month', $now);
$last_month_day = date('d', $last_month_cal);
$last_month_month = date('m', $last_month_cal);
$last_month_year = date('Y', $last_month_cal);
$last_month_date = date('d.m.Y', $last_month_cal);

$yesterdaycal1 = strtotime('-2 days', $now);
$yesterday2day = date('d', $yesterdaycal1);
$yesterday2month = date('m', $yesterdaycal1);
$yesterday2year = date('Y', $yesterdaycal1);
$yesterday2date = date('d.m.Y', $yesterdaycal1);


// define datetimes
define('SEVZ_YEAR', $year);
define('SEVZ_MONTH', $month);
define('SEVZ_DAY', $day);
define('SEVZ_YESTERDAY_DAY', $yesterdayday);
define('SEVZ_YESTERDAY_MONTH', $yesterdaymonth);
define('SEVZ_YESTERDAY_YEAR', $yesterdayyear);
define('SEVZ_TOMORROW_DAY', $tomorrowday);
define('SEVZ_TOMORROW_MONTH', $tomorrowmonth);
define('SEVZ_TOMORROW_YEAR', $tomorrowyear);
define('SEVZ_TODAY_TIME', $today_time);
define('SEVZ_TODAY', $today);
define('SEVZ_YESTERDAY', $yesterdaydate);
define('SEVZ_TOMORROW', $tomorrow);

define('SEVZ_LAST_MONTH_DAY', $last_month_day);
define('SEVZ_LAST_MONTH_MONTH', $last_month_month);
define('SEVZ_LAST_MONTH_YEAR', $last_month_year);
define('SEVZ_LAST_MONTH_DATE', $last_month_date);

define('SEVZ_LAST_TWO_DAY', $yesterday2day);
define('SEVZ_LAST_TWO_MONTH', $yesterday2month);
define('SEVZ_LAST_TWO_YEAR', $yesterday2year);
define('SEVZ_LAST_TWO_DATE', $yesterday2date);

define('SEVZ_LAST_YEAR', $years);




/*$name      = 'AUTO POST';
$type      = 'post';
$content   = 'DUMMY CONTENT';
$category  = array(1,2);
$template  = NULL;
$author_id = '1';
$status    = 'publish';


define( 'SEVZ_POST_NAME', $name );
define( 'SEVZ_POST_TYPE', $type );
define( 'SEVZ_POST_CONTENT', $content );
define( 'SEVZ_POST_CATEGORY', $category );
define( 'SEVZ_POST_TEMPLATE', '' );
define( 'SEVZ_POST_AUTH_ID', $author_id );
define( 'SEVZ_POST_STATUS', $status );*/


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_solar_energy_visualizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sevz-activator.php';
	SEVZ_Activator::activate();
	// add user roles
/*	if ( ! wp_next_scheduled( 'sevz_add_user_roles' ) ) {
		wp_schedule_single_event( time(), 'sevz_add_user_roles' );
	}
	// add user tables
	if ( ! wp_next_scheduled( 'sevz_add_user_tables' ) ) {
		wp_schedule_single_event( time(), 'sevz_add_user_tables' );
	}
	add_action( 'sevz_add_user_roles', 'sevz_add_user_roles_function' );
	add_action( 'sevz_add_user_tables', 'sevz_add_user_tables_function' );
	SEVZ_Activator::sevz_add_user_roles_function();
	SEVZ_Activator::sevz_add_user_tables_function();


	if(!get_page_by_title('Rechnung')){
		SEVZ_Activator::PostCreator( 'Rechnung', 'page', '[sevz_show_invoice]' );
	}
	if(!get_page_by_title('Gutschrift')){
		SEVZ_Activator::PostCreator( 'Gutschrift', 'page', '[sevz_add_credit]' );
	}*/
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pv-anlagen-deactivator.php
 */
function deactivate_solar_energy_visualizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sevz-deactivator.php';
	SEVZ_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_solar_energy_visualizer' );
register_deactivation_hook( __FILE__, 'deactivate_solar_energy_visualizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sevz.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_solar_energy_visualizer() {

	$plugin = new SEVZ_Plugin();
	$plugin->run();

}
run_solar_energy_visualizer();
