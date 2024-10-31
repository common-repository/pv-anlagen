<?php

/**
 * Login View Forecast
 * 
 * @param		array		$sys_data		array with current user data.
 * @param		string		$table_name		Table name: producer, prosumer, consumer.
 * 
 */
class SEVZ_Add_energy_forecast{

	function __construct($sys_data, $table_name) {

		$user= wp_get_current_user();
		$user_id = $user->ID;

		//MySQL
		global $wpdb;	
		foreach( $sys_data as $value ) {
			$link = $value->link1;
			$imsys = $value->imsysid;
			$benutzername = $value->benutzername;
			$passwort = $value->passwort;
			$benutzername1 = $value->name;
		}

		//Show PV-Anlage if exists   
		if(!empty($sys_data)){


			//JSON-Daten "OptiPower"
			$ki_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->base_prefix}ki_{$table_name}_{$user_id}` WHERE DAY(`datetime`) = %d AND MONTH(`datetime`)=%d AND YEAR(`datetime`) = %d ORDER BY `datetime` ASC", SEVZ_DAY, SEVZ_MONTH, SEVZ_YEAR));

			//Anlage-Daten-Register mit Charts für Tag, Monat, Jahr, Gesamt

			if (count($ki_data)> 1){
				foreach( $ki_data as $value ) {
					$timestampki[] = $value->datetime;
					$feedinki[] = $value->fed_into_grid;
				}
			}
			/**********************************************prosumer forecast*************************************/
			if ($table_name =="prosumer"){
				require_once 'energy_forecast_templates/prosumer_template_forecast.php';
			}
			/**************************************************Consumer Forecast***************************************************/
			if ($table_name =="consumer"){
				require_once 'energy_forecast_templates/consumer_template_forecast.php';									 
			}
			/**********************************************producer forecast*************************************/
			if ($table_name =="producer"){
				require_once 'energy_forecast_templates/producer_template_forecast.php';
			}
		}
	}
}