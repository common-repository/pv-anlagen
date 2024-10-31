<?php


/**
	 * handle cron jobs of the plugin.
	 *
	 * @since    1.0.0
	 */


//class SEVZ_Job_Manager {


//	public function do_job(){

//date_default_timezone_get();

// add user roles
if ( ! wp_next_scheduled( 'sevz_add_user_roles' ) ) {
	wp_schedule_single_event( time(), 'sevz_add_user_roles' );
	if ( wp_next_scheduled( 'sevz_add_user_roles' ) ) {
		wp_clear_scheduled_hook('sevz_add_user_roles');
	}
}

// add user tables
if ( ! wp_next_scheduled( 'sevz_add_user_tables' ) ) {
	wp_schedule_single_event( time(), 'sevz_add_user_tables' );
	if (wp_next_scheduled( 'sevz_add_user_tables' ) ) {
		wp_clear_scheduled_hook('sevz_add_user_tables');
	}		
}



add_action( 'sevz_add_user_roles', 'sevz_add_user_roles_function' );
add_action( 'sevz_add_user_tables', 'sevz_add_user_tables_function' );



$poweroptions = get_option( 'selectpoweropti' );
$imsysoptions = get_option( 'selectimsys' );
$weatherforecast = get_option('weatherapiVisual');
$weatheractual = get_option('weatherapiUnderground');

if($weatherforecast){
	// save weather data
	if ( ! wp_next_scheduled( 'sevz_weather_data_forecast_hookname' ) ) {
		wp_schedule_event( strtotime('04:10:00'), 'daily', 'sevz_weather_data_forecast_hookname' );
	}
}elseif(!$weatherforecast){
	wp_clear_scheduled_hook('sevz_weather_data_forecast_hookname');
}
if($weatheractual){
	if ( ! wp_next_scheduled( 'sevz_weather_data_hookname' ) ) {
		wp_schedule_event( strtotime('07:00:00'), 'daily', 'sevz_weather_data_hookname' );
	}
}elseif(!$weatheractual){
	wp_clear_scheduled_hook('sevz_weather_data_hookname');
}
if($poweroptions && !$imsysoptions){

	wp_clear_scheduled_hook('sevz_save_imsys_data_hookname');
	wp_clear_scheduled_hook('sevz_save_imsys_h_hookname');
	wp_clear_scheduled_hook('sevz_save_imsys_day_hookname');
	wp_clear_scheduled_hook('sevz_save_imsys_month_hookname');


	//get Poweropti-JSON-Data from 1:00 yesterday to 0:45 today
	if ( ! wp_next_scheduled( 'sevz_get_json_data_0' ) ) {
		wp_schedule_event( strtotime('01:00:00'), 'daily', 'sevz_get_json_data_0' );
	}
	if ( ! wp_next_scheduled( 'sevz_get_json_data_1' ) ) {
		wp_schedule_event( strtotime('01:10:00'), 'daily', 'sevz_get_json_data_1' );
	}
	if ( ! wp_next_scheduled( 'sevz_get_json_data_2' ) ) {
		wp_schedule_event( strtotime('01:20:00'), 'daily', 'sevz_get_json_data_2' );
	}
	if ( ! wp_next_scheduled( 'sevz_get_json_data_3' ) ) {
		wp_schedule_event( strtotime('01:30:00'), 'daily', 'sevz_get_json_data_3' );
	}

	//if data does not extist, set flag
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_0' ) ) {
		wp_schedule_event( strtotime('01:40:00'), 'daily', 'sevz_set_flag_hookname_0' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_0' ) ) {
		wp_schedule_event( strtotime('01:50:00'), 'daily', 'sevz_delete_flag_0' );
	}
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_1' ) ) {
		wp_schedule_event( strtotime('02:00:00'), 'daily', 'sevz_set_flag_hookname_1' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_1' ) ) {
		wp_schedule_event( strtotime('02:10:00'), 'daily', 'sevz_delete_flag_1' );
	}
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_2' ) ) {
		wp_schedule_event( strtotime('02:20:00'), 'daily', 'sevz_set_flag_hookname_2' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_2' ) ) {
		wp_schedule_event( strtotime('02:30:00'), 'daily', 'sevz_delete_flag_2' );
	}
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_3' ) ) {
		wp_schedule_event( strtotime('02:40:00'), 'daily', 'sevz_set_flag_hookname_3' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_3' ) ) {
		wp_schedule_event( strtotime('02:50:00'), 'daily', 'sevz_delete_flag_3' );
	}

	// sava data for the day in h
	if ( ! wp_next_scheduled( 'sevz_get_json_data_day' ) ) {
		wp_schedule_event( strtotime('03:30:00'), 'daily', 'sevz_get_json_data_day' );
	}

	// sava data for the month in days
	if ( ! wp_next_scheduled( 'sevz_get_json_data_month' ) ) {
		wp_schedule_event( strtotime('03:40:00'), 'daily', 'sevz_get_json_data_month' );
	}

	// sava data for the year in months
	if ( ! wp_next_scheduled( 'sevz_get_json_data_year' ) ) {
		wp_schedule_event( strtotime('03:50:00'), 'daily', 'sevz_get_json_data_year' );
	}
	// process data for the startpage
	if ( ! wp_next_scheduled( 'sevz_startpage_hookname' ) ) {
		wp_schedule_event( strtotime('04:00:00'), 'daily', 'sevz_startpage_hookname' );
	}
}elseif($imsysoptions && !$poweroptions){

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


	// save imsys data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_data_hookname' ) ) {
		wp_schedule_event( strtotime('12:30:00'), 'daily', 'sevz_save_imsys_data_hookname' );
	}

	// save imsys h data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_h_hookname' ) ) {
		wp_schedule_event( strtotime('12:40:00'), 'daily', 'sevz_save_imsys_h_hookname' );
	}

	// save imsys day data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_day_hookname' ) ) {
		wp_schedule_event( strtotime('12:50:00'), 'daily', 'sevz_save_imsys_day_hookname' );
	}

	// save imsys day data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_month_hookname' ) ) {
		wp_schedule_event( strtotime('13:00:00'), 'daily', 'sevz_save_imsys_month_hookname' );
	}
	// process data for the startpage
	if ( ! wp_next_scheduled( 'sevz_startpage_hookname' ) ) {
		wp_schedule_event( strtotime('04:00:00'), 'daily', 'sevz_startpage_hookname' );
	}
}elseif($poweroptions && $imsysoptions){
	//get Poweropti-JSON-Data from 1:00 yesterday to 0:45 today
	if ( ! wp_next_scheduled( 'sevz_get_json_data_0' ) ) {
		wp_schedule_event( strtotime('01:00:00'), 'daily', 'sevz_get_json_data_0' );
	}
	if ( ! wp_next_scheduled( 'sevz_get_json_data_1' ) ) {
		wp_schedule_event( strtotime('01:10:00'), 'daily', 'sevz_get_json_data_1' );
	}
	if ( ! wp_next_scheduled( 'sevz_get_json_data_2' ) ) {
		wp_schedule_event( strtotime('01:20:00'), 'daily', 'sevz_get_json_data_2' );
	}
	if ( ! wp_next_scheduled( 'sevz_get_json_data_3' ) ) {
		wp_schedule_event( strtotime('01:30:00'), 'daily', 'sevz_get_json_data_3' );
	}

	//if data does not extist, set flag
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_0' ) ) {
		wp_schedule_event( strtotime('01:40:00'), 'daily', 'sevz_set_flag_hookname_0' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_0' ) ) {
		wp_schedule_event( strtotime('01:50:00'), 'daily', 'sevz_delete_flag_0' );
	}
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_1' ) ) {
		wp_schedule_event( strtotime('02:00:00'), 'daily', 'sevz_set_flag_hookname_1' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_1' ) ) {
		wp_schedule_event( strtotime('02:10:00'), 'daily', 'sevz_delete_flag_1' );
	}
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_2' ) ) {
		wp_schedule_event( strtotime('02:20:00'), 'daily', 'sevz_set_flag_hookname_2' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_2' ) ) {
		wp_schedule_event( strtotime('02:30:00'), 'daily', 'sevz_delete_flag_2' );
	}
	if ( ! wp_next_scheduled( 'sevz_set_flag_hookname_3' ) ) {
		wp_schedule_event( strtotime('02:40:00'), 'daily', 'sevz_set_flag_hookname_3' );
	}
	if ( ! wp_next_scheduled( 'sevz_delete_flag_3' ) ) {
		wp_schedule_event( strtotime('02:50:00'), 'daily', 'sevz_delete_flag_3' );
	}

	// sava data for the day in h
	if ( ! wp_next_scheduled( 'sevz_get_json_data_day' ) ) {
		wp_schedule_event( strtotime('03:30:00'), 'daily', 'sevz_get_json_data_day' );
	}

	// sava data for the month in days
	if ( ! wp_next_scheduled( 'sevz_get_json_data_month' ) ) {
		wp_schedule_event( strtotime('03:40:00'), 'daily', 'sevz_get_json_data_month' );
	}

	// sava data for the year in months
	if ( ! wp_next_scheduled( 'sevz_get_json_data_year' ) ) {
		wp_schedule_event( strtotime('03:50:00'), 'daily', 'sevz_get_json_data_year' );
	}



	// save imsys data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_data_hookname' ) ) {
		wp_schedule_event( strtotime('12:30:00'), 'daily', 'sevz_save_imsys_data_hookname' );
	}

	// save imsys h data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_h_hookname' ) ) {
		wp_schedule_event( strtotime('12:40:00'), 'daily', 'sevz_save_imsys_h_hookname' );
	}

	// save imsys day data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_day_hookname' ) ) {
		wp_schedule_event( strtotime('12:50:00'), 'daily', 'sevz_save_imsys_day_hookname' );
	}

	// save imsys day data
	if ( ! wp_next_scheduled( 'sevz_save_imsys_month_hookname' ) ) {
		wp_schedule_event( strtotime('13:00:00'), 'daily', 'sevz_save_imsys_month_hookname' );
	}
	// process data for the startpage
	if ( ! wp_next_scheduled( 'sevz_startpage_hookname' ) ) {
		wp_schedule_event( strtotime('04:00:00'), 'daily', 'sevz_startpage_hookname' );
	}
}else{

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


//}
//}




require_once 'cronJobs.php';

/************ get poweropti data************************/

add_action( 'sevz_get_json_data_0', 'sevz_get_json_data_function_0' );
add_action( 'sevz_get_json_data_1', 'sevz_get_json_data_function_1' );
add_action( 'sevz_get_json_data_2', 'sevz_get_json_data_function_2' );
add_action( 'sevz_get_json_data_3', 'sevz_get_json_data_function_3' );

//set flags if data 01-07h does not exist
add_action( 'sevz_set_flag_hookname_0', 'sevz_set_flag_0' );
add_action( 'sevz_set_flag_hookname_1', 'sevz_set_flag_1' );
add_action( 'sevz_set_flag_hookname_2', 'sevz_set_flag_2' );
add_action( 'sevz_set_flag_hookname_3', 'sevz_set_flag_3' );

//delete flags 01-07h
add_action( 'sevz_delete_flag_0', 'sevz_delete_flag_function_0' );
//delete flags 07-13h
add_action( 'sevz_delete_flag_1', 'sevz_delete_flag_function_1' );
//Delete Flags 13-19h
add_action( 'sevz_delete_flag_2', 'sevz_delete_flag_function_2' );
//Delete flags 19-01h
add_action( 'sevz_delete_flag_3', 'sevz_delete_flag_function_3' );

//Speicherung im Stunden-Takt
add_action( 'sevz_get_json_data_day', 'sevz_get_json_data_function_day' );
//Speicherung im Tages-Takt
add_action( 'sevz_get_json_data_month', 'sevz_get_json_data_function_month' );
//Speicherung im Monats-Takt
add_action( 'sevz_get_json_data_year', 'sevz_get_json_data_function_year' );
add_action( 'sevz_startpage_hookname', 'sevz_compute_startpage' );




/*iMSys***************************************************************+*/

//save imsys 15min data in database
add_action( 'sevz_save_imsys_data_hookname', 'sevz_save_imsys_data' );
//save imsys h data in database
add_action( 'sevz_save_imsys_h_hookname', 'sevz_save_imsys_h' );
//save imsys day data in database
add_action( 'sevz_save_imsys_day_hookname', 'sevz_save_imsys_day' );
//save imsys day data in database
add_action( 'sevz_save_imsys_month_hookname', 'sevz_save_imsys_month' );




/*forecast weather****************************************************************************************************/
//save weather forecast data in external database
add_action( 'sevz_weather_data_forecast_hookname', 'sevz_weather_data_forecast' );
//save current weather data
add_action( 'sevz_weather_data_hookname', 'sevz_weather_data' );



